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
                    <th class="p-4">Image</th>
                    <th class="p-4">Cost</th>
                    <th class="p-4">Operator</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-black/5">
                    <td class="p-4 text-[var(--text-main)]">17/04/2026 09:12:45</td>
                    <td class="p-4">
                        <span class="bg-[#10b981]/20 text-[#34d399] px-2.5 py-1 rounded-[20px] text-[0.75rem] font-semibold">IN</span>
                    </td>
                    <td class="p-4 font-mono">A1B2C3D4</td>
                    <td class="p-4 font-semibold">29A-12345</td>
                    <td class="p-4">
                        <div class="w-[60px] h-[40px] bg-black/10 rounded-md flex items-center justify-center text-[0.75rem] text-[var(--text-muted)]">Img</div>
                    </td>
                    <td class="p-4 text-[var(--text-main)]">-</td>
                    <td class="p-4 text-[var(--text-muted)]">staff1</td>
                </tr>
                <tr class="border-b border-black/5">
                    <td class="p-4 text-[var(--text-main)]">17/04/2026 08:30:10</td>
                    <td class="p-4">
                        <span class="bg-[#ef4444]/20 text-red-500 px-2.5 py-1 rounded-[20px] text-[0.75rem] font-semibold">OUT</span>
                    </td>
                    <td class="p-4 font-mono">E5F6G7H8</td>
                    <td class="p-4 font-semibold">30H-98765</td>
                    <td class="p-4">
                        <div class="w-[60px] h-[40px] bg-black/10 rounded-md flex items-center justify-center text-[0.75rem] text-[var(--text-muted)]">Img</div>
                    </td>
                    <td class="p-4 text-[#10b981] font-semibold">5,000 đ</td>
                    <td class="p-4 text-[var(--text-muted)]">staff1</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination Placeholder -->
    <div class="flex justify-center mt-6 pt-6 border-t border-black/5">
        <div class="flex gap-2">
            <button class="px-4 py-2 border border-black/10 bg-transparent text-[var(--text-muted)] rounded-md cursor-pointer">Previous</button>
            <button class="px-4 py-2 border-none bg-[var(--accent-primary)] text-white rounded-md cursor-pointer">1</button>
            <button class="px-4 py-2 border border-black/10 bg-transparent text-[var(--text-main)] rounded-md cursor-pointer">2</button>
            <button class="px-4 py-2 border border-black/10 bg-transparent text-[var(--text-muted)] rounded-md cursor-pointer">Next</button>
        </div>
    </div>
</div>
@endsection
