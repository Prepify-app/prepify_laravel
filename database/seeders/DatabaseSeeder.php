<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class   DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'Admin','guard_name'=>'api']);
        $userRole = Role::create(['name' => 'User', 'guard_name'=>'api']);

        $adminUser = User::factory()->create([
            'name' => 'Admin',
            'email' => 'abcdefg1214489@gmail.com',
            'password' => bcrypt('password'),
            'user_level_id' => 1,
        ]);

        $adminUser->assignRole('Admin');
    }
}
