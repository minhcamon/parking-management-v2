<?php

namespace Database\Seeders;

use App\Models\Cards;
use App\Models\ParkingSessions;
use App\Models\TicketTypes;
use App\Models\Transactions;
use App\Models\User;
use App\Models\VehicleTypes;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Nhân viên Cổng 1',
            'email' => 'staff@gmail.com',
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
            ]
        );

        $ticketCar = TicketTypes::firstOrCreate(
            ['name' => 'Vé ô tô (Ban ngày)'],
            [
                'price' => 30000,
                'is_active' => true,
                'vehicle_type_id' => $car->id,
            ]
        );

        // 4. Create Tags/Cards
        $cards = [];
        for ($i = 1; $i <= 10; $i++) {
            $cards[] = Cards::firstOrCreate(
                ['rfid_code' => 'RFID_' . str_pad($i, 5, '0', STR_PAD_LEFT)],
                ['status' => ($i <= 5) ? 'inuse' : 'available']
            );
        }

        // 5. Create Parking Sessions (In-Use)
        for ($i = 0; $i < 5; $i++) {
            $isMotor = $i < 3;

            $session = ParkingSessions::create([
                'card_id' => $cards[$i]->id,
                'ticket_type_id' => $isMotor ? $ticketMotor->id : $ticketCar->id,
                'license_plate' => $isMotor ? "29A1-1234{$i}" : "30F-9999{$i}",
                'check_in_time' => Carbon::now()->subHours(rand(1, 10)),
                'check_out_time' => null,
                'staff_id_in' => $admin->id,
                'staff_id_out' => null,
                'status' => 'parking',
            ]);
        }

        // 6. Create Historical Completed Sessions & Transactions
        for ($i = 5; $i < 8; $i++) {
            $isMotor = $i < 7;

            // Completed session
            $session = ParkingSessions::create([
                'card_id' => $cards[$i]->id,
                'ticket_type_id' => $isMotor ? $ticketMotor->id : $ticketCar->id,
                'license_plate' => $isMotor ? "29H1-8888{$i}" : "30K-4444{$i}",
                'check_in_time' => Carbon::now()->subDays(1)->subHours(rand(1, 5)),
                'check_out_time' => Carbon::now()->subDays(1),
                'staff_id_in' => $admin->id,
                'staff_id_out' => $admin->id,
                'status' => 'completed',
            ]);

            // Transaction for this session
            Transactions::create([
                'session_id' => $session->id,
                'monthly_pass_id' => null,
                'amount' => $isMotor ? $ticketMotor->price : $ticketCar->price,
                'payment_time' => $session->check_out_time,
                'staff_id' => $admin->id,
            ]);
        }
    }
}
