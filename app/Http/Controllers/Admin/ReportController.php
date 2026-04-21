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
    public function transactions()
    {
        // 1. Chaining 'with' vào trực tiếp câu truy vấn
        $transactions = Transactions::with(['session.card', 'staff'])
        ->latest()
        ->paginate(2);

        // 2. Dùng through() thay vì map() để KHÔNG BỊ MẤT phân trang (Pagination)
        $transactions->through(function ($t) {
        return [
        'id'           => $t->id,
        // Gọi thuộc tính (không ngoặc đơn), dùng toán tử Null Coalescing (??) cho gọn
        'type'         => $t->session ? "Vé lượt" : "Vé tháng",
        'bg-color'     => $t->session ? "bg-[#10b981]/20" : "bg-[#ef4444]/20",
         'text_color'   => $t->session ? "text-[#34d399]" : "text-red-500",
        // Format tiền tệ thẳng ở BE
         'amount'       => number_format($t->amount, 0, ',', '.') . ' đ',

        // Format ngày tháng (Giống SimpleDateFormat của Java)
        'payment_time' => Carbon::parse($t->payment_time)->format('H:i d/m/Y'),

        'staff_name'   => $t->staff->name ?? 'N/A', // Nếu staff null thì in 'N/A'

        // Bổ sung dữ liệu cho bảng FE (Biển số, RFID)
        'license_plate'=> $t->session->license_plate ?? 'N/A',
        'rfid_code'    => $t->session->card->rfid_code ?? 'N/A',
        ];
    });

    return view('admin.reports.transactions', compact('transactions'));
    }

    public function revenue(Request $request)
    {
        // 1. Lấy ngày lọc từ Request (Mặc định: 30 ngày gần nhất)
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->toDateString());

        // 2. Tạo câu Query gốc (Lọc theo khoảng thời gian)
        $baseQuery = Transactions::whereBetween('payment_time', [
            $startDate . ' 00:00:00',
            $endDate . ' 23:59:59'
        ]);

        // 3. Tính toán các KPI (Dùng 'clone' để không làm hỏng câu lệnh gốc)
        $totalRevenue = (clone $baseQuery)->sum('amount');

        // Vé lượt: Có gắn với Phiên đỗ xe (session_id != null)
        $casualRevenue = (clone $baseQuery)->whereNotNull('session_id')->sum('amount');

        // Vé tháng: Không gắn với Phiên đỗ xe (session_id == null)
        $monthlyRevenue = (clone $baseQuery)->whereNull('session_id')->sum('amount');

        // Tính phần trăm (%) an toàn tránh chia cho 0
        $monthlyPercent = $totalRevenue > 0 ? round(($monthlyRevenue / $totalRevenue) * 100, 1) : 0;
        $casualPercent  = $totalRevenue > 0 ? round(($casualRevenue / $totalRevenue) * 100, 1) : 0;

        // 4. Lấy dữ liệu cho biểu đồ (Group By theo ngày)
        $dailyData = (clone $baseQuery)
            ->select(
                DB::raw('DATE(payment_time) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Tách thành 2 mảng [Ngày] và [Số tiền] để ném vào ApexCharts
        $chartDates  = $dailyData->pluck('date');
        $chartTotals = $dailyData->pluck('total');

        // 5. Đóng gói dữ liệu ra View
        return view('admin.reports.revenue', [
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
