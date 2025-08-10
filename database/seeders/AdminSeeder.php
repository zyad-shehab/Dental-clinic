<?php

namespace Database\Seeders;

use App\Models\AdminModel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdminModel::query()->create([
            'name'=>'mohamed hrz',
            'username'=>'admin',
            'password'=>Hash::make('123456'),
        ]);
        User::create([
                'name' => 'mohamed hrz',
                'username' => 'hrzadmin',
                'password' => Hash::make('123456'),
                'type' => 'admin',
                'type_id' => 1, // Assuming the admin ID is 1
            ]);
    }
}
