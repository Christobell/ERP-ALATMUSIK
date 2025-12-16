@extends('layouts.master')

@section('title', 'Edit Purchase Order')
@section('content')
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        
        <div class="card">
            <div class="card-header">
                <h5>Edit Purchase Order: {{ $purchaseOrder->po_number }}</h5>
            </div>
            <div class="card-block">
                <form action="{{ route('purchase-order.update', $purchaseOrder->id) }}" method="POST" id="poForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Nomor PO</label>
                                <div class="col-sm-8">
                                    <input type="text" name="po_number" class="form-control" 
                                           value="{{ $purchaseOrder->po_number }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Tanggal PO</label>
                                <div class="col-sm-8">
                                    <input type="date" name="order_date" class="form-control" 
                                           value="{{ $purchaseOrder->order_date->format('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Vendor</label>
                        <div class="col-sm-10">
                            <input type="text" name="vendor_name" class="form-control" 
                                   value="{{ $purchaseOrder->vendor_name }}" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Kontak Person</label>
                                <div class="col-sm-8">
                                    <input type="text" name="contact_person" class="form-control" 
                                           value="{{ $purchaseOrder->contact_person }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Telepon</label>
                                <div class="col-sm-8">
                                    <input type="text" name="vendor_phone" class="form-control" 
                                           value="{{ $purchaseOrder->vendor_phone }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Alamat Pengiriman</label>
                        <div class="col-sm-10">
                            <textarea name="delivery_address" class="form-control" rows="2" required>{{ $purchaseOrder->delivery_address }}</textarea>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Item/Material</label>
                        <div class="col-sm-10">
                            <div id="items-container">
                                @if($purchaseOrder->items)
                                    @php
                                        $items = json_decode($purchaseOrder->items, true);
                                    @endphp
                                    @foreach($items as $index => $item)
                                    <div class="item-row row mb-2">
                                        <div class="col-md-5">
                                            <select name="items[{{ $index }}][material_id]" class="form-control select-material" required>
                                                <option value="">-- Pilih Material --</option>
                                                @foreach($materials as $material)
                                                    <option value="{{ $material->id }}" 
                                                            data-unit="{{ $material->unit }}"
                                                            data-price="{{ $material->price }}"
                                                            {{ $item['material_id'] == $material->id ? 'selected' : '' }}>
                                                        {{ $material->code }} - {{ $material->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="items[{{ $index }}][quantity]" 
                                                   class="form-control item-quantity" 
                                                   value="{{ $item['quantity'] }}" min="1" required>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" name="items[{{ $index }}][unit]" 
                                                   class="form-control item-unit" 
                                                   value="{{ $item['material_unit'] }}" readonly required>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="items[{{ $index }}][unit_price]" 
                                                   class="form-control item-price" 
                                                   value="{{ $item['unit_price'] }}" min="0" step="0.01" required>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeItem(this)">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
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
                                @endif
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
                                           class="form-control" readonly value="Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}">
                                    <input type="hidden" name="total_amount" id="total_amount" value="{{ $purchaseOrder->total_amount }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Status</label>
                                <div class="col-sm-8">
                                    <select name="status" class="form-control" required>
                                        <option value="draft" {{ $purchaseOrder->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="pending" {{ $purchaseOrder->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ $purchaseOrder->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ $purchaseOrder->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Catatan</label>
                        <div class="col-sm-10">
                            <textarea name="notes" class="form-control" rows="2">{{ $purchaseOrder->notes }}</textarea>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update Purchase Order
                            </button>
                            <a href="{{ route('purchase-order.show', $purchaseOrder->id) }}" class="btn btn-default">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>

<script>
let itemCounter = {{ $purchaseOrder->items ? count(json_decode($purchaseOrder->items, true)) : 1 }};

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
        select.dispatchEvent(new Event('change'));
    });
    
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('item-quantity') || 
            e.target.classList.contains('item-price')) {
            updateTotal();
        }
    });
    
    updateTotal();
});
</script>
@endsection