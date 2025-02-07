<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function category()
    {

        return view('category');
    }

    public function viewcategory()
    {
        $category = Category::all();
        return view('categoryview', compact('category'));
    }

    // Show the form for creating a new category.

    // Store a newly created category in storage.
    public function categorystore(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        Category::create($validated);

        return redirect()->route('category.view')
            ->with('success', 'Category created successfully.');
    }
    public function categoryedit($id)
    {
        $category = Category::findOrFail($id);
        return view('categoryedit', compact('category'));
    }
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        // Validate the input; your database column is 'category_name'
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        // Update the category record.
        $category->update($validated);

        return redirect()->route('category.view')
            ->with('success', 'Category updated successfully.');
    }
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('category.view')
            ->with('success', 'Category deleted successfully.');
    }
}
