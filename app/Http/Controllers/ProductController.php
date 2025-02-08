<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;


class ProductController extends Controller
{
    public function product()
    {
        $categories = Category::all();
        return view('product', compact('categories'));
    }

    public function productstore(Request $request)
    {

        $request->validate([
            'category_id'   => 'required',
            'product_name'  => 'required',
            'product_image' => 'required',
            'description'   => 'required',
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

            $product = Product::create($data);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $imageFile) {
                    $filename = time() . '_' . $imageFile->getClientOriginalName();
                    $path = $imageFile->storeAs('product_images', $filename, 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image'      => $path,
                    ]);
                }
            }
            return redirect()->route('product.view')
                ->with('success', 'Product created successfully.');
        } catch (\Exception $e) {

            \Log::error('Error creating product: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'There was an error creating the product.');
        }
    }

    public function productview()
    {
        $products = Product::all();
        return view('productview', compact('products'));
    }
    public function productedit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('productedit', compact('product', 'categories'));
    }

    // public function update(Request $request, $id)
    // {
    //     $product = Product::findOrFail($id);


    //     $validated = $request->validate([
    //         'category_id'   => 'required',
    //         'product_name'  => 'required',
    //         'description'   => 'required',
    //         'price'         => 'required|numeric',

    //     ]);


    //     if ($request->hasFile('product_image')) {
    //         $file = $request->file('product_image');
    //         $file = $request->file('product_image');
    //         $filename = time() . '_' . $file->getClientOriginalName();
    //         $path = $file->storeAs('products', $filename, 'public');
    //         $validated['product_image'] = $path;
    //     }

    //     foreach ($request->file('images') as $imageFile) {
    //         $filename = time() . '_' . $imageFile->getClientOriginalName();
    //         $path = $imageFile->storeAs('product_images', $filename, 'public');
    //         // Create a new record in the product_images table.
    //         ProductImage::create([
    //             'product_id' => $product->id,
    //             'image'      => $path,
    //         ]);
    //     }
    //     if ($request->hasFile('images')) {

    //         foreach ($product->images as $oldImage) {
    //             \Storage::disk('public')->delete($oldImage->image);
    //             $oldImage->delete();
    //         }

    //         foreach ($request->file('images') as $imageFile) {
    //             $filename = time() . '_' . $imageFile->getClientOriginalName();
    //             $path = $imageFile->storeAs('product_images', $filename, 'public');
    //             ProductImage::create([
    //                 'product_id' => $product->id,
    //                 'image'      => $path,
    //             ]);
    //         }
    //     }


    //     $product->update($validated);

    //     return redirect()->route('product.view')
    //         ->with('success', 'Product updated successfully.');
    // }
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        
        $validated = $request->validate([
            'category_id'   => 'required',
            'product_name'  => 'required',
            'description'   => 'required',
            'price'         => 'required|numeric',
            // For update, product_image can be optional.
            'product_image' => 'nullable|image|max:2048',
            // Additional images validation (each file)
            'images.*'      => 'nullable|image|max:2048',
        ]);

       
        if ($request->hasFile('product_image')) {
            $file = $request->file('product_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('products', $filename, 'public');
            $validated['product_image'] = $path;

            // Optionally, delete the old primary image:
            // \Storage::disk('public')->delete($product->product_image);
        }

      
        $product->update($validated);

       
        if ($request->hasFile('images')) {
            $newImages = $request->file('images'); // array of new image files
            $existingImages = $product->images;      // Collection of existing ProductImage models

            // Loop over existing images and update them with new files (by order)
            foreach ($existingImages as $index => $oldImage) {
                if (isset($newImages[$index])) {
                    $imageFile = $newImages[$index];
                    $filename = time() . '_' . $imageFile->getClientOriginalName();
                    $path = $imageFile->storeAs('product_images', $filename, 'public');

                    // Delete old file from storage.
                    \Storage::disk('public')->delete($oldImage->image);

                    // Update existing record with new path.
                    $oldImage->update(['image' => $path]);
                } else {
                    // If there are more existing images than new ones, delete the extra old images.
                    \Storage::disk('public')->delete($oldImage->image);
                    $oldImage->delete();
                }
            }

            // If there are more new images than existing ones, create new records.
            if (count($newImages) > $existingImages->count()) {
                for ($i = $existingImages->count(); $i < count($newImages); $i++) {
                    $imageFile = $newImages[$i];
                    $filename = time() . '_' . $imageFile->getClientOriginalName();
                    $path = $imageFile->storeAs('product_images', $filename, 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image'      => $path,
                    ]);
                }
            }
        }

        return redirect()->route('product.view')
            ->with('success', 'Product updated successfully.');
    }

    public function productimagedestroy($id)
    {
        $image = ProductImage::findOrFail($id);

        // Delete the file from storage.
        \Storage::disk('public')->delete($image->image);

        // Delete the database record.
        $image->delete();

        return redirect()->back()->with('success', 'Image deleted successfully.');
    }

   
    public function productviewimage($id)
    {
        // Eager load related images and category.
        $product = Product::with(['images', 'category'])->findOrFail($id);
        return view('produtmoreimage', compact('product'));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('product.view')
            ->with('success', 'Product deleted successfully.');
    }
}
