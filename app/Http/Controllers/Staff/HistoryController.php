<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ParkingSessions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        // 1. Query danh sách xe vào/ra, load kèm Thẻ và Giao dịch
        $query = ParkingSessions::with(['card', 'transactions']);

        // Search theo Biển số hoặc RFID
        $query->when($request->search, function ($q, $search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('license_plate', 'like', "%{$search}%")
                    ->orWhereHas('card', function ($c) use ($search) {
                        $c->where('rfid_code', 'like', "%{$search}%");
                    });
            });
        });

        // Filter theo loại (IN/OUT)
        $query->when($request->type, function ($q, $type) {
            if ($type === 'in') {
                $q->where('status', 'active');
            } elseif ($type === 'out') {
                $q->where('status', 'completed');
            }
        });

        // Filter theo ngày
        $query->when($request->date, function ($q, $date) {
            $q->whereDate('updated_at', $date);
        });

        $sessions = $query->latest('updated_at')->paginate(10)->withQueryString();

        // 2. Format dữ liệu UI qua hàm through()
        $history = $sessions->through(function ($session) {
            $isOut = $session->status === 'completed';

            // Lấy đúng thời điểm thực tế
            $timestamp = $isOut ? $session->check_out_time : $session->check_in_time;
            $carbonTime = $timestamp ? Carbon::parse($timestamp) : $session->updated_at;

            // Logic tính tiền
            $cost = ($isOut && $session->transaction)
                ? number_format($session->transaction->amount, 0, ',', '.') . ' đ'
                : '-';

            return [
                'id'            => $session->id,
                'time'          => $carbonTime->format('h:i A'),
                'date_label'    => $carbonTime->isToday() ? 'Today' : $carbonTime->format('d/m/Y'),
                'type'          => $isOut ? 'OUT' : 'IN',
                'type_class'    => $isOut
                    ? 'bg-[#ef4444]/20 text-[#ef4444]'
                    : 'bg-[#10b981]/20 text-[#34d399]',
                'license_plate' => $session->license_plate,
                'card_code'     => $session->card->rfid_code ?? 'N/A',
                'cost'          => $cost,
            ];
        });

        return view('staff.history.index', compact('history'));
    }
}
