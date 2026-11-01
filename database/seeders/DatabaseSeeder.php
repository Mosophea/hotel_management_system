<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomType;
use App\Models\Room;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $standard = RoomType::firstOrCreate(['name' => 'Standard'], ['description' => 'Comfortable standard room', 'price_per_night' => 25]);
        $deluxe = RoomType::firstOrCreate(['name' => 'Deluxe'], ['description' => 'Deluxe room with extra space', 'price_per_night' => 45]);
        $suite = RoomType::firstOrCreate(['name' => 'Suite'], ['description' => 'Premium suite', 'price_per_night' => 80]);
        foreach ([['101', $standard->id, 1], ['102', $standard->id, 1], ['201', $deluxe->id, 2], ['301', $suite->id, 3]] as [$num, $type, $floor]) Room::firstOrCreate(['room_number' => $num], ['room_type_id' => $type, 'floor' => $floor, 'status' => 'available']);
    }
}
