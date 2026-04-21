@extends('staff.staff-layout')
@section('title', 'History - Staff')

@section('content')
    <div class="premium-card max-w-[1000px] mx-auto mb-6">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <h3 class="font-['Outfit'] text-[1.2rem] m-0">My Shift History</h3>

            <div class="flex gap-4 w-full max-w-[400px]">
                <div class="relative flex-1">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-[var(--text-muted)]"></i>
                    <input type="text" class="form-control pl-10 mb-0" placeholder="Search license plate...">
                </div>
                <select class="form-control w-[120px] mb-0">
                    <option value="">All</option>
                    <option value="in">Check In</option>
                    <option value="out">Check Out</option>
                </select>
            </div>
        </div>
    </div>

    <div class="premium-card max-w-[1000px] mx-auto">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                <tr class="border-b-2 border-black/5 text-[var(--text-muted)] text-[0.85rem] uppercase tracking-[0.5px]">
                    <th class="p-4">Time</th>
                    <th class="p-4">Type</th>
                    <th class="p-4">Vehicle Info</th>
                    <th class="p-4 text-right">Cost</th>
                </tr>
                </thead>
                <tbody>

                @forelse($history as $item)
                    <tr class="border-b border-black/5 last:border-none hover:bg-gray-50 transition-colors">

                        <td class="p-4 text-[var(--text-main)]">
                            {{ $item['time'] }} <br>
                            <span class="text-[0.85rem] text-[var(--text-muted)]">{{ $item['date_label'] }}</span>
                        </td>

                        <td class="p-4">
                        <span class="{{ $item['type_class'] }} px-2.5 py-1 rounded-[20px] text-[0.75rem] font-semibold">
                            {{ $item['type'] }}
                        </span>
                        </td>

                        <td class="p-4">
                            <div class="font-semibold font-mono text-[1rem] text-[var(--text-main)]">
                                {{ $item['license_plate'] }}
                            </div>
                            <div class="text-[0.85rem] text-[var(--text-muted)]">
                                Card: {{ $item['card_code'] }}
                            </div>
                        </td>

                        <td class="p-4 text-right {{ $item['cost'] !== '-' ? 'text-[#10b981] font-semibold' : 'text-[var(--text-main)]' }}">
                            {{ $item['cost'] }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-[var(--text-muted)] italic">
                            <i class="ph ph-clock text-3xl mb-2 block"></i>
                            No history records found for this shift.
                        </td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>

        @if($history->hasPages())
            <div class="mt-4 pt-4 border-t border-black/5">
                {{ $history->links() }}
            </div>
        @endif
    </div>
@endsection
