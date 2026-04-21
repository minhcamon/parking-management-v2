@extends('admin.admin-layout')
@section('title', 'Transaction Reports - Admin')
@section('page-title', 'Transaction History')

@section('content')
<div class="premium-card mb-6">
    <div class="grid grid-cols-[repeat(auto-fit,minmax(200px,1fr))] gap-4">
        <div>
            <label class="block mb-2 text-[0.85rem] text-[var(--text-muted)] font-semibold uppercase">Transaction Type</label>
            <select class="form-control mb-0">
                <option value="">All Types</option>
                <option value="in">Check In</option>
                <option value="out">Check Out</option>
            </select>
        </div>
        <div>
            <label class="block mb-2 text-[0.85rem] text-[var(--text-muted)] font-semibold uppercase">Date</label>
            <input type="date" class="form-control mb-0">
        </div>
        <div>
            <label class="block mb-2 text-[0.85rem] text-[var(--text-muted)] font-semibold uppercase">Search Query</label>
            <input type="text" class="form-control mb-0" placeholder="Card ID or License Plate">
        </div>
        <div class="flex items-end">
            <button class="btn btn-primary w-full py-[0.8rem] bg-[var(--accent-primary)] text-white border-none rounded-lg font-medium cursor-pointer">
                Search History
            </button>
        </div>
    </div>
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
                    <tr class="border-b border-black/5">
                        <td class="p-4 text-[var(--text-main)]">{{$t['payment_time']}}</td>
                        <td class="p-4">
                            <span class="bg-[#ef4444]/20 text-red-500 px-2.5 py-1 rounded-[20px] text-[0.75rem] font-semibold">{{$t['type']}}</span>
                        </td>
                        <td class="p-4 font-mono">{{$t['rfid_code']}}</td>
                        <td class="p-4 font-semibold">{{$t['license_plate']}}</td>

                        <td class="p-4 text-[#10b981] font-semibold">{{$t['amount']}}</td>
                        <td class="p-4 text-[var(--text-muted)]">{{$t['staff_name']}}</td>
                    </tr>
                @empty

                @endforelse

            </tbody>
        </table>
    </div>
    <div class="mt-4 flex justify-end">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
