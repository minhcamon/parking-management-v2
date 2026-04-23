@extends('staff.staff-layout')

@section('title', 'Terminal - ParkGrid')

@push('styles')
<style>
    /* Lock scrolling on Dashboard */
    body { overflow: hidden; }

    .pos-grid {
        display: grid;
        /* Đổi từ 1fr 400px sang 6fr 4fr để chia đúng tỉ lệ 6-4 */
        grid-template-columns: 6fr 4fr;
        gap: 1.5rem;
        height: calc(100vh - 140px);
    }

    @media (max-width: 1024px) {
        .pos-grid {
            /* Giữ nguyên cái này để hiển thị 1 cột trên màn hình nhỏ */
            grid-template-columns: 1fr;
            height: auto;
        }
    }

    /* Left Panel: Scanning Area */
    .scan-panel {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    /* Alpine.js Tabs */
    .tab-nav {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        background: var(--glass-bg);
        padding: 0.5rem;
        border-radius: 16px;
        border: 1px solid var(--glass-border);
    }

    .tab-btn {
        flex: 1 1 0;
        width: 100%;
        padding: 0.8rem;
        text-align: center;
        border-radius: 12px;
        font-size: 1.1rem;
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        border: none;
        background: transparent;
        color: var(--text-muted);
    }

    .tab-btn.active-in {
        background: linear-gradient(135deg, var(--accent-primary), #4f46e5);
        color: white;
        box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
    }

    .tab-btn.active-out {
        background: linear-gradient(135deg, #10b981, #059669); /* Green for checkout */
        color: white;
        box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.4);
    }

    .input-giant {
        width: 100%;
        padding: 1.5rem;
        font-size: 2rem;
        text-align: center;
        border-radius: 20px;
        border: 2px dashed rgba(99, 102, 241, 0.5);
        background: rgba(255, 255, 255, 0.5);
        color: var(--text-main);
        outline: none;
        font-family: monospace;
        letter-spacing: 2px;
        transition: all 0.3s;
    }

    :root[data-theme="dark"] .input-giant {
        background: rgba(0, 0, 0, 0.2);
        border-color: rgba(99, 102, 241, 0.3);
    }

    .input-giant:focus {
        border-color: var(--accent-primary);
        border-style: solid;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .form-input {
        width: 100%;
        height: 48px;
        padding: 0.8rem 1rem;
        background: rgba(0,0,0,0.02);
        border: 1px solid var(--glass-border);
        border-radius: 10px;
        color: var(--text-main);
        outline: none;
        transition: all 0.3s;
        font-family: inherit;
        font-size: 1rem;
    }
    :root[data-theme="dark"] .form-input {
        background: rgba(0,0,0,0.2);
        border-color: rgba(255,255,255,0.1);
    }
    .form-input:focus {
        border-color: var(--accent-primary);
    }

    /* Right Panel: Side Widgets */
    .widget-panel {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        height: 100%;
    }

    .info-widget {
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
    }

    .widget-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-muted);
    }

    .slot-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.8rem 0;
        border-bottom: 1px dashed var(--header-border);
    }
    .slot-row:last-child { border-bottom: none; }

    /* Scrollable History View */
    .history-list {
        flex-grow: 1;
        overflow-y: auto;
        height: 0; /* Let flex-grow control size */
        min-height: 200px;
        padding-right: 10px;
    }

    .history-list::-webkit-scrollbar { width: 6px; }
    .history-list::-webkit-scrollbar-track { background: transparent; }
    .history-list::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }

    .history-item {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        background: rgba(0,0,0,0.02);
        border-radius: 12px;
        margin-bottom: 0.5rem;
        align-items: center;
    }
    :root[data-theme="dark"] .history-item { background: rgba(0,0,0,0.2); }

    .badge-in { background: rgba(99, 102, 241, 0.1); color: var(--accent-primary); padding: 4px 8px; border-radius: 6px; font-weight:bold; font-size: 0.8rem; }
    .badge-out { background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 4px 8px; border-radius: 6px; font-weight:bold; font-size: 0.8rem; }
</style>
@endpush

@section('content')
<div class="pos-grid" x-data="{ mode: 'checkin' }">
    <!-- LEFT PANEL: SCAN AREA -->
    <x-card class="scan-panel">
        <div class="tab-nav">
            <button class="tab-btn"
                    :class="mode === 'checkin' ? 'active-in' : ''"
                    @click="mode = 'checkin'; $nextTick(() => $refs.scanInput.focus())">
                <i class="ph-fill ph-arrow-circle-right"></i> CHECK IN
            </button>
            <button class="tab-btn"
                    :class="mode === 'checkout' ? 'active-out' : ''"
                    @click="mode = 'checkout'; $nextTick(() => $refs.scanInput.focus())">
                <i class="ph-fill ph-arrow-circle-left"></i> CHECK OUT
            </button>
        </div>

        <div class="grow flex flex-col justify-center">

            <form method="POST"
                  :action="mode === 'checkin' ? '{{ route('staff.check-in') }}' : '{{ route('staff.check-out') }}'">
                @csrf
                <input type="hidden" name="action_type" x-model="mode">

                <div class="flex flex-col gap-4 text-left min-h-[200px]">
                    <!-- RFID Scan -->
                    <div>
                        <label class="font-semibold text-muted text-sm mb-1.5 block">1. MÃ THẺ (RFID)</label>
                        <input type="text"
                            name="rfid_code"
                            class="input-giant p-4 text-2xl rounded-xl mb-0"
                            placeholder="QUẸT THẺ VÀO ĐÂY..."
                            x-ref="scanInput"
                            autofocus
                            autocomplete="off">
                    </div>

                    <div class="flex flex-col gap-4">

    <div>
        <label class="font-semibold text-muted text-sm mb-1.5 block">2. BIỂN SỐ XE</label>
        <input type="text"
            name="license_plate"
            class="form-input"
            placeholder="Nhập biển số xe (VD: 29A12345)...">
    </div>

    <div x-show="mode === 'checkin'"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;"
         x-init="$el.style.display = 'block'">

        <label class="font-semibold text-muted text-sm mb-1.5 block">3. LOẠI XE</label>
        <select name="vehicle_type_id" class="form-input">
            <option value="1">Xe Máy (MOTO)</option>
            <option value="2">Ô tô (CAR)</option>
        </select>
    </div>

</div>
                </div>

                <button type="submit" class="btn-gradient w-full mt-6 justify-center text-lg p-4 rounded-xl">
                    <i class="ph-fill ph-check-circle text-2xl"></i>
                    <span class="font-bold">XÁC NHẬN <span x-text="mode === 'checkin' ? 'VÀO BÃI' : 'RA BÃI'"></span></span>
                </button>

            </form>
        </div>
    </x-card>


    <!-- RIGHT PANEL: WIDGETS -->
    <div class="widget-panel">

        <!-- Widget 1: Parking Slots -->
        <div class="info-widget">
            <div class="widget-title">
                <i class="ph-fill ph-garage"></i> LIVE AVAILABILITY
            </div>
{{--            <!-- Mock Data -->--}}
{{--            <div class="slot-row">--}}
{{--                <div class="flex items-center gap-2">--}}
{{--                    <i class="ph-fill ph-motorcycle text-2xl text-muted"></i>--}}
{{--                    <strong>Xe Máy</strong>--}}
{{--                </div>--}}
{{--                <div>--}}
{{--                    <span class="text-xl font-bold text-[#10b981]">185</span>--}}
{{--                    <span class="text-muted">/ 300</span>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="slot-row">--}}
{{--                <div class="flex items-center gap-2">--}}
{{--                    <i class="ph-fill ph-car text-2xl text-muted"></i>--}}
{{--                    <strong>Ô Tô</strong>--}}
{{--                </div>--}}
{{--                <div>--}}
{{--                    <span class="text-xl font-bold text-[#f59e0b]">5</span>--}}
{{--                    <span class="text-muted">/ 50</span>--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="slots-container">
                @foreach($liveAvailability as $slot)
                    <div class="slot-row">
                        <div class="flex items-center gap-2">
                            <i class="ph-fill {{ $slot['icon'] }} text-2xl text-muted"></i>
                            <strong>{{ $slot['name'] }}</strong>
                        </div>
                        <div>
                <span class="text-xl font-bold {{ $slot['color_class'] }}">
                    {{ $slot['occupied'] }}
                </span>
                            <span class="text-muted">/ {{ $slot['total'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        <!-- Widget 2: Recent History -->
        <div class="info-widget grow flex flex-col overflow-hidden">
            <div class="widget-title">
                <i class="ph-fill ph-clock-counter-clockwise"></i> RECENT ACTIVITY
            </div>

                <div class="history-list overflow-y-auto">
                    @forelse($recentActivity as $activity)
                        <div class="history-item flex items-center p-3 border-b border-header-border last:border-0">

                            <div class="{{ $activity['badge_class'] }} mr-3">
                                {{ $activity['type'] }}
                            </div>

                            <div class="grow">
                                <div class="font-medium text-main">{{ $activity['plate'] }}</div>
                                <div class="text-[0.8rem] text-muted mt-0.5">
                                    {{$activity['rfid']  }} • {{ $activity['vehicle'] }}
                                </div>
                            </div>

                            <div class="text-[0.8rem] text-muted text-right">
                                <span>{{ $activity['time'] }}</span><br>

                                @if($activity['amount'] > 0)
                                    <span class="text-[#10b981] font-bold block mt-0.5">
                            +{{ number_format($activity['amount'], 0, ',', '.') }}đ
                        </span>
                                @endif
                            </div>

                        </div>
                    @empty
                        <div class="p-5 text-center text-muted italic">
                            Chưa có hoạt động nào.
                        </div>
                    @endforelse
                </div>
            </div>

    </div>

</div>
@endsection
