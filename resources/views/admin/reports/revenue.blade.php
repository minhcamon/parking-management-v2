@extends('admin.admin-layout')
@section('title', 'Revenue Reports - Admin')
@section('page-title', 'Revenue Analytics')

@section('content')

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h3 class="text-2xl font-black m-0 mb-1">Báo cáo Doanh thu</h3>
            <p class="text-[0.8rem] text-muted font-medium">Theo dõi tình hình kinh doanh và doanh thu hệ thống</p>
        </div>
    </div>

    <div x-data="{ openCustom: @json($period === 'custom') }" class="bg-nav-hover p-4 rounded-2xl border border-header-border mb-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            
            <!-- Quick Filter Tabs (Segmented Control) -->
            <form method="GET" action="{{ route('admin.reports.revenue') }}" class="flex flex-wrap items-center gap-1 p-1 bg-dropdown-bg rounded-xl border border-header-border w-full md:w-auto">
                <button type="submit" name="period" value="today"
                        class="px-4 py-1.5 rounded-lg text-sm font-semibold transition {{ $period === 'today' ? 'bg-accent text-white shadow-md' : 'text-muted hover:text-main' }}">
                    Hôm nay
                </button>
                <button type="submit" name="period" value="this_week"
                        class="px-4 py-1.5 rounded-lg text-sm font-semibold transition {{ $period === 'this_week' ? 'bg-accent text-white shadow-md' : 'text-muted hover:text-main' }}">
                    Tuần này
                </button>
                <button type="submit" name="period" value="this_month"
                        class="px-4 py-1.5 rounded-lg text-sm font-semibold transition {{ $period === 'this_month' ? 'bg-accent text-white shadow-md' : 'text-muted hover:text-main' }}">
                    Tháng này
                </button>
                <button type="submit" name="period" value="this_year"
                        class="px-4 py-1.5 rounded-lg text-sm font-semibold transition {{ $period === 'this_year' ? 'bg-accent text-white shadow-md' : 'text-muted hover:text-main' }}">
                    Năm nay
                </button>
            </form>

            <!-- Toggle Custom Filter -->
            <button type="button" @click="openCustom = !openCustom"
                    class="btn btn-sm w-full md:w-auto transition-colors"
                    :class="openCustom ? 'btn-primary' : 'btn-ghost'">
                <i class="ph-bold ph-calendar-blank"></i> Tùy chọn ngày
            </button>
        </div>

        <!-- Custom Date Range Form -->
        <form method="GET" action="{{ route('admin.reports.revenue') }}" x-show="openCustom"
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0 -translate-y-2"
              x-transition:enter-end="opacity-100 translate-y-0"
              class="mt-4 pt-4 border-t border-header-border flex flex-wrap items-end gap-4" style="display: none;">
            <input type="hidden" name="period" value="custom">
            
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-muted uppercase mb-2">Từ ngày</label>
                <input type="date" name="start_date" class="w-full py-2 border-transparent focus:bg-dropdown-bg" value="{{ $filters['startDate'] }}" required>
            </div>
            
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-muted uppercase mb-2">Đến ngày</label>
                <input type="date" name="end_date" class="w-full py-2 border-transparent focus:bg-dropdown-bg" value="{{ $filters['endDate'] }}" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-md">
                <i class="ph-bold ph-check"></i> Áp dụng
            </button>
        </form>
    </div>

    <div class="stats-grid grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <x-card class="p-5 var(--bg-color) rounded-xl shadow-sm border border-header-border">
            <div class="icon-box bg-green-100 text-green-600 w-12 h-12 flex items-center justify-center rounded-lg mb-3">
                <i class="ph-fill ph-money text-2xl"></i>
            </div>
            <div class="stat-label text-muted text-sm font-semibold uppercase">Tổng doanh thu</div>
            <div class="stat-value text-3xl font-black mt-1">{{ $totalRevenue }} đ</div>
            <div class="text-muted text-xs mt-2">
                <i class="ph ph-calendar text-muted"></i> Trong khoảng thời gian đã chọn
            </div>
        </x-card>

        <x-card class="p-5 var(--bg-color) rounded-xl shadow-sm border border-header-border">
            <div class="icon-box bg-blue-100 text-blue-600 w-12 h-12 flex items-center justify-center rounded-lg mb-3">
                <i class="ph-fill ph-credit-card text-2xl"></i>
            </div>
            <div class="stat-label text-muted text-sm font-semibold uppercase">Doanh thu Vé tháng</div>
            <div class="stat-value text-3xl font-black mt-1">{{ $monthlyRevenue }} đ</div>
            <div class="text-muted text-xs mt-2">
                <span class="text-green-500 font-bold">{{ $monthlyPercent }}%</span> tổng doanh thu
            </div>
        </x-card>

        <x-card class="p-5 var(--bg-color) rounded-xl shadow-sm border border-header-border">
            <div class="icon-box bg-orange-100 text-orange-600 w-12 h-12 flex items-center justify-center rounded-lg mb-3">
                <i class="ph-fill ph-ticket text-2xl"></i>
            </div>
            <div class="stat-label text-muted text-sm font-semibold uppercase">Doanh thu Vé lượt</div>
            <div class="stat-value text-3xl font-black mt-1">{{ $casualRevenue }} đ</div>
            <div class="text-muted text-xs mt-2">
                <span class="text-orange-500 font-bold">{{ $casualPercent }}%</span> tổng doanh thu
            </div>
        </x-card>
    </div>

    <x-card class="p-6 var(--bg-color) rounded-xl shadow-sm border border-header-border mt-6">
        <h3 class="text-xl font-bold var(--text-main) mb-6">Xu hướng doanh thu</h3>
        <div id="revenueChart" class="w-full h-[350px]"></div>
    </x-card>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Nhận mảng dữ liệu từ Laravel bọc vào JSON
            const chartDates = {!! json_encode($chartDates) !!};
            const chartTotals = {!! json_encode($chartTotals) !!};

            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const textColor = isDark ? '#f8fafc' : '#1e293b';
            const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';

            const options = {
                series: [{
                    name: 'Doanh thu',
                    data: chartTotals
                }],
                chart: {
                    type: 'area',
                    height: 350,
                    fontFamily: 'inherit',
                    toolbar: { show: false },
                    background: 'transparent'
                },
                colors: ['#6366f1'], // Màu chủ đạo (var(--accent-primary))
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                grid: {
                    borderColor: gridColor,
                    strokeDashArray: 4,
                },
                xaxis: {
                    categories: chartDates,
                    type: 'datetime', // Tự động format ngày ở trục X
                    labels: {
                        style: { colors: textColor }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        style: { colors: textColor },
                        formatter: function (value) {
                            return new Intl.NumberFormat('vi-VN').format(value) +" đ";
                        }
                    }
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light',
                    y: {
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

            // Listen for theme switch to update chart colors dynamically
            document.getElementById('themeToggle')?.addEventListener('change', function(e) {
                const newTextColor = e.target.checked ? '#f8fafc' : '#1e293b';
                const newGridColor = e.target.checked ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
                const newTheme = e.target.checked ? 'dark' : 'light';
                
                chart.updateOptions({
                    grid: { borderColor: newGridColor },
                    xaxis: { labels: { style: { colors: newTextColor } } },
                    yaxis: { labels: { style: { colors: newTextColor } } },
                    tooltip: { theme: newTheme }
                });
            });
        });
    </script>
@endsection
