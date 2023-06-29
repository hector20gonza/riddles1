<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UseradminSeeder extends Seeder
{
    public function run()
    {
       $contraseña = "Tester2023";
            $user = new User([
              
                "name" => "Admin",
                "email" => "admin@gmail.com",
                "password" =>bcrypt($contraseña),
                "is_admin"=>true,
            ]);
        $user->saveOrFail();
      }
}
