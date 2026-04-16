<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storefront\StoreCheckoutRequest;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\MidtransPaymentService;
use Throwable;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected CheckoutService $checkoutService,
        protected MidtransPaymentService $midtransPaymentService,
    ) {
    }

    public function show()
    {
        $user = request()->user();
        $cartItems = $this->cartService->items($user);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Add products to the cart before checkout.');
        }

        return view('checkout.show', [
            'cartItems' => $cartItems,
            'subtotal' => $this->cartService->subtotal($user),
            'user' => $user,
        ]);
    }

    public function store(StoreCheckoutRequest $request)
    {
        $order = $this->checkoutService->createPendingOrder($request->user(), $request->validated());

        try {
            $payment = $this->midtransPaymentService->createOrRefreshTransaction($order->load('items'));

            if ($payment->snap_redirect_url) {
                return redirect()->away($payment->snap_redirect_url);
            }
        } catch (Throwable $exception) {
            return redirect()
                ->route('orders.show', $order->order_number)
                ->with('error', 'Order created, but Midtrans payment initialization failed. Configure the gateway and retry payment from the order detail page.');
        }

        return redirect()->route('orders.show', $order->order_number);
    }
}
