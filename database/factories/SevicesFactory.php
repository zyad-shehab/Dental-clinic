<?php

namespace Database\Factories;

use App\Models\ServicesModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SevicesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model=ServicesModel::class;

    public function definition(): array
    {

        return [
            'name'=>fake()->Str::random(5)
        ];
    }
}
