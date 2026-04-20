<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class StaffManagerController extends Controller
{
    public function index()
    {
        // 1. Lấy dữ liệu từ DB (Có thể dùng paginate() nếu dữ liệu nhiều)
        $rawUsers = User::all();

        // 2. Định dạng dữ liệu và nhúng Logic UI
        $users = $rawUsers->map(function ($user) {
            $isAdmin = $user->role === 'admin';

            return [
                'id'           => $user->id,
                'name'         => $user->name,
                'email'        => $user->email,

                // Logic UI cho Role (Chữ và Background màu)
                'role_label'   => $isAdmin ? 'ADMIN' : 'STAFF',
                'role_class'   => $isAdmin
                    ? 'bg-[#ec4899]/10 text-[#ec4899]'
                    : 'bg-[#6366f1]/10 text-[var(--accent-primary)]',

                // Logic UI cho Avatar (Màu nền trùng với màu Role)
                'avatar_bg'    => $isAdmin ? 'ec4899' : '6366f1',

                // Text mô tả ca làm việc (Giả lập theo giao diện của bạn)
                'subtitle'     => $isAdmin ? 'All Access' : 'Morning Shift',

                // Logic Status (Mặc định màu xanh Active, có thể mở rộng sau)
                'status_label' => 'ACTIVE',
                'status_class' => 'bg-[#10b981]/20 text-[#34d399]',

                // Logic hành động (Chỉ Staff mới bị xóa)
                'can_delete'   => !$isAdmin,
            ];
        });

        return view('admin.staff.index', compact('users'));
    }
}
