<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Material;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $purchaseOrders = PurchaseOrder::latest()->paginate(10);
        $materials = Material::where('is_active', true)->orderBy('name')->get();
        
        return view('purchase.purchase_order.index', compact('purchaseOrders', 'materials'));
    }

    public function create()
    {
        $materials = Material::where('is_active', true)->orderBy('name')->get();
        return view('purchase.purchase_order.create', compact('materials'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'po_number' => 'required|string|max:50|unique:purchase_orders',
            'vendor_name' => 'required|string|max:255',
            'order_date' => 'required|date',
            'contact_person' => 'nullable|string|max:100',
            'vendor_phone' => 'nullable|string|max:20',
            'delivery_address' => 'required|string',
            'status' => 'required|in:draft,pending,approved,rejected',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.material_id' => 'required|exists:material,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $total = 0;
        $itemsWithMaterialData = [];
        
        foreach ($validated['items'] as $item) {
            $material = Material::find($item['material_id']);
            $total += $item['quantity'] * $item['unit_price'];
            $itemsWithMaterialData[] = [
                'material_id' => $material->id,
                'material_code' => $material->code,
                'material_name' => $material->name,
                'material_unit' => $material->unit,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price']
            ];
        }

        PurchaseOrder::create([
            'po_number' => $validated['po_number'],
            'vendor_name' => $validated['vendor_name'],
            'order_date' => $validated['order_date'],
            'contact_person' => $validated['contact_person'],
            'vendor_phone' => $validated['vendor_phone'],
            'delivery_address' => $validated['delivery_address'],
            'total_amount' => $total,
            'status' => $validated['status'],
            'notes' => $validated['notes'],
            'items' => json_encode($itemsWithMaterialData),
        ]);

        return redirect()->route('purchase-order.index')->with('success', 'Purchase order created successfully.');
    }

    public function show(string $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        return view('purchase.purchase_order.show', compact('purchaseOrder'));
    }

    public function edit(string $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $materials = Material::where('is_active', true)->orderBy('name')->get();
        return view('purchase.purchase_order.edit', compact('purchaseOrder', 'materials'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'po_number' => 'required|string|max:50|unique:purchase_orders,po_number,' . $id,
            'vendor_name' => 'required|string|max:255',
            'order_date' => 'required|date',
            'contact_person' => 'nullable|string|max:100',
            'vendor_phone' => 'nullable|string|max:20',
            'delivery_address' => 'required|string',
            'status' => 'required|in:draft,pending,approved,rejected',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.material_id' => 'required|exists:material,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $total = 0;
        $itemsWithMaterialData = [];
        
        foreach ($validated['items'] as $item) {
            $material = Material::find($item['material_id']);
            $total += $item['quantity'] * $item['unit_price'];
            $itemsWithMaterialData[] = [
                'material_id' => $material->id,
                'material_code' => $material->code,
                'material_name' => $material->name,
                'material_unit' => $material->unit,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price']
            ];
        }

        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->update([
            'po_number' => $validated['po_number'],
            'vendor_name' => $validated['vendor_name'],
            'order_date' => $validated['order_date'],
            'contact_person' => $validated['contact_person'],
            'vendor_phone' => $validated['vendor_phone'],
            'delivery_address' => $validated['delivery_address'],
            'total_amount' => $total,
            'status' => $validated['status'],
            'notes' => $validated['notes'],
            'items' => json_encode($itemsWithMaterialData),
        ]);

        return redirect()->route('purchase-order.index')->with('success', 'Purchase order updated successfully.');
    }

    public function destroy(string $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->delete();
        return redirect()->route('purchase-order.index')->with('success', 'Purchase order deleted successfully.');
    }

    public function approve(string $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->update(['status' => 'approved']);
        return redirect()->route('purchase-order.index')->with('success', 'Purchase order approved successfully.');
    }

    public function print(string $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        return view('purchase.purchase_order.print', compact('purchaseOrder'));
    }
}