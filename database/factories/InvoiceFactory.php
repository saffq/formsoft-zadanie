<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Invoice::class;
    public function definition(): array
    {
        return [
            'invoice_number' => 'F/' . $this->faker->unique()->numberBetween(1, 1000) . '/' . date('Y'),
            'seller_nip'     => $this->faker->numerify('##########'),
            'buyer_nip'      => $this->faker->numerify('##########'),
            'product_name'   => $this->faker->words(3, true),
            'net_amount'     => $this->faker->randomFloat(2, 100, 99999),
            'issuance_date'  => $this->faker->date('Y-m-d'),
        ];
    }
}
