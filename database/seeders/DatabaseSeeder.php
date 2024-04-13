<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MeteringPoint;
use App\Models\DataPoint;
use Faker\Core\DateTime;
use Ramsey\Uuid\Type\Decimal;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(3)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        /* MeteringPoint::create([
            'id' => '1234567890',
            'user_id' => 1,
            'type' => 'A04',
            'sheet_id' => '0123456789'
        ]);

        MeteringPoint::create([
            'id' => '1234567891',
            'user_id' => 2,
            'type' => 'A04',
            'sheet_id' => '0123456789'
        ]); */

        $meteringpoints = [];
        for ($i = 0; $i < 5; $i++) {
            $meteringpoints[] =
                MeteringPoint::factory()->create([
                    'user_id' => $users[rand(0, count($users) - 1)]->id
                ]);
        }
        DataPoint::create([
            'metering_point_id' => $meteringpoints[0]->id,
            'quantity' => '000.123',
            'quality' => 'A04',
            'time_start' => Carbon::create(2024, 01, 23, 00, 0, 0)
        ]);
        DataPoint::create([
            'metering_point_id' => $meteringpoints[1]->id,
            'quantity' => '000.123',
            'quality' => 'A04',
            'time_start' => Carbon::parse("2024-03-21T23:00:00Z")
            // Carbon::create(2024, 01, 23, 01, 0, 0)
        ]);
        DataPoint::create([
            'metering_point_id' => $meteringpoints[1]->id,
            'quantity' => '000.123',
            'quality' => 'A04',
            'time_start' => Carbon::parse("2024-03-21T22:00:00Z")

        ]);
    }
}
