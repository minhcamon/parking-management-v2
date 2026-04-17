@extends('staff.staff-layout')
@section('title', 'Search - Staff')

@section('content')
<div class="max-w-[800px] mx-auto">
    
    <div class="text-center mb-12 mt-8">
        <i class="ph-fill ph-magnifying-glass text-[3rem] text-[var(--accent-primary)] mb-4"></i>
        <h2 class="font-['Outfit'] text-[2rem] text-[var(--text-main)] mb-2">Quick Search</h2>
        <p class="text-[var(--text-muted)]">Lookup vehicle passing info by Card ID or License Plate</p>
    </div>

    <div class="premium-card mb-8 !p-[10px]">
        <div class="flex items-center">
            <input type="text" placeholder="Enter License Plate or scan Card..." class="flex-1 p-6 text-[1.2rem] border-none bg-transparent text-[var(--text-main)] outline-none font-mono" autofocus>
            <button class="btn btn-primary px-10 py-[1.2rem] bg-[var(--accent-primary)] text-white border-none rounded-xl font-semibold text-[1.1rem] cursor-pointer mr-[5px]">
                Search
            </button>
        </div>
    </div>

    <!-- Example Result Placeholder: Found Monthly Pass -->
    <div class="premium-card border-l-[5px] border-l-[#10b981]">
        <div class="flex justify-between items-start mb-6">
            <div>
                <span class="bg-[#10b981]/20 text-[#34d399] px-3 py-1.5 rounded-[20px] text-[0.85rem] font-bold tracking-[0.5px]"><i class="ph-fill ph-check-circle"></i> MATCH FOUND</span>
            </div>
            <div class="text-right">
                <div class="font-['Outfit'] text-[1.2rem] font-bold text-[var(--text-main)]">Monthly Pass</div>
                <div class="text-[#10b981] font-semibold">ACTIVE</div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 border-t border-black/5 pt-6">
            <div>
                <div class="text-[var(--text-muted)] text-[0.85rem] mb-1.5">License Plate</div>
                <div class="text-[1.5rem] font-bold font-mono text-[var(--text-main)]">29A-12345</div>
            </div>
            <div>
                <div class="text-[var(--text-muted)] text-[0.85rem] mb-1.5">Owner</div>
                <div class="text-[1.2rem] font-semibold text-[var(--text-main)]">Nguyen Van A</div>
            </div>
            <div>
                <div class="text-[var(--text-muted)] text-[0.85rem] mb-1.5">Card ID Assigned</div>
                <div class="text-[1.2rem] font-semibold text-[var(--text-main)]">A1B2C3D4</div>
            </div>
            <div>
                <div class="text-[var(--text-muted)] text-[0.85rem] mb-1.5">Expiration Date</div>
                <div class="text-[1.2rem] font-semibold text-[var(--text-main)]">01/10/2026 <span class="text-[0.9rem] text-[#10b981]">(160 days left)</span></div>
            </div>
        </div>
    </div>

</div>
@endsection
