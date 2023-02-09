<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTableSeeder::class);
        $user  = User::create([
            'name' => 'Admin',
            'email'=> 'demo@admin.com',
            'password' => Hash::make(123456789),
            'roles_name' => ['Owner'],
            'Status' => 'مفعل'
        ]);
        $user->assignRole("admin");

    }
}
