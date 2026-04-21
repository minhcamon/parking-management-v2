@extends('admin.admin-layout')
@section('title', 'Parking Site - Admin')
@section('page-title', 'Vehicle Types & Card Management')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_2fr] gap-6"
         x-data="{
        showCardModal: false,
        modalMode: 'add',
        cardData: { id: '', hex_code: '', type: '', status: '' }
     }">

        <div class="premium-card">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-['Outfit'] text-[1.2rem]">Vehicle Types</h3>
                <button @click="editing = !editing" class="bg-[#6366f1]/10 text-[var(--accent-primary)] border-none w-8 h-8 rounded-lg cursor-pointer flex items-center justify-center hover:bg-[#6366f1]/20 transition">
                    <i class="ph-bold ph-plus" ></i>
                </button>
            </div>

            <div class="flex flex-col gap-4">
                @forelse($vehicles as $v)
                    <div x-data="{ editing: false }" class="border border-black/5 rounded-xl bg-black/5 overflow-hidden transition-all duration-300">

                        <div class="p-4 flex justify-between items-center">
                            <div>
                                <div class="font-semibold text-[var(--text-main)] mb-1">{{$v->name}}</div>
                                <div class="text-[0.85rem] text-[var(--text-muted)]">{{$v->total_slots}} Slot Assigned</div>
                            </div>
                            <div class="flex gap-2">
                                <button @click="editing = !editing"
                                        class="bg-transparent border-none text-[var(--text-muted)] hover:text-[var(--accent-primary)] cursor-pointer transition">
                                    <i class="ph-fill ph-pencil-simple text-[1.1rem]" x-show="!editing"></i>
                                    <i class="ph-bold ph-x text-[1.1rem] text-red-400" x-show="editing" style="display:none;"></i>
                                </button>
                            </div>
                        </div>

                        <div x-show="editing"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             style="display: none;"
                             class="px-4 pb-4 pt-2 border-t border-black/5">

                            <form action="/vehicles/setup" method="POST" class="flex flex-col gap-4">
                                @csrf
                                <h4 class="font-bold text-gray-700">1. Thông tin Khu vực đỗ</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label>Tên loại xe</label>
                                        <input type="text" name="name" placeholder="VD: Xe Đạp" required class="form-control">
                                    </div>
                                    <div>
                                        <label>Tổng số chỗ (Slots)</label>
                                        <input type="number" name="max_capacity" placeholder="VD: 100" required class="form-control">
                                    </div>
                                </div>

                                <h4 class="font-bold text-gray-700 mt-4 border-t pt-4">2. Bảng giá mặc định</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label>Giá vé Lượt (VNĐ)</label>
                                        <input type="number" name="price_normal" placeholder="VD: 3000" class="form-control">
                                    </div>
                                    <div>
                                        <label>Giá vé Tháng (VNĐ)</label>
                                        <input type="number" name="price_pass" placeholder="VD: 50000" class="form-control">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary mt-4">Lưu Cấu Hình Tổng</button>
                            </form>
                        </div>

                    </div>
                @empty
                    <div class="p-5 text-center text-[var(--text-muted)] italic bg-black/5 rounded-xl">
                        Chưa có cấu hình loại xe nào.
                    </div>
                @endforelse
            </div>
        </div>

            <div class="premium-card">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-['Outfit'] text-[1.2rem]">Quản lý Thẻ (RFID Cards)</h3>

                    <button @click="showCardModal = true; modalMode = 'add'; cardData = {id:'', rfid_code:'', status:'available'}"
                            class="px-4 py-[0.6rem] bg-[var(--accent-primary)] text-white border-none rounded-lg font-medium cursor-pointer flex items-center gap-2 hover:scale-105 transition shadow-lg shadow-indigo-500/30">
                        <i class="ph-bold ph-plus"></i> Phát hành thẻ
                    </button>
                </div>

                <div class="overflow-x-auto grow">
                    <table class="w-full border-collapse text-left">
                        <thead>
                        <tr class="border-b-2 border-black/5 text-[var(--text-muted)] text-[0.85rem] uppercase tracking-[0.5px]">
                            <th class="p-4">Mã RFID</th>
                            <th class="p-4">Trạng thái</th>
                            <th class="p-4 text-right">Thao tác</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($cards as $c)
                            <tr class="border-b border-black/5 hover:bg-black/5 transition">
                                <td class="p-4 font-mono text-[0.95rem] tracking-wider">{{ $c->rfid_code }}</td>

                                <td class="p-4">
                                    @if($c->status === 'available')
                                        <span class="bg-[#10b981]/20 text-[#10b981] px-3 py-1 rounded-full text-[0.75rem] font-bold uppercase">Sẵn sàng</span>
                                    @elseif($c->status === 'inuse')
                                        <span class="bg-[#fbbf24]/20 text-[#f59e0b] px-3 py-1 rounded-full text-[0.75rem] font-bold uppercase">Đang đỗ</span>
                                    @elseif($c->status === 'assigned')
                                        <span class="bg-[#6366f1]/20 text-[#6366f1] px-3 py-1 rounded-full text-[0.75rem] font-bold uppercase">Vé tháng</span>
                                    @elseif($c->status === 'lost')
                                        <span class="bg-[#ef4444]/20 text-[#ef4444] px-3 py-1 rounded-full text-[0.75rem] font-bold uppercase">Đã mất</span>
                                    @endif
                                </td>

                                <td class="p-4 text-right">
                                    <button @click="showCardModal = true; modalMode = 'edit'; cardData = {id: '{{ $c->id }}', rfid_code: '{{ $c->rfid_code }}', status: '{{ $c->status }}'}"
                                            class="bg-black/5 p-2 rounded-lg border-none text-[var(--text-muted)] hover:text-[var(--accent-primary)] hover:bg-indigo-50 cursor-pointer transition">
                                        <i class="ph-fill ph-pencil-simple text-[1.1rem]"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-[var(--text-muted)] italic bg-black/5 rounded-xl">Không có dữ liệu thẻ nào trong hệ thống.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 pt-4 border-t border-black/5">
                    {{ $cards->links() }}
                </div>
            </div>

            <div x-show="showCardModal"
                 style="display: none;"
                 class="fixed inset-0 z-[100] flex items-center justify-center">

                <div x-show="showCardModal"
                     x-transition.opacity.duration.300ms
                     @click="showCardModal = false"
                     class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

                <div x-show="showCardModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                     class="relative w-full max-w-md m-4 premium-card bg-white dark:bg-[#1e293b] shadow-2xl">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-['Outfit'] text-[1.3rem] font-bold" x-text="modalMode === 'add' ? 'Thêm Thẻ Mới' : 'Cập nhật thông tin Thẻ'"></h3>
                        <button type="button" @click="showCardModal = false" class="bg-black/5 hover:bg-red-100 text-[var(--text-muted)] hover:text-red-500 w-8 h-8 rounded-full flex items-center justify-center border-none cursor-pointer transition">
                            <i class="ph-bold ph-x"></i>
                        </button>
                    </div>

                    <form action="{{-- route('cards.save') --}}" method="POST" class="flex flex-col gap-4">
                        @csrf
                        <template x-if="modalMode === 'edit'">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <input type="hidden" name="id" x-model="cardData.id">

                        <div>
                            <label class="text-[0.85rem] font-semibold text-[var(--text-muted)] mb-1 block">Mã thẻ (RFID Code) <span class="text-red-500">*</span></label>
                            <input type="text" name="rfid_code" x-model="cardData.rfid_code" placeholder="VD: 0012345678" required autocomplete="off"
                                   class="w-full p-2.5 text-[1rem] font-mono rounded-xl border border-black/10 bg-black/5 outline-none focus:border-[var(--accent-primary)] focus:bg-transparent transition">
                        </div>

                        <div>
                            <label class="text-[0.85rem] font-semibold text-[var(--text-muted)] mb-1 block">Trạng thái Thẻ</label>
                            <select name="status" x-model="cardData.status"
                                    class="w-full p-2.5 text-[0.95rem] rounded-xl border border-black/10 bg-black/5 outline-none focus:border-[var(--accent-primary)] focus:bg-transparent transition font-medium">
                                <option value="available">Sẵn sàng (Available)</option>
                                <option value="inuse">Đang sử dụng (In-Use)</option>
                                <option value="assigned">Vé tháng (Assigned)</option>
                                <option value="lost">Đã mất (Lost)</option>
                            </select>
                        </div>

                        <div class="flex justify-end gap-3 mt-4 pt-4 border-t border-black/5">
                            <button type="button" @click="showCardModal = false" class="px-5 py-2.5 rounded-xl bg-black/5 hover:bg-black/10 text-[var(--text-main)] font-semibold transition">Hủy bỏ</button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-[var(--accent-primary)] text-white font-semibold shadow-lg shadow-indigo-500/30 hover:scale-105 transition">
                                <span x-text="modalMode === 'add' ? 'Lưu thẻ' : 'Cập nhật'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
