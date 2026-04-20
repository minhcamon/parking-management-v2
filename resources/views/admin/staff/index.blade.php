@extends('admin.admin-layout')
@section('title', 'Staff Management - Admin')
@section('page-title', 'Staff Management')

@section('content')

    <div x-data="{
        showModal: false,
        modalMode: 'add',
        staffData: { id: '', name: '', email: '', role: 'staff', is_active: 1, password: '' }
    }">

        <div class="premium-card mb-6">
            <div class="flex justify-between items-center">
                <div class="relative w-[300px]">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-[var(--text-muted)]"></i>
                    <input type="text" class="form-control pl-10 mb-0" placeholder="Search staff members...">
                </div>

                <button @click="showModal = true; modalMode = 'add'; staffData = { id: '', name: '', email: '', role: 'staff', is_active: 1, password: '' }"
                        class="btn btn-primary px-6 py-[0.8rem] bg-[var(--accent-primary)] text-white border-none rounded-lg font-medium cursor-pointer flex items-center gap-2 hover:scale-105 transition shadow-lg shadow-indigo-500/30">
                    <i class="ph-bold ph-user-plus"></i> Add New Staff
                </button>
            </div>
        </div>

        <div class="premium-card">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                    <tr class="border-b-2 border-black/5 text-[var(--text-muted)] text-[0.85rem] uppercase tracking-[0.5px]">
                        <th class="p-4">User</th>
                        <th class="p-4">Role</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $u)
                        <tr class="border-b border-black/5 hover:bg-black/5 transition last:border-none">

                            <td class="p-4">
                                <div class="flex items-center gap-4">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($u['name']) }}&background={{ ltrim($u['avatar_bg'], '#') }}&color=fff" alt="Avatar" class="w-10 h-10 rounded-full shadow-sm">
                                    <div>
                                        <div class="font-semibold text-[var(--text-main)]">{{ $u['name'] }}</div>
                                        <div class="text-[0.85rem] text-[var(--text-muted)]">{{ $u['subtitle'] }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="p-4">
                                <span class="{{ $u['role_class'] }} px-2.5 py-1 rounded-[20px] text-[0.75rem] font-semibold">
                                    {{ $u['role_label'] }}
                                </span>
                            </td>

                            <td class="p-4 text-[var(--text-muted)]">{{ $u['email'] }}</td>

                            <td class="p-4">
                                <span class="{{ $u['status_class'] }} px-2.5 py-1 rounded-[20px] text-[0.75rem] font-semibold">
                                    {{ $u['status_label'] }}
                                </span>
                            </td>

                            <td class="p-4 text-right">
                                <button @click="showModal = true; modalMode = 'edit'; staffData = { id: '{{ $u['id'] ?? '' }}', name: '{{ $u['name'] }}', email: '{{ $u['email'] }}', role: '{{ $u['role_label'] }}', is_active: {{ $u['is_active'] ?? 1 }} }"
                                        class="bg-black/5 p-2 rounded-lg border-none text-[var(--text-muted)] hover:text-[var(--accent-primary)] hover:bg-indigo-50 cursor-pointer transition mr-2">
                                    <i class="ph-fill ph-pencil-simple text-[1.1rem]"></i>
                                </button>

                                @if($u['can_delete'])
                                    <button class="bg-black/5 p-2 rounded-lg border-none text-[var(--text-muted)] hover:text-red-600 hover:bg-red-50 cursor-pointer transition">
                                        <i class="ph-fill ph-trash text-[1.1rem]"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-[var(--text-muted)] italic bg-black/5 rounded-xl">
                                Không có nhân viên nào trong hệ thống.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>


        <div x-show="showModal"
             style="display: none;"
             class="fixed inset-0 z-[100] flex items-center justify-center">

            <div x-show="showModal"
                 x-transition.opacity.duration.300ms
                 @click="showModal = false"
                 class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

            <div x-show="showModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                 class="relative w-full max-w-lg m-4 premium-card bg-white dark:bg-[#1e293b] shadow-2xl p-6 rounded-2xl">

                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[var(--accent-primary)]/10 flex items-center justify-center text-[var(--accent-primary)]">
                            <i class="ph-fill" :class="modalMode === 'add' ? 'ph-user-plus' : 'ph-pencil-simple'"></i>
                        </div>
                        <h3 class="font-['Outfit'] text-[1.3rem] font-bold text-[var(--text-main)]" x-text="modalMode === 'add' ? 'Thêm Nhân Viên Mới' : 'Cập Nhật Thông Tin'"></h3>
                    </div>

                    <button type="button" @click="showModal = false" class="bg-black/5 hover:bg-red-100 text-[var(--text-muted)] hover:text-red-500 w-8 h-8 rounded-full flex items-center justify-center border-none cursor-pointer transition">
                        <i class="ph-bold ph-x"></i>
                    </button>
                </div>

                <form action="{{-- route('staff.save') --}}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <template x-if="modalMode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <input type="hidden" name="id" x-model="staffData.id">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[0.85rem] font-semibold text-[var(--text-muted)] mb-1 block">Họ và Tên <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="staffData.name" placeholder="Ví dụ: Nguyễn Văn A" required autocomplete="off"
                                   class="w-full p-2.5 text-[0.95rem] rounded-xl border border-black/10 bg-black/5 outline-none focus:border-[var(--accent-primary)] focus:bg-transparent transition">
                        </div>

                        <div>
                            <label class="text-[0.85rem] font-semibold text-[var(--text-muted)] mb-1 block">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" x-model="staffData.email" placeholder="nhanvien@congty.com" required autocomplete="off"
                                   class="w-full p-2.5 text-[0.95rem] rounded-xl border border-black/10 bg-black/5 outline-none focus:border-[var(--accent-primary)] focus:bg-transparent transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[0.85rem] font-semibold text-[var(--text-muted)] mb-1 block">Phân Quyền (Role) <span class="text-red-500">*</span></label>
                            <select name="role" x-model="staffData.role" required
                                    class="w-full p-2.5 text-[0.95rem] rounded-xl border border-black/10 bg-black/5 outline-none focus:border-[var(--accent-primary)] focus:bg-transparent transition font-medium">
                                <option value="staff">Nhân viên (Staff)</option>
                                <option value="manager">Quản lý bãi (Manager)</option>
                                <option value="admin">Quản trị viên (Admin)</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-[0.85rem] font-semibold text-[var(--text-muted)] mb-1 block">Trạng Thái</label>
                            <select name="is_active" x-model="staffData.is_active"
                                    class="w-full p-2.5 text-[0.95rem] rounded-xl border border-black/10 bg-black/5 outline-none focus:border-[var(--accent-primary)] focus:bg-transparent transition font-medium">
                                <option value="1">Đang làm việc (Active)</option>
                                <option value="0">Đã nghỉ việc (Inactive)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-[0.85rem] font-semibold text-[var(--text-muted)] mb-1 flex items-center justify-between">
                            <span>Mật Khẩu <span x-show="modalMode === 'add'" class="text-red-500">*</span></span>
                            <span x-show="modalMode === 'edit'" class="text-[0.75rem] text-orange-500 font-normal italic">Bỏ trống nếu không muốn đổi mật khẩu</span>
                        </label>
                        <input type="password" name="password" x-model="staffData.password" placeholder="Nhập mật khẩu..."
                               :required="modalMode === 'add'"
                               class="w-full p-2.5 text-[0.95rem] rounded-xl border border-black/10 bg-black/5 outline-none focus:border-[var(--accent-primary)] focus:bg-transparent transition">
                    </div>

                    <div class="flex justify-end gap-3 mt-4 pt-5 border-t border-black/5">
                        <button type="button" @click="showModal = false" class="px-5 py-2.5 rounded-xl bg-black/5 hover:bg-black/10 text-[var(--text-main)] font-semibold transition">Hủy bỏ</button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-[var(--accent-primary)] text-white font-semibold shadow-lg shadow-indigo-500/30 hover:scale-105 transition flex items-center gap-2">
                            <i class="ph-bold ph-floppy-disk"></i>
                            <span x-text="modalMode === 'add' ? 'Lưu Nhân Viên' : 'Cập Nhật'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div> @endsection
