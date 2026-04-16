<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="category_id" class="form-label">Category</label>
        <select id="category_id" name="category_id" class="form-select" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="sku" class="form-label">SKU</label>
        <input id="sku" type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="form-input" required>
    </div>
    <div>
        <label for="brand" class="form-label">Brand</label>
        <input id="brand" type="text" name="brand" value="{{ old('brand', $product->brand) }}" class="form-input" required>
    </div>
    <div>
        <label for="name" class="form-label">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name', $product->name) }}" class="form-input" required>
    </div>
    <div>
        <label for="slug" class="form-label">Slug</label>
        <input id="slug" type="text" name="slug" value="{{ old('slug', $product->slug) }}" class="form-input">
    </div>
    <div>
        <label for="price" class="form-label">Price</label>
        <input id="price" type="number" step="0.01" min="0.01" name="price" value="{{ old('price', $product->price) }}" class="form-input" required>
    </div>
    <div>
        <label for="stock" class="form-label">Stock</label>
        <input id="stock" type="number" min="0" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" class="form-input" required>
    </div>
</div>
<div>
    <label for="featured_image" class="form-label">Featured Image</label>
    <input id="featured_image" type="url" name="featured_image" value="{{ old('featured_image', $product->featured_image ?? $product->image_url) }}" class="form-input">
</div>
<div>
    <label for="description" class="form-label">Description</label>
    <textarea id="description" name="description" rows="6" class="form-textarea" required>{{ old('description', $product->description) }}</textarea>
</div>
<div class="flex flex-wrap gap-6">
    <label class="flex items-center gap-3 text-sm text-slate-600">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->exists ? $product->is_active : true)) class="h-4 w-4 rounded border-slate-300 bg-white text-red-600">
        Active
    </label>
    <label class="flex items-center gap-3 text-sm text-slate-600">
        <input type="checkbox" name="featured" value="1" @checked(old('featured', $product->featured)) class="h-4 w-4 rounded border-slate-300 bg-white text-red-600">
        Featured
    </label>
</div>
