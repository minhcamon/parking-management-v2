<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\MonthlyPasses;
use Illuminate\Http\Request;

use App\Models\Cards;
use App\Models\TicketTypes;
use App\Models\VehicleTypes;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonthlyPassController extends Controller {
    public function index(Request $request)
    {
        $query = MonthlyPasses::with(['card', 'ticket_type']);

        $query->when($request->search, function ($q, $search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('license_plate', 'like', "%{$search}%")
                    ->orWhereHas('card', function ($c) use ($search) {
                        $c->where('rfid_code', 'like', "%{$search}%");
                    });
            });
        });

        $query->when($request->status, function ($q, $status) {
            if ($status === 'active') {
                $q->where('end_date', '>=', now());
            } elseif ($status === 'expired') {
                $q->where('end_date', '<', now());
            }
        });

        $passes = $query->latest()->paginate(10)->withQueryString();

        // Lấy 1 thẻ sẵn sàng để cấp vé tháng
        $availableCard = Cards::where('status', 'available')->first();
        // Lấy các loại phương tiện
        $vehicleTypes = VehicleTypes::all();
        // Lấy các loại vé tháng
        $ticketTypes = TicketTypes::where('type', 'pass')->with('vehicle_type')->get();

        return view('admin.monthly-passes.index', compact('passes', 'availableCard', 'vehicleTypes', 'ticketTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'license_plate' => 'required|string|max:20',
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'card_id' => 'required|exists:cards,id',
            'start_date' => 'required|date',
            'months' => 'required|integer|min:1|max:12',
        ]);

        if (!is_valid_vehicle_plate($request->license_plate)) {
            toast('Biển số xe không đúng định dạng Việt Nam!', 'error');
            return redirect()->back()->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $card = Cards::findOrFail($request->card_id);
                if ($card->status !== 'available') {
                    throw new \Exception('Thẻ này đã được sử dụng hoặc không sẵn sàng.');
                }

                $startDate = Carbon::parse($request->start_date);
                $endDate = $startDate->copy()->addMonths((int)$request->months);

                $pass = MonthlyPasses::create([
                    'card_id' => $card->id,
                    'ticket_type_id' => $request->ticket_type_id,
                    'customer_name' => strtoupper($request->customer_name),
                    'license_plate' => strtoupper(trim($request->license_plate)),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]);

                // Record transaction for revenue tracking
                $ticketType = TicketTypes::findOrFail($request->ticket_type_id);
                \App\Models\Transactions::create([
                    'monthly_pass_id' => $pass->id,
                    'amount' => $ticketType->price * (int)$request->months,
                    'payment_time' => now(),
                    'staff_id' => auth()->id() ?? 1,
                ]);

                // Update card status
                $card->update(['status' => 'assigned']);
            });

            toast('Đăng ký vé tháng thành công!', 'success');
        } catch (\Exception $e) {
            toast('Lỗi: ' . $e->getMessage(), 'error');
        }

        return redirect()->back();
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'license_plate' => 'required|string|max:20',
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if (!is_valid_vehicle_plate($request->license_plate)) {
            toast('Biển số xe không đúng định dạng Việt Nam!', 'error');
            return redirect()->back()->withInput();
        }

        try {
            $pass = MonthlyPasses::findOrFail($id);
            $pass->update([
                'customer_name' => strtoupper($request->customer_name),
                'license_plate' => strtoupper(trim($request->license_plate)),
                'ticket_type_id' => $request->ticket_type_id,
                'start_date' => Carbon::parse($request->start_date),
                'end_date' => Carbon::parse($request->end_date),
            ]);
            toast('Cập nhật vé tháng thành công!', 'success');
        } catch (\Exception $e) {
            toast('Lỗi: ' . $e->getMessage(), 'error');
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $pass = MonthlyPasses::findOrFail($id);
                // Đánh dấu thẻ là rảnh
                if ($pass->card) {
                    $pass->card->update(['status' => 'available']);
                }
                $pass->delete();
            });
            toast('Đã xóa vé tháng và giải phóng thẻ!', 'success');
        } catch (\Exception $e) {
            toast('Lỗi khi xóa: ' . $e->getMessage(), 'error');
        }

        return redirect()->back();
    }
}
