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
                    <th class="p-4">Image</th>
                    <th class="p-4 text-right">Cost</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-black/5">
                    <td class="p-4 text-[var(--text-main)]">09:12 AM <br><span class="text-[0.85rem] text-[var(--text-muted)]">Today</span></td>
                    <td class="p-4">
                        <span class="bg-[#10b981]/20 text-[#34d399] px-2.5 py-1 rounded-[20px] text-[0.75rem] font-semibold">IN</span>
                    </td>
                    <td class="p-4">
                        <div class="font-semibold font-mono text-[1rem] text-[var(--text-main)]">29A-12345</div>
                        <div class="text-[0.85rem] text-[var(--text-muted)]">Card: A1B2</div>
                    </td>
                    <td class="p-4">
                        <div class="w-[80px] h-[50px] bg-black/10 rounded-md flex items-center justify-center text-[0.75rem] text-[var(--text-muted)]">Img</div>
                    </td>
                    <td class="p-4 text-right text-[var(--text-main)]">
                        -
                    </td>
                </tr>
                <tr class="border-none">
                    <td class="p-4 text-[var(--text-main)]">08:30 AM <br><span class="text-[0.85rem] text-[var(--text-muted)]">Today</span></td>
                    <td class="p-4">
                        <span class="bg-[#ef4444]/20 text-[#ef4444] px-2.5 py-1 rounded-[20px] text-[0.75rem] font-semibold">OUT</span>
                    </td>
                    <td class="p-4">
                        <div class="font-semibold font-mono text-[1rem] text-[var(--text-main)]">30H-98765</div>
                        <div class="text-[0.85rem] text-[var(--text-muted)]">Card: E5F6</div>
                    </td>
                    <td class="p-4">
                        <div class="w-[80px] h-[50px] bg-black/10 rounded-md flex items-center justify-center text-[0.75rem] text-[var(--text-muted)]">Img</div>
                    </td>
                    <td class="p-4 text-right text-[#10b981] font-semibold">
                        5,000 đ
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
