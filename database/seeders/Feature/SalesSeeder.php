<?php

namespace Database\Seeders\Feature;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Sale;
use Illuminate\Database\Seeder;
use Ranium\SeedOnce\Traits\SeedOnce;

class SalesSeeder extends Seeder
{
    use SeedOnce;

    public function run()
    {
        return Sale::factory()->count(100)->create();
    }
}
