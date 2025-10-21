@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    @if(session('success'))
        <div class="mb-4 p-4 bg-gray-100 border border-gray-200 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Categories</h1>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">
            New Category
        </a>
    </div>

    <div class="p-6 border border-gray-200 rounded bg-white">
        <div class="space-y-2">
            @forelse($categories as $category)
                <div class="card flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full" style="background-color: {{ $category->color }}"></span>
                        <span class="font-medium text-gray-900">{{ $category->name }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-outline">Edit</a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">No categories yet.</p>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection