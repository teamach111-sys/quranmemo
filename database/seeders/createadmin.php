<?php

namespace Database\Seeders;

use App\Models\User;

class createadmin extends \Illuminate\Database\Seeder
{
    public function run()
    {
        User::create([
            'name' => 'muslim',
            'email' => 'admin@muslim.com',
            'password' => bcrypt('GsuDNt5E.bk:R4Q'),
            'role' => 'administrateur',
        ]);
    }
}