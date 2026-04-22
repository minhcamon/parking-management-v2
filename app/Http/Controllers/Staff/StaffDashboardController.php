<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Cards;
use App\Models\ParkingSessions;
use App\Models\Transactions; // Nhớ import model này cho hàm Check-out
use App\Models\MonthlyPasses; // Nếu có dùng vé tháng
use App\Models\TicketTypes; // Nếu có lấy giá vé
use App\Models\VehicleTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StaffDashboardController extends Controller
{
    // ==========================================
    // 1. TRANG CHỦ DASHBOARD
    // ==========================================
    public function index()
    {
        $liveAvailability = $this->getLiveAvailability();
        $recentActivity = $this->getRecentActivity();

        return view('staff.dashboard', compact('liveAvailability', 'recentActivity'));
    }

    // ==========================================
    // 2. CHECK IN
    // ==========================================
    public function checkIn(Request $request)
    {
        $rules = [
            'rfid_code' => 'bail|required|exists:cards,rfid_code',
            'license_plate' => 'bail|required|string',
            'vehicle_type_id' => 'bail|required|exists:vehicle_types,id',
        ];

        $messages = [
            'rfid_code.required' => 'Quên quẹt thẻ rồi nhân viên ơi!',
            'rfid_code.exists' => 'Mã thẻ này không tồn tại trong hệ thống kho.',
            'license_plate.required' => 'Bắt buộc phải nhập biển số xe lúc Check-in.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error');
            return redirect()->back()->withInput();
        }

        // --- VALIDATE BIỂN SỐ XE ---
        if (!is_valid_vehicle_plate($request->license_plate)) {
            toast('Biển số xe không đúng định dạng Việt Nam. Vui lòng kiểm tra lại!', 'error');
            return redirect()->back()->withInput();
        }

        try {
            $session = DB::transaction(function () use ($request) {
                $card = Cards::where('rfid_code', $request->rfid_code)->lockForUpdate()->first();

                if ($card->status === 'inuse' || $card->status === 'lost') {
                    throw new \Exception("Thẻ này đang được sử dụng hoặc đã báo mất, không thể check-in!");
                }

                if ($card->status === 'assigned') {
                    $ticket_type = TicketTypes::where('vehicle_type_id', $request->vehicle_type_id)
                                                ->where('type', 'pass')
                                                ->first();
                } else if ($card->status === 'available') {
                    $ticket_type = TicketTypes::where('vehicle_type_id', $request->vehicle_type_id)
                                                ->where('type', 'normal')
                                                ->first();
                } else {
                    throw new \Exception("Trạng thái thẻ không hợp lệ.");
                }

                if (!$ticket_type) {
                    throw new \Exception("Hệ thống chưa cấu hình Loại vé cho xe này. Vui lòng báo Admin!");
                }

                $newSession = ParkingSessions::create([
                    'card_id' => $card->id,
                    'ticket_type_id' => $ticket_type->id,
                    'license_plate' => strtoupper(trim($request->license_plate)), // Chuẩn hóa biển số
                    'check_in_time' => now(),
                    'staff_id_in' => Auth::id() ?? 1,
                    'status' => 'parking',
                ]);

                if ($card->status === 'available') {
                    $card->update(['status' => 'inuse']);
                }

                return $newSession;
            });

            toast("Xe {$session->license_plate} đã vào bãi thành công!", 'success');
            return redirect()->back();

        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    // ==========================================
    // 3. CHECK OUT
    // ==========================================
    public function checkOut(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rfid_code' => 'bail|required|exists:cards,rfid_code',
            'license_plate' => 'bail|required|string',
        ], [
            'rfid_code.required' => 'Vui lòng quẹt thẻ để Check-out.',
            'rfid_code.exists' => 'Mã thẻ này không tồn tại trong hệ thống.',
        ]);

        if ($validator->fails()) {
            toast($validator->errors()->first(), 'error');
            return redirect()->back()->withInput();
        }

        // --- VALIDATE BIỂN SỐ XE ---
        if (!is_valid_vehicle_plate($request->license_plate)) {
            toast('Biển số xe không đúng định dạng. Vui lòng kiểm tra lại!', 'error');
            return redirect()->back()->withInput();
        }

        try {
            $checkoutData = DB::transaction(function () use ($request) {
                // Lock thẻ để tránh race condition
                $card = Cards::where('rfid_code', $request->rfid_code)->lockForUpdate()->first();

                // Tìm xe đang đỗ bằng thẻ này
                $session = ParkingSessions::where('card_id', $card->id)
                    ->where('status', 'parking')
                    ->first();

                if (!$session) {
                    throw new \Exception("Thẻ này chưa check-in hoặc xe đã ra khỏi bãi!");
                }

                // Kiểm tra biển số lúc ra có khớp lúc vào không (Bảo mật thêm)
                if (strtoupper(trim($request->license_plate)) !== strtoupper(trim($session->license_plate))) {
                    throw new \Exception("Biển số xe không khớp với lúc vào bãi!");
                }

                $amount = TicketTypes::find($session->ticket_type_id)->price;

                // Cập nhật Phiên
                $session->update([
                    'check_out_time' => now(),
                    'staff_id_out'   => Auth::id() ?? 1,
                    'status'         => 'completed',
                ]);

                // Trả thẻ về kho (Nếu là thẻ vãng lai)
                if ($card->status === 'inuse') {
                    $card->update(['status' => 'available']);
                }

                // Ghi nhận doanh thu
                Transactions::create([
                    'session_id'   => $session->id,
                    'amount'       => $amount,
                    'payment_time' => now(),
                    'staff_id'     => Auth::id() ?? 1,
                ]);

                return [
                    'license_plate' => $session->license_plate,
                    'amount' => $amount
                ];
            });

            $formattedAmount = number_format($checkoutData['amount'], 0, ',', '.');
            toast("Xe {$checkoutData['license_plate']} ra bãi. Thu: {$formattedAmount} VNĐ.", 'success');
            return redirect()->back();

        } catch (\Exception $e) {
            toast($e->getMessage(), 'error');
            return redirect()->back();
        }
    }

    // ==========================================
    // 4. CÁC HÀM TIỆN ÍCH (PRIVATE)
    // ==========================================
    private function getLiveAvailability()
    {
        // 1. Lấy TẤT CẢ các loại xe từ Database
        // Không cần where('name', ...), lấy hết ra để hệ thống tự động co giãn
        $vehicleTypes = VehicleTypes::all();

        // 2. Query đếm số xe đang đỗ (Key sẽ là vehicle_type_id: 1, 2, 3...)
        $occupiedCounts = DB::table('parking_sessions')
            ->join('ticket_types', 'parking_sessions.ticket_type_id', '=', 'ticket_types.id')
            ->select('ticket_types.vehicle_type_id', DB::raw('count(*) as total'))
            ->where('parking_sessions.status', 'parking')
            ->groupBy('ticket_types.vehicle_type_id')
            ->pluck('total', 'ticket_types.vehicle_type_id');

        // 3. Khai báo icon (Map theo tên xe trong DB để dễ khớp)
        $uiSettings = [
            'Xe Máy' => 'ph-motorcycle',
            'Ô Tô'   => 'ph-car',
            // Nếu DB có 'Xe Đạp', thêm vào đây: 'Xe Đạp' => 'ph-bicycle'
        ];

        $availability = [];

        // 4. Lặp qua TỪNG MODEL loại xe đã lấy ở Bước 1
        foreach ($vehicleTypes as $vehicle) {

            $typeId = $vehicle->id; // Lấy ID để đối chiếu
            // CHÚ Ý CHỖ NÀY: Thay 'max_capacity' bằng đúng tên cột sức chứa trong bảng vehicle_types của bạn
            $maxCapacity = (int) $vehicle->total_slots;

            // Lấy số xe đang đỗ đúng theo ID
            $occupied = $occupiedCounts[$typeId] ?? 0;

            // LOGIC MÀU SẮC THÔNG MINH
            // Tránh lỗi chia cho 0 nếu maxCapacity trong DB chưa được setup
            if ($maxCapacity > 0) {
                if ($occupied >= $maxCapacity) {
                    $colorClass = 'text-red-600';
                } elseif ($occupied >= ($maxCapacity * 0.8)) {
                    $colorClass = 'text-[#f59e0b]'; // Sắp đầy
                } else {
                    $colorClass = 'text-[#10b981]'; // An toàn
                }
            } else {
                $colorClass = 'text-gray-400'; // Nếu maxCapacity = 0 (chưa setup)
            }

            // Đóng gói mảng đẩy ra View
            $availability[] = [
                'name'        => $vehicle->name, // Lấy thẳng tên tiếng Việt từ DB
                'icon'        => $uiSettings[$vehicle->name] ?? 'ph-vehicle', // Map icon theo tên
                'occupied'    => $occupied,
                'total'       => $maxCapacity,
                'color_class' => $colorClass,
            ];
        }

        return $availability;
    }

    private function getRecentActivity()
    {
        // 1. Kéo 6 giao dịch mới nhất, load kèm thông tin Thẻ, Loại vé và Giao dịch tính tiền
        $sessions = ParkingSessions::with(['card', 'ticket_type', 'transactions'])
            ->latest('updated_at')
            ->take(6)
            ->get();

        // 2. Format dữ liệu chuẩn bị sẵn cho View
        return $sessions->map(function ($session) {
            $isOut = $session->status === 'completed';

            // Xác định loại xe bằng tiếng Việt
            $vehicleName = ($session->ticketType->vehicle_type ?? '') === 'motorbike' ? 'Xe Máy' : 'Ô Tô';

            return [
                // Giao diện Badge
                'type'        => $isOut ? 'OUT' : 'IN',
                'badge_class' => $isOut ? 'badge-out' : 'badge-in',

                // Thông tin xe
                'rfid'        => $session->card->rfid_code ?? 'N/A',
                'plate'       => $session->license_plate,
                'vehicle'     => $vehicleName,

                'time'        => $session->updated_at->diffForHumans(),

                'amount'      => ($isOut && $session->transaction) ? $session->transaction->amount : 0,
            ];
        });
    }
}
