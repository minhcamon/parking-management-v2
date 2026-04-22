<?php
namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;
use App\Models\MonthlyPasses;
use App\Models\ParkingSessions;
use Illuminate\Http\Request;

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
            $sessions = ParkingSessions::with(['card', 'ticket_type']) // Đổi vehicle_type thành ticket_type
            ->where('status', 'parking') // SỬA Ở ĐÂY
            ->where(function ($q) use ($query) {
                $q->where('license_plate', 'like', "%{$query}%")
                    ->orWhereHas('card', function ($c) use ($query) {
                        $c->where('rfid_code', 'like', "%{$query}%");
                    });
            })
                ->get(); // Dùng get() để lấy danh sách thay vì first()

            // 2. TÌM TRONG VÉ THÁNG (Giả sử Model MonthlyPasses của bạn cấu hình chuẩn)
            $passes = MonthlyPasses::with(['card', 'ticket_type'])
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
        return view('staff.operations.monthly-register');
    }
}
