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
        <label for="brand" class="form-label">Brand</label>
        <input id="brand" type="text" name="brand" value="{{ old('brand', $product->brand) }}" class="form-input" required>
    </div>
    <div>
        <label for="name" class="form-label">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name', $product->name) }}" class="form-input" required>
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
<div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_280px]">
    <div>
        <label for="featured_image" class="form-label">Featured Image</label>
        <input id="featured_image" type="file" name="featured_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="form-input file:mr-4 file:rounded-xl file:border-0 file:bg-red-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-red-700 hover:file:bg-red-100">
        <p class="mt-2 text-sm text-slate-500">Upload JPG, JPEG, PNG, atau WEBP. Maksimal 3 MB. Jika Anda mengganti gambar saat edit, file lama akan ditimpa dengan gambar baru.</p>
        @error('featured_image')
            <p class="mt-2 text-sm font-medium text-rose-600">{{ $message }}</p>
        @enderror
    </div>
    <div class="panel-muted overflow-hidden p-4">
        <p class="form-label mb-3">Preview</p>
        <div class="aspect-[4/3] overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <img src="{{ $product->display_image_url }}" alt="{{ $product->name ?: 'Product preview' }}" class="h-full w-full object-cover">
        </div>
        <p class="mt-3 text-sm text-slate-500">
            {{ $product->exists ? 'Gambar utama yang aktif saat ini untuk produk ini.' : 'Produk baru yang belum memiliki gambar akan memakai placeholder aman.' }}
        </p>
    </div>
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
