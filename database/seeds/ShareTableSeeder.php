<?php

use Illuminate\Database\Seeder;

class ShareTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Share::class , 100)->create();
    }
}
