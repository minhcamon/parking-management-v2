<?php
namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;

class OperationController extends Controller
{
    public function search() {
        return view('staff.operations.search');
    }

    public function registerPass() {
        return view('staff.operations.monthly-register');
    }
}
