<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RgpdPlantilla>
 */
class RgpdPlantillaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject' => $this->faker->sentence(),
            'body' => $this->faker->paragraph(5),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
