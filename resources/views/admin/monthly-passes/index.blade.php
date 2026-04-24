@extends('admin.admin-layout')
@section('title', 'Monthly Passes - Admin')
@section('page-title', 'Monthly Passes')

@section('content')
<div x-data="{ showPassModal: false, showEditModal: false, editData: {}, allTicketTypes: {{ $ticketTypes->toJson() }} }">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <h3 class="text-2xl font-black m-0 mb-1">Danh sách vé tháng</h3>
            <p class="text-[0.8rem] text-muted font-medium">Quản lý và gia hạn vé tháng cho khách hàng</p>
        </div>

        <button type="button" @click="showPassModal = true" class="btn btn-md btn-secondary">
            <i class="ph-bold ph-plus"></i> Đăng ký vé tháng
        </button>
    </div>

    <!-- Filters & Search -->
    <div class="bg-nav-hover p-4 rounded-2xl border border-header-border mb-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <form action="{{ route('admin.monthly-passes.index') }}" method="GET" class="flex flex-wrap items-center gap-1 p-1 bg-dropdown-bg rounded-xl border border-header-border w-full md:w-auto flex-1">
                <div class="relative min-w-[250px] flex-1">
                    <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-muted"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="pl-10 m-0 py-2 w-full text-sm bg-transparent border-transparent focus:bg-nav-hover rounded-lg transition-colors" placeholder="Tìm tên khách, biển số, thẻ RFID...">
                </div>
                
                <div class="w-[1px] h-6 bg-header-border hidden md:block"></div>

                <select name="status" onchange="this.form.submit()" class="m-0 py-2 w-[150px] text-sm bg-transparent border-transparent focus:bg-nav-hover rounded-lg transition-colors">
                    <option value="">Mọi trạng thái</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hiệu lực</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Đã hết hạn</option>
                </select>

                <button type="submit" class="btn btn-sm btn-primary px-4 py-2 ml-1">Lọc</button>

                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.monthly-passes.index') }}" class="btn btn-sm px-3 py-2 ml-1 bg-transparent border-transparent text-red-500 hover:bg-red-500/10 transition-colors" title="Xóa lọc">
                        <i class="ph-bold ph-arrow-counter-clockwise"></i>
                    </a>
                @endif
            </form>
        </div>
    </div>

    <x-card>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="text-muted text-[0.75rem] uppercase font-bold tracking-wider border-b border-header-border">
                        <th class="px-4 py-4">Khách hàng / Mã thẻ</th>
                        <th class="px-4 py-4">Biển số xe</th>
                        <th class="px-4 py-4">Loại vé</th>
                        <th class="px-4 py-4">Hạn sử dụng</th>
                        <th class="px-4 py-4">Trạng thái</th>
                        <th class="px-4 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5">
                    @forelse($passes as $p)
                        @php
                            $isExpired = \Carbon\Carbon::parse($p->end_date)->isPast();
                        @endphp
                        <tr class="hover:bg-nav-hover transition">
                            <td class="px-4 py-4">
                                <div class="font-bold text-main">{{ $p->customer_name }}</div>
                                <div class="text-[0.75rem] text-accent font-mono font-medium">RFID: {{ $p->card->rfid_code ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="bg-nav-hover px-3 py-1 rounded-lg font-mono text-[0.95rem] font-black border border-header-border">{{ $p->license_plate }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm font-bold text-main">{{ $p->ticket_type->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-xs font-medium text-muted uppercase tracking-tight">Từ: {{ \Carbon\Carbon::parse($p->start_date)->format('d/m/Y') }}</div>
                                <div class="text-sm font-bold text-main">Đến: {{ \Carbon\Carbon::parse($p->end_date)->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-4 py-4">
                                @if($isExpired)
                                    <span class="bg-red-500/10 text-red-500 px-3 py-1 rounded-lg text-[0.7rem] font-black uppercase tracking-wider border border-red-500/20 italic">Hết hạn</span>
                                @else
                                    <span class="bg-[#10b981]/10 text-[#10b981] px-3 py-1 rounded-lg text-[0.7rem] font-black uppercase tracking-wider border border-[#10b981]/20">Hiệu lực</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button type="button" @click="showEditModal = true; editData = {
                                        id: {{ $p->id }},
                                        customer_name: '{{ addslashes($p->customer_name) }}',
                                        license_plate: '{{ addslashes($p->license_plate) }}',
                                        vehicle_type_id: '{{ $p->ticket_type->vehicle_type_id ?? '' }}',
                                        ticket_type_id: '{{ $p->ticket_type_id }}',
                                        start_date: '{{ \Carbon\Carbon::parse($p->start_date)->format('Y-m-d') }}',
                                        end_date: '{{ \Carbon\Carbon::parse($p->end_date)->format('Y-m-d') }}'
                                    }" class="btn p-2 rounded-lg btn-ghost" title="Sửa">
                                        <i class="ph-bold ph-pencil-simple text-base"></i>
                                    </button>
                                    <form action="{{ route('admin.monthly-passes.destroy', $p->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa vé tháng này và giải phóng thẻ không?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500/10 p-2 rounded-lg border-none text-red-400 hover:text-red-600 hover:bg-dropdown-bg transition cursor-pointer hover:shadow-sm" title="Xóa">
                                            <i class="ph-bold ph-trash text-base"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-20 text-center">
                                <div class="flex flex-col items-center gap-4 text-muted">
                                    <i class="ph ph-identification-card text-4xl opacity-20"></i>
                                    <p class="font-bold italic">Không tìm thấy vé tháng nào phù hợp.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 pt-6 border-t border-header-border">
            {{ $passes->links() }}
        </div>
    </x-card>

    <!-- Modal Đăng ký vé tháng -->
    <div x-show="showPassModal"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         style="display: none;">
        <div x-show="showPassModal" @click="showPassModal = false" x-transition.opacity class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <x-card x-show="showPassModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" class="relative w-full max-w-xl !p-0 overflow-hidden shadow-2xl">
            <div class="p-6 border-b border-header-border flex justify-between items-center bg-nav-hover">
                <h3 class="text-xl font-bold m-0">Đăng ký Vé tháng mới</h3>
                <button @click="showPassModal = false" class="btn hover:bg-red-500/10 hover:text-red-500 w-8 h-8 rounded-full btn-ghost text-red-500">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>

            <div class="p-6" x-data="{ selectedVehicle: '', ticketTypes: {{ $ticketTypes->toJson() }} }">
                <form action="{{ route('admin.monthly-passes.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @csrf
                    <div class="md:col-span-2">
                        <label class="text-[0.7rem] font-bold text-muted uppercase mb-1.5 block tracking-wider">Họ tên khách hàng <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_name" required placeholder="VD: NGUYỄN VĂN A" >
                    </div>

                    <div>
                        <label class="text-[0.7rem] font-bold text-muted uppercase mb-1.5 block tracking-wider">Biển số xe <span class="text-red-500">*</span></label>
                        <input type="text" name="license_plate" required placeholder="VD: 29A-123.45" class="font-mono uppercase font-bold">
                    </div>

                    <div>
                        <label class="text-[0.7rem] font-bold text-muted uppercase mb-1.5 block tracking-wider">Loại phương tiện <span class="text-red-500">*</span></label>
                        <select x-model="selectedVehicle" required class="font-bold">
                            <option value="">Chọn phương tiện...</option>
                            @foreach($vehicleTypes as $vt)
                                <option value="{{ $vt->id }}">{{ $vt->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-[0.7rem] font-bold text-muted uppercase mb-1.5 block tracking-wider">Loại vé tháng <span class="text-red-500">*</span></label>
                        <select name="ticket_type_id" required :disabled="!selectedVehicle" class="font-bold">
                            <option value="">Chọn loại vé...</option>
                            <template x-for="type in ticketTypes.filter(t => t.vehicle_type_id == selectedVehicle)" :key="type.id">
                                <option :value="type.id" x-text="type.name + ' - ' + new Intl.NumberFormat('vi-VN').format(type.price) + 'đ'"></option>
                            </template>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-[0.7rem] font-bold text-muted uppercase mb-1.5 block tracking-wider">Thẻ RFID cấp mới <span class="text-red-500">*</span></label>
                        @if($availableCard)
                            <input type="hidden" name="card_id" value="{{ $availableCard->id }}">
                            <input type="text" value="{{ $availableCard->rfid_code }}" readonly class="font-mono font-bold bg-nav-hover border-transparent">
                        @else
                            <div class="text-red-500 text-sm font-bold p-3 bg-red-500/10 rounded-lg border border-red-500/20">
                                Không còn thẻ trống. Vui lòng thêm thẻ mới.
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="text-[0.7rem] font-bold text-muted uppercase mb-1.5 block tracking-wider">Ngày bắt đầu <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" required class="font-bold">
                    </div>

                    <div>
                        <label class="text-[0.7rem] font-bold text-muted uppercase mb-1.5 block tracking-wider">Thời hạn đăng ký <span class="text-red-500">*</span></label>
                        <select name="months" required class="font-bold">
                            <option value="1">01 Tháng</option>
                            <option value="3">03 Tháng</option>
                            <option value="6">06 Tháng</option>
                            <option value="12">12 Tháng (1 Năm)</option>
                        </select>
                    </div>

                    <div class="md:col-span-2 flex justify-end gap-3 mt-4 pt-4 border-t border-header-border">
                        <button type="button" @click="showPassModal = false" class="btn btn-lg btn-ghost">Hủy bỏ</button>
                        <button type="submit" class="btn btn-lg btn-primary">Xác nhận đăng ký</button>
                    </div>
                </form>
            </div>
        </x-card>
    </div>
    <!-- Modal Sửa vé tháng -->
    <div x-show="showEditModal"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         style="display: none;">
        <div x-show="showEditModal" @click="showEditModal = false" x-transition.opacity class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <x-card x-show="showEditModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" class="relative w-full max-w-xl !p-0 overflow-hidden shadow-2xl">
            <div class="p-6 border-b border-header-border flex justify-between items-center bg-nav-hover">
                <h3 class="text-xl font-bold m-0">Sửa thông tin Vé tháng</h3>
                <button @click="showEditModal = false" class="btn hover:bg-red-500/10 hover:text-red-500 w-8 h-8 rounded-full btn-ghost text-red-500">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>

            <div class="p-6">
                <form :action="`/admin/monthly-passes/${editData.id}`" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @csrf
                    @method('PUT')
                    <div class="md:col-span-2">
                        <label class="text-[0.7rem] font-bold text-muted uppercase mb-1.5 block tracking-wider">Họ tên khách hàng <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_name" required x-model="editData.customer_name">
                    </div>

                    <div>
                        <label class="text-[0.7rem] font-bold text-muted uppercase mb-1.5 block tracking-wider">Biển số xe <span class="text-red-500">*</span></label>
                        <input type="text" name="license_plate" readonly class="font-mono uppercase font-bold" x-model="editData.license_plate">
                    </div>

                    <div>
                        <label class="text-[0.7rem] font-bold text-muted uppercase mb-1.5 block tracking-wider">Loại phương tiện <span class="text-red-500">*</span></label>
                        <select readonly x-model="editData.vehicle_type_id" @change="editData.ticket_type_id = ''" required class="font-bold">
                            <option value="">Chọn phương tiện...</option>
                            @foreach($vehicleTypes as $vt)
                                <option value="{{ $vt->id }}">{{ $vt->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-[0.7rem] font-bold text-muted uppercase mb-1.5 block tracking-wider">Loại vé tháng <span class="text-red-500">*</span></label>
                        <select readonly name="ticket_type_id" required x-model="editData.ticket_type_id" class="font-bold">
                            <option value="">Chọn loại vé...</option>
                            <template x-for="type in allTicketTypes.filter(t => t.vehicle_type_id == editData.vehicle_type_id)" :key="type.id">
                                <option :value="type.id" x-text="type.name + ' - ' + new Intl.NumberFormat('vi-VN').format(type.price) + 'đ'"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="text-[0.7rem] font-bold text-muted uppercase mb-1.5 block tracking-wider">Ngày bắt đầu <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" required class="font-bold" x-model="editData.start_date">
                    </div>

                    <div>
                        <label class="text-[0.7rem] font-bold text-muted uppercase mb-1.5 block tracking-wider">Ngày hết hạn <span class="text-red-500">*</span></label>
                        <input type="date" name="end_date" required class="font-bold" x-model="editData.end_date">
                    </div>

                    <div class="md:col-span-2 flex justify-end gap-3 mt-4 pt-4 border-t border-header-border">
                        <button type="button" @click="showEditModal = false" class="btn btn-lg btn-ghost">Hủy bỏ</button>
                        <button type="submit" class="btn btn-lg btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </x-card>
    </div>
</div>
@endsection
