@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('categories.index') }}" class="text-sm text-gray-700 hover:text-gray-900">← Back to categories</a>
    </div>

    <h1 class="text-3xl font-bold mb-8">{{ isset($category) ? 'Edit Category' : 'New Category' }}</h1>

    <form action="{{ isset($category) ? route('categories.update', $category) : route('categories.store') }}" method="POST" class="space-y-6">
        @csrf
        @if(isset($category))
            @method('PUT')
        @endif

        <div>
            <label for="name" class="block text-sm font-medium mb-2">Name *</label>
            <input type="text" name="name" id="name" required
                   value="{{ old('name', $category->name ?? '') }}"
                   class="input">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="color" class="block text-sm font-medium mb-2">Color *</label>
            <div class="flex items-center gap-2">
                <input type="color" name="color" id="color"
                       value="{{ old('color', $category->color ?? '#000000') }}"
                       class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
            </div>
            @error('color')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('categories.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary">{{ isset($category) ? 'Update' : 'Create' }}</button>
        </div>
    </form>
</div>
@endsection