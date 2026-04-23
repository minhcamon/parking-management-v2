@extends('staff.staff-layout')
@section('title', 'Register Pass - Staff')

@section('content')
<form>
    <x-card class="max-w-[800px] mx-auto mb-6">
        <h3 class="text-xl mb-6 flex items-center gap-2 border-b border-header-border pb-4">
            <i class="ph-fill ph-user text-accent"></i> Customer Information
        </h3>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 text-sm font-medium">Full Name <span class="text-red-500">*</span></label>
                <input type="text"  placeholder="Nguyen Van A" required>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium">Phone Number <span class="text-red-500">*</span></label>
                <input type="tel"  placeholder="0901234567" required>
            </div>
            <div class="col-span-2">
                <label class="block mb-2 text-sm font-medium">Email Address</label>
                <input type="email"  placeholder="customer@example.com">
            </div>
        </div>
    </x-card>

    <x-card class="max-w-[800px] mx-auto mb-6">
        <h3 class="text-xl mb-6 flex items-center gap-2 border-b border-header-border pb-4">
            <i class="ph-fill ph-car text-accent-secondary"></i> Vehicle Details
        </h3>
        
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="block mb-2 text-sm font-medium">License Plate <span class="text-red-500">*</span></label>
                <input type="text" class="uppercase font-mono text-lg font-semibold" placeholder="e.g. 29A-12345" required>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium">Vehicle Type <span class="text-red-500">*</span></label>
                <select  required>
                    <option value="">Select Type</option>
                    <option value="1">Car</option>
                    <option value="2">Motorbike</option>
                </select>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium">Brand / Color</label>
                <input type="text"  placeholder="Honda / Red">
            </div>
        </div>
    </x-card>

    <x-card class="max-w-[800px] mx-auto mb-6">
        <h3 class="text-xl mb-6 flex items-center gap-2 border-b border-header-border pb-4">
            <i class="ph-fill ph-calendar-check text-[#34d399]"></i> Subscription Plan
        </h3>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 text-sm font-medium">Start Date <span class="text-red-500">*</span></label>
                <input type="date"  required>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium">Duration <span class="text-red-500">*</span></label>
                <select  required>
                    <option value="1">1 Month</option>
                    <option value="3">3 Months</option>
                    <option value="6">6 Months</option>
                    <option value="12">1 Year</option>
                </select>
            </div>
        </div>
        
        <div class="mt-8 flex justify-end">
            <button type="submit" class="btn btn-primary px-8 py-4 bg-gradient-to-br from-accent to-accent-secondary text-white border-none rounded-lg text-lg font-semibold cursor-pointer shadow-[0_4px_15px_rgba(99,102,241,0.3)]">
                Create Monthly Pass
            </button>
        </div>
    </x-card>
</form>
@endsection
