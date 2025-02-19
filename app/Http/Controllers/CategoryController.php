<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function category()
    {
        $category = Category::all();
        return view('category', compact('category'));
    }

    // public function viewcategory()
    // {
    //     $category = Category::all();
    //     return view('categoryview', compact('category'));
    // }


    public function categorystore(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required',
        ]);

        Category::create($validated);

        return redirect()->route('admin.category')
            ->with('success', 'Category created successfully.');
    }


    public function categoryedit($id)
    {
        $category = Category::findOrFail($id);
        
        return view('category', compact('category'));
    }



    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);  // Find the category by ID

        // Validate the updated category name
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        // Update the category
        $category->update($validated);

        // Redirect back with a success message
        return redirect()->route('admin.category')->with('success', 'Category updated successfully.');
    }


    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.category')
            ->with('success', 'Category deleted successfully.');
    }
}
