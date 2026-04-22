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
                'role_label'   => strtoupper($user->role), // Hiển thị chuẩn role từ DB
                'role_class'   => $isAdmin
                    ? 'bg-[#ec4899]/10 text-[#ec4899]'
                    : 'bg-[#6366f1]/10 text-[var(--accent-primary)]',
                'avatar_bg'    => $isAdmin ? 'ec4899' : '6366f1',
                'subtitle'     => $isAdmin ? 'All Access' : 'Staff Member',
                'status_label' => 'ACTIVE',
                'status_class' => 'bg-[#10b981]/20 text-[#34d399]',
                'can_delete'   => !$isAdmin,
            ];
        });

        return view('admin.staff.index', compact('users'));
    }
}
