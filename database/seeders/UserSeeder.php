<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super_admin = new User;
        $super_admin->name = 'Tosby';
        $super_admin->email = 'super@test.com';
        $super_admin->tel = '1234567891';
        $super_admin->address = 'Kisumu';
        $super_admin->town = 'Kisumu';
        $super_admin->code = '40100';
        $super_admin->password = bcrypt('Master1234');
        $super_admin->api_token = bin2hex(openssl_random_pseudo_bytes(30));
        $super_admin->save();
        $super_admin->attachRole('superadministrator');
    }
}
