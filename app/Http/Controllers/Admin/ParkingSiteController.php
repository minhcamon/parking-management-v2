<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cards;
use App\Models\TicketTypes;
use App\Models\VehicleTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ParkingSiteController extends Controller
{
    public function index(Request $request)
    {
        $vehicles = VehicleTypes::all();
        $ticketTypes = TicketTypes::with('vehicle_type')->get();
        $query = Cards::query();

        $query->when($request->search, function ($q, $search) {
            $q->where('rfid_code', 'like', "%{$search}%");
        });

        $query->when($request->status, function ($q, $status) {
            $q->where('status', $status);
        });

        $cards = $query->latest()->paginate(10)->withQueryString();

        return view('admin.parking-site.index')->with(compact('vehicles', 'cards', 'ticketTypes'));
    }

    public function getFormData()
    {
        // 1. Lấy danh sách Loại vé tháng (Chỉ lấy loại 'pass' và đang active)
        $ticketTypes = TicketTypes::where('type', 'pass')
            ->where('is_active', 1)
            ->get();

        // 2. Lấy danh sách Thẻ RFID chưa sử dụng
        $availableCards = Cards::where('status', 'available')->get();

        // 3. Trả về JSON chứa cả 2 mảng dữ liệu
        return response()->json([
            'ticketTypes'    => $ticketTypes,
            'availableCards' => $availableCards
        ]);
    }

    public function updateVehicle(Request $request, $id)
    {
        // 1. Tìm loại xe
        $vehicle = VehicleTypes::findOrFail($id);

        // 2. Validate dữ liệu
        $request->validate([
            'name'         => 'required|string|max:100',
            'total_slots'  => 'required|integer|min:0', // Hoặc max_capacity tùy bạn đặt tên cột
        ]);

        // 3. Cập nhật
        $vehicle->update([
            'name'        => $request->name,
            'total_slots' => $request->total_slots,
        ]);

        toast('Đã cập nhật cấu hình bãi đỗ thành công!', 'success');
        return redirect()->back();
    }

    public function store(Request $request)
    {
        $request->validate([
            'rfid_code' => 'required|unique:cards,rfid_code|string|max:50',
        ]);

        Cards::create([
            'rfid_code' => strtoupper($request->rfid_code),
            'status' => 'available',
        ]);

        toast('Đã thêm thẻ mới thành công!', 'success');
        return redirect()->back();
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:100',
        ]);

        $quantity = $request->quantity;
        $prefix = "CARD-";
        $now = now(); // Lấy thời gian hiện tại để dùng cho Bulk Insert

        try {
            // 1. Tạo mảng chứa các mã thẻ ngẫu nhiên trên RAM
            $newCodes = [];
            while (count($newCodes) < $quantity) {
                // Str::random(8) tạo chuỗi ngẫu nhiên cả chữ và số
                $rfid = $prefix . strtoupper(Str::random(4));
                $newCodes[$rfid] = $rfid; // Dùng key mảng để tự động chống trùng lặp sinh ra trong cùng 1 lô
            }

            // 2. Kiểm tra xem lô mã vừa tạo có bị trùng với Database không
            $existingCodes = Cards::whereIn('rfid_code', $newCodes)->pluck('rfid_code')->toArray();

            // 3. Lọc bỏ các mã đã tồn tại trong DB
            $validCodes = array_diff($newCodes, $existingCodes);

            // 4. Đóng gói Data để chuẩn bị Insert
            $insertData = [];
            foreach ($validCodes as $code) {
                $insertData[] = [
                    'rfid_code'  => $code,
                    'status'     => 'available', // Mặc định trạng thái thẻ mới
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // 5. BULK INSERT: Chèn toàn bộ vào DB bằng ĐÚNG 1 CÂU LỆNH SQL
            if (!empty($insertData)) {
                Cards::insert($insertData);
            }

            $created = count($insertData);
            toast("Đã khởi tạo thành công {$created} thẻ ngẫu nhiên!", 'success');

        } catch (\Exception $e) {
            toast("Lỗi khi tạo thẻ hàng loạt: " . $e->getMessage(), 'error');
        }

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        // 1. Tìm bản ghi hoặc trả về 404 nếu không thấy
        $card = Cards::findOrFail($id);

        // 2. Validate dữ liệu
        $request->validate([
            // Luật unique:cards,rfid_code,$id có nghĩa là:
            // Kiểm tra duy nhất trong bảng cards, cột rfid_code, nhưng BỎ QUA ID hiện tại
            'rfid_code' => 'required|string|max:50|unique:cards,rfid_code,' . $id,
            'status'    => 'required|in:available,inuse,assigned,lost',
        ]);

        // 3. Thực hiện cập nhật
        $card->update([
            'rfid_code' => strtoupper($request->rfid_code),
            'status'    => $request->status,
        ]);

        // 4. Thông báo và điều hướng (Sử dụng RealRashid/SweetAlert nếu bạn đã cài)
        // Nếu chưa cài toast() bạn dùng: return redirect()->back()->with('success', '...');
        toast('Cập nhật thông tin thẻ thành công!', 'success');
        return redirect()->back();
    }
}
