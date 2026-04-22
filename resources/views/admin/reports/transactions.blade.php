@extends('admin.admin-layout')
@section('title', 'Transaction Reports - Admin')
@section('page-title', 'Transaction History')

@section('content')
<div class="premium-card mb-6">
    <form action="{{ route('admin.reports.transactions') }}" method="GET" class="grid grid-cols-[repeat(auto-fit,minmax(200px,1fr))] gap-4">
        <div>
            <label class="block mb-2 text-[0.85rem] text-[var(--text-muted)] font-semibold uppercase">Transaction Type</label>
            <select name="type" onchange="this.form.submit()" class="form-control mb-0">
                <option value="">All Types</option>
                <option value="casual" {{ request('type') == 'casual' ? 'selected' : '' }}>Vé lượt</option>
                <option value="monthly" {{ request('type') == 'monthly' ? 'selected' : '' }}>Vé tháng</option>
            </select>
        </div>
        <div>
            <label class="block mb-2 text-[0.85rem] text-[var(--text-muted)] font-semibold uppercase">Date</label>
            <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()" class="form-control mb-0">
        </div>
        <div>
            <label class="block mb-2 text-[0.85rem] text-[var(--text-muted)] font-semibold uppercase">Search Query</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control mb-0" placeholder="Card ID or License Plate">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="btn btn-primary flex-1 py-[0.8rem] bg-[var(--accent-primary)] text-white border-none rounded-lg font-medium cursor-pointer">
                Search
            </button>
            @if(request()->hasAny(['type', 'date', 'search']))
                <a href="{{ route('admin.reports.transactions') }}" class="btn bg-black/5 p-3 rounded-lg text-red-500 flex items-center justify-center">
                    <i class="ph ph-x-circle text-xl"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<div class="premium-card">
    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-left">
            <thead>
                <tr class="border-b-2 border-black/5 text-[var(--text-muted)] text-[0.85rem] uppercase tracking-[0.5px]">
                    <th class="p-4">Time</th>
                    <th class="p-4">Type</th>
                    <th class="p-4">Card ID</th>
                    <th class="p-4">License Plate</th>
                    <th class="p-4">Amount</th>
                    <th class="p-4">Operator</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                    <tr class="border-b border-black/5 hover:bg-black/5 transition">
                        <td class="p-4 text-[var(--text-main)] font-medium text-sm">{{$t['payment_time']}}</td>
                        <td class="p-4">
                            <span class="{{$t['bg_color']}} {{$t['text_color']}} px-2.5 py-1 rounded-[20px] text-[0.7rem] font-bold uppercase tracking-wider">
                                {{$t['type']}}
                            </span>
                        </td>
                        <td class="p-4 font-mono text-sm text-[var(--text-muted)]">{{$t['rfid_code']}}</td>
                        <td class="p-4 font-semibold text-sm">
                            <span class="bg-black/5 px-2 py-1 rounded">{{$t['license_plate']}}</span>
                        </td>
                        <td class="p-4 text-[#10b981] font-bold">{{$t['amount']}}</td>
                        <td class="p-4 text-[var(--text-muted)] text-sm">
                            <div class="flex items-center gap-2">
                                <i class="ph ph-user-circle text-lg"></i>
                                {{$t['staff_name']}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-[var(--text-muted)] italic bg-black/5 rounded-xl">
                            <i class="ph ph-detective text-4xl block mb-2 opacity-20"></i>
                            Không có giao dịch nào khớp với bộ lọc của bạn.
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>
    <div class="mt-4 flex justify-end">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
