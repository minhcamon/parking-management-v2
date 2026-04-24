@extends('staff.staff-layout')
@section('title', 'Lịch sử - Nhân viên')

@section('content')
    <x-card class="max-w-[1000px] mx-auto mb-6">
        <form action="{{ route('staff.history.index') }}" method="GET" class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h3 class="text-xl m-0">Lịch sử làm việc</h3>

            <div class="flex flex-wrap gap-2 w-full md:w-auto">
                <div class="relative flex-1 min-w-[200px]">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-muted"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="pl-10 mb-0" placeholder="Biển số hoặc RFID...">
                </div>
                <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()" class="w-auto mb-0">
                <select name="type" onchange="this.form.submit()" class="w-[120px] mb-0">
                    <option value="">Tất cả</option>
                    <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Vào bãi</option>
                    <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Ra bãi</option>
                </select>
                <button type="submit" class="btn btn-sm btn-primary">
                    Lọc
                </button>
                @if(request()->hasAny(['search', 'type', 'date']))
                    <a href="{{ route('staff.history.index') }}" class="bg-dropdown-bg p-2 rounded-lg text-red-500 hover:bg-red-500/10 transition border border-header-border flex items-center justify-center">
                        <i class="ph-bold ph-arrow-counter-clockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </x-card>

    <x-card class="max-w-[1000px] mx-auto">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                <tr class="border-b-2 border-header-border text-muted text-sm uppercase tracking-[0.5px]">
                    <th class="p-4">Thời gian</th>
                    <th class="p-4">Hành động</th>
                    <th class="p-4">Thông tin xe</th>
                    <th class="p-4 text-right">Thanh toán</th>
                </tr>
                </thead>
                <tbody>

                @forelse($history as $item)
                    <tr class="border-b border-header-border last:border-none hover:bg-dropdown-bg transition-colors">

                        <td class="p-4 text-main">
                            {{ $item['time'] }} <br>
                            <span class="text-sm text-muted">{{ $item['date_label'] }}</span>
                        </td>

                        <td class="p-4">
                        <span class="{{ $item['type_class'] }} px-2.5 py-1 rounded-[20px] text-[0.75rem] font-semibold">
                            {{ $item['type'] }}
                        </span>
                        </td>

                        <td class="p-4">
                            <div class="font-semibold font-mono text-base text-main">
                                {{ $item['license_plate'] }}
                            </div>
                            <div class="text-sm text-muted">
                                Card: {{ $item['card_code'] }}
                            </div>
                        </td>

                        <td class="p-4 text-right {{ $item['cost'] !== '-' ? 'text-[#10b981] font-semibold' : 'text-main' }}">
                            {{ $item['cost'] }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-muted italic">
                            <i class="ph ph-clock text-3xl mb-2 block"></i>
                            No history records found for this shift.
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>

        @if($history->hasPages())
            <div class="mt-4 pt-4 border-t border-header-border">
                {{ $history->links() }}
            </div>
        @endif
    </x-card>
@endsection
