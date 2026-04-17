<?php
namespace App\Http\Controllers\Staff;
use App\Http\Controllers\Controller;
use App\Models\Cards;
use App\Models\ParkingSessions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StaffDashboardController extends Controller
{
    public function index() {
        return view('staff.dashboard');
    }

    public function checkIn(Request $request)
    {
        // 1. Validate đầu vào
        // 1. Khai báo các quy tắc (Rules)
        $rules = [
            'rfid_code' => 'required|exists:cards,rfid_code',
            'license_plate' => 'required|string',
        ];

        // 2. Tự viết lời chửi (Custom Messages) bằng tiếng Việt
        // Cú pháp: 'tên_trường.tên_rule' => 'Lời nhắn'
        $messages = [
            'rfid_code.required' => 'Quên quẹt thẻ rồi nhân viên ơi!',
            'rfid_code.exists' => 'Mã thẻ này không tồn tại trong hệ thống kho.',
            'license_plate.required' => 'Bắt buộc phải nhập biển số xe lúc Check-in.',
        ];

        // 3. Khởi tạo Validator
        $validator = Validator::make($request->all(), $rules, $messages);

        // 4. Nếu phát hiện lỗi -> Ép nó vào session('error')
        if ($validator->fails()) {
            // errors()->first() sẽ chỉ lấy ra cái lỗi đầu tiên vấp phải
            // để hiển thị 1 câu ngắn gọn trên Toast cho đẹp
            $firstError = $validator->errors()->first();

            return redirect()->back()
                ->with('error', $firstError)
                ->withInput(); // Nhớ hàm này để giữ lại biển số khách vừa gõ nhé!
        }

        // 2. Thực hiện nghiệp vụ trong Transaction
        try {
            $session = DB::transaction(function () use ($request) {
                // Lấy thông tin thẻ từ mã RFID
                $card = Cards::where('rfid_code', $request->rfid_code)->first();

                // Kiểm tra xem thẻ có đang được sử dụng không (Logic nghiệp vụ thêm)
                if ($card->status === 'in_use') {
                    throw new \Exception("Thẻ này đang được sử dụng, không thể check-in!");
                }

                // A. Tạo phiên gửi xe bằng hàm create()
                $newSession = ParkingSessions::create([
                    'card_id' => $card->id,
                    'ticket_type_id' => $request->ticket_type_id,
                    'license_plate' => $request->license_plate,
                    'check_in_time' => now(), // Lấy thời gian hiện tại
                    'staff_id_in' => Auth::id(), // ID của nhân viên đang đăng nhập
                    'status' => 'parking',
                ]);

                // B. Cập nhật trạng thái thẻ sang 'in_use'
                $card->update(['status' => 'in_use']);

                return $newSession;
            });

            return redirect()->back()->with('success', "Xe {$session->license_plate} đã vào bãi thành công!");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
        }
    }

    public function checkOut() {

    }


}
