@extends('staff.staff-layout')
@section('title', 'Register Pass - Staff')

@section('content')
<form>
    <div class="premium-card max-w-[800px] mx-auto mb-6">
        <h3 class="font-['Outfit'] text-[1.2rem] mb-6 flex items-center gap-2 border-b border-black/5 pb-4">
            <i class="ph-fill ph-user text-[var(--accent-primary)]"></i> Customer Information
        </h3>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 text-[0.85rem] font-medium">Full Name <span class="text-red-500">*</span></label>
                <input type="text" class="form-control" placeholder="Nguyen Van A" required>
            </div>
            <div>
                <label class="block mb-2 text-[0.85rem] font-medium">Phone Number <span class="text-red-500">*</span></label>
                <input type="tel" class="form-control" placeholder="0901234567" required>
            </div>
            <div class="col-span-2">
                <label class="block mb-2 text-[0.85rem] font-medium">Email Address</label>
                <input type="email" class="form-control" placeholder="customer@example.com">
            </div>
        </div>
    </div>

    <div class="premium-card max-w-[800px] mx-auto mb-6">
        <h3 class="font-['Outfit'] text-[1.2rem] mb-6 flex items-center gap-2 border-b border-black/5 pb-4">
            <i class="ph-fill ph-car text-[var(--accent-secondary)]"></i> Vehicle Details
        </h3>
        
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="block mb-2 text-[0.85rem] font-medium">License Plate <span class="text-red-500">*</span></label>
                <input type="text" class="form-control uppercase font-mono text-[1.1rem] font-semibold" placeholder="e.g. 29A-12345" required>
            </div>
            <div>
                <label class="block mb-2 text-[0.85rem] font-medium">Vehicle Type <span class="text-red-500">*</span></label>
                <select class="form-control" required>
                    <option value="">Select Type</option>
                    <option value="1">Car</option>
                    <option value="2">Motorbike</option>
                </select>
            </div>
            <div>
                <label class="block mb-2 text-[0.85rem] font-medium">Brand / Color</label>
                <input type="text" class="form-control" placeholder="Honda / Red">
            </div>
        </div>
    </div>

    <div class="premium-card max-w-[800px] mx-auto mb-6">
        <h3 class="font-['Outfit'] text-[1.2rem] mb-6 flex items-center gap-2 border-b border-black/5 pb-4">
            <i class="ph-fill ph-calendar-check text-[#34d399]"></i> Subscription Plan
        </h3>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 text-[0.85rem] font-medium">Start Date <span class="text-red-500">*</span></label>
                <input type="date" class="form-control" required>
            </div>
            <div>
                <label class="block mb-2 text-[0.85rem] font-medium">Duration <span class="text-red-500">*</span></label>
                <select class="form-control" required>
                    <option value="1">1 Month</option>
                    <option value="3">3 Months</option>
                    <option value="6">6 Months</option>
                    <option value="12">1 Year</option>
                </select>
            </div>
        </div>
        
        <div class="mt-8 flex justify-end">
            <button type="submit" class="btn btn-primary px-8 py-4 bg-gradient-to-br from-[var(--accent-primary)] to-[var(--accent-secondary)] text-white border-none rounded-lg text-[1.1rem] font-semibold cursor-pointer shadow-[0_4px_15px_rgba(99,102,241,0.3)]">
                Create Monthly Pass
            </button>
        </div>
    </div>
</form>
@endsection
