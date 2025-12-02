<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'icon' => 'required|string|max:255',
            'color' => 'required|string|max:50',
        ]);

        // Set default values
        $validated['is_active'] = true;
        $validated['sort_order'] = 0;

        Category::create($validated);

        return redirect()->route('menus.index')->with('success', 'Category created successfully!');
    }

    /**
     * Show the form for editing the specified category
     */
    public function edit(Category $category)
    {
        // Return JSON for AJAX requests
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json($category);
        }

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'icon' => 'required|string|max:255',
            'color' => 'required|string|max:50',
        ]);

        $category->update($validated);

        return redirect()->route('menus.index')->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category)
    {
        // Check if category has menu items
        if ($category->menuItems()->count() > 0) {
            return redirect()->route('menus.index')->with('error', 'Cannot delete category with existing menu items!');
        }

        $category->delete();

        return redirect()->route('menus.index')->with('success', 'Category deleted successfully!');
    }
}
