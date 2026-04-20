<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cards;
use App\Models\VehicleTypes;
use Illuminate\Http\Request;

class ParkingSiteController extends Controller
{
    public function index() {

        $vehicles = VehicleTypes::all();
        $cards = Cards::latest()->paginate(5);

        return view('admin.parking-site.index')->with(compact('vehicles', 'cards'));
    }


}
