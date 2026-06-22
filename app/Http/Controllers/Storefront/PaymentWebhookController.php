<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Services\MidtransPaymentService;
use Illuminate\Http\Request;

class PaymentWebhookController extends Controller
{
    public function __construct(
        protected MidtransPaymentService $midtransPaymentService,
    ) {
    }

    public function __invoke(Request $request)
    {
        $this->midtransPaymentService->handleCallback($request->all());

        return response()->json(['received' => true]);
    }
}
