@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('tasks.index') }}" class="text-sm text-gray-600 hover:text-black">← Back to tasks</a>
    </div>

    <h1 class="text-3xl font-bold mb-8">{{ isset($task) ? 'Edit Task' : 'New Task' }}</h1>

    <form action="{{ isset($task) ? route('tasks.update', $task) : route('tasks.store') }}" method="POST" class="space-y-6">
        @csrf
        @if(isset($task))
            @method('PUT')
        @endif

        <div>
            <label for="name" class="block text-sm font-medium mb-2">Task Name *</label>
            <input type="text" name="name" id="name" required
                   value="{{ old('name', $task->name ?? '') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-black">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="category_id" class="block text-sm font-medium mb-2">Category</label>
            <select name="category_id" id="category_id"
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-black">
                <option value="">No category</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $task->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="deadline" class="block text-sm font-medium mb-2">Deadline</label>
            <input type="date" name="deadline" id="deadline"
                   value="{{ old('deadline', isset($task) && $task->deadline ? $task->deadline->format('Y-m-d') : '') }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-black">
        </div>

        <div>
            <label for="details" class="block text-sm font-medium mb-2">Details</label>
            <textarea name="details" id="details" rows="4"
                      class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-black">{{ old('details', $task->details ?? '') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">Subtasks</label>
            <div id="subtasks-container" class="space-y-2">
                @if(isset($task) && $task->subtasks)
                    @foreach($task->subtasks as $index => $subtask)
                        <div class="flex gap-2">
                            <input type="text" name="subtasks[]" value="{{ $subtask['text'] }}"
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-black"
                                   placeholder="Subtask {{ $index + 1 }}">
                            <button type="button" onclick="this.parentElement.remove()"
                                    class="px-3 py-2 text-gray-600 hover:text-black">×</button>
                        </div>
                    @endforeach
                @endif
            </div>
            <button type="button" onclick="addSubtask()"
                    class="mt-2 text-sm text-gray-600 hover:text-black">+ Add subtask</button>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit"
                    class="px-6 py-2 bg-black text-white rounded hover:bg-gray-800 transition">
                {{ isset($task) ? 'Update Task' : 'Create Task' }}
            </button>
            <a href="{{ route('tasks.index') }}"
               class="px-6 py-2 border border-gray-300 rounded hover:bg-gray-50 transition">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
let subtaskCount = {{ isset($task) && $task->subtasks ? count($task->subtasks) : 0 }};

function addSubtask() {
    subtaskCount++;
    const container = document.getElementById('subtasks-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" name="subtasks[]"
               class="flex-1 px-4 py-2 border border-gray-300 rounded focus:outline-none focus:border-black"
               placeholder="Subtask ${subtaskCount}">
        <button type="button" onclick="this.parentElement.remove()"
                class="px-3 py-2 text-gray-600 hover:text-black">×</button>
    `;
    container.appendChild(div);
}
</script>
@endsection