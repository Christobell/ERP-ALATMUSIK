@extends('layouts.master')

@section('title', 'Edit Purchase Order: ' . $purchaseOrder->po_number)
@section('content')
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        
        <div class="card">
            <div class="card-header">
                <h5>
                    <i class="fa fa-edit text-primary"></i> 
                    Edit Purchase Order: {{ $purchaseOrder->po_number }}
                </h5>
                <div class="card-header-right">
                    <a href="{{ route('purchase-orders.show', $purchaseOrder->id) }}" 
                       class="btn btn-secondary btn-sm">
                        <i class="fa fa-eye"></i> Lihat Detail
                    </a>
                    <a href="{{ route('purchase-orders.index') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            
            @if(!in_array($purchaseOrder->status, ['draft', 'pending']))
                <div class="alert alert-warning m-3">
                    <i class="fa fa-exclamation-triangle"></i> 
                    <strong>Perhatian!</strong> Purchase Order dengan status 
                    <span class="badge bg-{{ $purchaseOrder->status_color }}">
                        {{ ucfirst($purchaseOrder->status) }}
                    </span> 
                    tidak dapat diedit. Hanya PO dengan status <strong>Draft</strong> atau <strong>Pending</strong> yang dapat diedit.
                </div>
            @endif
            
            <form action="{{ route('purchase-orders.update', $purchaseOrder->id) }}" method="POST" id="poForm" 
                  @if(!in_array($purchaseOrder->status, ['draft', 'pending'])) onsubmit="return false;" @endif>
                @csrf
                @method('PUT')
                
                <div class="card-block">
                    {{-- INFO HEADER --}}
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> 
                        No. PO: <strong>{{ $purchaseOrder->po_number }}</strong> | 
                        Status: <span class="badge bg-{{ $purchaseOrder->status_color }}">
                            {{ ucfirst($purchaseOrder->status) }}
                        </span> | 
                        Dibuat oleh: {{ $purchaseOrder->creator->name ?? '-' }} pada 
                        {{ $purchaseOrder->created_at->format('d/m/Y H:i') }}
                    </div>
                    
                    {{-- SECTION 1: INFORMASI UMUM --}}
                    <div class="card-subheader mb-3">
                        <h6><i class="fa fa-info-circle text-primary"></i> Informasi Umum</h6>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pilih Vendor <span class="text-danger">*</span></label>
                                <select name="vendor_id" class="form-control select2" required
                                        @if(!in_array($purchaseOrder->status, ['draft', 'pending'])) disabled @endif>
                                    <option value="">-- Pilih Vendor --</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" 
                                                {{ old('vendor_id', $purchaseOrder->vendor_id) == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->code }} - {{ $vendor->company_name }}
                                            @if($vendor->contact_person)
                                                ({{ $vendor->contact_person }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @if(!in_array($purchaseOrder->status, ['draft', 'pending']))
                                    <input type="hidden" name="vendor_id" value="{{ $purchaseOrder->vendor_id }}">
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal PO <span class="text-danger">*</span></label>
                                <input type="date" name="order_date" class="form-control" 
                                       value="{{ old('order_date', $purchaseOrder->order_date->format('Y-m-d')) }}" required
                                       @if(!in_array($purchaseOrder->status, ['draft', 'pending'])) disabled @endif>
                                @if(!in_array($purchaseOrder->status, ['draft', 'pending']))
                                    <input type="hidden" name="order_date" value="{{ $purchaseOrder->order_date->format('Y-m-d') }}">
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Delivery</label>
                                <input type="date" name="delivery_date" class="form-control" 
                                       value="{{ old('delivery_date', $purchaseOrder->delivery_date ? $purchaseOrder->delivery_date->format('Y-m-d') : '') }}"
                                       @if(!in_array($purchaseOrder->status, ['draft', 'pending'])) disabled @endif>
                                <small class="text-muted">Opsional</small>
                                @if(!in_array($purchaseOrder->status, ['draft', 'pending']) && $purchaseOrder->delivery_date)
                                    <input type="hidden" name="delivery_date" value="{{ $purchaseOrder->delivery_date->format('Y-m-d') }}">
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    {{-- SECTION 2: ITEMS --}}
                    <div class="card-subheader mb-3 mt-4">
                        <h6>
                            <i class="fa fa-boxes text-primary"></i> Item Purchase Order 
                            <span class="badge bg-info">{{ $purchaseOrder->items->count() }} item</span>
                        </h6>
                    </div>
                    
                    @if(!in_array($purchaseOrder->status, ['draft', 'pending']))
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> 
                            Item tidak dapat diedit karena PO sudah berstatus {{ ucfirst($purchaseOrder->status) }}.
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="40%">Material <span class="text-danger">*</span></th>
                                    <th width="15%">Quantity <span class="text-danger">*</span></th>
                                    <th width="15%">Harga Satuan <span class="text-danger">*</span></th>
                                    <th width="15%">Subtotal</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <!-- Dynamic rows will be added here -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5">
                                        @if(in_array($purchaseOrder->status, ['draft', 'pending']))
                                            <button type="button" class="btn btn-primary btn-sm" id="addItem">
                                                <i class="fa fa-plus"></i> Tambah Item
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-secondary btn-sm" disabled>
                                                <i class="fa fa-lock"></i> Edit Item Dinonaktifkan
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Subtotal</strong></td>
                                    <td colspan="2">
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control text-right" 
                                                   id="subtotal" 
                                                   value="{{ number_format($purchaseOrder->total_amount, 2, '.', '') }}" 
                                                   readonly>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right">
                                        <strong>Pajak (PPN 11%)</strong>
                                    </td>
                                    <td colspan="2">
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control text-right" 
                                                   id="tax" 
                                                   value="{{ number_format($purchaseOrder->tax_amount, 2, '.', '') }}" 
                                                   readonly>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="table-active">
                                    <td colspan="3" class="text-right">
                                        <h5 class="mb-0"><strong>Grand Total</strong></h5>
                                    </td>
                                    <td colspan="2">
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control text-right fw-bold text-primary" 
                                                   id="grandTotal" 
                                                   value="{{ number_format($purchaseOrder->grand_total, 2, '.', '') }}" 
                                                   readonly>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    {{-- SECTION 3: CATATAN --}}
                    <div class="card-subheader mb-3 mt-4">
                        <h6><i class="fa fa-sticky-note text-primary"></i> Catatan & Informasi Tambahan</h6>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Catatan (Opsional)</label>
                                <textarea name="notes" class="form-control" rows="3" 
                                          placeholder="Tambahkan catatan atau instruksi khusus..."
                                          @if(!in_array($purchaseOrder->status, ['draft', 'pending'])) disabled @endif>{{ old('notes', $purchaseOrder->notes) }}</textarea>
                                @if(!in_array($purchaseOrder->status, ['draft', 'pending']))
                                    <input type="hidden" name="notes" value="{{ $purchaseOrder->notes }}">
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    {{-- VALIDATION ERRORS --}}
                    @if($errors->any())
                        <div class="alert alert-danger mt-3">
                            <h6><i class="fa fa-exclamation-triangle"></i> Terdapat kesalahan:</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                
                <div class="card-footer">
                    @if(in_array($purchaseOrder->status, ['draft', 'pending']))
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Update Purchase Order
                        </button>
                        <button type="submit" name="action" value="submit" class="btn btn-warning">
                            <i class="fa fa-paper-plane"></i> Update & Submit untuk Approval
                        </button>
                    @endif
                    <a href="{{ route('purchase-orders.show', $purchaseOrder->id) }}" class="btn btn-info">
                        <i class="fa fa-eye"></i> Lihat Detail
                    </a>
                    <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
        
    </div>
</div>

{{-- Modal untuk Pilih Material --}}
@if(in_array($purchaseOrder->status, ['draft', 'pending']))
<div class="modal fade" id="selectMaterialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Material</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover" id="materialsTable">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Material</th>
                            <th>Stok</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($materials as $material)
                            <tr>
                                <td>{{ $material->code }}</td>
                                <td>{{ $material->name }}</td>
                                <td>{{ $material->stock }} {{ $material->unit }}</td>
                                <td>Rp {{ number_format($material->price, 0, ',', '.') }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" 
                                            onclick="selectMaterial({{ $material->id }}, '{{ $material->code }}', '{{ $material->name }}', {{ $material->price }})">
                                        <i class="fa fa-check"></i> Pilih
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

{{-- CSS --}}
<style>
.card-subheader {
    padding: 10px 15px;
    background-color: #f8f9fa;
    border-left: 4px solid #007bff;
    border-radius: 4px;
    margin-bottom: 15px;
}
.card-subheader h6 {
    margin: 0;
    color: #495057;
}
#itemsTable th, #itemsTable td {
    vertical-align: middle;
}
.input-group-text {
    background-color: #f8f9fa;
}
.disabled-field {
    background-color: #e9ecef;
    opacity: 1;
}
</style>

{{-- JavaScript --}}
<script>
let itemIndex = 0;
const materials = @json($materials);
const purchaseOrder = @json($purchaseOrder);
const isEditable = @json(in_array($purchaseOrder->status, ['draft', 'pending']));

// Fungsi untuk menambahkan baris item
function addItemRow(materialId = '', materialName = '', unitPrice = 0, quantity = 1, description = '') {
    const isDisabled = !isEditable ? 'disabled' : '';
    const readonlyClass = !isEditable ? 'disabled-field' : '';
    
    const row = `
        <tr id="row${itemIndex}">
            <td>
                <div class="input-group">
                    <select name="items[${itemIndex}][material_id]" 
                            class="form-control material-select ${readonlyClass}" required
                            onchange="updateMaterialInfo(${itemIndex}, this.value)"
                            ${isDisabled}>
                        <option value="">-- Pilih Material --</option>
                        ${materials.map(m => `
                            <option value="${m.id}" ${materialId == m.id ? 'selected' : ''}
                                    data-price="${m.price}" data-unit="${m.unit}">
                                ${m.code} - ${m.name}
                            </option>
                        `).join('')}
                    </select>
                    ${isEditable ? `
                    <button type="button" class="btn btn-outline-primary" 
                            onclick="openMaterialModal(${itemIndex})">
                        <i class="fa fa-search"></i>
                    </button>
                    ` : ''}
                </div>
                <input type="text" name="items[${itemIndex}][description]" 
                       class="form-control mt-1 ${readonlyClass}" 
                       placeholder="Deskripsi (opsional)"
                       value="${description}"
                       ${isDisabled}>
                <small id="materialInfo${itemIndex}" class="text-muted"></small>
                ${!isEditable && materialId ? `
                    <input type="hidden" name="items[${itemIndex}][material_id]" value="${materialId}">
                    <input type="hidden" name="items[${itemIndex}][description]" value="${description}">
                ` : ''}
            </td>
            <td>
                <div class="input-group">
                    <input type="number" name="items[${itemIndex}][quantity]" 
                           class="form-control quantity ${readonlyClass}" 
                           step="0.01" min="0.01" 
                           value="${quantity}" 
                           required 
                           oninput="calculateRow(${itemIndex})"
                           ${isDisabled}>
                    <span class="input-group-text" id="unit${itemIndex}">pcs</span>
                </div>
                ${!isEditable ? `<input type="hidden" name="items[${itemIndex}][quantity]" value="${quantity}">` : ''}
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="items[${itemIndex}][unit_price]" 
                           class="form-control unit-price text-right ${readonlyClass}" 
                           step="0.01" min="0" 
                           value="${unitPrice}" 
                           required 
                           oninput="calculateRow(${itemIndex})"
                           ${isDisabled}>
                </div>
                ${!isEditable ? `<input type="hidden" name="items[${itemIndex}][unit_price]" value="${unitPrice}">` : ''}
            </td>
            <td>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" class="form-control total-price text-right" 
                           readonly 
                           id="total${itemIndex}"
                           value="${(quantity * unitPrice).toFixed(2)}">
                </div>
            </td>
            <td>
                ${isEditable ? `
                <button type="button" class="btn btn-sm btn-danger" 
                        onclick="removeItem(${itemIndex})">
                    <i class="fa fa-trash"></i>
                </button>
                ` : `
                <button type="button" class="btn btn-sm btn-secondary" disabled>
                    <i class="fa fa-lock"></i>
                </button>
                `}
            </td>
        </tr>
    `;
    $('#itemsBody').append(row);
    
    if (materialId) {
        updateMaterialInfo(itemIndex, materialId);
    }
    
    itemIndex++;
    calculateTotal();
}

// Fungsi untuk membuka modal pilih material
function openMaterialModal(rowIndex) {
    if (!isEditable) return;
    window.currentRowIndex = rowIndex;
    $('#selectMaterialModal').modal('show');
}

// Fungsi untuk memilih material dari modal
function selectMaterial(materialId, code, name, price) {
    if (!isEditable) return;
    const row = $('#row' + window.currentRowIndex);
    row.find('.material-select').val(materialId);
    row.find('.unit-price').val(price);
    updateMaterialInfo(window.currentRowIndex, materialId);
    calculateRow(window.currentRowIndex);
    $('#selectMaterialModal').modal('hide');
}

// Fungsi untuk mengupdate info material
function updateMaterialInfo(rowIndex, materialId) {
    const material = materials.find(m => m.id == materialId);
    if (material) {
        $(`#unit${rowIndex}`).text(material.unit);
        $(`#materialInfo${rowIndex}`).html(`
            Stok: ${material.stock} ${material.unit} | 
            Min. Stok: ${material.min_stock} ${material.unit}
        `);
        
        // Auto-set unit price jika kosong
        if (isEditable) {
            const unitPriceInput = $(`#row${rowIndex} .unit-price`);
            if (!unitPriceInput.val() || unitPriceInput.val() == 0) {
                unitPriceInput.val(material.price);
                calculateRow(rowIndex);
            }
        }
    }
}

// Fungsi untuk menghitung per baris
function calculateRow(rowIndex) {
    if (!isEditable) return;
    
    const row = $('#row' + rowIndex);
    const quantity = parseFloat(row.find('.quantity').val()) || 0;
    const unitPrice = parseFloat(row.find('.unit-price').val()) || 0;
    const total = quantity * unitPrice;
    row.find('.total-price').val(total.toFixed(2));
    calculateTotal();
}

// Fungsi untuk menghitung total keseluruhan
function calculateTotal() {
    let subtotal = 0;
    $('.total-price').each(function() {
        subtotal += parseFloat($(this).val()) || 0;
    });
    
    const tax = subtotal * 0.11;
    const grandTotal = subtotal + tax;
    
    $('#subtotal').val(subtotal.toFixed(2));
    $('#tax').val(tax.toFixed(2));
    $('#grandTotal').val(grandTotal.toFixed(2));
}

// Fungsi untuk menghapus item
function removeItem(rowIndex) {
    if (!isEditable) return;
    $('#row' + rowIndex).remove();
    calculateTotal();
}

// Load existing items
function loadExistingItems() {
    const items = purchaseOrder.items;
    items.forEach((item, index) => {
        addItemRow(
            item.material_id,
            item.material.name,
            parseFloat(item.unit_price),
            parseFloat(item.quantity),
            item.description || ''
        );
    });
}

// Event listeners
$(document).ready(function() {
    // Load existing items
    loadExistingItems();
    
    // Jika tidak ada item, tambahkan satu item kosong
    if (itemIndex === 0 && isEditable) {
        addItemRow();
    }
    
    // Tombol tambah item
    $('#addItem').click(function() {
        if (!isEditable) return;
        addItemRow();
    });
    
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap',
        disabled: !isEditable
    });
    
    // Initialize materials table
    if (isEditable) {
        $('#materialsTable').DataTable({
            "pageLength": 5
        });
    }
    
    // Form submission validation
    $('#poForm').submit(function(e) {
        if (!isEditable) {
            e.preventDefault();
            alert('Purchase Order tidak dapat diedit karena statusnya ' + purchaseOrder.status);
            return false;
        }
        
        if ($('#itemsBody tr').length === 0) {
            e.preventDefault();
            alert('Minimal tambahkan satu item pada Purchase Order!');
            return false;
        }
        
        // Validasi quantity dan harga
        let isValid = true;
        $('.quantity').each(function() {
            if (!$(this).val() || parseFloat($(this).val()) <= 0) {
                isValid = false;
                $(this).addClass('is-invalid');
            }
        });
        
        $('.unit-price').each(function() {
            if (!$(this).val() || parseFloat($(this).val()) <= 0) {
                isValid = false;
                $(this).addClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Harap periksa quantity dan harga pada semua item!');
            return false;
        }
        
        // Konfirmasi sebelum submit
        if (!confirm('Update Purchase Order ini?')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Jika form disabled, ubah semua input menjadi readonly
    if (!isEditable) {
        $('#poForm input, #poForm select, #poForm textarea, #poForm button[type="submit"]').prop('disabled', true);
        $('#poForm .btn:not([type="submit"])').prop('disabled', false); // Biarkan tombol aksi lain aktif
    }
});

// Fungsi untuk reset form ke data awal
function resetForm() {
    if (!confirm('Reset semua perubahan?')) return;
    
    $('#itemsBody').empty();
    loadExistingItems();
    
    // Reset form values
    $('#poForm')[0].reset();
    $('.select2').val(purchaseOrder.vendor_id).trigger('change');
    $('input[name="order_date"]').val(purchaseOrder.order_date);
    $('input[name="delivery_date"]').val(purchaseOrder.delivery_date);
    $('textarea[name="notes"]').val(purchaseOrder.notes);
    
    calculateTotal();
}
</script>

@if(in_array($purchaseOrder->status, ['draft', 'pending']))
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endif

{{-- Tombol Reset untuk PO yang bisa diedit --}}
@if(in_array($purchaseOrder->status, ['draft', 'pending']))
<div class="pcoded-inner-content mt-3">
    <div class="card">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fa fa-redo"></i> Opsi Lainnya</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <button type="button" class="btn btn-outline-warning btn-block" onclick="resetForm()">
                        <i class="fa fa-undo"></i> Reset Perubahan
                    </button>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('purchase-orders.show', $purchaseOrder->id) }}" 
                       class="btn btn-outline-info btn-block">
                        <i class="fa fa-eye"></i> Lihat Detail PO
                    </a>
                </div>
                <div class="col-md-4">
                    <form action="{{ route('purchase-orders.destroy', $purchaseOrder->id) }}" 
                          method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-block"
                                onclick="return confirm('Hapus Purchase Order {{ $purchaseOrder->po_number }}?')">
                            <i class="fa fa-trash"></i> Hapus PO
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection