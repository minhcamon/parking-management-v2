@extends('admin.admin-layout')
@section('title', 'Monthly Passes - Admin')
@section('page-title', 'Monthly Passes')

@section('content')
<div x-data="{ showPassModal: false }">
    <div class="premium-card">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h3 class="font-['Be+Vietnam+Pro'] text-[1.4rem] font-black m-0 mb-1">Danh sách vé tháng</h3>
                <p class="text-[0.8rem] text-[var(--text-muted)] font-medium">Quản lý và gia hạn vé tháng cho khách hàng</p>
            </div>

            <button type="button" @click="showPassModal = true" class="px-5 py-2.5 bg-[var(--accent-secondary)] text-white border-none rounded-xl font-bold cursor-pointer flex items-center gap-2 hover:scale-[1.02] active:scale-95 transition shadow-lg shadow-pink-500/20">
                <i class="ph-bold ph-plus"></i> Đăng ký vé tháng
            </button>
        </div>

        <!-- Filters & Search -->
        <div class="flex flex-col md:flex-row gap-3 mb-6 p-4 bg-black/5 rounded-2xl border border-black/[0.03]">
            <form action="{{ route('admin.monthly-passes.index') }}" method="GET" class="flex flex-wrap gap-2 flex-1">
                <div class="relative min-w-[250px] flex-1">
                    <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[var(--text-muted)]"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control pl-10 mb-0 py-2 text-sm border-transparent focus:bg-white" placeholder="Tìm tên khách, biển số, thẻ RFID...">
                </div>
                <select name="status" onchange="this.form.submit()" class="form-control mb-0 py-2 text-sm w-[150px] border-transparent focus:bg-white">
                    <option value="">Mọi trạng thái</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hiệu lực</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Đã hết hạn</option>
                </select>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.monthly-passes.index') }}" class="bg-white p-2 rounded-lg text-red-500 hover:bg-red-50 transition border border-black/5 flex items-center justify-center" title="Xóa lọc">
                        <i class="ph-bold ph-arrow-counter-clockwise"></i>
                    </a>
                @endif
                <button type="submit" class="px-4 py-2 bg-black/5 text-[var(--text-main)] border-none rounded-lg font-bold text-sm cursor-pointer hover:bg-black/10 transition">
                    Lọc dữ liệu
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="text-[var(--text-muted)] text-[0.75rem] uppercase font-bold tracking-wider border-b border-black/5">
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
                        <tr class="hover:bg-black/[0.02] transition">
                            <td class="px-4 py-4">
                                <div class="font-bold text-[var(--text-main)]">{{ $p->customer_name }}</div>
                                <div class="text-[0.75rem] text-[var(--accent-primary)] font-mono font-medium">RFID: {{ $p->card->rfid_code ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="bg-black/5 px-3 py-1 rounded-lg font-mono text-[0.95rem] font-black border border-black/[0.05]">{{ $p->license_plate }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm font-bold text-[var(--text-main)]">{{ $p->ticket_type->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-xs font-medium text-[var(--text-muted)] uppercase tracking-tight">Từ: {{ \Carbon\Carbon::parse($p->start_date)->format('d/m/Y') }}</div>
                                <div class="text-sm font-bold text-[var(--text-main)]">Đến: {{ \Carbon\Carbon::parse($p->end_date)->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-4 py-4">
                                @if($isExpired)
                                    <span class="bg-red-50 text-red-500 px-3 py-1 rounded-lg text-[0.7rem] font-black uppercase tracking-wider border border-red-100 italic">Hết hạn</span>
                                @else
                                    <span class="bg-[#10b981]/10 text-[#10b981] px-3 py-1 rounded-lg text-[0.7rem] font-black uppercase tracking-wider border border-[#10b981]/20">Hiệu lực</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <button class="bg-black/5 p-2 rounded-lg border-none text-[var(--text-muted)] hover:text-[var(--accent-primary)] hover:bg-white transition cursor-pointer hover:shadow-sm">
                                        <i class="ph-bold ph-pencil-simple text-[1rem]"></i>
                                    </button>
                                    <button class="bg-red-50 p-2 rounded-lg border-none text-red-400 hover:text-red-600 hover:bg-white transition cursor-pointer hover:shadow-sm">
                                        <i class="ph-bold ph-trash text-[1rem]"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-20 text-center">
                                <div class="flex flex-col items-center gap-4 text-[var(--text-muted)]">
                                    <i class="ph ph-identification-card text-4xl opacity-20"></i>
                                    <p class="font-bold italic">Không tìm thấy vé tháng nào phù hợp.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6 pt-6 border-t border-black/5">
            {{ $passes->links() }}
        </div>
    </div>

    <!-- Modal Đăng ký vé tháng -->
    <div x-show="showPassModal"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4"
         style="display: none;">
        <div x-show="showPassModal" @click="showPassModal = false" x-transition.opacity class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div x-show="showPassModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" class="relative w-full max-w-xl premium-card !p-0 overflow-hidden shadow-2xl">
            <div class="p-6 border-b border-black/5 flex justify-between items-center bg-black/[0.02]">
                <h3 class="font-['Be+Vietnam+Pro'] text-[1.2rem] font-bold m-0">Đăng ký Vé tháng mới</h3>
                <button @click="showPassModal = false" class="bg-black/5 hover:bg-red-50 text-[var(--text-muted)] hover:text-red-500 w-8 h-8 rounded-full flex items-center justify-center border-none cursor-pointer transition">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>

            <div class="p-6">
                <form action="{{ route('admin.monthly-passes.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @csrf
                    <div class="md:col-span-2">
                        <label class="text-[0.7rem] font-bold text-[var(--text-muted)] uppercase mb-1.5 block tracking-wider">Họ tên khách hàng <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_name" required placeholder="VD: NGUYỄN VĂN A" class="form-control">
                    </div>

                    <div>
                        <label class="text-[0.7rem] font-bold text-[var(--text-muted)] uppercase mb-1.5 block tracking-wider">Biển số xe <span class="text-red-500">*</span></label>
                        <input type="text" name="license_plate" required placeholder="VD: 29A-123.45" class="form-control font-mono uppercase font-bold">
                    </div>

                    <div>
                        <label class="text-[0.7rem] font-bold text-[var(--text-muted)] uppercase mb-1.5 block tracking-wider">Loại vé / Phương tiện <span class="text-red-500">*</span></label>
                        <select name="ticket_type_id" required class="form-control font-bold">
                            <option value="">Chọn loại vé...</option>
                            @foreach($ticketTypes as $tt)
                                <option value="{{ $tt->id }}">{{ $tt->name }} - {{ number_format($tt->price, 0) }}đ</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-[0.7rem] font-bold text-[var(--text-muted)] uppercase mb-1.5 block tracking-wider">Thẻ RFID cấp mới <span class="text-red-500">*</span></label>
                        <select name="card_id" required class="form-control font-mono font-bold">
                            <option value="">Chọn thẻ...</option>
                            @foreach($availableCards as $card)
                                <option value="{{ $card->id }}">{{ $card->rfid_code }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-[0.7rem] font-bold text-[var(--text-muted)] uppercase mb-1.5 block tracking-wider">Thời hạn đăng ký <span class="text-red-500">*</span></label>
                        <select name="months" required class="form-control font-bold">
                            <option value="1">01 Tháng</option>
                            <option value="3">03 Tháng</option>
                            <option value="6">06 Tháng</option>
                            <option value="12">12 Tháng (1 Năm)</option>
                        </select>
                    </div>

                    <div class="md:col-span-2 flex justify-end gap-3 mt-4 pt-4 border-t border-black/5">
                        <button type="button" @click="showPassModal = false" class="px-6 py-2.5 rounded-xl bg-black/5 font-bold transition border-none cursor-pointer">Hủy bỏ</button>
                        <button type="submit" class="px-8 py-2.5 rounded-xl bg-[var(--accent-primary)] text-white font-bold shadow-lg shadow-indigo-500/20 hover:scale-[1.02] transition border-none cursor-pointer">Xác nhận đăng ký</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
