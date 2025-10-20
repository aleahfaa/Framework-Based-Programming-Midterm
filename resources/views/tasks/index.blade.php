@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    @if(session('success'))
        <div class="mb-4 p-4 bg-gray-100 border border-gray-200 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">My Tasks</h1>
        <a href="{{ route('tasks.create') }}" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition">
            New Task
        </a>
    </div>

    <!-- Categories Section -->
    <div class="mb-8 p-6 border border-gray-200 rounded">
        <h2 class="text-lg font-semibold mb-4">Categories</h2>
        
        <form action="{{ route('categories.store') }}" method="POST" class="flex gap-2 mb-4">
            @csrf
            <input type="text" name="name" placeholder="Category name" required
                   class="flex-1 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-black">
            <input type="color" name="color" value="#000000"
                   class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
            <button type="submit" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 transition">
                Add
            </button>
        </form>

        <div class="flex flex-wrap gap-2">
            @forelse($categories as $category)
                <div class="flex items-center gap-2 px-3 py-1 border border-gray-300 rounded">
                    <span class="w-3 h-3 rounded-full" style="background-color: {{ $category->color }}"></span>
                    <span class="text-sm">{{ $category->name }}</span>
                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-gray-600">×</button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-gray-500">No categories yet.</p>
            @endforelse
        </div>
    </div>

    <!-- Tasks List -->
    <div class="space-y-3">
        @forelse($tasks as $task)
            <div class="p-4 border border-gray-200 rounded hover:shadow-md transition {{ $task->is_completed ? 'bg-gray-50' : '' }}">
                <div class="flex items-start gap-3">
                    <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="mt-1 w-5 h-5 border-2 border-gray-300 rounded {{ $task->is_completed ? 'bg-black border-black' : '' }} hover:border-gray-400 transition">
                            @if($task->is_completed)
                                <svg class="w-3 h-3 text-white mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            @endif
                        </button>
                    </form>

                    <div class="flex-1">
                        <h3 class="font-medium {{ $task->is_completed ? 'line-through text-gray-500' : '' }}">
                            {{ $task->name }}
                        </h3>

                        @if($task->category)
                            <div class="flex items-center gap-2 mt-1">
                                <span class="w-2 h-2 rounded-full" style="background-color: {{ $task->category->color }}"></span>
                                <span class="text-xs text-gray-600">{{ $task->category->name }}</span>
                            </div>
                        @endif

                        @if($task->details)
                            <p class="text-sm text-gray-600 mt-2">{{ $task->details }}</p>
                        @endif

                        @if($task->subtasks)
                            <div class="mt-2 space-y-1">
                                @foreach($task->subtasks as $subtask)
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <span class="w-3 h-3 border border-gray-300 rounded"></span>
                                        <span>{{ $subtask['text'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if($task->deadline)
                            <p class="text-xs text-gray-500 mt-2">
                                Due: {{ $task->deadline->format('M d, Y') }}
                            </p>
                        @endif
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('tasks.edit', $task) }}" class="text-sm text-gray-600 hover:text-black">Edit</a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-gray-600 hover:text-black" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-500">
                <p class="text-lg">No tasks yet.</p>
                <p class="text-sm mt-2">Create your first task to get started!</p>
            </div>
        @endforelse
    </div>
</div>
@endsection