<?php
namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;

class HistoryController extends Controller
{
    public function index() {
        return view('staff.history.index');
    }
}
