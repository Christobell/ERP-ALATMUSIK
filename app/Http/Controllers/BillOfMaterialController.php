<?php

namespace App\Http\Controllers;

use App\Models\Bom;
use App\Models\BomItem;
use App\Models\Material;
use App\Models\Product;
use Illuminate\Http\Request;

class BillOfMaterialController extends Controller
{
    public function index(Request $request)
    {
        $bom = null;

        if ($request->product_id) {
            $bom = Bom::with(['bomItem.material', 'product'])
                ->where('product_id', $request->product_id)
                ->first();
        }
        return view('manufacturing.billOfMaterial.index', [
            'products'  => Product::all(),
            'materials' => Material::all(),
            'bom'       => $bom,
            'product_id' => $request->product_id
        ]);
    }

    public function show($id)
    {
        $bom = Bom::with('bomItem.material', 'product')->findOrFail($id);

        return view('manufacturing.billOfMaterial.show', compact('bom'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'  => 'required|exists:product,id',
            'material_id' => 'required|exists:material,id',
            'quantity'    => 'required|numeric|min:0.01',
            'unit'        => 'required|string|max:20',
        ]);

        $bom = Bom::firstOrCreate(
            ['product_id' => $request->product_id],
            ['total_price' => 0, 'status' => 'pending']
        );

        $material = Material::findOrFail($request->material_id);

        $item = BomItem::where('bom_id', $bom->id)
            ->where('material_id', $request->material_id)
            ->first();

        if ($item) {
            $item->quantity += $request->quantity;
            $item->subtotal_price = $item->quantity * $item->unit_price;
            $item->save();
        } else {
            BomItem::create([
                'bom_id'         => $bom->id,
                'material_id'    => $request->material_id,
                'quantity'       => $request->quantity,
                'unit'           => $request->unit,
                'unit_price'     => $material->price,
                'subtotal_price' => $request->quantity * $material->price,
            ]);
        }

        $bom->update([
            'total_price' => $bom->bomItem()->sum('subtotal_price')
        ]);

        return redirect()
            ->route('bom.index', ['product_id' => $bom->product_id])
            ->with('success', 'Material berhasil ditambahkan ke BOM.');
    }



    public function destroy($id)
    {
        $item = BomItem::findOrFail($id);
        $bom = $item->bom;

        $item->delete();

        // Update total price setelah delete
        $bom->total_price = $bom->bomItem()->sum('subtotal_price');
        $bom->save();

        return back()->with('success', 'Material berhasil dihapus dari BOM.');
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        $bom = Bom::findOrFail($id);
        $bom->status = $request->status;
        $bom->save();

        return back()->with('success', 'Status BOM berhasil diperbarui.');
    }
}
