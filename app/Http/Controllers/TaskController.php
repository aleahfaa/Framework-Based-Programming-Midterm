<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index() {
        $tasks = Auth::user()->tasks()
            ->with('category')
            ->orderByRaw('CASE WHEN deadline IS NULL THEN 1 ELSE 0 END')
            ->orderBy('deadline', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $categories = Auth::user()->categories()->latest()->paginate(4);
        return view('tasks.index', compact('tasks', 'categories'));
    }
    public function create() {
        $categories = Auth::user()->categories;
        return view('tasks.form', compact('categories'));
    }
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'deadline' => 'nullable|date',
            'subtasks' => 'nullable|array',
            'subtasks.*' => 'nullable|string',
        ]);
        $subtasks = array_filter($request->subtasks ?? [], fn($item) => !empty($item));
        $subtasks = array_map(fn($item) => ['text' => $item, 'completed' => false], $subtasks);
        Auth::user()->tasks()->create([
            'name' => $validated['name'],
            'details' => $validated['details'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'deadline' => $validated['deadline'] ?? null,
            'subtasks' => !empty($subtasks) ? $subtasks : null,
        ]);
        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }
    public function edit(Task $task){
        $this->authorize('update', $task);
        $categories = Auth::user()->categories;
        return view('tasks.form', compact('task', 'categories'));
    }
    public function update(Request $request, Task $task) {
        $this->authorize('update', $task);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'deadline' => 'nullable|date',
            'subtasks' => 'nullable|array',
            'subtasks.*' => 'nullable|string',
        ]);
        $subtasks = array_filter($request->subtasks ?? [], fn($item) => !empty($item));
        $subtasks = array_map(fn($item) => ['text' => $item, 'completed' => false], $subtasks);
        $task->update([
            'name' => $validated['name'],
            'details' => $validated['details'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'deadline' => $validated['deadline'] ?? null,
            'subtasks' => !empty($subtasks) ? $subtasks : null,
        ]);
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }
    public function destroy(Task $task) {
        $this->authorize('delete', $task);
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully!');
    }
    public function toggleComplete(Task $task){
        $this->authorize('update', $task);
        $task->update(['is_completed' => !$task->is_completed]);
        return back();
    }
}
