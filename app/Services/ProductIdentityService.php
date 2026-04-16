<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ProductIdentityService
{
    public function generateUniqueSlug(Product $product): string
    {
        $baseSlug = Str::slug((string) $product->name);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'product';

        $slug = $baseSlug;
        $suffix = 2;

        while ($this->slugExists($slug, $product)) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    public function generateUniqueSku(Product $product): string
    {
        $prefix = Product::skuPrefixForCategory($this->resolveCategoryName($product));
        $nextNumber = $this->nextSkuSequence();

        do {
            $sku = sprintf('WGT-%s-%04d', $prefix, $nextNumber);
            $nextNumber++;
        } while ($this->skuExists($sku, $product));

        return $sku;
    }

    protected function resolveCategoryName(Product $product): ?string
    {
        if ($product->relationLoaded('category')) {
            return $product->category?->name;
        }

        return Category::query()
            ->whereKey($product->category_id)
            ->value('name');
    }

    protected function nextSkuSequence(): int
    {
        $maxSequence = Product::query()
            ->pluck('sku')
            ->pipe(fn (Collection $skus) => $skus
                ->map(function (?string $sku): int {
                    if (! is_string($sku) || ! preg_match('/^WGT-[A-Z]{3}-([0-9]{4,})$/', $sku, $matches)) {
                        return 0;
                    }

                    return (int) $matches[1];
                })
                ->max());

        return ((int) $maxSequence) + 1;
    }

    protected function slugExists(string $slug, Product $product): bool
    {
        return Product::query()
            ->where('slug', $slug)
            ->when($product->exists, fn ($query) => $query->whereKeyNot($product->id))
            ->exists();
    }

    protected function skuExists(string $sku, Product $product): bool
    {
        return Product::query()
            ->where('sku', $sku)
            ->when($product->exists, fn ($query) => $query->whereKeyNot($product->id))
            ->exists();
    }
}
