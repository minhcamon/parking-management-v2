<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cards;
use App\Models\Transactions;
use App\Models\VehicleTypes;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the Admin Dashboard with aggregated statistics.
     *
     * @return View
     */
    public function getDashboard()
    {
        // 1. Total Revenue: Sum of all transaction amounts
        $totalRevenue = Transactions::sum('amount');

        // 2. Card Manager: Total count (Moved to slots area, Preview removed)
        $totalCards = Cards::count();

        // 3. Current Vehicle Slot Remain:
        // Query VehicleTypes with the count of active parking sessions (parked_count)
        // parked_count is filtered where status = 'parking'
        $vehicleTypes = VehicleTypes::withCount(['parking_sessions as parked_count' => function ($query) {
            $query->where('status', 'parking');
        }])->get();

        // Calculate slot_remain for each vehicle type
        $vehicleTypes->each(function ($type) {
            $type->slot_remain = $type->total_slots - $type->parked_count;
        });
        // 4. Ticket Distribution Chart Data (Monthly vs Casual from Transactions)
        $casualTickets = Transactions::whereNull('monthly_pass_id')->count();
        $monthlyTickets = Transactions::whereNotNull('monthly_pass_id')->count();
        $chartData = [
            'casual' => $casualTickets,
            'monthly' => $monthlyTickets
        ];

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalCards',
            'vehicleTypes',
            'chartData'
        ));
    }

    /**
     * Handle the request to add a new rfid card.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function addNewCard(Request $request)
    {
        // Validate input: rfid_code must be unique in cards table
        $request->validate([
            'rfid_code' => 'required|string|unique:cards,rfid_code',
        ]);

        // Create the card record
        Cards::create([
            'rfid_code' => $request->rfid_code,
        ]);

        // Redirect back with success (can be handled in Blade)
        return redirect()->back();
    }
}
