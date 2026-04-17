@extends('admin.admin-layout')
@section('title', 'Revenue Reports - Admin')
@section('page-title', 'Revenue Analytics')

@section('content')
<div class="premium-card mb-6">
    <div class="flex justify-between items-center flex-wrap gap-4">
        <h3 class="font-['Outfit'] text-[1.2rem] m-0">Report Filters</h3>
        <div class="flex gap-4">
            <input type="date" class="form-control mb-0" value="2026-04-01">
            <span class="flex items-center text-[var(--text-muted)]">to</span>
            <input type="date" class="form-control mb-0" value="2026-04-17">
            <button class="btn btn-primary px-6 py-[0.8rem] bg-[var(--accent-primary)] text-white border-none rounded-lg font-medium cursor-pointer">
                Apply Filter
            </button>
        </div>
    </div>
</div>

<div class="stats-grid">
    <!-- KPI 1 -->
    <div class="premium-card">
        <div class="icon-box bg-success-soft">
            <i class="ph-fill ph-money"></i>
        </div>
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value">45,120,000 đ</div>
        <div class="text-[var(--text-muted)] text-[0.85rem]">
            <i class="ph ph-trend-up text-[#34d399]"></i> Period Total
        </div>
    </div>
    
    <!-- KPI 2 -->
    <div class="premium-card">
        <div class="icon-box bg-primary-soft">
            <i class="ph-fill ph-credit-card"></i>
        </div>
        <div class="stat-label">Monthly Passes</div>
        <div class="stat-value">32,500,000 đ</div>
        <div class="text-[var(--text-muted)] text-[0.85rem]">
            <i class="ph ph-trend-up text-[#34d399]"></i> 72% of total
        </div>
    </div>
    
    <!-- KPI 3 -->
    <div class="premium-card">
        <div class="icon-box bg-warning-soft">
            <i class="ph-fill ph-ticket"></i>
        </div>
        <div class="stat-label">Casual Tickets</div>
        <div class="stat-value">12,620,000 đ</div>
        <div class="text-[var(--text-muted)] text-[0.85rem]">
            <i class="ph ph-trend-down text-[#ef4444]"></i> 28% of total
        </div>
    </div>
</div>

<div class="premium-card mt-6">
    <h3 class="font-['Outfit'] text-[1.2rem] mb-6">Revenue Trend</h3>
    <div class="h-[350px] flex items-center justify-center bg-black/2 border border-dashed border-black/10 rounded-xl text-[var(--text-muted)]">
        [ ApexCharts Line Graph Placeholder ]
    </div>
</div>
@endsection
