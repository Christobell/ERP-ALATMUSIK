<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::all(); // ganti jadi $materials (plural)
        return view('manufacturing.material.index', compact('materials'));
    }

    public function show($id)
    {
        $material = Material::findOrFail($id);
        return view('manufacturing.material.update', compact('material'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:material,code',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'code', 'price', 'stock']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('materials', 'public');
        }

        Material::create($data);

        return redirect()->route('material.index')->with('success', 'Material dibuat.');
    }

    public function update(Request $request, $id)
    {
        
        $material = Material::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:material,code,' . $material->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable',
        ]);
        

        $data = $request->only(['name', 'code', 'price', 'stock']);

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($material->image && Storage::exists('public/' . $material->image)) {
                Storage::delete('public/' . $material->image);
            }
            
            // Simpan gambar baru
            $data['image'] = $request->file('image')->store('materials', 'public');
        }

        $material->update($data);

        return redirect()->route('material.index')->with('success', 'Material diperbarui.');
    }

    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        
        // Hapus gambar jika ada
        if ($material->image && Storage::exists('public/' . $material->image)) {
            Storage::delete('public/' . $material->image);
        }
        
        $material->delete();
        
        return redirect()->route('material.index')->with('success', 'Material dihapus.');
    }
}