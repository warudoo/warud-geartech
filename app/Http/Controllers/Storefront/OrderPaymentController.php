<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MidtransPaymentService;

class OrderPaymentController extends Controller
{
    public function __construct(
        protected MidtransPaymentService $midtransPaymentService,
    ) {
    }

    public function store(Order $order)
    {
        $this->authorize('retryPayment', $order);

        $payment = $this->midtransPaymentService->createOrRefreshTransaction($order->load('items'));

        abort_unless($payment->snap_redirect_url, 500, 'Midtrans did not return a redirect URL.');

        return redirect()->away($payment->snap_redirect_url);
    }
}
