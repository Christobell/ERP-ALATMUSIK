<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Vendor;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['vendor', 'creator'])
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter by vendor
        if ($request->has('vendor_id') && $request->vendor_id) {
            $query->where('vendor_id', $request->vendor_id);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('order_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('order_date', '<=', $request->end_date);
        }

        $purchaseOrders = $query->paginate(10);
        $vendors = Vendor::where('is_active', true)->get();
        
        // Data statistik
        $totalPO = PurchaseOrder::count();
        $pendingCount = PurchaseOrder::where('status', 'pending')->count();
        $approvedCount = PurchaseOrder::where('status', 'approved')->count();
        $completedCount = PurchaseOrder::where('status', 'completed')->count();
        $rejectedCount = PurchaseOrder::where('status', 'rejected')->count();
        $totalValue = PurchaseOrder::sum('grand_total');
        
        return view('purchase.purchase_order.index', compact(
            'purchaseOrders', 
            'vendors',
            'totalPO',
            'pendingCount',
            'approvedCount',
            'completedCount',
            'rejectedCount',
            'totalValue'
        ));
    }

    public function create()
    {
        $vendors = Vendor::where('is_active', true)->get();
        $materials = Material::all();
        
        return view('purchase.purchase_order.create', compact('vendors', 'materials'));
    }

   public function store(Request $request)
{
    \Log::info('=== STORE PO DIPANGGIL ===');
    \Log::info('Action dari button:', ['action' => $request->action]);
    
    // Debug semua input
    \Log::info('Semua request data:', $request->all());
    
    // Validasi
    $request->validate([
        'vendor_id' => 'required|exists:vendors,id',
        'order_date' => 'required|date',
        'delivery_date' => 'nullable|date|after_or_equal:order_date',
        'notes' => 'nullable|string',
        'items' => 'required|array|min:1',
        'items.*.material_id' => 'required|exists:material,id',
        'items.*.quantity' => 'required|numeric|min:0.01',
        'items.*.unit_price' => 'required|numeric|min:0',
        'items.*.description' => 'nullable|string',
    ]);
    
    \Log::info('Validasi berhasil');
    
    try {
        DB::beginTransaction();
        
        // Hitung total
        $subtotal = 0;
        foreach ($request->items as $index => $item) {
            \Log::info("Item $index:", $item);
            $subtotal += $item['quantity'] * $item['unit_price'];
        }
        
        $tax = $subtotal * 0.11;
        $grandTotal = $subtotal + $tax;
        
        // Tentukan status berdasarkan tombol yang ditekan
        $status = ($request->action == 'submit') ? 'pending' : 'draft';
        
        // Generate PO number sederhana
        $poNumber = 'PO-' . date('YmdHis') . rand(100, 999);
        
        // Buat PO
        $purchaseOrder = PurchaseOrder::create([
            'po_number' => $poNumber,
            'vendor_id' => $request->vendor_id,
            'order_date' => $request->order_date,
            'delivery_date' => $request->delivery_date,
            'status' => $status,
            'total_amount' => $subtotal,
            'tax_amount' => $tax,
            'grand_total' => $grandTotal,
            'notes' => $request->notes,
            'created_by' => auth()->id() ?? 1,
        ]);
        
        \Log::info('PO Created - ID: ' . $purchaseOrder->id);
        
        // Buat items
        foreach ($request->items as $item) {
            PurchaseOrderItem::create([
                'purchase_order_id' => $purchaseOrder->id,
                'material_id' => $item['material_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
                'description' => $item['description'] ?? null,
            ]);
        }
        
        DB::commit();
        
        \Log::info('=== PO BERHASIL DISIMPAN ===');
        \Log::info('Redirecting to index...');
        
        // Redirect ke index
        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase Order berhasil dibuat! Status: ' . $status);
            
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('ERROR: ' . $e->getMessage());
        \Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
        
        return back()
            ->withInput()
            ->with('error', 'Gagal: ' . $e->getMessage());
    }
}

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['vendor', 'creator', 'approver', 'items.material']);
        return view('purchase.purchase_order.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        if (!in_array($purchaseOrder->status, ['draft', 'pending'])) {
            return redirect()->route('purchase-orders.show', $purchaseOrder->id)
                ->with('error', 'Purchase Order tidak dapat diedit karena statusnya ' . $purchaseOrder->status);
        }

        $vendors = Vendor::where('is_active', true)->get();
        $materials = Material::all();
        $purchaseOrder->load('items');
        
        return view('purchase.purchase_order.edit', compact('purchaseOrder', 'vendors', 'materials'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        if (!in_array($purchaseOrder->status, ['draft', 'pending'])) {
            return back()->with('error', 'Purchase Order tidak dapat diedit karena statusnya ' . $purchaseOrder->status);
        }

        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date|after_or_equal:order_date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|exists:material,id', // GANTI 'materials' jadi 'material'
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.description' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }
            
            $tax = $subtotal * 0.11;
            $grandTotal = $subtotal + $tax;

            // Update purchase order
            $purchaseOrder->update([
                'vendor_id' => $request->vendor_id,
                'order_date' => $request->order_date,
                'delivery_date' => $request->delivery_date,
                'total_amount' => $subtotal,
                'tax_amount' => $tax,
                'grand_total' => $grandTotal,
                'notes' => $request->notes,
                // Jika ada action submit, update status
                'status' => $request->action == 'submit' ? 'pending' : $purchaseOrder->status,
            ]);

            // Delete old items
            $purchaseOrder->items()->delete();

            // Create new items
            foreach ($request->items as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'material_id' => $item['material_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'], // TAMBAHKAN 'total_price'
                    'description' => $item['description'] ?? null,
                ]);
            }

            DB::commit();

            $message = 'Purchase Order berhasil diperbarui!';
            if ($request->action == 'submit') {
                $message .= ' PO telah disubmit untuk approval.';
            }

            return redirect()->route('purchase-orders.show', $purchaseOrder->id)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if (!in_array($purchaseOrder->status, ['draft', 'pending'])) {
            return back()->with('error', 'Purchase Order tidak dapat dihapus karena statusnya ' . $purchaseOrder->status);
        }

        $purchaseOrder->delete();
        
        return redirect()->route('purchase-orders.index')
            ->with('success', 'Purchase Order berhasil dihapus!');
    }

    // Additional Actions
    public function submit(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status != 'draft') {
            return back()->with('error', 'Hanya PO dengan status draft yang dapat disubmit!');
        }

        $purchaseOrder->update(['status' => 'pending']);
        
        return back()->with('success', 'Purchase Order berhasil disubmit untuk persetujuan!');
    }

    public function approve(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status != 'pending') {
            return back()->with('error', 'Hanya PO dengan status pending yang dapat disetujui!');
        }

        $purchaseOrder->update([
            'status' => 'approved',
            'approved_by' => Auth::id(), // GANTI 1 MENJADI Auth::id()
            'approved_at' => now(),
        ]);
        
        return back()->with('success', 'Purchase Order berhasil disetujui!');
    }

    public function reject(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status != 'pending') {
            return back()->with('error', 'Hanya PO dengan status pending yang dapat ditolak!');
        }

        $purchaseOrder->update(['status' => 'rejected']);
        
        return back()->with('success', 'Purchase Order telah ditolak!');
    }

    public function complete(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status != 'approved') {
            return back()->with('error', 'Hanya PO dengan status approved yang dapat diselesaikan!');
        }

        $purchaseOrder->update(['status' => 'completed']);
        
        return back()->with('success', 'Purchase Order telah diselesaikan!');
    }

    public function cancel(PurchaseOrder $purchaseOrder)
    {
        if (!in_array($purchaseOrder->status, ['draft', 'pending', 'approved'])) {
            return back()->with('error', 'Purchase Order tidak dapat dibatalkan!');
        }

        $purchaseOrder->update(['status' => 'cancelled']);
        
        return back()->with('success', 'Purchase Order telah dibatalkan!');
    }

    public function print(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['vendor', 'creator', 'approver', 'items.material']);
        return view('purchase.purchase_order.print', compact('purchaseOrder'));
    }
}