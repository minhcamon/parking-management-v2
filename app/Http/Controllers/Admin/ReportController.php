<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function transactions() {
        return view('admin.reports.transactions');
    }

    public function revenue() {
        return view('admin.reports.revenue');
    }
}
