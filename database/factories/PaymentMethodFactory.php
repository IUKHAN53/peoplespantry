<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentMethod>
 */
class PaymentMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'card_number' => fake()->numberBetween($min = 1000, $max = 9000),
            'exp_year' => fake()->year(),
            'exp_month' => fake()->month(),
            'payment_method_id' => 'pm_1L2ubmL2lXGLOCK8TtSf3VGW',
            'is_primary' => false,
        ];
    }

    public function primary()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_primary' => true,
            ];
        });
    }
}
