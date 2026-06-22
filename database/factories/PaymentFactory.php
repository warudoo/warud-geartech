<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'provider' => 'midtrans',
            'provider_order_id' => 'ORD-'.fake()->unique()->numerify('########'),
            'snap_token' => 'snap-'.fake()->unique()->uuid(),
            'snap_redirect_url' => 'https://app.sandbox.midtrans.com/snap/v4/redirection/'.fake()->uuid(),
            'transaction_id' => 'TX-'.fake()->unique()->numerify('########'),
            'transaction_status' => 'pending',
            'status_code' => '201',
            'payment_type' => fake()->randomElement(['bank_transfer', 'qris', 'gopay', 'credit_card']),
            'fraud_status' => null,
            'va_numbers' => null,
            'raw_payload' => null,
            'paid_at' => null,
            'expires_at' => now()->addDay(),
            'last_callback_at' => null,
        ];
    }
}
