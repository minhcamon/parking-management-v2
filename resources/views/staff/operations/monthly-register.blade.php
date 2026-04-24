@extends('staff.staff-layout')
@section('title', 'Register Pass - Staff')

@section('content')
<form action="{{ route('staff.operations.store-pass') }}" method="POST">
    @csrf
    <x-card class="max-w-[800px] mx-auto mb-6">
        <h3 class="text-xl mb-6 flex items-center gap-2 border-b border-header-border pb-4">
            <i class="ph-fill ph-user text-accent"></i> Thông tin khách hàng
        </h3>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 text-sm font-medium">Họ và tên <span class="text-red-500">*</span></label>
                <input type="text" name="customer_name" placeholder="Nguyễn Văn A" required>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium">Số điện thoại</label>
                <input type="tel" name="customer_phone" placeholder="0901234567">
            </div>
            <div class="col-span-2">
                <label class="block mb-2 text-sm font-medium">Địa chỉ Email</label>
                <input type="email" name="customer_email" placeholder="customer@example.com">
            </div>
        </div>
    </x-card>

    <x-card class="max-w-[800px] mx-auto mb-6">
        <h3 class="text-xl mb-6 flex items-center gap-2 border-b border-header-border pb-4">
            <i class="ph-fill ph-car text-accent-secondary"></i> Thông tin phương tiện
        </h3>
        
        <div class="grid grid-cols-2 gap-4" x-data="{ selectedVehicle: '', ticketTypes: {{ $ticketTypes->toJson() }} }">
            <div class="col-span-2">
                <label class="block mb-2 text-sm font-medium">Biển số xe <span class="text-red-500">*</span></label>
                <input type="text" name="license_plate" class="uppercase font-mono text-lg font-semibold" placeholder="VD: 29A-12345" required>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium">Loại xe <span class="text-red-500">*</span></label>
                <select x-model="selectedVehicle" required>
                    <option value="">Chọn loại xe</option>
                    @foreach($vehicleTypes as $vt)
                        <option value="{{ $vt->id }}">{{ $vt->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium">Loại vé <span class="text-red-500">*</span></label>
                <select name="ticket_type_id" required :disabled="!selectedVehicle">
                    <option value="">Chọn loại vé</option>
                    <template x-for="type in ticketTypes.filter(t => t.vehicle_type_id == selectedVehicle)" :key="type.id">
                        <option :value="type.id" x-text="type.name + ' - ' + new Intl.NumberFormat('vi-VN').format(type.price) + ' VNĐ'"></option>
                    </template>
                </select>
            </div>
            <div class="col-span-2">
                <label class="block mb-2 text-sm font-medium">Thẻ RFID cấp cho khách <span class="text-red-500">*</span></label>
                @if($availableCard)
                    <input type="hidden" name="card_id" value="{{ $availableCard->id }}">
                    <input type="text" class="font-mono text-lg font-bold text-accent" value="{{ $availableCard->rfid_code }}" readonly>
                @else
                    <div class="text-red-500 font-bold p-3 bg-red-50 rounded-lg border border-red-200">
                        Không tìm thấy thẻ trống. Vui lòng thêm thẻ mới vào hệ thống.
                    </div>
                @endif
            </div>
        </div>
    </x-card>

    <x-card class="max-w-[800px] mx-auto mb-6">
        <h3 class="text-xl mb-6 flex items-center gap-2 border-b border-header-border pb-4">
            <i class="ph-fill ph-calendar-check text-[#34d399]"></i> Gói đăng ký
        </h3>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 text-sm font-medium">Ngày bắt đầu <span class="text-red-500">*</span></label>
                <input type="date" name="start_date" required>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium">Thời hạn <span class="text-red-500">*</span></label>
                <select name="months" required>
                    <option value="1">1 Tháng</option>
                    <option value="3">3 Tháng</option>
                    <option value="6">6 Tháng</option>
                    <option value="12">1 Năm</option>
                </select>
            </div>
        </div>
        
        <div class="mt-8 flex justify-end">
            <button type="submit" class="btn btn-primary px-8 py-4 bg-gradient-to-br from-accent to-accent-secondary text-white border-none rounded-lg text-lg font-semibold cursor-pointer shadow-[0_4px_15px_rgba(99,102,241,0.3)]">
                Tạo Vé tháng
            </button>
        </div>
    </x-card>
</form>
@endsection
