<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use RuntimeException;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        /** @var EloquentCollection<int, User> $users */
        $users = User::query()
            ->where('role', UserRole::USER)
            ->get()
            ->keyBy('email');

        /** @var EloquentCollection<int, Product> $products */
        $products = Product::query()
            ->get()
            ->keyBy('name');

        foreach ($this->orders() as $index => $definition) {
            $this->createSeedOrder($definition, $index + 1, $users, $products);
        }
    }

    protected function createSeedOrder(array $definition, int $sequence, Collection $users, Collection $products): void
    {
        /** @var User|null $user */
        $user = $users->get($definition['user_email']);

        if (! $user) {
            throw new RuntimeException("User [{$definition['user_email']}] is not available for order seeding.");
        }

        $placedAt = CarbonImmutable::parse($definition['placed_at']);
        $status = $definition['status'];
        $items = collect($definition['items'])->map(function (array $item) use ($products) {
            /** @var Product|null $product */
            $product = $products->get($item['product']);

            if (! $product) {
                throw new RuntimeException("Product [{$item['product']}] is not available for order seeding.");
            }

            $quantity = (int) $item['quantity'];
            $unitPrice = (int) $product->price;

            return [
                'product' => $product,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $unitPrice * $quantity,
            ];
        });

        $timeline = $this->timelineForStatus($status, $placedAt);
        $subtotal = $items->sum('line_total');

        $order = Order::factory()->create([
            'user_id' => $user->id,
            'order_number' => sprintf('ORD-202604-%04d', $sequence),
            'status' => $status,
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'customer_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'shipping_address' => $user->address,
            'notes' => $definition['notes'] ?? null,
            'payment_provider' => 'midtrans',
            'payment_payload' => [
                'seeded' => true,
                'channel' => $definition['payment_type'],
                'status' => $status->value,
            ],
            'paid_at' => $timeline['paid_at'],
            'expired_at' => $timeline['expired_at'],
            'shipped_at' => $timeline['shipped_at'],
            'completed_at' => $timeline['completed_at'],
            'cancelled_at' => $timeline['cancelled_at'],
            'stock_deducted_at' => $timeline['stock_deducted_at'],
            'created_at' => $placedAt,
            'updated_at' => $timeline['updated_at'],
        ]);

        $items->each(function (array $item) use ($order): void {
            /** @var Product $product */
            $product = $item['product'];

            OrderItem::factory()->create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'sku' => $product->sku,
                'unit_price' => $item['unit_price'],
                'quantity' => $item['quantity'],
                'line_total' => $item['line_total'],
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
            ]);
        });

        Payment::factory()->create([
            'order_id' => $order->id,
            'provider' => 'midtrans',
            'provider_order_id' => $order->order_number,
            'snap_token' => sprintf('snap-seed-%04d', $sequence),
            'snap_redirect_url' => 'https://app.sandbox.midtrans.com/snap/v4/redirection/'.$order->order_number,
            'transaction_id' => sprintf('TX-SEED-%04d', $sequence),
            'transaction_status' => $this->paymentTransactionStatus($status),
            'status_code' => $this->paymentStatusCode($status),
            'payment_type' => $definition['payment_type'],
            'fraud_status' => in_array($status, OrderStatus::paidStates(), true) ? 'accept' : null,
            'va_numbers' => $this->vaNumbersForPaymentType($definition['payment_type'], $sequence),
            'raw_payload' => [
                'seeded' => true,
                'order_id' => $order->order_number,
                'transaction_status' => $this->paymentTransactionStatus($status),
                'payment_type' => $definition['payment_type'],
            ],
            'paid_at' => $timeline['paid_at'],
            'expires_at' => $placedAt->addDay(),
            'last_callback_at' => $this->paymentCallbackAt($timeline),
            'created_at' => $placedAt,
            'updated_at' => $timeline['updated_at'],
        ]);

        if (in_array($status, OrderStatus::paidStates(), true)) {
            $this->deductStock($items, $products);
        }
    }

    protected function deductStock(Collection $items, Collection $products): void
    {
        $items->each(function (array $item) use ($products): void {
            /** @var Product $product */
            $product = $item['product']->fresh();

            if ($product->stock < $item['quantity']) {
                throw new RuntimeException("Insufficient stock while seeding orders for product [{$product->name}].");
            }

            $product->decrement('stock', $item['quantity']);
            $products->put($product->name, $product->fresh());
        });
    }

    protected function timelineForStatus(OrderStatus $status, CarbonImmutable $placedAt): array
    {
        $paidAt = $placedAt->addMinutes(24);
        $shippedAt = $paidAt->addDay();
        $completedAt = $shippedAt->addDays(2);
        $cancelledAt = $placedAt->addHours(4);
        $expiredAt = $placedAt->addDay();

        return match ($status) {
            OrderStatus::PENDING_PAYMENT => [
                'paid_at' => null,
                'expired_at' => null,
                'shipped_at' => null,
                'completed_at' => null,
                'cancelled_at' => null,
                'stock_deducted_at' => null,
                'updated_at' => $placedAt->addMinutes(10),
            ],
            OrderStatus::PAID => [
                'paid_at' => $paidAt,
                'expired_at' => null,
                'shipped_at' => null,
                'completed_at' => null,
                'cancelled_at' => null,
                'stock_deducted_at' => $paidAt,
                'updated_at' => $paidAt,
            ],
            OrderStatus::PROCESSING => [
                'paid_at' => $paidAt,
                'expired_at' => null,
                'shipped_at' => null,
                'completed_at' => null,
                'cancelled_at' => null,
                'stock_deducted_at' => $paidAt,
                'updated_at' => $paidAt->addHours(6),
            ],
            OrderStatus::SHIPPED => [
                'paid_at' => $paidAt,
                'expired_at' => null,
                'shipped_at' => $shippedAt,
                'completed_at' => null,
                'cancelled_at' => null,
                'stock_deducted_at' => $paidAt,
                'updated_at' => $shippedAt,
            ],
            OrderStatus::COMPLETED => [
                'paid_at' => $paidAt,
                'expired_at' => null,
                'shipped_at' => $shippedAt,
                'completed_at' => $completedAt,
                'cancelled_at' => null,
                'stock_deducted_at' => $paidAt,
                'updated_at' => $completedAt,
            ],
            OrderStatus::CANCELLED => [
                'paid_at' => null,
                'expired_at' => null,
                'shipped_at' => null,
                'completed_at' => null,
                'cancelled_at' => $cancelledAt,
                'stock_deducted_at' => null,
                'updated_at' => $cancelledAt,
            ],
            OrderStatus::EXPIRED => [
                'paid_at' => null,
                'expired_at' => $expiredAt,
                'shipped_at' => null,
                'completed_at' => null,
                'cancelled_at' => null,
                'stock_deducted_at' => null,
                'updated_at' => $expiredAt,
            ],
        };
    }

    protected function paymentCallbackAt(array $timeline): ?CarbonImmutable
    {
        return $timeline['paid_at']
            ?? $timeline['cancelled_at']
            ?? $timeline['expired_at'];
    }

    protected function paymentTransactionStatus(OrderStatus $status): string
    {
        return match ($status) {
            OrderStatus::PENDING_PAYMENT => 'pending',
            OrderStatus::PAID,
            OrderStatus::PROCESSING,
            OrderStatus::SHIPPED,
            OrderStatus::COMPLETED => 'settlement',
            OrderStatus::CANCELLED => 'cancel',
            OrderStatus::EXPIRED => 'expire',
        };
    }

    protected function paymentStatusCode(OrderStatus $status): string
    {
        return match ($status) {
            OrderStatus::PENDING_PAYMENT => '201',
            OrderStatus::PAID,
            OrderStatus::PROCESSING,
            OrderStatus::SHIPPED,
            OrderStatus::COMPLETED => '200',
            OrderStatus::CANCELLED => '202',
            OrderStatus::EXPIRED => '407',
        };
    }

    protected function vaNumbersForPaymentType(string $paymentType, int $sequence): ?array
    {
        if ($paymentType !== 'bank_transfer') {
            return null;
        }

        return [[
            'bank' => $sequence % 2 === 0 ? 'bca' : 'bni',
            'va_number' => sprintf('8808%08d', $sequence),
        ]];
    }

    protected function orders(): array
    {
        return [
            [
                'user_email' => 'aditya.pratama@warudgeartech.test',
                'placed_at' => '2026-04-01 09:15:00',
                'status' => OrderStatus::PENDING_PAYMENT,
                'payment_type' => 'bank_transfer',
                'notes' => 'Tolong kirim setelah jam 10 pagi.',
                'items' => [
                    ['product' => 'Razer DeathAdder V3 Wired', 'quantity' => 1],
                    ['product' => 'Fantech Strikepad Pro MPC450', 'quantity' => 1],
                ],
            ],
            [
                'user_email' => 'nabila.putri@warudgeartech.test',
                'placed_at' => '2026-04-02 14:20:00',
                'status' => OrderStatus::PENDING_PAYMENT,
                'payment_type' => 'qris',
                'items' => [
                    ['product' => 'HyperX Cloud III Wireless', 'quantity' => 1],
                ],
            ],
            [
                'user_email' => 'rizky.saputra@warudgeartech.test',
                'placed_at' => '2026-04-03 20:10:00',
                'status' => OrderStatus::PAID,
                'payment_type' => 'gopay',
                'items' => [
                    ['product' => 'Logitech G Pro X Superlight 2', 'quantity' => 1],
                ],
            ],
            [
                'user_email' => 'vania.maharani@warudgeartech.test',
                'placed_at' => '2026-04-04 11:05:00',
                'status' => OrderStatus::PAID,
                'payment_type' => 'bank_transfer',
                'items' => [
                    ['product' => 'HyperX Cloud Earbuds II', 'quantity' => 1],
                    ['product' => 'Warud Geartech Glide Control XXL', 'quantity' => 1],
                ],
            ],
            [
                'user_email' => 'fadhil.hakim@warudgeartech.test',
                'placed_at' => '2026-04-05 16:45:00',
                'status' => OrderStatus::PROCESSING,
                'payment_type' => 'qris',
                'notes' => 'Packing ekstra bubble wrap.',
                'items' => [
                    ['product' => 'Fantech Maxfit81 Frost Wireless', 'quantity' => 1],
                    ['product' => 'Fantech Aria XD7', 'quantity' => 1],
                ],
            ],
            [
                'user_email' => 'keisha.anggraini@warudgeartech.test',
                'placed_at' => '2026-04-06 10:30:00',
                'status' => OrderStatus::PROCESSING,
                'payment_type' => 'credit_card',
                'items' => [
                    ['product' => 'ASUS ROG Strix XG249CM 24.5', 'quantity' => 1],
                ],
            ],
            [
                'user_email' => 'muhammad.rayhan@warudgeartech.test',
                'placed_at' => '2026-04-06 21:12:00',
                'status' => OrderStatus::PROCESSING,
                'payment_type' => 'bank_transfer',
                'items' => [
                    ['product' => 'Logitech G Pro TKL + G304 Wireless Bundle', 'quantity' => 1],
                ],
            ],
            [
                'user_email' => 'salsa.anindita@warudgeartech.test',
                'placed_at' => '2026-04-07 13:08:00',
                'status' => OrderStatus::SHIPPED,
                'payment_type' => 'gopay',
                'items' => [
                    ['product' => 'SteelSeries Arctis Nova 7', 'quantity' => 1],
                ],
            ],
            [
                'user_email' => 'galang.prakoso@warudgeartech.test',
                'placed_at' => '2026-04-08 08:42:00',
                'status' => OrderStatus::SHIPPED,
                'payment_type' => 'qris',
                'items' => [
                    ['product' => 'ASUS ROG Cetra True Wireless SpeedNova', 'quantity' => 1],
                    ['product' => 'Razer Gigantus V2 Medium', 'quantity' => 1],
                ],
            ],
            [
                'user_email' => 'tiara.kusuma@warudgeartech.test',
                'placed_at' => '2026-04-08 19:55:00',
                'status' => OrderStatus::SHIPPED,
                'payment_type' => 'bank_transfer',
                'items' => [
                    ['product' => 'Redragon Ruby GM24X5IPS 24', 'quantity' => 1],
                ],
            ],
            [
                'user_email' => 'aditya.pratama@warudgeartech.test',
                'placed_at' => '2026-04-09 09:10:00',
                'status' => OrderStatus::COMPLETED,
                'payment_type' => 'credit_card',
                'items' => [
                    ['product' => 'Logitech G733 Lightspeed', 'quantity' => 1],
                    ['product' => 'Logitech G640 Cloth', 'quantity' => 1],
                ],
            ],
            [
                'user_email' => 'nabila.putri@warudgeartech.test',
                'placed_at' => '2026-04-10 12:22:00',
                'status' => OrderStatus::COMPLETED,
                'payment_type' => 'bank_transfer',
                'items' => [
                    ['product' => 'SteelSeries Apex 3 + Rival 3 Bundle', 'quantity' => 1],
                ],
            ],
            [
                'user_email' => 'rizky.saputra@warudgeartech.test',
                'placed_at' => '2026-04-10 20:40:00',
                'status' => OrderStatus::COMPLETED,
                'payment_type' => 'gopay',
                'items' => [
                    ['product' => 'Warud Geartech Starter Stream Bundle', 'quantity' => 1],
                ],
            ],
            [
                'user_email' => 'vania.maharani@warudgeartech.test',
                'placed_at' => '2026-04-11 15:18:00',
                'status' => OrderStatus::COMPLETED,
                'payment_type' => 'credit_card',
                'notes' => 'Unit monitor untuk workstation editing dan gaming.',
                'items' => [
                    ['product' => 'Corsair Xeneon 27QHD240 OLED', 'quantity' => 1],
                ],
            ],
            [
                'user_email' => 'fadhil.hakim@warudgeartech.test',
                'placed_at' => '2026-04-12 10:14:00',
                'status' => OrderStatus::CANCELLED,
                'payment_type' => 'qris',
                'items' => [
                    ['product' => 'Razer BlackWidow V4 75%', 'quantity' => 1],
                ],
            ],
            [
                'user_email' => 'keisha.anggraini@warudgeartech.test',
                'placed_at' => '2026-04-12 17:48:00',
                'status' => OrderStatus::CANCELLED,
                'payment_type' => 'bank_transfer',
                'items' => [
                    ['product' => 'Warud Geartech Vision 27Q 180Hz', 'quantity' => 1],
                    ['product' => 'Corsair HS80 Max Wireless', 'quantity' => 1],
                ],
            ],
            [
                'user_email' => 'muhammad.rayhan@warudgeartech.test',
                'placed_at' => '2026-04-13 09:35:00',
                'status' => OrderStatus::EXPIRED,
                'payment_type' => 'bank_transfer',
                'items' => [
                    ['product' => 'Razer Cobra + Gigantus Speed Bundle', 'quantity' => 1],
                ],
            ],
            [
                'user_email' => 'salsa.anindita@warudgeartech.test',
                'placed_at' => '2026-04-13 20:18:00',
                'status' => OrderStatus::EXPIRED,
                'payment_type' => 'gopay',
                'items' => [
                    ['product' => 'Logitech G Pro X TKL Rapid', 'quantity' => 1],
                    ['product' => 'Logitech G Pro X Superlight 2', 'quantity' => 1],
                ],
            ],
            [
                'user_email' => 'galang.prakoso@warudgeartech.test',
                'placed_at' => '2026-04-14 11:50:00',
                'status' => OrderStatus::PAID,
                'payment_type' => 'qris',
                'items' => [
                    ['product' => 'Warud Geartech Arena IEM One', 'quantity' => 2],
                ],
            ],
            [
                'user_email' => 'tiara.kusuma@warudgeartech.test',
                'placed_at' => '2026-04-15 18:05:00',
                'status' => OrderStatus::COMPLETED,
                'payment_type' => 'credit_card',
                'items' => [
                    ['product' => 'ASUS ROG XG27ACS 27', 'quantity' => 1],
                    ['product' => 'ASUS ROG Keris II Ace', 'quantity' => 1],
                ],
            ],
        ];
    }
}
