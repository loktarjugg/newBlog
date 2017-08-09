<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(ArticleSeeder::class);
        $this->call(ShareTableSeeder::class);
        $this->call(TagSeeder::class);
    }
}
