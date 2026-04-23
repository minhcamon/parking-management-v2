<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StaffManagerController extends Controller
{
    public function index(Request $request)
    {
        // 1. Lấy dữ liệu từ DB với Search và Filter
        $query = User::query();

        // Search theo Tên hoặc Email
        $query->when($request->search, function ($q, $search) {
            $q->where(function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
            });
        });

        // Lọc theo Role
        $query->when($request->role, function ($q, $role) {
            $q->where('role', $role);
        });

        // 2. Phân trang và giữ lại các tham số query trên URL
        $rawUsers = $query->paginate(10)->withQueryString();

        // 3. Định dạng dữ liệu và nhúng Logic UI qua through() của Paginate
        $users = $rawUsers->through(function ($user) {
            $isAdmin = $user->role === 'admin';

            return [
                'id'           => $user->id,
                'name'         => $user->name,
                'email'        => $user->email,
                'role'         => $user->role,
                'role_label'   => strtoupper($user->role), // Hiển thị chuẩn role từ DB
                'role_class'   => $isAdmin
                    ? 'bg-[#ec4899]/10 text-[#ec4899]'
                    : 'bg-[#6366f1]/10 text-[var(--accent-primary)]',
                'avatar_bg'    => $isAdmin ? 'ec4899' : '6366f1',
                'subtitle'     => $isAdmin ? 'All Access' : 'Staff Member',
                'status_label' => $user->is_active ? 'ACTIVE' : 'INACTIVE',
                'status_class' => $user->is_active
                    ? 'bg-[#10b981]/20 text-[#34d399]'
                    : 'bg-red-500/10 text-red-500',
                'can_delete'   => !$isAdmin,
                'can_edit'     => !$isAdmin,
                'is_active'    => $user->is_active,
            ];
        });

        return view('admin.staff.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string|in:admin,staff', // Matching DB enum
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => bcrypt($request->password),
            'is_active' => $request->is_active ?? 1,
        ]);

        toast('Nhân viên mới đã được tạo thành công!', 'success');
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Chặn chỉnh sửa nếu là tài khoản Admin để bảo vệ hệ thống
        if ($user->role === 'admin') {
            toast('Không thể chỉnh sửa tài khoản Quản trị viên để đảm bảo an toàn hệ thống!', 'error');
            return redirect()->back();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|string|in:admin,staff',
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->is_active ?? 1,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        toast('Cập nhật thông tin nhân viên thành công!', 'success');
        return redirect()->back();
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            toast('Không thể xóa quản trị viên hệ thống!', 'error');
            return redirect()->back();
        }

        $user->delete();
        toast('Đã xóa nhân viên khỏi hệ thống!', 'success');
        return redirect()->back();
    }
}
