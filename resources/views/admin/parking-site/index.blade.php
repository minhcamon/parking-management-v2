@extends('admin.admin-layout')
@section('title', 'Parking Site - Admin')
@section('page-title', 'Vehicle Types & Card Management')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-[1fr_2fr] gap-6">
    <!-- Column 1: Vehicle Types -->
    <div class="premium-card">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-['Outfit'] text-[1.2rem]">Vehicle Types</h3>
            <button class="bg-[#6366f1]/10 text-[var(--accent-primary)] border-none w-8 h-8 rounded-lg cursor-pointer flex items-center justify-center">
                <i class="ph-bold ph-plus"></i>
            </button>
        </div>
        
        <div class="flex flex-col gap-4">
            <!-- Type 1 -->
            <div class="p-4 border border-black/5 rounded-xl bg-black/5 flex justify-between items-center">
                <div>
                    <div class="font-semibold text-[var(--text-main)] mb-1">Car</div>
                    <div class="text-[0.85rem] text-[var(--text-muted)]">200 Slots Assigned</div>
                </div>
                <div class="flex gap-2">
                    <button class="bg-transparent border-none text-[var(--text-muted)] cursor-pointer"><i class="ph ph-pencil-simple text-[1.1rem]"></i></button>
                </div>
            </div>
            <!-- Type 2 -->
            <div class="p-4 border border-black/5 rounded-xl bg-black/5 flex justify-between items-center">
                <div>
                    <div class="font-semibold text-[var(--text-main)] mb-1">Motorbike</div>
                    <div class="text-[0.85rem] text-[var(--text-muted)]">500 Slots Assigned</div>
                </div>
                <div class="flex gap-2">
                    <button class="bg-transparent border-none text-[var(--text-muted)] cursor-pointer"><i class="ph ph-pencil-simple text-[1.1rem]"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Column 2: Cards Registry -->
    <div class="premium-card">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-['Outfit'] text-[1.2rem]">Parking Cards Registry</h3>
            <button class="btn btn-primary px-4 py-[0.6rem] bg-[var(--accent-primary)] text-white border-none rounded-lg font-medium cursor-pointer flex items-center gap-2">
                <i class="ph-bold ph-plus"></i> Issue New Cards
            </button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="border-b-2 border-black/5 text-[var(--text-muted)] text-[0.85rem] uppercase tracking-[0.5px]">
                        <th class="p-4">ID</th>
                        <th class="p-4">Card Hex code</th>
                        <th class="p-4">Assigned Type</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-black/5">
                        <td class="p-4 text-[var(--text-main)] font-semibold">1</td>
                        <td class="p-4 font-mono">A1B2C3D4</td>
                        <td class="p-4">Car</td>
                        <td class="p-4">
                            <span class="bg-[#10b981]/20 text-[#34d399] px-2.5 py-1 rounded-[20px] text-[0.75rem] font-semibold">AVAILABLE</span>
                        </td>
                    </tr>
                    <tr class="border-b border-black/5">
                        <td class="p-4 text-[var(--text-main)] font-semibold">2</td>
                        <td class="p-4 font-mono">E5F6G7H8</td>
                        <td class="p-4">Motorbike</td>
                        <td class="p-4">
                            <span class="bg-[#fbbf24]/20 text-[#fbbf24] px-2.5 py-1 rounded-[20px] text-[0.75rem] font-semibold">IN USE</span>
                        </td>
                    </tr>
                    <tr class="border-none">
                        <td class="p-4 text-[var(--text-main)] font-semibold">3</td>
                        <td class="p-4 font-mono">I9J0K1L2</td>
                        <td class="p-4">Car</td>
                        <td class="p-4">
                            <span class="bg-[#ef4444]/20 text-red-500 px-2.5 py-1 rounded-[20px] text-[0.75rem] font-semibold">LOST</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="text-center mt-4">
            <a href="#" class="text-[var(--accent-primary)] no-underline text-[0.9rem] font-medium">View All Cards &rarr;</a>
        </div>
    </div>
</div>
@endsection
