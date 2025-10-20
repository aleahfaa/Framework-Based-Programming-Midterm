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
}