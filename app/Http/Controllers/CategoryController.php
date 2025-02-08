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

    
    public function categorystore(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required',
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

       
        $validated = $request->validate([
            'category_name' => 'required',
        ]);

       
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
