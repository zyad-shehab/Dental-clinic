<?php

namespace Database\Factories;

use App\Models\TeethModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TeethFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model=TeethModel::class;

    public function definition(): array
    {
        return [
            'name'=>fake()->numberBetween(11,48)
        ];
    }
}
