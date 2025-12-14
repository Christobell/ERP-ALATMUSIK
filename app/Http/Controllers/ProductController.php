<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('manufacturing.product.index', compact('products'));
    }

    public function show($id)
    {
        $products = Product::find($id);
        return view('manufacturing.product.update', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'image' => 'required',
        ]);
        // dd($request->all());

        $imagePath = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('products', 'public');
        }
        Product::create([
            'name' => $request->name,
            'code' => $request->code,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath
        ]);

        return redirect()->route('product.index')->with('success', 'Product created successfully');
    }

    public function update(Request $request, $id)
    {
        $products = Product::find($id);

        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        $imagePath = $products->image;
        if ($request->hasFile('image')) {

            if ($products->image && Storage::disk('public')->exists($products->image)) {
                Storage::disk('public')->delete($products->image);
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $products->image = $imagePath;
        }
        $products->update([
            'name' => $request->name,
            'code' => $request->code,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath
        ]);
        return redirect()->route('product.index')->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        $products = Product::find($id);
        if ($products->image && Storage::disk('public')->exists($products->image)) {
            Storage::disk('public')->delete($products->image);
        }
        $products->delete();
        return redirect()->route('product.index')->with('success', 'Product deleted successfully');
    }
}
