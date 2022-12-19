<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Database\Seeders\Feature\SalesSeeder;
use Database\Seeders\Feature\UsersTableSeeder;
use Illuminate\Database\Seeder;
use Ranium\SeedOnce\Traits\SeedOnce;

class DatabaseSeeder extends Seeder
{
    use SeedOnce;

    public function run()
    {
         $this->call(UsersTableSeeder::class);

         if (!app()->environment('production')) {
             $this->call(SalesSeeder::class);
         }
    }
}
