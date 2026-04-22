@extends('admin.admin-layout')
@section('title', 'Parking Site - Admin')
@section('page-title', 'Vehicle Types & Card Management')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_2fr] gap-6"
         x-data="{
        showCardModal: false,
        isBulk: false,
        modalMode: 'add',
        cardData: { id: '', rfid_code: '', status: '' }
     }">

        <!-- LEFT COLUMN: Configurations -->
        <div class="flex flex-col gap-6">
            <!-- Vehicle Configuration Card -->
            <div class="premium-card">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-[1.1rem] font-bold m-0 flex items-center gap-2">
                        <i class="ph-bold ph-gear-six text-[var(--accenQuản lý Thẻ RFIDt-primary)]"></i> Khu vực đỗ
                    </h3>
                    <span class="text-[0.7rem] font-bold text-[var(--text-muted)] bg-[var(--bg-color)] px-2 py-1 rounded-md uppercase tracking-wider">Update Only</span>
                </div>

                <div class="flex flex-col gap-3">
                    @foreach($vehicles as $v)
                        <div x-data="{ editing: false }" class="border border-black/[0.05] rounded-xl bg-black/5 overflow-hidden transition-all duration-300">
                            <div class="p-4 flex justify-between items-center">
                                <div>
                                    <div class="font-bold text-[var(--text-main)] mb-0.5">{{ $v->name }}</div>
                                    <div class="text-[0.8rem] text-[var(--text-muted)] font-medium">{{ $v->total_slots }} Slots</div>
                                </div>
                                <button @click="editing = !editing" class="bg-white/50 w-8 h-8 rounded-lg border-none text-[var(--text-muted)] hover:text-[var(--accent-primary)] cursor-pointer transition flex items-center justify-center">
                                    <i class="ph-fill ph-pencil-simple text-[1rem]" x-show="!editing"></i>
                                    <i class="ph-bold ph-x text-[1rem] text-red-400" x-show="editing" style="display:none;"></i>
                                </button>
                            </div>

                            <div x-show="editing" x-collapse style="display: none;" class="px-4 pb-4 pt-2 border-t border-black/5 bg-white/30">
                                <form action="{{ route('admin.vehicles.update', $v->id) }}" method="POST" class="flex flex-col gap-3">
                                    @csrf
                                    @method('PUT')
                                    <div>
                                        <label class="text-[0.7rem] font-bold text-[var(--text-muted)] uppercase mb-1 block">Tên loại xe</label>
                                        <input type="text" name="name" value="{{ $v->name }}" required class="form-control !py-1.5 !text-sm">
                                    </div>
                                    <div>
                                        <label class="text-[0.7rem] font-bold text-[var(--text-muted)] uppercase mb-1 block">Số chỗ đỗ</label>
                                        <input type="number" name="total_slots" value="{{ $v->total_slots }}" required min="0" class="form-control !py-1.5 !text-sm">
                                    </div>
                                    <div class="flex justify-end gap-2 mt-1">
                                        <button type="submit" class="px-4 py-1.5 text-[0.8rem] rounded-lg bg-[var(--accent-primary)] text-white font-bold shadow-sm cursor-pointer hover:opacity-90 transition">Lưu</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pricing Card -->
            <div class="premium-card">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-[1.1rem] font-bold m-0 flex items-center gap-2">
                        <i class="ph-bold ph-currency-circle-dollar text-[var(--accent-primary)]"></i> Bảng giá vé
                    </h3>
                </div>

                <div class="flex flex-col gap-2">
                    <div class="flex justify-between items-center p-3 rounded-xl bg-black/5 border border-black/[0.03]">
                        <div class="text-[0.9rem] font-bold">Vé Lượt (Xe Máy)</div>
                        <div class="text-[0.9rem] font-black text-[#10b981]">5,000 đ</div>
                    </div>
                    <div class="flex justify-between items-center p-3 rounded-xl bg-black/5 border border-black/[0.03]">
                        <div class="text-[0.9rem] font-bold">Vé Tháng (Xe Máy)</div>
                        <div class="text-[0.9rem] font-black text-[#10b981]">100,000 đ</div>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-black/5">
                    <button class="w-full py-2.5 rounded-xl border-2 border-dashed border-black/10 text-[var(--text-muted)] text-[0.8rem] font-bold hover:border-[var(--accent-primary)] hover:text-[var(--accent-primary)] transition cursor-pointer bg-transparent">
                        + Thêm loại vé mới
                    </button>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: Card Management -->
        <div class="flex flex-col gap-6">
            <div class="premium-card">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                    <div>
                        <h3 class="text-[1.4rem] font-black m-0 mb-1">Quản lý Thẻ RFID</h3>
                        <p class="text-[0.8rem] text-[var(--text-muted)] font-medium">Theo dõi và phát hành thẻ gửi xe</p>
                    </div>

                    <button @click="showCardModal = true; modalMode = 'add'; isBulk = false; cardData = {id:'', rfid_code:'', status:'available'}"
                            class="px-5 py-2.5 bg-[var(--accent-primary)] text-white border-none rounded-xl font-bold cursor-pointer flex items-center gap-2 hover:scale-[1.02] active:scale-95 transition shadow-lg shadow-indigo-500/20">
                        <i class="ph-bold ph-plus"></i> Phát hành thẻ
                    </button>
                </div>

                <!-- Filters & Search -->
                <div class="flex flex-col md:flex-row gap-3 mb-6 p-4 bg-black/5 rounded-2xl border border-black/[0.03]">
                    <form action="{{ route('admin.parking-site.index') }}" method="GET" class="flex flex-wrap gap-2 flex-1">
                        <div class="relative min-w-[200px] flex-1">
                            <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-muted)]"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="form-control pl-10 mb-0 py-2 text-sm border-transparent focus:bg-white" placeholder="Tìm mã thẻ...">
                        </div>
                        <select name="status" onchange="this.form.submit()" class="form-control mb-0 py-2 text-sm w-[150px] border-transparent focus:bg-white">
                            <option value="">Mọi trạng thái</option>
                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Sẵn sàng</option>
                            <option value="inuse" {{ request('status') == 'inuse' ? 'selected' : '' }}>Đang đỗ</option>
                            <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Vé tháng</option>
                            <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Đã mất</option>
                        </select>
                        @if(request()->hasAny(['search', 'status']))
                            <a href="{{ route('admin.parking-site.index') }}" class="bg-white p-2 rounded-lg text-red-500 hover:bg-red-50 transition border border-black/5 flex items-center justify-center">
                                <i class="ph-bold ph-arrow-counter-clockwise"></i>
                            </a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left">
                        <thead>
                        <tr class="text-[var(--text-muted)] text-[0.75rem] uppercase font-bold tracking-wider border-b border-black/5">
                            <th class="px-4 py-4">Mã RFID</th>
                            <th class="px-4 py-4">Trạng thái</th>
                            <th class="px-4 py-4 text-right">Lựa chọn</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-black/5">
                        @forelse($cards as $c)
                            <tr class="hover:bg-black/[0.02] transition">
                                <td class="px-4 py-4 font-mono text-[0.95rem] font-bold text-[var(--accent-primary)]">{{ $c->rfid_code }}</td>

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
                                            class="bg-black/5 p-2 rounded-lg border-none text-[var(--text-muted)] hover:text-[var(--accent-primary)] hover:bg-white transition cursor-pointer hover:shadow-sm">
                                        <i class="ph-bold ph-pencil-simple text-[1rem]"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-20 text-center">
                                    <div class="flex flex-col items-center gap-4 text-[var(--text-muted)]">
                                        <i class="ph ph-mask-sad text-4xl opacity-20"></i>
                                        <p class="font-bold italic">Không có dữ liệu thẻ nào trong hệ thống.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 pt-6 border-t border-black/5">
                    {{ $cards->links() }}
                </div>
            </div>

            <div x-show="showCardModal"
                 style="display: none;"
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4">

                <div x-show="showCardModal"
                     x-transition.opacity.duration.300ms
                     @click="showCardModal = false"
                     class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

                <div x-show="showCardModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     class="relative w-full max-w-md premium-card !p-0 overflow-hidden shadow-2xl">

                    <div class="p-6 border-b border-black/5 flex justify-between items-center bg-black/[0.02]">
                        <h3 class="font-['Outfit'] text-[1.2rem] font-bold m-0" x-text="modalMode === 'add' ? 'Phát hành thẻ mới' : 'Cập nhật thẻ'"></h3>
                        <button type="button" @click="showCardModal = false" class="bg-black/5 hover:bg-red-50 text-[var(--text-muted)] hover:text-red-500 w-8 h-8 rounded-full flex items-center justify-center border-none cursor-pointer transition">
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

                            <div x-show="modalMode === 'add'" class="flex bg-black/5 p-1 rounded-xl mb-2">
                                <button type="button" @click="isBulk = false"
                                        :class="!isBulk ? 'bg-white shadow-sm text-[var(--accent-primary)]' : 'text-[var(--text-muted)]'"
                                        class="flex-1 py-2 text-[0.8rem] font-bold rounded-lg transition border-none cursor-pointer">
                                    Thêm 1 thẻ
                                </button>
                                <button type="button" @click="isBulk = true"
                                        :class="isBulk ? 'bg-white shadow-sm text-[var(--accent-primary)]' : 'text-[var(--text-muted)]'"
                                        class="flex-1 py-2 text-[0.8rem] font-bold rounded-lg transition border-none cursor-pointer">
                                    Hàng loạt
                                </button>
                            </div>

                            <div x-show="!isBulk">
                                <label class="text-[0.7rem] font-bold text-[var(--text-muted)] uppercase mb-1.5 block tracking-wider">Mã thẻ (RFID Code)</label>
                                <input type="text" name="rfid_code" x-model="cardData.rfid_code" placeholder="VD: 0012345678" :required="!isBulk" autocomplete="off"
                                       class="form-control !py-2.5 font-mono">
                            </div>

                            <div x-show="isBulk" style="display: none;">
                                <label class="text-[0.7rem] font-bold text-[var(--text-muted)] uppercase mb-1.5 block tracking-wider">Số lượng thẻ cần tạo</label>
                                <input type="number" name="quantity" min="1" max="100" value="10" :required="isBulk"
                                       class="form-control !py-2.5 font-bold">
                                <p class="text-[0.75rem] text-[var(--text-muted)] mt-2 italic bg-black/5 p-3 rounded-lg border border-black/[0.03]">
                                    <i class="ph ph-info font-bold"></i> Mã thẻ sẽ được tạo ngẫu nhiên theo định dạng chuẩn.
                                </p>
                            </div>

                            <div x-show="modalMode === 'edit'">
                                <label class="text-[0.7rem] font-bold text-[var(--text-muted)] uppercase mb-1.5 block tracking-wider">Trạng thái thẻ</label>
                                <select name="status" x-model="cardData.status" class="form-control !py-2.5 font-bold">
                                    <option value="available">Sẵn sàng (Available)</option>
                                    <option value="inuse">Đang sử dụng (In-Use)</option>
                                    <option value="assigned">Vé tháng (Assigned)</option>
                                    <option value="lost">Đã mất (Lost)</option>
                                </select>
                            </div>

                            <div class="flex gap-3 mt-4 pt-4 border-t border-black/5">
                                <button type="button" @click="showCardModal = false" class="flex-1 py-2.5 rounded-xl bg-black/5 hover:bg-black/10 text-[var(--text-main)] font-bold transition border-none cursor-pointer">Hủy bỏ</button>
                                <button type="submit" class="flex-[2] py-2.5 rounded-xl bg-[var(--accent-primary)] text-white font-bold shadow-lg shadow-indigo-500/20 hover:scale-[1.02] transition border-none cursor-pointer">
                                    <span x-text="isBulk ? 'Bắt đầu tạo thẻ' : (modalMode === 'add' ? 'Lưu thẻ' : 'Cập nhật')"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection
