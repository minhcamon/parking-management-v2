@extends('admin.admin-layout')

@section('title', 'Dashboard - ParkGrid')
@section('page-title', 'Overview Statistics')

@push('styles')
<style>
    /* Lock scrolling on Dashboard */
    body { overflow: hidden; }

    .stat-value {
        font-family: 'Outfit', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        margin: 0.5rem 0;
        background: linear-gradient(135deg, #1e293b, #6366f1);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .stat-label {
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
        font-weight: 600;
        display:flex;
        align-items:center;
        gap: 8px;
    }

    .icon-box {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 0.8rem;
    }

    .bg-primary-soft { background: rgba(99, 102, 241, 0.2); color: #818cf8; }
    .bg-success-soft { background: rgba(16, 185, 129, 0.2); color: #34d399; }
    .bg-warning-soft { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }
    .bg-pink-soft { background: rgba(236, 72, 153, 0.2); color: #f472b6; }

    /* Slots List */
    .slot-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.6rem 1rem;
        background: rgba(0,0,0,0.02);
        border-radius: 10px;
        margin-bottom: 0.5rem;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .progress-wrapper {
        flex-grow: 1;
        margin: 0 1.5rem;
        height: 8px;
        background: rgba(0,0,0,0.05);
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
        border-radius: 4px;
    }

    /* Form Styles */
    .form-control {
        width: 100%;
        padding: 0.8rem 1rem;
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.1);
        border-radius: 8px;
        color: var(--text-main);
        margin-bottom: 1rem;
        outline: none;
        transition: border 0.3s;
    }

    .form-control:focus {
        border-color: var(--accent-primary);
    }

    .card-list {
        list-style: none;
        margin-top: 1.5rem;
    }

    .card-list li {
        padding: 0.8rem 0;
        border-bottom: 1px dashed rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.9rem;
    }

    .card-list li:last-child {
        border-bottom: none;
    }
    
    .status-badge {
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .status-available { background: rgba(16, 185, 129, 0.2); color: #34d399; }
    .premium-card {
        padding: 1.5rem !important; /* Override standard 2rem to save space */
    }
    .content {
        padding: 1.5rem 3rem !important; /* Save vertical space in layout */
    }
    .stats-grid {
        gap: 1.5rem !important;
        margin-bottom: 1.5rem !important;
    }
</style>
@endpush

@section('content')

<div class="stats-grid">
    <!-- 1. Total Revenue Card -->
    <div class="premium-card">
        <div class="icon-box bg-success-soft">
            <i class="ph-fill ph-currency-dollar"></i>
        </div>
        <div class="stat-label">Total Revenue</div>
        {{-- Format number to currency style VNĐ --}}
        <div class="stat-value">{{ number_format($totalRevenue, 0, ',', '.') }} đ</div>
        <div class="text-[var(--text-muted)] text-[0.85rem]">
            <i class="ph ph-trend-up text-[#34d399]"></i> +12% from last month
        </div>
    </div>

    <!-- 2. Current Vehicle Slot Remain -->
    <div class="premium-card col-span-2">
        <div class="icon-box bg-primary-soft">
            <i class="ph-fill ph-car-profile"></i>
        </div>
        <div class="stat-label justify-between">
            Live Parking Slots
            <span class="bg-[#6366f1]/10 px-2.5 py-1 rounded-[20px] text-[0.75rem] text-[var(--accent-primary)]">
                <i class="ph-fill ph-identification-card"></i> {{ $totalCards }} Cards Total
            </span>
        </div>
        <div class="mt-6">
            @forelse($vehicleTypes as $type)
                @php
                    $percentage = $type->total_slots > 0 
                        ? (($type->total_slots - $type->slot_remain) / $type->total_slots) * 100 
                        : 0;
                @endphp
                <div class="slot-item">
                    <div class="w-[80px] font-medium">{{ $type->name }}</div>
                    <div class="progress-wrapper">
                        <div class="progress-bar" style="width: {{ $percentage }}%"></div>
                    </div>
                    <div class="w-[100px] text-right">
                        <span class="text-[var(--text-main)] font-bold">{{ $type->slot_remain }}</span> 
                        <span class="text-[var(--text-muted)]">/ {{ $type->total_slots }}</span>
                    </div>
                </div>
            @empty
                <p class="text-[var(--text-muted)]">No vehicle types configured yet.</p>
            @endforelse
        </div>
    </div>
</div>

<div class="stats-grid">
    <!-- 3. Ticket Distribution Chart -->
    <div class="premium-card col-span-2">
        <div class="icon-box bg-pink-soft">
            <i class="ph-fill ph-pie-chart"></i>
        </div>
        <div class="stat-label">Ticket Distribution (Vé Lượt vs Vé Tháng)</div>
        
        <div id="ticketChart" class="mt-4 min-h-[220px]"></div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
        const textColor = isDark ? '#f8fafc' : '#1e293b';

        var options = {
            series: [{{ $chartData['monthly'] ?? 0 }}, {{ $chartData['casual'] ?? 0 }}],
            chart: {
                type: 'donut',
                height: 220,
                fontFamily: 'Inter, sans-serif',
                background: 'transparent'
            },
            labels: ['Vé Tháng (Monthly)', 'Vé Lượt (Casual)'],
            colors: ['#6366f1', '#ec4899'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            name: { color: textColor },
                            value: { color: textColor, fontSize: '1.5rem', fontWeight: 600 },
                            total: {
                                show: true,
                                showAlways: true,
                                label: 'Total',
                                color: textColor,
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            legend: {
                position: 'bottom',
                labels: { colors: textColor }
            },
            stroke: { show: false }
        };

        var chart = new ApexCharts(document.querySelector("#ticketChart"), options);
        chart.render();

        // Listen for theme switch to update chart colors dynamically
        document.getElementById('themeToggle')?.addEventListener('change', function(e) {
            const newColor = e.target.checked ? '#f8fafc' : '#1e293b';
            chart.updateOptions({
                plotOptions: { pie: { donut: { labels: { name: { color: newColor }, value: { color: newColor }, total: { color: newColor } } } } },
                legend: { labels: { colors: newColor } }
            });
        });
    });
</script>
@endpush
