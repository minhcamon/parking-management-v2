@extends('admin.admin-layout')
@section('title', 'Staff Management - Admin')
@section('page-title', 'Staff Management')

@section('content')

    <div x-data="{
        showModal: false,
        modalMode: 'add',
        staffData: { id: '', name: '', email: '', role: 'staff', is_active: 1, password: '' }
    }">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h3 class="text-2xl font-black m-0 mb-1">Quản lý Nhân Viên</h3>
                <p class="text-[0.8rem] text-muted font-medium">Theo dõi và phân quyền nhân viên hệ thống</p>
            </div>

            <button @click="showModal = true; modalMode = 'add'; staffData = { id: '', name: '', email: '', role: 'staff', is_active: 1, password: '' }"
                    class="btn btn-primary btn-md">
                <i class="ph-bold ph-user-plus"></i> Thêm Nhân Viên
            </button>
        </div>

        <div class="bg-nav-hover p-4 rounded-2xl border border-header-border mb-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <form action="{{ route('admin.staff.index') }}" method="GET" class="flex flex-wrap items-center gap-1 p-1 bg-dropdown-bg rounded-xl border border-header-border w-full md:w-auto flex-1">
                    <div class="relative min-w-[250px] flex-1">
                        <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-muted"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="pl-10 m-0 py-2 w-full text-sm bg-transparent border-transparent focus:bg-nav-hover rounded-lg transition-colors" placeholder="Tìm tên hoặc email...">
                    </div>

                    <div class="w-[1px] h-6 bg-header-border hidden md:block"></div>

                    <select name="role" onchange="this.form.submit()"
                            class="m-0 py-2 w-[150px] text-sm bg-transparent border-transparent focus:bg-nav-hover rounded-lg transition-colors">
                        <option value="">Tất cả vai trò</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>

                    <button type="submit" class="btn btn-sm btn-primary px-4 py-2 ml-1">Lọc</button>

                    @if(request()->hasAny(['search', 'role']))
                        <a href="{{ route('admin.staff.index') }}" class="btn btn-sm px-3 py-2 ml-1 bg-transparent border-transparent text-red-500 hover:bg-red-500/10 transition-colors" title="Xóa lọc">
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
                    <tr class="border-b-2 border-header-border text-muted text-sm uppercase tracking-[0.5px]">
                        <th class="p-4">User</th>
                        <th class="p-4">Role</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $u)
                        <tr class="border-b border-header-border hover:bg-nav-hover transition last:border-none">

                            <td class="p-4">
                                <div class="flex items-center gap-4">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($u['name']) }}&background={{ ltrim($u['avatar_bg'], '#') }}&color=fff" alt="Avatar" class="w-10 h-10 rounded-full shadow-sm">
                                    <div>
                                        <div class="font-semibold text-main">{{ $u['name'] }}</div>
                                        <div class="text-sm text-muted">{{ $u['subtitle'] }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="p-4">
                                <span class="{{ $u['role_class'] }} px-2.5 py-1 rounded-[20px] text-[0.75rem] font-semibold">
                                    {{ $u['role_label'] }}
                                </span>
                            </td>

                            <td class="p-4 text-muted">{{ $u['email'] }}</td>

                            <td class="p-4">
                                <span class="{{ $u['status_class'] }} px-2.5 py-1 rounded-[20px] text-[0.75rem] font-semibold">
                                    {{ $u['status_label'] }}
                                </span>
                            </td>

                            <td class="p-4 text-right">
                                @if($u['can_edit'])
                                    <button @click="showModal = true; modalMode = 'edit'; staffData = { id: '{{ $u['id'] }}', name: '{{ $u['name'] }}', email: '{{ $u['email'] }}', role: '{{ $u['role'] }}', is_active: {{ $u['is_active'] ? 1 : 0 }} }"
                                            class="btn p-2 hover:bg-indigo-50 mr-2 p-2 rounded-lg btn-ghost">
                                        <i class="ph-fill ph-pencil-simple text-lg"></i>
                                    </button>
                                @endif

                                @if($u['can_delete'])
                                    <form action="{{ route('admin.staff.destroy', $u['id']) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhân viên này? Thao tác này không thể hoàn tác.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn p-2 hover:bg-red-500/10 rounded-lg btn-ghost text-red-500">
                                            <i class="ph-fill ph-trash text-lg"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-muted italic bg-nav-hover rounded-xl">
                                Không có nhân viên nào trong hệ thống.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        <div class="mt-6">
            {{ $users->links() }}
        </div>


        <div x-show="showModal"
             style="display: none;"
             class="fixed inset-0 z-[100] flex items-center justify-center">

            <div x-show="showModal"
                 x-transition.opacity.duration.300ms
                 @click="showModal = false"
                 class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

            <x-card x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-8 scale-95" class="relative w-full max-w-lg m-4 bg-dropdown-bg  shadow-2xl p-6 rounded-2xl">

                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-accent/10 flex items-center justify-center text-accent">
                            <i class="ph-fill" :class="modalMode === 'add' ? 'ph-user-plus' : 'ph-pencil-simple'"></i>
                        </div>
                        <h3 class="text-[1.3rem] font-bold" x-text="modalMode === 'add' ? 'Thêm Nhân Viên Mới' : 'Cập Nhật Thông Tin'"></h3>
                    </div>

                    <button type="button" @click="showModal = false" class="btn hover:bg-red-100 hover:text-red-500 w-8 h-8 rounded-full btn-ghost">
                        <i class="ph-bold ph-x"></i>
                    </button>
                </div>

                <form :action="modalMode === 'add' ? '{{ route('admin.staff.store') }}' : '{{ route('admin.staff.update', 'DUMMY_ID') }}'.replace('DUMMY_ID', staffData.id)" 
                      method="POST" class="flex flex-col gap-4">
                    @csrf
                    <template x-if="modalMode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <input type="hidden" name="id" x-model="staffData.id">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-muted mb-1 block">Họ và Tên <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="staffData.name" placeholder="Ví dụ: Nguyễn Văn A" required autocomplete="off"
                                   class="w-full p-2.5 text-[0.95rem] rounded-xl border border-header-border bg-nav-hover outline-none focus:border-accent focus:bg-transparent transition">
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-muted mb-1 block">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" x-model="staffData.email" placeholder="nhanvien@congty.com" required autocomplete="off"
                                   class="w-full p-2.5 text-[0.95rem] rounded-xl border border-header-border bg-nav-hover outline-none focus:border-accent focus:bg-transparent transition">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-muted mb-1 block">Phân Quyền (Role) <span class="text-red-500">*</span></label>
                            <select name="role" x-model="staffData.role" required
                                    class="w-full p-2.5 text-[0.95rem] rounded-xl border border-header-border bg-nav-hover outline-none focus:border-accent focus:bg-transparent transition font-medium">
                                <option value="staff">Nhân viên (Staff)</option>
                                <option value="admin">Quản trị viên (Admin)</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-muted mb-1 block">Trạng Thái</label>
                            <select name="is_active" x-model="staffData.is_active"
                                    class="w-full p-2.5 text-[0.95rem] rounded-xl border border-header-border bg-nav-hover outline-none focus:border-accent focus:bg-transparent transition font-medium">
                                <option value="1">Đang làm việc (Active)</option>
                                <option value="0">Đã nghỉ việc (Inactive)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-muted mb-1 flex items-center justify-between">
                            <span>Mật Khẩu <span x-show="modalMode === 'add'" class="text-red-500">*</span></span>
                            <span x-show="modalMode === 'edit'" class="text-[0.75rem] text-orange-500 font-normal italic">Bỏ trống nếu không muốn đổi mật khẩu</span>
                        </label>
                        <input type="password" name="password" x-model="staffData.password" placeholder="Nhập mật khẩu..."
                               :required="modalMode === 'add'"
                               class="w-full p-2.5 text-[0.95rem] rounded-xl border border-header-border bg-nav-hover outline-none focus:border-accent focus:bg-transparent transition">
                    </div>

                    <div class="flex justify-end gap-3 mt-4 pt-5 border-t border-header-border">
                        <button type="button" @click="showModal = false" class="btn btn-md btn-ghost">Hủy bỏ</button>
                        <button type="submit" class="btn btn-lg btn-primary">
                            <i class="ph-bold ph-floppy-disk"></i>
                            <span x-text="modalMode === 'add' ? 'Lưu Nhân Viên' : 'Cập Nhật'"></span>
                        </button>
                    </div>
                </form>
            </x-card>
        </div>

    </div> @endsection
