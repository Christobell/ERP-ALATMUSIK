<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index()
    {
        $material = Material::all();
        return view('manufacturing.material.index', compact('material'));
    }

    public function show($id)
    {
        $material = Material::find($id);
        return view('manufacturing.material.update', compact('material'));
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
            $imagePath = $image->store('materials', 'public');
        }

        Material::create([
            'name' => $request->name,
            'code' => $request->code,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath
        ]);

        return redirect()->route('material.index')->with('success', 'Material created successfully.');
    }

    public function update(Request $request, $id)
    {
        $material = Material::find($id);


        $request->validate([
            'name' => 'required',
            'code' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

        ]);
        // dd($request->all());


        $imagePath = $material->image;
        if ($request->hasFile('image')) {

            if ($material->image && Storage::disk('public')->exists($material->image)) {
                Storage::disk('public')->delete($material->image);
            }

            $imagePath = $request->file('image')->store('materials', 'public');
            $material->image = $imagePath;
        }

        $material->update([
            'name' => $request->name,
            'code' => $request->code,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $imagePath
        ]);

        return redirect()->route('material.index')->with('success', 'Material updated successfully.');
    }

    public function destroy($id)
    {
        $material = Material::find($id);
        $material->delete();
        return redirect()->route('material.index')->with('success', 'Material deleted successfully.');
    }
}
