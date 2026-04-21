@extends('admin.admin-layout')
@section('title', 'Revenue Reports - Admin')
@section('page-title', 'Revenue Analytics')

@section('content')

    <form method="GET" action="{{ route('admin.reports.revenue') }}">
        <div class="premium-card mb-6">
            <div class="flex justify-between items-center flex-wrap gap-4">
                <h3 class="font-['Outfit'] text-[1.2rem] m-0">Report Filters</h3>
                <div class="flex gap-4">
                    <input type="date" name="start_date" class="form-control mb-0" value="{{ $filters['startDate'] }}">
                    <span class="flex items-center text-[var(--text-muted)]">to</span>
                    <input type="date" name="end_date" class="form-control mb-0" value="{{ $filters['endDate'] }}">
                    <button type="submit" class="btn btn-primary px-6 py-[0.8rem] bg-[var(--accent-primary)] text-white border-none rounded-lg font-medium cursor-pointer">
                        Apply
                    </button>
                </div>
            </div>
        </div>
    </form>

    <div class="stats-grid grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="premium-card p-5 var(--bg-color) rounded-xl shadow-sm border border-gray-100">
            <div class="icon-box bg-green-100 text-green-600 w-12 h-12 flex items-center justify-center rounded-lg mb-3">
                <i class="ph-fill ph-money text-2xl"></i>
            </div>
            <div class="stat-label text-gray-500 text-sm font-semibold uppercase">Total Revenue</div>
            <div class="stat-value text-3xl font-black mt-1">{{ $totalRevenue }} đ</div>
            <div class="text-gray-400 text-xs mt-2">
                <i class="ph ph-calendar text-gray-500"></i> Selected Period
            </div>
        </div>

        <div class="premium-card p-5 var(--bg-color) rounded-xl shadow-sm border border-gray-100">
            <div class="icon-box bg-blue-100 text-blue-600 w-12 h-12 flex items-center justify-center rounded-lg mb-3">
                <i class="ph-fill ph-credit-card text-2xl"></i>
            </div>
            <div class="stat-label text-gray-500 text-sm font-semibold uppercase">Monthly Passes</div>
            <div class="stat-value text-3xl font-black mt-1">{{ $monthlyRevenue }} đ</div>
            <div class="text-gray-400 text-xs mt-2">
                <span class="text-green-500 font-bold">{{ $monthlyPercent }}%</span> of total
            </div>
        </div>

        <div class="premium-card p-5 var(--bg-color) rounded-xl shadow-sm border border-gray-100">
            <div class="icon-box bg-orange-100 text-orange-600 w-12 h-12 flex items-center justify-center rounded-lg mb-3">
                <i class="ph-fill ph-ticket text-2xl"></i>
            </div>
            <div class="stat-label text-gray-500 text-sm font-semibold uppercase">Casual Tickets</div>
            <div class="stat-value text-3xl font-black mt-1">{{ $casualRevenue }} đ</div>
            <div class="text-gray-400 text-xs mt-2">
                <span class="text-orange-500 font-bold">{{ $casualPercent }}%</span> of total
            </div>
        </div>
    </div>

    <div class="premium-card p-6 var(--bg-color) rounded-xl shadow-sm border border-gray-100 mt-6">
        <h3 class="font-['Outfit'] text-[1.2rem] font-bold var(--text-main) mb-6">Revenue Trend</h3>
        <div id="revenueChart" class="w-full h-[350px]"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Nhận mảng dữ liệu từ Laravel bọc vào JSON
            const chartDates = {!! json_encode($chartDates) !!};
            const chartTotals = {!! json_encode($chartTotals) !!};

            const options = {
                series: [{
                    name: 'Revenue (VNĐ)',
                    data: chartTotals
                }],
                chart: {
                    type: 'area',
                    height: 350,
                    fontFamily: 'inherit',
                    toolbar: { show: false }
                },
                colors: ['#6366f1'], // Màu chủ đạo (var(--accent-primary))
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: chartDates,
                    type: 'datetime' // Tự động format ngày ở trục X
                },
                yaxis: {
                    labels: {
                        formatter: function (value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + " đ";
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.05,
                        stops: [0, 100]
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#revenueChart"), options);
            chart.render();
        });
    </script>
@endsection
