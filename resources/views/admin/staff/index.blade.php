@extends('admin.admin-layout')
@section('title', 'Staff Management - Admin')
@section('page-title', 'Staff Management')

@section('content')
<div class="premium-card mb-6">
    <div class="flex justify-between items-center">
        <div class="relative w-[300px]">
            <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-[var(--text-muted)]"></i>
            <input type="text" class="form-control pl-10 mb-0" placeholder="Search staff members...">
        </div>
        <button class="btn btn-primary px-6 py-[0.8rem] bg-[var(--accent-primary)] text-white border-none rounded-lg font-medium cursor-pointer flex items-center gap-2">
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
                <!-- Staff 1 -->
                <tr class="border-b border-black/5">
                    <td class="p-4">
                        <div class="flex items-center gap-4">
                            <img src="https://ui-avatars.com/api/?name=John+Doe&background=6366f1&color=fff" alt="Avatar" class="w-10 h-10 rounded-full">
                            <div>
                                <div class="font-semibold text-[var(--text-main)]">John Doe</div>
                                <div class="text-[0.85rem] text-[var(--text-muted)]">Morning Shift</div>
                            </div>
                        </div>
                    </td>
                    <td class="p-4">
                        <span class="bg-[#6366f1]/10 text-[var(--accent-primary)] px-2.5 py-1 rounded-[20px] text-[0.75rem] font-semibold">STAFF</span>
                    </td>
                    <td class="p-4 text-[var(--text-muted)]">john.doe@parkgrid.com</td>
                    <td class="p-4">
                        <span class="bg-[#10b981]/20 text-[#34d399] px-2.5 py-1 rounded-[20px] text-[0.75rem] font-semibold">ACTIVE</span>
                    </td>
                    <td class="p-4 text-right">
                        <button class="bg-transparent border-none text-[var(--text-muted)] cursor-pointer mr-2"><i class="ph ph-pencil-simple text-[1.2rem]"></i></button>
                        <button class="bg-transparent border-none text-red-500 cursor-pointer"><i class="ph ph-trash text-[1.2rem]"></i></button>
                    </td>
                </tr>
                <!-- Admin 1 -->
                <tr class="border-none">
                    <td class="p-4">
                        <div class="flex items-center gap-4">
                            <img src="https://ui-avatars.com/api/?name=Admin+User&background=ec4899&color=fff" alt="Avatar" class="w-10 h-10 rounded-full">
                            <div>
                                <div class="font-semibold text-[var(--text-main)]">System Admin</div>
                                <div class="text-[0.85rem] text-[var(--text-muted)]">All Access</div>
                            </div>
                        </div>
                    </td>
                    <td class="p-4">
                        <span class="bg-[#ec4899]/10 text-[#ec4899] px-2.5 py-1 rounded-[20px] text-[0.75rem] font-semibold">ADMIN</span>
                    </td>
                    <td class="p-4 text-[var(--text-muted)]">admin@parkgrid.com</td>
                    <td class="p-4">
                        <span class="bg-[#10b981]/20 text-[#34d399] px-2.5 py-1 rounded-[20px] text-[0.75rem] font-semibold">ACTIVE</span>
                    </td>
                    <td class="p-4 text-right">
                        <button class="bg-transparent border-none text-[var(--text-muted)] cursor-pointer mr-2"><i class="ph ph-pencil-simple text-[1.2rem]"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
