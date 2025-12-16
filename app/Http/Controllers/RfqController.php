<?php

namespace App\Http\Controllers;

use App\Models\Rfq;
use App\Models\Material;
use App\Models\User;
use Illuminate\Http\Request;

class RfqController extends Controller
{
    public function index()
    {
        $rfqs = Rfq::latest()->paginate(10);
        return view('purchase.rfq.index', compact('rfqs'));
    }

    // Di RfqController create() method, ganti users dengan array manual
public function create()
{
    $materials = Material::where('is_active', true)->orderBy('name')->get();
    
    // Coba ambil dari database
    $dbUsers = User::orderBy('name')->get();
    
    // Jika tidak ada data, buat array manual
    if ($dbUsers->isEmpty()) {
        $users = [
            (object) ['id' => 1, 'name' => 'Admin System', 'email' => 'admin@company.com'],
            (object) ['id' => 2, 'name' => 'Purchasing Manager', 'email' => 'purchasing@company.com'],
            (object) ['id' => 3, 'name' => 'Production Head', 'email' => 'production@company.com'],
            (object) ['id' => 4, 'name' => 'Maintenance Supervisor', 'email' => 'maintenance@company.com'],
        ];
    } else {
        $users = $dbUsers;
    }
    
    $departments = [
        (object) ['id' => 1, 'name' => 'Purchasing'],
        (object) ['id' => 2, 'name' => 'Production'],
        (object) ['id' => 3, 'name' => 'Maintenance'],
        (object) ['id' => 4, 'name' => 'Engineering'],
        (object) ['id' => 5, 'name' => 'Finance'],
        (object) ['id' => 6, 'name' => 'HRD'],
        (object) ['id' => 7, 'name' => 'IT'],
        (object) ['id' => 8, 'name' => 'Warehouse'],
    ];
    
    return view('purchase.rfq.create', compact('materials', 'users', 'departments'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rfq_number' => 'required|string|max:50|unique:rfqs',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'request_date' => 'required|date',
            'deadline_date' => 'required|date|after_or_equal:request_date',
            'requested_by' => 'required|exists:users,id',
            'department_id' => 'required',
            'estimated_budget' => 'required|numeric|min:0',
            'status' => 'required|in:draft,pending',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.material_id' => 'required|exists:material,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.description' => 'nullable|string',
            'items.*.specifications' => 'nullable|string',
        ]);

        $itemsWithMaterialData = [];
        foreach ($validated['items'] as $item) {
            $material = Material::find($item['material_id']);
            $itemsWithMaterialData[] = [
                'material_id' => $material->id,
                'material_code' => $material->code,
                'material_name' => $material->name,
                'material_unit' => $material->unit,
                'quantity' => $item['quantity'],
                'description' => $item['description'] ?? $material->name,
                'specifications' => $item['specifications'] ?? '',
                'estimated_price' => $material->price
            ];
        }

        Rfq::create([
            'rfq_number' => $validated['rfq_number'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'request_date' => $validated['request_date'],
            'deadline_date' => $validated['deadline_date'],
            'requested_by' => $validated['requested_by'],
            'department_id' => $validated['department_id'],
            'estimated_budget' => $validated['estimated_budget'],
            'status' => $validated['status'],
            'notes' => $validated['notes'],
            'items' => json_encode($itemsWithMaterialData),
        ]);

        return redirect()->route('rfq.index')->with('success', 'RFQ created successfully.');
    }

    public function show(string $id)
    {
        $rfq = Rfq::findOrFail($id);
        return view('purchase.rfq.show', compact('rfq'));
    }

    public function edit(string $id)
    {
        $rfq = Rfq::findOrFail($id);
        $materials = Material::where('is_active', true)->orderBy('name')->get();
        $users = User::orderBy('name')->get(); // TANPA is_active
        
        $departments = [
            (object) ['id' => 1, 'name' => 'Purchasing'],
            (object) ['id' => 2, 'name' => 'Production'],
            (object) ['id' => 3, 'name' => 'Maintenance'],
            (object) ['id' => 4, 'name' => 'Engineering'],
            (object) ['id' => 5, 'name' => 'Finance'],
            (object) ['id' => 6, 'name' => 'HRD'],
            (object) ['id' => 7, 'name' => 'IT'],
            (object) ['id' => 8, 'name' => 'Warehouse'],
        ];
        
        return view('purchase.rfq.edit', compact('rfq', 'materials', 'users', 'departments'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'rfq_number' => 'required|string|max:50|unique:rfqs,rfq_number,' . $id,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'request_date' => 'required|date',
            'deadline_date' => 'required|date|after_or_equal:request_date',
            'requested_by' => 'required|exists:users,id',
            'department_id' => 'required',
            'estimated_budget' => 'required|numeric|min:0',
            'status' => 'required|in:draft,pending,quotation_received,evaluating,approved,rejected,cancelled',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.material_id' => 'required|exists:material,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.description' => 'nullable|string',
            'items.*.specifications' => 'nullable|string',
        ]);

        $itemsWithMaterialData = [];
        foreach ($validated['items'] as $item) {
            $material = Material::find($item['material_id']);
            $itemsWithMaterialData[] = [
                'material_id' => $material->id,
                'material_code' => $material->code,
                'material_name' => $material->name,
                'material_unit' => $material->unit,
                'quantity' => $item['quantity'],
                'description' => $item['description'] ?? $material->name,
                'specifications' => $item['specifications'] ?? '',
                'estimated_price' => $material->price
            ];
        }

        $rfq = Rfq::findOrFail($id);
        $rfq->update([
            'rfq_number' => $validated['rfq_number'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'request_date' => $validated['request_date'],
            'deadline_date' => $validated['deadline_date'],
            'requested_by' => $validated['requested_by'],
            'department_id' => $validated['department_id'],
            'estimated_budget' => $validated['estimated_budget'],
            'status' => $validated['status'],
            'notes' => $validated['notes'],
            'items' => json_encode($itemsWithMaterialData),
        ]);

        return redirect()->route('rfq.index')->with('success', 'RFQ updated successfully.');
    }

    public function destroy(string $id)
    {
        $rfq = Rfq::findOrFail($id);
        $rfq->delete();
        
        return redirect()->route('rfq.index')->with('success', 'RFQ deleted successfully.');
    }

    public function sendToVendor(string $id)
    {
        $rfq = Rfq::findOrFail($id);
        $rfq->update(['status' => 'pending']);
        
        return redirect()->route('rfq.show', $id)->with('success', 'RFQ sent to vendors.');
    }

    public function approve(string $id)
    {
        $rfq = Rfq::findOrFail($id);
        $rfq->update(['status' => 'approved']);
        
        return redirect()->route('rfq.show', $id)->with('success', 'RFQ approved.');
    }

    public function print(string $id)
    {
        $rfq = Rfq::findOrFail($id);
        return view('purchase.rfq.print', compact('rfq'));
    }
}