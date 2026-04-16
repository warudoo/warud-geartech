<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $amount = fake()->numberBetween(199000, 4999000);

        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-'.fake()->unique()->numerify('########'),
            'status' => OrderStatus::PENDING_PAYMENT,
            'subtotal' => $amount,
            'total' => $amount,
            'customer_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('08##########'),
            'shipping_address' => fake()->address(),
            'notes' => fake()->boolean(30) ? fake()->sentence() : null,
            'payment_provider' => 'midtrans',
            'payment_payload' => null,
            'paid_at' => null,
            'expired_at' => null,
            'shipped_at' => null,
            'completed_at' => null,
            'cancelled_at' => null,
            'stock_deducted_at' => null,
        ];
    }
}
