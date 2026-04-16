<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\ToggleProductStatusRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Http\Requests\Admin\UpdateProductStockRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Product::class);

        return view('admin.products.index', [
            'products' => Product::query()
                ->with('category')
                ->search($request->string('search')->value())
                ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->integer('category')))
                ->latest()
                ->paginate(12)
                ->withQueryString(),
            'categories' => Category::query()->active()->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        $this->authorize('create', Product::class);

        return view('admin.products.create', [
            'product' => new Product(),
            'categories' => Category::query()->active()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $this->authorize('create', Product::class);

        $attributes = $request->validated();
        $attributes['image_url'] = $attributes['featured_image'] ?? null;

        Product::query()->create($attributes);

        return redirect()->route('admin.products.index')->with('status', 'Product created.');
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::query()->active()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $attributes = $request->validated();
        $attributes['image_url'] = $attributes['featured_image'] ?? null;

        $product->update($attributes);

        return redirect()->route('admin.products.index')->with('status', 'Product updated.');
    }

    public function updateStock(UpdateProductStockRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $product->update([
            'stock' => $request->integer('stock'),
        ]);

        return redirect()->route('admin.products.index', request()->query())->with('status', 'Product stock updated.');
    }

    public function toggleStatus(ToggleProductStatusRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $product->update([
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.products.index', request()->query())->with('status', 'Product status updated.');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        if ($product->orderItems()->exists()) {
            $product->update([
                'is_active' => false,
            ]);

            return redirect()
                ->route('admin.products.index', request()->query())
                ->with('status', 'Product has past orders, so it was deactivated instead of deleted.');
        }

        $product->delete();

        return redirect()->route('admin.products.index', request()->query())->with('status', 'Product deleted.');
    }
}
