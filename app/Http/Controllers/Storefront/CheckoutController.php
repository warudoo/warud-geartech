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
    ) {}

    public function show()
    {
        $user = request()->user();
        $ids = request()->input('cart_item_ids');

        if ($ids) {
            $cartItems = $this->cartService->getSelectedItems($user, $ids);
            $subtotal = $this->cartService->subtotalFromItems($cartItems);
        } else {
            $cartItems = $this->cartService->items($user);
            $subtotal = $this->cartService->subtotal($user);
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Add products to the cart before checkout.');
        }

        return view('checkout.show', compact('cartItems', 'subtotal', 'user'));
    }

    public function store(StoreCheckoutRequest $request)
    {
        $order = $this->checkoutService->createPendingOrder(
            $request->user(),
            $request->validated(),
            $request->input('cart_item_ids')
        );

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
