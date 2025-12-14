<?php

namespace App\Http\Controllers;

use App\Models\Bom;
use App\Models\Mo;
use App\Models\Product;
use Illuminate\Http\Request;

class ManufacturingOrderController extends Controller
{
    public function index()
    {
        return view('manufacturing.order.index', [
            'products' => Product::all(),
            'boms'     => Bom::with('product')->get(),
            'orders'   => Mo::with(['product', 'bom'])->latest()->get(),
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'bom_id'     => 'required|exists:bom,id',
            'quantity'   => 'required|numeric|min:1',
        ]);

        // Optional: pastikan BOM sesuai product
        $bom = Bom::where('id', $request->bom_id)
            ->where('product_id', $request->product_id)
            ->first();

        if (! $bom) {
            return back()->withErrors([
                'bom_id' => 'BOM tidak sesuai dengan product'
            ]);
        }

        Mo::create([
            'product_id' => $request->product_id,
            'bom_id'     => $request->bom_id,
            'quantity'   => $request->quantity,
            'status'     => 'draft',
        ]);

        return redirect()
            ->route('mo.index')
            ->with('success', 'Manufacturing Order berhasil dibuat');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:draft,confirmed,in_progress,done,cancelled'
        ]);

        $mo = Mo::findOrFail($id);

        $allowedFlow = [
            'draft' => ['confirmed', 'cancelled'],
            'confirmed' => ['in_progress', 'cancelled'],
            'in_progress' => ['done'],
        ];

        if (
            isset($allowedFlow[$mo->status]) &&
            !in_array($request->status, $allowedFlow[$mo->status])
        ) {
            return back()->with('error', 'Perubahan status tidak valid');
        }

        $mo->status = $request->status;
        $mo->save();

        return back()->with('success', 'Status MO berhasil diperbarui');
    }
}
