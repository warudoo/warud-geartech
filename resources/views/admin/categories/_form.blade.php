<div class="space-y-5">
    <div>
        <label for="name" class="form-label">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name', $category->name) }}" class="form-input" required>
    </div>
    <div>
        <label for="slug" class="form-label">Slug</label>
        <input id="slug" type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="form-input">
    </div>
    <div>
        <label for="description" class="form-label">Description</label>
        <textarea id="description" name="description" rows="4" class="form-textarea">{{ old('description', $category->description) }}</textarea>
    </div>
    <label class="flex items-center gap-3 text-sm text-slate-600">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->exists ? $category->is_active : true)) class="h-4 w-4 rounded border-slate-300 bg-white text-red-600">
        Active category
    </label>
</div>
