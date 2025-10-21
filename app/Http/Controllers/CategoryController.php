<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
        ]);
        Auth::user()->categories()->create($validated);
        return back()->with('success', 'Category created successfully!');
    }

    public function destroy(Category $category){
        $this->authorize('delete', $category);
        $category->delete();
        return back()->with('success', 'Category deleted successfully!');
    }

    public function index(){
        $categories = Auth::user()->categories()->latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create(){
        return view('categories.form');
    }

    public function edit(Category $category){
        $this->authorize('update', $category);
        return view('categories.form', compact('category'));
    }

    public function update(Request $request, Category $category){
        $this->authorize('update', $category);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
        ]);
        $category->update($validated);
        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }
}