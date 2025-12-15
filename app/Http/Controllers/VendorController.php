<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Material;
use App\Models\VendorItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    /**
     * Display a listing of the vendors.
     */
    public function index()
    {
        // PERBAIKAN 1: Hapus purchaseOrders_count sementara
        $vendors = Vendor::withCount(['items']) // Hanya items dulu
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // PERBAIKAN 2: Tabel 'material' bukan 'materials'
        $materials = Material::select('id', 'code', 'name', 'price')
            ->orderBy('name')
            ->get();
        
        // Hitung statistik untuk dashboard
        $stats = [
            'totalVendors' => Vendor::count(),
            'activeVendors' => Vendor::where('is_active', true)->count(),
            // 'monthlyPOs' dihapus dulu karena model PurchaseOrder belum siap
        ];
        
        return view('purchase.vendor.index', compact('vendors', 'materials', 'stats'));
    }

    /**
     * Show the form for creating a new vendor.
     */
    public function create()
    {
        return view('vendor.create');
    }

    /**
     * Store a newly created vendor in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:vendors,code',
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string',
            'is_active' => 'sometimes|boolean'
        ]);
        
        // Set default value
        $validated['is_active'] = $request->has('is_active');
        
        try {
            Vendor::create($validated);
            
            return redirect()->route('vendor.index')
                ->with('success', 'Vendor berhasil ditambahkan!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan vendor: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified vendor.
     */
    public function show($id)
    {
        // PERBAIKAN: Hapus relasi purchaseOrders sementara
        $vendor = Vendor::with(['items.material'])
            ->withCount(['items'])
            ->findOrFail($id);
        
        // PERBAIKAN: Hapus $totalPurchase karena PurchaseOrder belum ada
        $materials = Material::select('id', 'code', 'name')
            ->orderBy('name')
            ->get();
        
        return view('purchase.vendor.show', compact('vendor', 'materials'));
    }

    /**
     * Show the form for editing the specified vendor.
     */
    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('purchase.vendor.edit', compact('vendor'));
    }

    /**
     * Update the specified vendor in storage.
     */
    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);
        
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:vendors,code,' . $id,
            'company_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'tax_number' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string',
            'is_active' => 'sometimes|boolean'
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        try {
            $vendor->update($validated);
            
            return redirect()->route('vendor.index')
                ->with('success', 'Data vendor berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui vendor: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified vendor from storage.
     */
    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Hapus vendor items terlebih dahulu
            $vendor->items()->delete();
            
            $vendorName = $vendor->company_name;
            $vendor->delete();
            
            DB::commit();
            
            return redirect()->route('vendor.index')
                ->with('success', 'Vendor ' . $vendorName . ' berhasil dihapus!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('vendor.index')
                ->with('error', 'Gagal menghapus vendor: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status aktif/nonaktif vendor.
     */
    public function toggleStatus($id)
    {
        $vendor = Vendor::findOrFail($id);
        
        $vendor->is_active = !$vendor->is_active;
        $vendor->save();
        
        $status = $vendor->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->route('vendor.index')
            ->with('success', 'Vendor ' . $vendor->company_name . ' berhasil ' . $status . '!');
    }

    /**
     * Add material to vendor (via AJAX modal).
     */
    /**
 * Add material to vendor (via modal di halaman show).
 */
    public function addMaterial(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'material_id' => 'required|exists:material,id',
            'vendor_price' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:20',
            'minimum_order' => 'nullable|integer|min:1',
            'lead_time' => 'nullable|integer|min:0',
        ]);
        
        // 2. Cek apakah material sudah ada di vendor ini
        $exists = \App\Models\VendorItem::where('vendor_id', $request->vendor_id)
            ->where('material_id', $request->material_id)
            ->exists();
        
        if ($exists) {
            return back()->with('error', 'Material ini sudah ada di vendor!');
        }
        
        // 3. Simpan ke database
        \App\Models\VendorItem::create([
            'vendor_id' => $request->vendor_id,
            'material_id' => $request->material_id,
            'vendor_price' => $request->vendor_price,
            'unit' => $request->unit ?? 'pcs',
            'minimum_order' => $request->minimum_order ?? 1,
            'lead_time' => $request->lead_time,
            'notes' => $request->notes,
        ]);
        
        // 4. Redirect kembali
        return back()->with('success', 'Material berhasil ditambahkan!');
    }

    /**
     * Remove material from vendor.
     */
    public function removeMaterial($vendorId, $itemId)
    {
        $item = VendorItem::where('vendor_id', $vendorId)
            ->where('id', $itemId)
            ->firstOrFail();
        
        $item->delete();
        
        return redirect()->back()
            ->with('success', 'Material berhasil dihapus dari vendor!');
    }

    /**
     * Update material price for vendor.
     */
    public function updateMaterialPrice(Request $request, $itemId)
    {
        $item = VendorItem::findOrFail($itemId);
        
        $validated = $request->validate([
            'vendor_price' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:20',
            'minimum_order' => 'nullable|integer|min:1',
            'lead_time' => 'nullable|integer|min:0',
            'notes' => 'nullable|string'
        ]);
        
        $item->update($validated);
        
        return redirect()->back()
            ->with('success', 'Harga material berhasil diperbarui!');
    }

    /**
     * Search vendors by keyword.
     */
    public function search(Request $request)
    {
        $keyword = $request->get('q');
        
        $vendors = Vendor::where('code', 'like', "%{$keyword}%")
            ->orWhere('company_name', 'like', "%{$keyword}%")
            ->orWhere('contact_person', 'like', "%{$keyword}%")
            ->orWhere('email', 'like', "%{$keyword}%")
            ->orWhere('phone', 'like', "%{$keyword}%")
            ->withCount(['items'])
            ->orderBy('company_name')
            ->limit(20)
            ->get();
        
        return response()->json($vendors);
    }

    /**
     * Get vendor details for select2 dropdown.
     */
    public function getVendorsSelect2(Request $request)
    {
        $vendors = Vendor::where('is_active', true)
            ->where(function($query) use ($request) {
                $query->where('code', 'like', "%{$request->q}%")
                    ->orWhere('company_name', 'like', "%{$request->q}%");
            })
            ->select('id', 'code', 'company_name')
            ->orderBy('company_name')
            ->limit(10)
            ->get();
        
        return response()->json([
            'results' => $vendors->map(function($vendor) {
                return [
                    'id' => $vendor->id,
                    'text' => $vendor->code . ' - ' . $vendor->company_name
                ];
            })
        ]);
    }

    /**
     * Export vendors to PDF/Excel.
     */
    public function export($type = 'pdf')
    {
        $vendors = Vendor::withCount(['items'])
            ->orderBy('company_name')
            ->get();
        
        return redirect()->back()
            ->with('info', 'Fitur export akan segera tersedia!');
    }
}