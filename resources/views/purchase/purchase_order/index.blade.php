@extends('layouts.master')

@section('title', 'Purchase Order')
@section('content')
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        
        <div class="card">
            <div class="card-header">
                <h5>Tambah Purchase Order Baru</h5>
            </div>
            <div class="card-block">
                <form action="{{ route('purchase-order.store') }}" method="POST" id="poForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Nomor PO</label>
                                <div class="col-sm-8">
                                    <input type="text" name="po_number" class="form-control" 
                                           placeholder="Contoh: PO-001" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Tanggal PO</label>
                                <div class="col-sm-8">
                                    <input type="date" name="order_date" class="form-control" 
                                           value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Vendor</label>
                        <div class="col-sm-10">
                            <input type="text" name="vendor_name" class="form-control" 
                                   placeholder="Nama vendor/perusahaan" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Kontak Person</label>
                                <div class="col-sm-8">
                                    <input type="text" name="contact_person" class="form-control" 
                                           placeholder="Nama kontak di vendor">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Telepon</label>
                                <div class="col-sm-8">
                                    <input type="text" name="vendor_phone" class="form-control" 
                                           placeholder="Telepon vendor">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Alamat Pengiriman</label>
                        <div class="col-sm-10">
                            <textarea name="delivery_address" class="form-control" rows="2" 
                                      placeholder="Alamat lengkap pengiriman" required></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Item/Material</label>
                        <div class="col-sm-10">
                            <div id="items-container">
                                <div class="item-row row mb-2">
                                    <div class="col-md-5">
                                        <select name="items[0][material_id]" class="form-control select-material" required>
                                            <option value="">-- Pilih Material --</option>
                                            @foreach($materials as $material)
                                                <option value="{{ $material->id }}" 
                                                        data-unit="{{ $material->unit }}"
                                                        data-price="{{ $material->price }}">
                                                    {{ $material->code }} - {{ $material->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="items[0][quantity]" 
                                               class="form-control item-quantity" 
                                               placeholder="Qty" min="1" value="1" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" name="items[0][unit]" 
                                               class="form-control item-unit" 
                                               placeholder="Satuan" readonly required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="items[0][unit_price]" 
                                               class="form-control item-price" 
                                               placeholder="Harga" min="0" step="0.01" required>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-info mt-2" onclick="addItem()">
                                <i class="fa fa-plus"></i> Tambah Item
                            </button>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Total Amount</label>
                                <div class="col-sm-8">
                                    <input type="text" name="total_amount_display" id="total_amount_display" 
                                           class="form-control" readonly value="Rp 0">
                                    <input type="hidden" name="total_amount" id="total_amount" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Status</label>
                                <div class="col-sm-8">
                                    <select name="status" class="form-control" required>
                                        <option value="draft" selected>Draft</option>
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Catatan</label>
                        <div class="col-sm-10">
                            <textarea name="notes" class="form-control" rows="2" 
                                      placeholder="Catatan tambahan"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Simpan Purchase Order
                            </button>
                            <a href="{{ route('purchase-order.index') }}" class="btn btn-default">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5>Daftar Purchase Order</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        <li><i class="fa fa fa-wrench open-card-option"></i></li>
                        <li><i class="fa fa-window-maximize full-card"></i></li>
                        <li><i class="fa fa-minus minimize-card"></i></li>
                        <li><i class="fa fa-refresh reload-card"></i></li>
                        <li><i class="fa fa-trash close-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block table-border-style">
                <div class="table-responsive">
                    <table class="table table-hover align-middle purchase-order-table">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nomor PO</th>
                                <th>Vendor</th>
                                <th>Tanggal PO</th>
                                <th>Total Amount</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchaseOrders as $index => $order)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-primary">{{ $order->po_number ?? 'PO-' . $order->id }}</span></td>
                                    <td class="fw-semibold">{{ $order->vendor_name }}</td>
                                    <td>{{ $order->order_date->format('d/m/Y') }}</td>
                                    <td><strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                                    <td>
                                        @if($order->items)
                                            @php
                                                $items = json_decode($order->items, true);
                                                $itemCount = count($items);
                                            @endphp
                                            <span class="badge bg-info">{{ $itemCount }} material</span>
                                            <br>
                                            <small>
                                                @foreach(array_slice($items, 0, 2) as $item)
                                                    {{ $item['material_code'] ?? '' }}<br>
                                                @endforeach
                                                @if($itemCount > 2)
                                                    + {{ $itemCount - 2 }} lainnya
                                                @endif
                                            </small>
                                        @else
                                            <span class="badge bg-secondary">0 material</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $order->status == 'approved' ? 'bg-success' : ($order->status == 'pending' ? 'bg-warning' : ($order->status == 'rejected' ? 'bg-danger' : 'bg-secondary')) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('purchase-order.show', $order->id) }}" class="btn btn-outline-info btn-sm" title="Detail PO"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('purchase-order.edit', $order->id) }}" class="btn btn-outline-primary btn-sm" title="Edit PO"><i class="fa fa-edit"></i></a>
                                        <a href="{{ route('purchase-order.print', $order->id) }}" class="btn btn-outline-secondary btn-sm" title="Print PO" target="_blank"><i class="fa fa-print"></i></a>
                                        <form action="{{ route('purchase-order.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus PO {{ $order->po_number }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus PO"><i class="fa fa-trash"></i></button>
                                        </form>
                                        @if($order->status == 'pending')
                                        <form action="{{ route('purchase-order.approve', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm" title="Approve PO"><i class="fa fa-check"></i></button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    @if($purchaseOrders->hasPages())
                    <div class="mt-3">
                        {{ $purchaseOrders->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
    </div>
</div>

<script>
let itemCounter = 1;

function addItem() {
    const container = document.getElementById('items-container');
    const newItem = document.createElement('div');
    newItem.className = 'item-row row mb-2';
    newItem.innerHTML = `
        <div class="col-md-5">
            <select name="items[${itemCounter}][material_id]" class="form-control select-material" required>
                <option value="">-- Pilih Material --</option>
                @foreach($materials as $material)
                    <option value="{{ $material->id }}" 
                            data-unit="{{ $material->unit }}"
                            data-price="{{ $material->price }}">
                        {{ $material->code }} - {{ $material->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="number" name="items[${itemCounter}][quantity]" 
                   class="form-control item-quantity" placeholder="Qty" 
                   min="1" value="1" required>
        </div>
        <div class="col-md-2">
            <input type="text" name="items[${itemCounter}][unit]" 
                   class="form-control item-unit" placeholder="Satuan" 
                   readonly required>
        </div>
        <div class="col-md-2">
            <input type="number" name="items[${itemCounter}][unit_price]" 
                   class="form-control item-price" placeholder="Harga" 
                   min="0" step="0.01" required>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">
                <i class="fa fa-times"></i>
            </button>
        </div>
    `;
    container.appendChild(newItem);
    itemCounter++;
    
    const select = newItem.querySelector('.select-material');
    select.addEventListener('change', handleMaterialSelect);
}

function removeItem(button) {
    button.closest('.item-row').remove();
    updateTotal();
}

function handleMaterialSelect(event) {
    const select = event.target;
    const selectedOption = select.options[select.selectedIndex];
    const row = select.closest('.item-row');
    
    if (selectedOption.value) {
        const unit = selectedOption.getAttribute('data-unit');
        const price = selectedOption.getAttribute('data-price');
        
        row.querySelector('.item-unit').value = unit || 'pcs';
        row.querySelector('.item-price').value = price || 0;
        
        updateTotal();
    }
}

function updateTotal() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        total += quantity * price;
    });
    
    document.getElementById('total_amount').value = total;
    document.getElementById('total_amount_display').value = 'Rp ' + formatNumber(total);
}

function formatNumber(num) {
    return num.toLocaleString('id-ID');
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.select-material').forEach(select => {
        select.addEventListener('change', handleMaterialSelect);
    });
    
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('item-quantity') || 
            e.target.classList.contains('item-price')) {
            updateTotal();
        }
    });
    
    const firstSelect = document.querySelector('.select-material');
    if (firstSelect) {
        firstSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection