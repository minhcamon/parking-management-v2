<?php
namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;
use App\Models\MonthlyPasses;
use App\Models\ParkingSessions;
use App\Models\Cards;
use App\Models\TicketTypes;
use App\Models\VehicleTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OperationController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        // Khởi tạo mảng rỗng thay vì null để ngoài View lặp foreach không bị lỗi
        $sessions = collect();
        $passes = collect();

        if ($query) {
            // 1. TÌM TRONG CÁC PHIÊN ĐANG ĐỖ (Lưu ý: status là 'parking')
            $sessions = ParkingSessions::with(['card.monthly_passes', 'ticket_type']) // Load thêm monthly_passes để gộp
            ->where('status', 'parking')
            ->where(function ($q) use ($query) {
                $q->where('license_plate', 'like', "%{$query}%")
                    ->orWhereHas('card', function ($c) use ($query) {
                        $c->where('rfid_code', 'like', "%{$query}%");
                    });
            })
                ->get();

            // Lấy danh sách card_id đang đỗ để loại trừ khỏi danh sách vé tháng rời
            $sessionCardIds = $sessions->pluck('card_id')->toArray();

            // 2. TÌM TRONG VÉ THÁNG (Chỉ lấy những vé không có trong danh sách đang đỗ)
            $passes = MonthlyPasses::with(['card', 'ticket_type'])
                ->whereNotIn('card_id', $sessionCardIds)
                ->where(function ($q) use ($query) {
                    $q->where('license_plate', 'like', "%{$query}%")
                        ->orWhere('customer_name', 'like', "%{$query}%")
                        ->orWhereHas('card', function ($c) use ($query) {
                            $c->where('rfid_code', 'like', "%{$query}%");
                        });
                })
                ->get();
        }

        return view('staff.operations.search', compact('sessions', 'passes', 'query'));
    }

    public function registerPass()
    {
        // Lấy 1 thẻ sẵn sàng để cấp vé tháng
        $availableCard = Cards::where('status', 'available')->first();
        // Lấy các loại phương tiện
        $vehicleTypes = VehicleTypes::all();
        // Lấy các loại vé tháng
        $ticketTypes = TicketTypes::where('type', 'pass')->with('vehicle_type')->get();

        return view('staff.operations.monthly-register', compact('availableCard', 'vehicleTypes', 'ticketTypes'));
    }

    public function storePass(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'license_plate' => 'required|string|max:20',
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'card_id' => 'required|exists:cards,id',
            'start_date' => 'required|date',
            'months' => 'required|integer|min:1|max:12',
        ]);

        if (!is_valid_vehicle_plate($request->license_plate)) {
            toast('Biển số xe không đúng định dạng Việt Nam!', 'error');
            return redirect()->back()->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $card = Cards::findOrFail($request->card_id);
                if ($card->status !== 'available') {
                    throw new \Exception('Thẻ này đã được sử dụng hoặc không sẵn sàng.');
                }

                $startDate = Carbon::parse($request->start_date);
                $endDate = $startDate->copy()->addMonths($request->months);

                MonthlyPasses::create([
                    'card_id' => $card->id,
                    'ticket_type_id' => $request->ticket_type_id,
                    'customer_name' => strtoupper($request->customer_name),
                    'license_plate' => strtoupper(trim($request->license_plate)),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]);

                // Update card status
                $card->update(['status' => 'assigned']);
            });

            toast('Đăng ký vé tháng thành công!', 'success');
        } catch (\Exception $e) {
            toast('Lỗi: ' . $e->getMessage(), 'error');
        }

        return redirect()->back();
    }
}
