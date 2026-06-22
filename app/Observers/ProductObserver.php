<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\ProductIdentityService;

class ProductObserver
{
    public function __construct(
        protected ProductIdentityService $identityService,
    ) {
    }

    public function creating(Product $product): void
    {
        if (blank($product->slug)) {
            $product->slug = $this->identityService->generateUniqueSlug($product);
        }

        if (blank($product->sku)) {
            $product->sku = $this->identityService->generateUniqueSku($product);
        }
    }

    public function updating(Product $product): void
    {
        if ($product->isDirty('name')) {
            $product->slug = $this->identityService->generateUniqueSlug($product);
        }
    }
}
