<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\seeds\CustomerSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
       // $this->call(CustomerSeeder::class);
       \App\Models\Receipt::factory(10)->create();
    }
}
