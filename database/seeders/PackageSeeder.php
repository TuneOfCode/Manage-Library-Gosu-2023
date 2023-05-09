<?php

namespace Database\Seeders;

use App\Enums\PackageType;
use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        Package::create([
            'name' => 'Free Package',
            'type' => PackageType::NORMAL(),
            'price' => 0,
            'discount' => 0,
        ]);
        Package::create([
            'name' => 'Brozen Package',
            'type' => PackageType::BROZEN(),
            'price' => 50000,
            'discount' => 5,
        ]);
        Package::create([
            'name' => 'Silver Package',
            'type' => PackageType::SILVER(),
            'price' => 100000,
            'discount' => 10,
        ]);
        Package::create([
            'name' => 'Gold Package',
            'type' => PackageType::GOLD(),
            'price' => 200000,
            'discount' => 15,
        ]);
        // Package::factory()
        //     ->count(1)
        //     // ->hasUsers(20)
        //     ->create();
    }
}
