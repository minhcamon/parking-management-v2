<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

class StaffManagerController extends Controller
{
    public function index() {
        return view('admin.staff.index');
    }
}
