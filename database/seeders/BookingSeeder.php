<?php

namespace Database\Seeders;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $bookings = [
            [
                'media_id'     => 2,
                'customer_id'  => 1,
                'price'        => 250.00,
                'start_offset' => 2,
                'duration'     => 5,
                'status'       => 'confirmed',
            ],
            [
                'media_id'     => 1,
                'customer_id'  => 2,
                'price'        => 120.00,
                'start_offset' => 3,
                'duration'     => 7,
                'status'       => 'confirmed',
            ],
            [
                'media_id'     => 3,
                'customer_id'  => 3,
                'price'        => 45.00,
                'start_offset' => 1,
                'duration'     => 14,
                'status'       => 'pending',
            ],
            [
                'media_id'     => 5,
                'customer_id'  => 5,
                'price'        => 300.00,
                'start_offset' => 10,
                'duration'     => 4,
                'status'       => 'pending',
            ],
            [
                'media_id'     => 4,
                'customer_id'  => 4,
                'price'        => 180.00,
                'start_offset' => 7,
                'duration'     => 10,
                'status'       => 'confirmed',
            ],
            [
                'media_id'     => 7,
                'customer_id'  => 6,
                'price'        => 55.00,
                'start_offset' => 4,
                'duration'     => 6,
                'status'       => 'confirmed',
            ],
        ];

        foreach ($bookings as $b) {
            $startsAt = Carbon::today()->addDays($b['start_offset']);
            $endsAt   = (clone $startsAt)->addDays($b['duration'] - 1);
            $days     = $startsAt->diffInDays($endsAt) + 1;

            Booking::create([
                'media_id'    => $b['media_id'],
                'customer_id' => $b['customer_id'],
                'starts_at'   => $startsAt->toDateString(),
                'ends_at'     => $endsAt->toDateString(),
                'total_price' => $days * $b['price'],
                'status'      => $b['status'],
            ]);
        }
    }
}
