<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

class MonthlyPassController extends Controller
{
    public function index() {
        return view('admin.monthly-passes.index');
    }
}
