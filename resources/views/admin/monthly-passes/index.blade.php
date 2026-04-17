@extends('admin.admin-layout')
@section('title', 'Monthly Passes - Admin')
@section('page-title', 'Monthly Passes')

@section('content')
<div class="premium-card mb-6">
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div class="flex gap-4 flex-1 min-w-[300px]">
            <div class="relative flex-1">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-[var(--text-muted)]"></i>
                <input type="text" class="form-control pl-10 mb-0" placeholder="Search by customer, license plate...">
            </div>
            <select class="form-control w-[150px] mb-0">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="expired">Expired</option>
            </select>
            <button class="btn btn-primary px-6 py-[0.8rem] bg-[var(--accent-primary)] text-white border-none rounded-lg font-medium cursor-pointer text-center">
                Filter
            </button>
        </div>
        <button class="btn btn-primary px-6 py-[0.8rem] bg-[var(--accent-secondary)] text-white border-none rounded-lg font-medium cursor-pointer flex items-center gap-2">
            <i class="ph-bold ph-plus"></i> Add New Pass
        </button>
    </div>
</div>

<div class="premium-card">
    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-left">
            <thead>
                <tr class="border-b-2 border-black/5 text-[var(--text-muted)] text-[0.85rem] uppercase tracking-[0.5px]">
                    <th class="p-4">Customer</th>
                    <th class="p-4">License Plate</th>
                    <th class="p-4">Vehicle Type</th>
                    <th class="p-4">Valid From</th>
                    <th class="p-4">Valid To</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Row 1 -->
                <tr class="border-b border-black/5">
                    <td class="p-4 font-medium text-[var(--text-main)]">Nguyen Van A</td>
                    <td class="p-4"><span class="bg-black/5 px-2 py-1 rounded font-mono text-base font-semibold">29A-12345</span></td>
                    <td class="p-4">Car</td>
                    <td class="p-4">01/05/2026</td>
                    <td class="p-4">01/06/2026</td>
                    <td class="p-4">
                        <span class="bg-[#10b981]/20 text-[#34d399] px-2.5 py-1 rounded-full text-xs font-semibold">ACTIVE</span>
                    </td>
                    <td class="p-4 text-right">
                        <button class="bg-transparent border-none text-[var(--text-muted)] cursor-pointer mr-2"><i class="ph ph-pencil-simple text-xl"></i></button>
                        <button class="bg-transparent border-none text-red-500 cursor-pointer"><i class="ph ph-trash text-xl"></i></button>
                    </td>
                </tr>
                <!-- Row 2 -->
                <tr class="border-b border-black/5">
                    <td class="p-4 font-medium text-[var(--text-main)]">Tran Thi B</td>
                    <td class="p-4"><span class="bg-black/5 px-2 py-1 rounded font-mono text-base font-semibold">30H-98765</span></td>
                    <td class="p-4">Motorbike</td>
                    <td class="p-4">15/03/2026</td>
                    <td class="p-4">15/04/2026</td>
                    <td class="p-4">
                        <span class="bg-[#ef4444]/20 text-red-500 px-2.5 py-1 rounded-full text-xs font-semibold">EXPIRED</span>
                    </td>
                    <td class="p-4 text-right">
                        <button class="bg-transparent border-none text-[var(--text-muted)] cursor-pointer mr-2"><i class="ph ph-pencil-simple text-xl"></i></button>
                        <button class="bg-transparent border-none text-red-500 cursor-pointer"><i class="ph ph-trash text-xl"></i></button>
                    </td>
                </tr>
                <!-- Row 3 -->
                <tr class="border-none">
                    <td class="p-4 font-medium text-[var(--text-main)]">Le Van C</td>
                    <td class="p-4"><span class="bg-black/5 px-2 py-1 rounded font-mono text-base font-semibold">51F-11223</span></td>
                    <td class="p-4">Car</td>
                    <td class="p-4">20/04/2026</td>
                    <td class="p-4">20/07/2026</td>
                    <td class="p-4">
                        <span class="bg-[#10b981]/20 text-[#34d399] px-2.5 py-1 rounded-full text-xs font-semibold">ACTIVE</span>
                    </td>
                    <td class="p-4 text-right">
                        <button class="bg-transparent border-none text-[var(--text-muted)] cursor-pointer mr-2"><i class="ph ph-pencil-simple text-xl"></i></button>
                        <button class="bg-transparent border-none text-red-500 cursor-pointer"><i class="ph ph-trash text-xl"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex justify-between items-center mt-6 border-t border-black/5 pt-6">
        <div class="text-[var(--text-muted)] text-[0.85rem]">Showing 1 to 3 of 124 entries</div>
        <div class="flex gap-2">
            <button class="px-4 py-2 border border-black/10 bg-transparent text-[var(--text-muted)] rounded-md cursor-pointer">Previous</button>
            <button class="px-4 py-2 border-none bg-[var(--accent-primary)] text-white rounded-md cursor-pointer">1</button>
            <button class="px-4 py-2 border border-black/10 bg-transparent text-[var(--text-main)] rounded-md cursor-pointer">2</button>
            <button class="px-4 py-2 border border-black/10 bg-transparent text-[var(--text-main)] rounded-md cursor-pointer">3</button>
            <button class="px-4 py-2 border border-black/10 bg-transparent text-[var(--text-muted)] rounded-md cursor-pointer">Next</button>
        </div>
    </div>
</div>
@endsection
