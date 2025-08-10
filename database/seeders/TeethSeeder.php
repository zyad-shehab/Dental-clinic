<?php

namespace Database\Seeders;

use App\Models\TeethModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeethSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TeethModel::factory()->count(32)->create();
    }
}
