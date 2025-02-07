<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function product()
    {
        $categories = Category::all();
        return view('product', compact('categories'));
    }

    public function productstore(Request $request)
    {
        // Validate the incoming request data.
        $request->validate([
            'category_id'   => 'nullable|exists:categories,id',
            'product_name'  => 'required|string|max:255',
            'product_image' => 'required|image|max:2048',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric',
        ]);

        
        $data = [
            'category_id'   => $request->input('category_id'),
            'product_name'  => $request->input('product_name'),
            'description'   => $request->input('description'),
            'price'         => $request->input('price'),
        ];
      
        if ($request->hasFile('product_image')) {
            $file = $request->file('product_image');
            $filename = time() . '_' . $file->getClientOriginalName();
          
            $path = $file->storeAs('products', $filename, 'public');
            $data['product_image'] = $path;
        }

        try {
            // Create the product record in the database.
            Product::create($data);
            return redirect()->route('product.view')
                ->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            // Log the error and return back with an error message.
            \Log::error('Error creating product: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'There was an error creating the product.');
        }
    }

    public function productview()
    {
        // Retrieve all products (you may paginate or sort as needed)
        $products = Product::all();
        return view('productview', compact('products'));
    }
    public function productedit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('productedit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Validate incoming data.
        $validated = $request->validate([
            'category_id'   => 'nullable|exists:categories,id',
            'product_name'  => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric',
            'product_image' => 'nullable|image|max:2048', // optional image update
        ]);

        // Process file upload if a new product image is provided.
        if ($request->hasFile('product_image')) {
            $file = $request->file('product_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('products', $filename, 'public');
            $validated['product_image'] = $path;
        }

        $product->update($validated);

        return redirect()->route('product.view')
            ->with('success', 'Product updated successfully.');
    }
    
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('product.view')
            ->with('success', 'Product deleted successfully.');
    }
}
