<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Ruben Stoop',
            'email' => 'neburpoots@test.com',
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'password' => Hash::make('Welkom!'),
            'is_admin' => false,
        ]);

        User::create([
            'name' => 'Mark de Haan',
            'email' => 'mark@test.com',
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'password' => Hash::make('Welkom!'),
            'is_admin' => true,
        ]);
    }
}
