<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a new admin
        Admin::create([
            'name' =>  'Admin',
            'email' =>  'tipsmandi@gmail.com',
            'password'   =>  Hash::make('a_Ka8SHyHDPr%HT%Rry!'),
            'last_login_at'   =>  Carbon::now()->toDateTimeString(),
            'last_login_ip'   =>  '2405:201:5c07:a04c:98dc:5f34:5864:6c63'
        ]);
    }
}
