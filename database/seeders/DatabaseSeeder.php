<?php

namespace Database\Seeders;

use Database\Seeders\LaratrustSeeder;
use Illuminate\Database\Seeder;
use UsersTableSeeder;

class DataBaseSeeder extends Seeder
{
    public function run(): void
    {
     $this->call(LaratrustSeeder::class);
     $this->call(UserTableSeeder::class);
    }
}