<?php

namespace Database\Seeders;

use App\Models\Cards;
use App\Models\ParkingSessions;
use App\Models\MonthlyPasses;
use App\Models\TicketTypes;
use App\Models\Transactions;
use App\Models\User;
use App\Models\VehicleTypes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users
        $admin = User::create([
            'name' => 'Quản lý Bãi xe',
            'email' => 'admin@parkgrid.com',
            'password' => bcrypt('123456'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Nhân viên Cổng 1',
            'email' => 'staff@parkgrid.com',
            'password' => bcrypt('123456'),
            'role' => 'staff',
        ]);

        // 2. Create Vehicle Types
        $motor = VehicleTypes::firstOrCreate(
            ['name' => 'Xe Máy'],
            ['total_slots' => 300]
        );

        $car = VehicleTypes::firstOrCreate(
            ['name' => 'Ô Tô'],
            ['total_slots' => 50]
        );

        // 3. Create Ticket Types
        $ticketMotor = TicketTypes::firstOrCreate(
            ['name' => 'Vé xe máy (Ban ngày)'],
            [
                'price' => 5000,
                'is_active' => true,
                'vehicle_type_id' => $motor->id,
                'type' => 'normal',
            ]
        );

        $ticketCar = TicketTypes::firstOrCreate(
            ['name' => 'Vé ô tô (Ban ngày)'],
            [
                'price' => 30000,
                'is_active' => true,
                'vehicle_type_id' => $car->id,
                'type' => 'normal',
            ]
        );

        $ticketMotorMonthly = TicketTypes::firstOrCreate(
            ['name' => 'Vé tháng xe máy'],
            [
                'price' => 150000,
                'is_active' => true,
                'vehicle_type_id' => $motor->id,
                'type' => 'pass',
            ]
        );

        $ticketCarMonthly = TicketTypes::firstOrCreate(
            ['name' => 'Vé tháng ô tô'],
            [
                'price' => 1200000,
                'is_active' => true,
                'vehicle_type_id' => $car->id,
                'type' => 'pass',
            ]
        );

        // 4. Create Tags/Cards
        $cards = [];
        for ($i = 1; $i <= 10; $i++) {
            $status = 'available';
            if ($i <= 3) $status = 'inuse'; // 1-3
            elseif ($i <= 5) $status = 'assigned'; // 4-5
            
            $cards[] = Cards::firstOrCreate(
                ['rfid_code' => 'RFID_' . str_pad($i, 5, '0', STR_PAD_LEFT)],
                ['status' => $status]
            );
        }

        // 5. Create Monthly Passes
        $pass1 = MonthlyPasses::create([
            'card_id' => $cards[3]->id, // Card 4
            'ticket_type_id' => $ticketMotorMonthly->id,
            'customer_name' => 'Nguyễn Văn A',
            'license_plate' => '29A1-99999',
            'start_date' => Carbon::now()->subDays(10),
            'end_date' => Carbon::now()->addDays(20),
        ]);
        Transactions::create([
            'monthly_pass_id' => $pass1->id,
            'amount' => $ticketMotorMonthly->price,
            'payment_time' => Carbon::now()->subDays(10),
            'staff_id' => $admin->id,
        ]);

        $pass2 = MonthlyPasses::create([
            'card_id' => $cards[4]->id, // Card 5
            'ticket_type_id' => $ticketCarMonthly->id,
            'customer_name' => 'Trần Thị B',
            'license_plate' => '30F-88888',
            'start_date' => Carbon::now()->subDays(5),
            'end_date' => Carbon::now()->addDays(25),
        ]);
        Transactions::create([
            'monthly_pass_id' => $pass2->id,
            'amount' => $ticketCarMonthly->price,
            'payment_time' => Carbon::now()->subDays(5),
            'staff_id' => $admin->id,
        ]);

        // 6. Create Parking Sessions (In-Use)
        for ($i = 0; $i < 3; $i++) {
            $isMotor = $i < 2;

            ParkingSessions::create([
                'card_id' => $cards[$i]->id, // Card 1-3
                'ticket_type_id' => $isMotor ? $ticketMotor->id : $ticketCar->id,
                'license_plate' => $isMotor ? "29A1-1234{$i}" : "30F-9999{$i}",
                'check_in_time' => Carbon::now()->subHours(rand(1, 10)),
                'check_out_time' => null,
                'staff_id_in' => $admin->id,
                'staff_id_out' => null,
                'status' => 'parking',
            ]);
        }

        // 7. Create Historical Completed Sessions & Casual Transactions
        for ($i = 5; $i < 8; $i++) {
            $isMotor = $i < 7;

            // Completed session
            $session = ParkingSessions::create([
                'card_id' => $cards[$i]->id, // Card 6-8
                'ticket_type_id' => $isMotor ? $ticketMotor->id : $ticketCar->id,
                'license_plate' => $isMotor ? "29H1-8888{$i}" : "30K-4444{$i}",
                'check_in_time' => Carbon::now()->subDays(1)->subHours(rand(1, 5)),
                'check_out_time' => Carbon::now()->subDays(1),
                'staff_id_in' => $admin->id,
                'staff_id_out' => $admin->id,
                'status' => 'completed',
            ]);

            // Transaction for this casual session (no session_id anymore)
            Transactions::create([
                'monthly_pass_id' => null,
                'amount' => $isMotor ? $ticketMotor->price : $ticketCar->price,
                'payment_time' => $session->check_out_time,
                'staff_id' => $admin->id,
            ]);
        }
    }
}
