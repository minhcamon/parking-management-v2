<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Dùng để format thời gian

class ReportController extends Controller
{
    public function transactions(Request $request)
    {
        // 1. Khởi tạo Query với các quan hệ
        $query = Transactions::with(['session.card', 'staff']);

        // Search theo Biển số hoặc RFID
        $query->when($request->search, function ($q, $search) {
            $q->whereHas('session', function ($sub) use ($search) {
                $sub->where('license_plate', 'like', "%{$search}%")
                    ->orWhereHas('card', function ($card) use ($search) {
                        $card->where('rfid_code', 'like', "%{$search}%");
                    });
            });
        });

        // Filter theo loại vé (Vé lượt / Vé tháng)
        $query->when($request->type, function ($q, $type) {
            if ($type === 'casual') {
                $q->whereNotNull('session_id');
            } elseif ($type === 'monthly') {
                $q->whereNull('session_id');
            }
        });

        // Filter theo ngày
        $query->when($request->date, function ($q, $date) {
            $q->whereDate('payment_time', $date);
        });

        // 2. Phân trang
        $transactions = $query->latest('payment_time')->paginate(15)->withQueryString();

        // 3. Transform dữ liệu cho View
        $transactions->through(function ($t) {
            $isCasual = !is_null($t->session_id);
            return [
                'id'           => $t->id,
                'type'         => $isCasual ? "Vé lượt" : "Vé tháng",
                'bg_color'     => $isCasual ? "bg-[#10b981]/20" : "bg-[#ef4444]/20",
                'text_color'   => $isCasual ? "text-[#34d399]" : "text-red-500",
                'amount'       => number_format($t->amount, 0, ',', '.') . ' đ',
                'payment_time' => Carbon::parse($t->payment_time)->format('H:i d/m/Y'),
                'staff_name'   => $t->staff->name ?? 'N/A',
                'license_plate'=> $t->session->license_plate ?? 'Pass Holder', // Monthly passes might not have a session in this simple schema
                'rfid_code'    => $t->session->card->rfid_code ?? $t->monthly_pass->card->rfid_code ?? 'N/A',
            ];
        });

        return view('admin.reports.transactions', compact('transactions'));
    }

    public function revenue(Request $request)
    {
        // 1. Xác định Period (Mặc định: tháng này)
        $period = $request->input('period', 'this_month');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        // Logic xử lý ngày dựa trên Period
        switch ($period) {
            case 'today':
                $startDate = Carbon::today()->toDateString();
                $endDate   = Carbon::today()->toDateString();
                break;
            case 'this_week':
                $startDate = Carbon::now()->startOfWeek()->toDateString();
                $endDate   = Carbon::now()->toDateString();
                break;
            case 'this_month':
                $startDate = Carbon::now()->startOfMonth()->toDateString();
                $endDate   = Carbon::now()->toDateString();
                break;
            case 'this_year':
                $startDate = Carbon::now()->startOfYear()->toDateString();
                $endDate   = Carbon::now()->toDateString();
                break;
            case 'custom':
                // Giữ nguyên giá trị từ request, nếu null thì fallback
                $startDate = $startDate ?: Carbon::now()->subDays(30)->toDateString();
                $endDate   = $endDate ?: Carbon::now()->toDateString();
                break;
            default:
                $startDate = Carbon::now()->startOfMonth()->toDateString();
                $endDate   = Carbon::now()->toDateString();
                break;
        }

        // 2. Tạo câu Query gốc (Lọc theo khoảng thời gian)
        $baseQuery = Transactions::whereBetween('payment_time', [
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59'
        ]);

        // 3. Tính toán các KPI (Dùng 'clone' để không làm hỏng câu lệnh gốc)
        $totalRevenue = (clone $baseQuery)->sum('amount');
        $casualRevenue = (clone $baseQuery)->whereNotNull('session_id')->sum('amount');
        $monthlyRevenue = (clone $baseQuery)->whereNull('session_id')->sum('amount');

        $monthlyPercent = $totalRevenue > 0 ? round(($monthlyRevenue / $totalRevenue) * 100, 1) : 0;
        $casualPercent  = $totalRevenue > 0 ? round(($casualRevenue / $totalRevenue) * 100, 1) : 0;

        // 4. Lấy dữ liệu cho biểu đồ
        $dailyData = (clone $baseQuery)
            ->select(
                DB::raw('DATE(payment_time) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartDates  = $dailyData->pluck('date');
        $chartTotals = $dailyData->pluck('total');

        // 5. Đóng gói dữ liệu ra View
        return view('admin.reports.revenue', [
            'period'          => $period,
            'filters'         => compact('startDate', 'endDate'),
            'totalRevenue'    => number_format($totalRevenue, 0, ',', '.'),
            'monthlyRevenue'  => number_format($monthlyRevenue, 0, ',', '.'),
            'casualRevenue'   => number_format($casualRevenue, 0, ',', '.'),
            'monthlyPercent'  => $monthlyPercent,
            'casualPercent'   => $casualPercent,
            'chartDates'      => $chartDates,
            'chartTotals'     => $chartTotals,
        ]);
    }
}
