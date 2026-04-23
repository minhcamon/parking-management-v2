@extends('admin.admin-layout')
@section('title', 'Parking Site - Admin')
@section('page-title', 'Vehicle Types & Card Management')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-[4fr_5fr] gap-6"
         x-data="{
        showCardModal: false,
        showTicketModal: false,
        isBulk: false,
        modalMode: 'add',
        cardData: { id: '', rfid_code: '', status: '' },
        ticketData: { name: '', price: 0, type: '', vehicle_name: '' }
     }">

        <!-- LEFT COLUMN: Configurations -->
        <div class="flex flex-col gap-6">
            <!-- Vehicle Configuration Card -->
            <x-card>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold m-0 flex items-center gap-2">
                        <i class="ph-bold ph-gear-six text-[var(--accenQuản lý Thẻ RFIDt-primary)]"></i> Khu vực đỗ
                    </h3>
                    <span class="text-[0.7rem] font-bold text-muted bg-[var(--bg-color)] px-2 py-1 rounded-md uppercase tracking-wider">Update Only</span>
                </div>

                <div class="flex flex-col gap-3">
                    @foreach($vehicles as $v)
                        <div x-data="{ editing: false }" class="border border-header-border rounded-xl bg-nav-hover overflow-hidden transition-all duration-300">
                            <div class="p-4 flex justify-between items-center">
                                <div>
                                    <div class="font-bold text-main mb-0.5">{{ $v->name }}</div>
                                    <div class="text-[0.8rem] text-muted font-medium">{{ $v->total_slots }} Slots</div>
                                </div>
                                <button @click="editing = !editing" class="bg-dropdown-bg/50 w-8 h-8 rounded-lg border-none text-muted hover:text-accent cursor-pointer transition flex items-center justify-center">
                                    <i class="ph-fill ph-pencil-simple text-base" x-show="!editing"></i>
                                    <i class="ph-bold ph-x text-base text-red-400" x-show="editing" style="display:none;"></i>
                                </button>
                            </div>

                            <div x-show="editing" x-collapse style="display: none;" class="px-4 pb-4 pt-2 border-t border-header-border bg-dropdown-bg/30">
                                <form action="{{ route('admin.vehicles.update', $v->id) }}" method="POST" class="flex flex-col gap-3">
                                    @csrf
                                    @method('PUT')
                                    <div>
                                        <label class="text-[0.7rem] font-bold text-muted uppercase mb-1 block">Tên loại xe</label>
                                        <input type="text" name="name" value="{{ $v->name }}" required class="form-control !py-1.5 !text-sm">
                                    </div>
                                    <div>
                                        <label class="text-[0.7rem] font-bold text-muted uppercase mb-1 block">Số chỗ đỗ</label>
                                        <input type="number" name="total_slots" value="{{ $v->total_slots }}" required min="0" class="form-control !py-1.5 !text-sm">
                                    </div>
                                    <div class="flex justify-end gap-2 mt-1">
                                        <button type="submit" class="btn text-[0.8rem] btn-sm btn-primary">Lưu</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-card>

            <!-- Pricing Card -->
            <x-card>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold m-0 flex items-center gap-2">
                        <i class="ph-bold ph-currency-circle-dollar text-accent"></i> Bảng giá vé
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left">
                        <thead>
                            <tr class="border-b border-header-border text-muted text-[0.7rem] uppercase font-bold tracking-wider">
                                <th class="pb-3 pr-2">Tên vé</th>
                                <th class="pb-3 px-2">Loại xe</th>
                                <th class="pb-3 px-2">Giá</th>
                                <th class="pb-3 pl-2 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-header-border/50">
                            @foreach($ticketTypes as $tt)
                                <tr class="group hover:bg-nav-hover/30 transition-colors">
                                    <td class="py-3 pr-2">
                                        <div class="text-sm font-bold text-main">{{ $tt->name }}</div>
                                        <div class="text-[0.7rem] text-muted">{{ $tt->type === 'pass' ? 'Vé tháng' : 'Vé lượt' }}</div>
                                    </td>
                                    <td class="py-3 px-2">
                                        <span class="text-xs font-medium text-muted bg-nav-hover px-2 py-1 rounded">
                                            {{ $tt->vehicle_type->name }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-2">
                                        <div class="text-sm font-black {{ $tt->price > 0 ? 'text-[#10b981]' : 'text-muted/30' }}">
                                            {{ $tt->price > 0 ? number_format($tt->price, 0, ',', '.') . ' đ' : 'Chưa có' }}
                                        </div>
                                    </td>
                                    <td class="py-3 pl-2 text-right">
                                        <button @click="showTicketModal = true; ticketData = { name: '{{ $tt->name }}', price: {{ $tt->price }}, type: '{{ $tt->type }}', vehicle_name: '{{ $tt->vehicle_type->name }}' }"
                                                class="bg-dropdown-bg/50 w-8 h-8 rounded-lg border-none text-muted hover:text-accent cursor-pointer transition flex items-center justify-center ml-auto">
                                            <i class="ph-fill ph-pencil-simple text-sm"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 pt-4 border-t border-header-border">
                    <p class="text-[0.7rem] text-muted italic">* Bảng giá được cố định theo 4 loại hình cơ bản.</p>
                </div>
            </x-card>
        </div>

        <!-- RIGHT COLUMN: Card Management -->
        <div class="flex flex-col gap-6">
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h3 class="text-2xl font-black m-0 mb-1">Quản lý Thẻ RFID</h3>
                    <p class="text-[0.8rem] text-muted font-medium">Theo dõi và phát hành thẻ gửi xe</p>
                </div>

                <button @click="showCardModal = true; modalMode = 'add'; isBulk = false; cardData = {id:'', rfid_code:'', status:'available'}"
                        class="btn btn-md btn-primary">
                    <i class="ph-bold ph-plus"></i> Phát hành thẻ
                </button>
            </div>

            <!-- Filters & Search -->
            <div class="bg-nav-hover p-4 rounded-2xl border border-header-border">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <form action="{{ route('admin.parking-site.index') }}" method="GET" class="flex flex-wrap items-center gap-1 p-1 bg-dropdown-bg rounded-xl border border-header-border w-full md:w-auto flex-1">
                        <div class="relative min-w-[200px] flex-1">
                            <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-muted"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="pl-10 m-0 py-2 w-full text-sm bg-transparent border-transparent focus:bg-nav-hover rounded-lg transition-colors" placeholder="Tìm mã thẻ...">
                        </div>
                        
                        <div class="w-[1px] h-6 bg-header-border hidden md:block"></div>

                        <select name="status" onchange="this.form.submit()" class="m-0 py-2 w-[150px] text-sm bg-transparent border-transparent focus:bg-nav-hover rounded-lg transition-colors">
                            <option value="">Mọi trạng thái</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Sẵn sàng</option>
                            <option value="inuse" {{ request('status') == 'inuse' ? 'selected' : '' }}>Đang đỗ</option>
                            <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Vé tháng</option>
                            <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Đã mất</option>
                        </select>

                        <button type="submit" class="btn btn-sm btn-primary px-4 py-2 ml-1">Lọc</button>

                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('admin.parking-site.index') }}" class="btn btn-sm px-3 py-2 ml-1 bg-transparent border-transparent text-red-500 hover:bg-red-500/10 transition-colors" title="Xóa lọc">
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
                            <th class="px-4 py-4">Mã RFID</th>
                            <th class="px-4 py-4">Trạng thái</th>
                            <th class="px-4 py-4 text-right">Lựa chọn</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5">
                        @forelse($cards as $c)
                            <tr class="hover:bg-nav-hover transition">
                                <td class="px-4 py-4 font-mono text-[0.95rem] font-bold text-accent">{{ $c->rfid_code }}</td>

                                <td class="px-4 py-4">
                                    @if($c->status === 'available')
                                        <span class="bg-[#10b981]/10 text-[#10b981] px-3 py-1 rounded-lg text-[0.7rem] font-black uppercase tracking-wider border border-[#10b981]/20">Sẵn sàng</span>
                                    @elseif($c->status === 'inuse')
                                        <span class="bg-[#f59e0b]/10 text-[#f59e0b] px-3 py-1 rounded-lg text-[0.7rem] font-black uppercase tracking-wider border border-[#f59e0b]/20">Đang đỗ</span>
                                    @elseif($c->status === 'assigned')
                                        <span class="bg-[#6366f1]/10 text-[#6366f1] px-3 py-1 rounded-lg text-[0.7rem] font-black uppercase tracking-wider border border-[#6366f1]/20">Vé tháng</span>
                                    @elseif($c->status === 'lost')
                                        <span class="bg-red-100 text-red-600 px-3 py-1 rounded-lg text-[0.7rem] font-black uppercase tracking-wider border border-red-200">Đã mất</span>
                                    @endif
                                </td>

                                <td class="px-4 py-4 text-right">
                                    <button @click="showCardModal = true; modalMode = 'edit'; isBulk = false; cardData = {id: '{{ $c->id }}', rfid_code: '{{ $c->rfid_code }}', status: '{{ $c->status }}'}"
                                            class="bg-nav-hover p-2 rounded-lg border-none text-muted hover:text-accent hover:bg-dropdown-bg transition cursor-pointer hover:shadow-sm">
                                        <i class="ph-bold ph-pencil-simple text-base"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-20 text-center">
                                    <div class="flex flex-col items-center gap-4 text-muted">
                                        <i class="ph ph-mask-sad text-4xl opacity-20"></i>
                                        <p class="font-bold italic">Không có dữ liệu thẻ nào trong hệ thống.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 pt-6 border-t border-header-border">
                    {{ $cards->links() }}
                </div>
            </x-card>

            <div x-show="showCardModal"
                 style="display: none;"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4">

                <div x-show="showCardModal"
                     x-transition.opacity.duration.300ms
                     @click="showCardModal = false"
                     class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

                <x-card x-show="showCardModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" class="relative w-full max-w-md !p-0 overflow-hidden shadow-2xl">

                    <div class="p-6 border-b border-header-border flex justify-between items-center bg-nav-hover">
                        <h3 class="text-xl font-bold m-0" x-text="modalMode === 'add' ? 'Phát hành thẻ mới' : 'Cập nhật thẻ'"></h3>
                        <button type="button" @click="showCardModal = false" class="btn hover:bg-red-500/10 hover:text-red-500 w-8 h-8 rounded-full btn-ghost text-red-500">
                            <i class="ph-bold ph-x"></i>
                        </button>
                    </div>

                    <div class="p-6">
                        <form :action="isBulk ? '{{ route('admin.cards.bulk') }}' : (modalMode === 'add' ? '{{ route('admin.cards.store') }}' : '{{ route('admin.cards.update', 'DUMMY_ID') }}'.replace('DUMMY_ID', cardData.id))"
                              method="POST" class="flex flex-col gap-5">
                            @csrf
                            <template x-if="modalMode === 'edit'">
                                <input type="hidden" name="_method" value="PUT">
                            </template>

                            <input type="hidden" name="id" x-model="cardData.id">

                            <div x-show="modalMode === 'add'" class="flex bg-nav-hover p-1 rounded-xl mb-2">
                                <button type="button" @click="isBulk = false"
                                        :class="!isBulk ? 'bg-dropdown-bg shadow-sm text-accent' : 'text-muted'"
                                        class="flex-1 py-2 text-[0.8rem] font-bold rounded-lg transition border-none cursor-pointer">
                                    Thêm 1 thẻ
                                </button>
                                <button type="button" @click="isBulk = true"
                                        :class="isBulk ? 'bg-dropdown-bg shadow-sm text-accent' : 'text-muted'"
                                        class="flex-1 py-2 text-[0.8rem] font-bold rounded-lg transition border-none cursor-pointer">
                                    Hàng loạt
                                </button>
                            </div>

                            <div x-show="!isBulk">
                                <label class="text-[0.7rem] font-bold text-muted uppercase mb-1.5 block tracking-wider">Mã thẻ (RFID Code)</label>
                                <input type="text" name="rfid_code" x-model="cardData.rfid_code" placeholder="VD: 0012345678" :required="!isBulk" autocomplete="off"
                                       class="!py-2.5 font-mono">
                            </div>

                            <div x-show="isBulk" style="display: none;">
                                <label class="text-[0.7rem] font-bold text-muted uppercase mb-1.5 block tracking-wider">Số lượng thẻ cần tạo</label>
                                <input type="number" name="quantity" min="1" max="100" value="10" :required="isBulk"
                                       class="!py-2.5 font-bold">
                                <p class="text-[0.75rem] text-muted mt-2 italic bg-nav-hover p-3 rounded-lg border border-header-border">
                                    <i class="ph ph-info font-bold"></i> Mã thẻ sẽ được tạo ngẫu nhiên theo định dạng chuẩn.
                                </p>
                            </div>

                            <div x-show="modalMode === 'edit'">
                                <label class="text-[0.7rem] font-bold text-muted uppercase mb-1.5 block tracking-wider">Trạng thái thẻ</label>
                                <select name="status" x-model="cardData.status" class="!py-2.5 font-bold">
                                    <option value="available">Sẵn sàng (Available)</option>
                                    <option value="inuse">Đang sử dụng (In-Use)</option>
                                    <option value="assigned">Vé tháng (Assigned)</option>
                                    <option value="lost">Đã mất (Lost)</option>
                                </select>
                            </div>

                            <div class="flex gap-3 mt-4 pt-4 border-t border-header-border">
                                <button type="button" @click="showCardModal = false" class="btn flex-1 btn-md btn-ghost">Hủy bỏ</button>
                                <button type="submit" class="btn flex-[2] btn-md btn-primary">
                                    <span x-text="isBulk ? 'Bắt đầu tạo thẻ' : (modalMode === 'add' ? 'Lưu thẻ' : 'Cập nhật')"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </x-card>
            </div>
            <div x-show="showTicketModal"
                 style="display: none;"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4">

                <div x-show="showTicketModal"
                     x-transition.opacity.duration.300ms
                     @click="showTicketModal = false"
                     class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

                <x-card x-show="showTicketModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" class="relative w-full max-w-md !p-0 overflow-hidden shadow-2xl">

                    <div class="p-6 border-b border-header-border flex justify-between items-center bg-nav-hover">
                        <h3 class="text-xl font-bold m-0">Cấu hình giá vé</h3>
                        <button type="button" @click="showTicketModal = false" class="btn hover:bg-red-500/10 hover:text-red-500 w-8 h-8 rounded-full btn-ghost text-red-500">
                            <i class="ph-bold ph-x"></i>
                        </button>
                    </div>

                    <div class="p-6">
                        <form action="{{ route('admin.ticket-types.update') }}" method="POST" class="flex flex-col gap-5">
                            @csrf
                            <input type="hidden" name="type" x-model="ticketData.type">
                            <input type="hidden" name="vehicle_name" x-model="ticketData.vehicle_name">

                            <div>
                                <label class="text-[0.7rem] font-bold text-muted uppercase mb-1.5 block tracking-wider">Tên hiển thị</label>
                                <input type="text" name="name" x-model="ticketData.name" required class="!py-2.5 font-bold">
                            </div>

                            <div>
                                <label class="text-[0.7rem] font-bold text-muted uppercase mb-1.5 block tracking-wider">Giá vé (VNĐ)</label>
                                <input type="number" name="price" x-model="ticketData.price" required min="0" step="500" class="!py-2.5 font-bold text-accent">
                                <p class="text-[0.7rem] text-muted mt-2 italic">Lưu ý: Nếu giá là 0, hệ thống sẽ coi như loại vé này chưa được cấu hình.</p>
                            </div>

                            <div class="flex gap-3 mt-4 pt-4 border-t border-header-border">
                                <button type="button" @click="showTicketModal = false" class="btn flex-1 btn-md btn-ghost">Hủy bỏ</button>
                                <button type="submit" class="btn flex-[2] btn-md btn-primary">Lưu cấu hình</button>
                            </div>
                        </form>
                    </div>
                </x-card>
            </div>
        </div>
@endsection
