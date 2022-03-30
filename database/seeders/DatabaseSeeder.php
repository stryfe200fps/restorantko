<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      $user =  new \App\Models\User;
      $user->name = "adrian";
      $user->email = "boragnit@gmail.com";
      $user->password = bcrypt('Asakaboi35');
      $user->save();

        // \App\Models\User::factory(10)->create();
    }
}
