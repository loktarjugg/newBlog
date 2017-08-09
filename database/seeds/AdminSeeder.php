<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if (\App\Models\User::where('email' , 'admin@admin.com')->first()){
            return;
        }

        factory(\App\Models\User::class , 1)->create([
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'name' => 'admin',
            'is_admin' => true
        ]);
    }
}
