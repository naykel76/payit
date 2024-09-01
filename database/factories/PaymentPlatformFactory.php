<?php

namespace Naykel\Payit\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Naykel\Payit\Models\PaymentPlatform;

class PaymentPlatformFactory extends Factory
{
    protected $model = PaymentPlatform::class;

    public function definition(): array
    {
        return [
            'platform_name' => $this->faker->word,
            'method' => $this->faker->unique()->word,
            'active' => true,
        ];
    }
}
