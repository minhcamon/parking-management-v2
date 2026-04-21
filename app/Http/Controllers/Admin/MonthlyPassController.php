<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\MonthlyPasses;

class MonthlyPassController extends Controller
{
    public function index() {

        $passes = MonthlyPasses::latest()->paginate(10);

        $passes->map(function($passes) {



        });


        return view('admin.monthly-passes.index')->with(compact('passes'));
    }
}
