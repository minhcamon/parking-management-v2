<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ParkingSessions;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        // 1. Query danh sách xe vào/ra, load kèm Thẻ và Giao dịch để tối ưu N+1
        $sessions = ParkingSessions::with(['card', 'transactions'])
            // Lọc theo nhân viên đang đăng nhập (Nếu bạn có lưu staff_id_in / out)
            // ->where(function($query) {
            //     $query->where('staff_id_in', Auth::id())
            //           ->orWhere('staff_id_out', Auth::id());
            // })
            ->latest('updated_at') // Sắp xếp mới nhất lên đầu
            ->paginate(4);

        // 2. Format dữ liệu UI qua hàm through()
        $history = $sessions->through(function ($session) {
            $isOut = $session->status === 'completed';

            // Lấy đúng thời điểm thực tế (Xe ra lấy giờ ra, Xe vào lấy giờ vào)
            $timestamp = $isOut ? $session->check_out_time : $session->check_in_time;
            $carbonTime = $timestamp ? Carbon::parse($timestamp) : $session->updated_at;

            // Logic tính tiền (Chỉ xe ra mới hiển thị tiền)
            $cost = ($isOut && $session->transaction)
                ? number_format($session->transaction->amount, 0, ',', '.') . ' đ'
                : '-';

            return [
                'id'            => $session->id,

                // Format giờ (VD: 09:12 AM)
                'time'          => $carbonTime->format('h:i A'),

                // Hàm isToday() của Carbon giúp trả về chữ 'Today' hoặc format ngày
                'date_label'    => $carbonTime->isToday() ? 'Today' : $carbonTime->format('d/m/Y'),

                'type'          => $isOut ? 'OUT' : 'IN',
                'type_class'    => $isOut
                    ? 'bg-[#ef4444]/20 text-[#ef4444]' // Đỏ cho OUT
                    : 'bg-[#10b981]/20 text-[#34d399]', // Xanh cho IN

                'license_plate' => $session->license_plate,
                'card_code'     => $session->card->rfid_code ?? 'N/A',
                'cost'          => $cost,
            ];
        });

        // 3. Đẩy ra View
        return view('staff.history.index', compact('history'));
    }
}
