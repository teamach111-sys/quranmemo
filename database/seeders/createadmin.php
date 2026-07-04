<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class createadmin extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'muslim',
            'email' => 'admin@muslim.com',
            'password' => bcrypt('GsuDNt5E.bk:R4Q'),
            'role' => 'administrateur',
        ]);
        User::create([
            'name' => 'muslim2',
            'email' => 'admin2@muslim.com',
            'password' => bcrypt('GsuDNt5E.bk:R4Q'),
            'role' => 'professeur',
        ]);
    }
}
