<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable([
    'category_id',
    'name',
    'slug',
    'sku',
    'brand',
    'description',
    'price',
    'stock',
    'featured_image',
    'image_url',
    'is_active',
    'featured',
])]
class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    public const SKU_CATEGORY_PREFIXES = [
        'keyboard' => 'KEY',
        'mouse' => 'MOU',
        'headset' => 'HEA',
        'iem' => 'IEM',
        'mousepad' => 'MPD',
        'monitor' => 'MON',
        'bundles' => 'BND',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'featured' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function (Builder $nested) use ($search) {
            $nested->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%")
                ->orWhere('brand', 'like', "%{$search}%");
        });
    }

    public static function skuPrefixForCategory(?string $categoryName): string
    {
        $normalized = Str::of((string) $categoryName)->trim()->lower()->value();

        return self::SKU_CATEGORY_PREFIXES[$normalized] ?? 'PRD';
    }
}
