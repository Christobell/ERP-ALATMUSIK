@extends('layouts.master')

@section('title', 'Buat Purchase Order Baru')
@section('content')
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        
        <div class="card">
            <div class="card-header">
                <h5>
                    <i class="fa fa-plus-circle text-primary"></i> 
                    Buat Purchase Order Baru
                </h5>
                <div class="card-header-right">
                    <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
            
            <form action="{{ route('purchase-orders.store') }}" method="POST" id="poForm">
                @csrf
                
                <div class="card-block">
                    {{-- INFO HEADER --}}
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> 
                        <strong>Panduan:</strong> 
                        1. Pilih Vendor → 2. Tambah Item → 3. Isi Quantity & Harga → 4. Simpan
                    </div>
                    
                    {{-- SECTION 1: INFORMASI UMUM --}}
                    <div class="card-subheader mb-3">
                        <h6><i class="fa fa-info-circle text-primary"></i> Informasi Umum PO</h6>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pilih Vendor <span class="text-danger">*</span></label>
                                <select name="vendor_id" id="vendorSelect" class="form-control select2" required>
                                    <option value="">-- Pilih Vendor --</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">
                                            {{ $vendor->code }} - {{ $vendor->company_name }}
                                            @if($vendor->contact_person)
                                                ({{ $vendor->contact_person }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Vendor yang dipilih akan mempengaruhi daftar material yang tersedia</small>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal PO <span class="text-danger">*</span></label>
                                <input type="date" name="order_date" class="form-control" 
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tanggal Delivery</label>
                                <input type="date" name="delivery_date" class="form-control" 
                                       min="{{ date('Y-m-d') }}">
                                <small class="text-muted">Opsional</small>
                            </div>
                        </div>
                    </div>
                    
                    {{-- SECTION 2: TAMBAH ITEM (BUTTON UTAMA) --}}
                    <div class="card-subheader mb-3 mt-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6><i class="fa fa-boxes text-primary"></i> Item Purchase Order</h6>
                            <button type="button" class="btn btn-primary" id="addItemBtn">
                                <i class="fa fa-plus"></i> Tambah Item Baru
                            </button>
                        </div>
                    </div>
                    
                    {{-- DAFTAR ITEM YANG SUDAH DITAMBAH --}}
                    <div class="table-responsive" id="itemsContainer">
                        <table class="table table-bordered" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="30%">Material</th>
                                    <th width="15%">Quantity</th>
                                    <th width="15%">Harga Satuan</th>
                                    <th width="15%">Subtotal</th>
                                    <th width="10%">Satuan</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                <tr id="noItemsRow">
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fa fa-box-open fa-2x mb-2"></i><br>
                                            Belum ada item. Klik "Tambah Item Baru" untuk mulai menambahkan.
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot id="itemsFooter" style="display: none;">
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Subtotal</strong></td>
                                    <td colspan="3">
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control text-right" id="subtotal" value="0" readonly>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right">
                                        <strong>Pajak (PPN 11%)</strong>
                                    </td>
                                    <td colspan="3">
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control text-right" id="tax" value="0" readonly>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="table-active">
                                    <td colspan="4" class="text-right">
                                        <h5 class="mb-0"><strong>Grand Total</strong></h5>
                                    </td>
                                    <td colspan="3">
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control text-right fw-bold text-primary" 
                                                   id="grandTotal" value="0" readonly>
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
                                          placeholder="Tambahkan catatan atau instruksi khusus..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    {{-- HIDDEN INPUTS UNTUK ITEMS --}}
                    <div id="hiddenItemsInputs"></div>
                    
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
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Simpan sebagai Draft
                    </button>
                    <button type="submit" name="action" value="submit" class="btn btn-warning">
                        <i class="fa fa-paper-plane"></i> Simpan & Submit untuk Approval
                    </button>
                    <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary">
                        <i class="fa fa-times"></i> Batal
                    </a>
                </div>
            </form>
        </div>
        
    </div>
</div>

{{-- MODAL TAMBAH ITEM --}}
<div class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-plus-circle text-primary"></i> Tambah Item Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Pilih Material <span class="text-danger">*</span></label>
                            <select id="materialSelect" class="form-control">
                                <option value="">-- Pilih Material --</option>
                                @foreach($materials as $material)
                                    <option value="{{ $material->id }}" 
                                            data-code="{{ $material->code }}"
                                            data-name="{{ $material->name }}"
                                            data-unit="{{ $material->unit }}"
                                            data-price="{{ $material->price }}"
                                            data-stock="{{ $material->stock }}">
                                        {{ $material->code }} - {{ $material->name }} 
                                        (Stok: {{ $material->stock }} {{ $material->unit }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3" id="materialDetails" style="display: none;">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6>Informasi Material</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td width="40%"><strong>Kode</strong></td>
                                        <td id="detailCode">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nama</strong></td>
                                        <td id="detailName">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Satuan</strong></td>
                                        <td id="detailUnit">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Stok Tersedia</strong></td>
                                        <td id="detailStock">-</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Harga Standar</strong></td>
                                        <td id="detailPrice">-</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6>Detail Item</h6>
                                <div class="form-group">
                                    <label>Quantity <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" id="itemQuantity" class="form-control" 
                                               min="1" step="1" value="1">
                                        <span class="input-group-text" id="quantityUnit">pcs</span>
                                    </div>
                                    <small class="text-muted" id="quantityHint"></small>
                                </div>
                                
                                <div class="form-group">
                                    <label>Harga Satuan <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" id="itemUnitPrice" class="form-control" 
                                               min="0" step="0.01" value="0">
                                    </div>
                                    <small class="text-muted">Harga per unit</small>
                                </div>
                                
                                <div class="form-group">
                                    <label>Deskripsi (Opsional)</label>
                                    <textarea id="itemDescription" class="form-control" rows="2" 
                                              placeholder="Catatan khusus untuk item ini"></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label>Subtotal</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" id="itemSubtotal" class="form-control text-right fw-bold" 
                                               value="0" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="modalAddBtn" disabled>
                    <i class="fa fa-cart-plus"></i> Tambah ke PO
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DAFTAR MATERIAL --}}
<div class="modal fade" id="materialsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-boxes text-primary"></i> Daftar Material
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="allMaterialsTable">
                        <thead>
                            <tr>
                                <th width="10%">Kode</th>
                                <th width="25%">Nama Material</th>
                                <th width="15%">Kategori</th>
                                <th width="10%">Stok</th>
                                <th width="10%">Satuan</th>
                                <th width="15%">Harga</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($materials as $material)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">{{ $material->code }}</span>
                                    </td>
                                    <td>{{ $material->name }}</td>
                                    <td>{{ $material->category ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($material->stock <= $material->min_stock)
                                            <span class="badge bg-danger">{{ $material->stock }}</span>
                                        @else
                                            <span class="badge bg-success">{{ $material->stock }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $material->unit }}</td>
                                    <td class="text-right">Rp {{ number_format($material->price, 0, ',', '.') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary select-material-btn" 
                                                data-id="{{ $material->id }}"
                                                data-code="{{ $material->code }}"
                                                data-name="{{ $material->name }}"
                                                data-unit="{{ $material->unit }}"
                                                data-price="{{ $material->price }}"
                                                data-stock="{{ $material->stock }}">
                                            <i class="fa fa-plus"></i> Pilih
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- JAVASCRIPT YANG BERFUNGSI 100% --}}
<script>
$(document).ready(function() {
    console.log('Document ready - Purchase Order Create');
    
    let itemsArray = [];
    let itemCounter = 0;
    let totalSubtotal = 0;

    // 1. TOMBOL TAMBAH ITEM BARU
    $('#addItemBtn').on('click', function() {
        console.log('Tombol Tambah Item Baru diklik');
        
        // Reset form modal
        $('#materialSelect').val('');
        $('#materialDetails').hide();
        $('#itemQuantity').val(1); // Default 1
        $('#itemUnitPrice').val(0);
        $('#itemDescription').val('');
        $('#itemSubtotal').val('0');
        $('#modalAddBtn').prop('disabled', true);
        
        // Tampilkan modal menggunakan Bootstrap 5
        const addItemModal = new bootstrap.Modal(document.getElementById('addItemModal'));
        addItemModal.show();
    });

    // 2. KETIKA MATERIAL DIPILIH
    $('#materialSelect').on('change', function() {
        console.log('Material select changed');
        
        const selectedOption = $(this).find('option:selected');
        const materialId = selectedOption.val();
        
        if (materialId) {
            const code = selectedOption.data('code');
            const name = selectedOption.data('name');
            const unit = selectedOption.data('unit');
            const price = selectedOption.data('price');
            const stock = selectedOption.data('stock');
            
            console.log('Material selected:', code, name);
            
            // Update detail material
            $('#detailCode').text(code);
            $('#detailName').text(name);
            $('#detailUnit').text(unit);
            $('#detailStock').text(stock + ' ' + unit);
            $('#detailPrice').text('Rp ' + parseFloat(price).toLocaleString('id-ID'));
            
            // Update form input - SET QUANTITY = 1 ketika pilih material
            $('#quantityUnit').text(unit);
            $('#itemQuantity').val(1); // ← INI YANG PENTING! SET KE 1
            $('#itemUnitPrice').val(price);
            
            // Update quantity hint
            if (stock > 0) {
                $('#quantityHint').html(`<span class="text-success">Stok tersedia: ${stock}</span>`);
            } else {
                $('#quantityHint').html('<span class="text-danger">Stok habis</span>');
            }
            
            // Tampilkan detail
            $('#materialDetails').show();
            $('#modalAddBtn').prop('disabled', false);
            
            // Hitung subtotal (1 × harga)
            calculateItemSubtotal();
        } else {
            $('#materialDetails').hide();
            $('#modalAddBtn').prop('disabled', true);
        }
    });

    // 3. PILIH MATERIAL DARI TABEL
    $(document).on('click', '.select-material-btn', function() {
        console.log('Pilih material dari tabel');
        
        const id = $(this).data('id');
        const code = $(this).data('code');
        const name = $(this).data('name');
        const unit = $(this).data('unit');
        const price = $(this).data('price');
        const stock = $(this).data('stock');
        
        // Set nilai di select
        $('#materialSelect').val(id).trigger('change');
        
        // Tutup modal daftar material
        const materialsModal = bootstrap.Modal.getInstance(document.getElementById('materialsModal'));
        if (materialsModal) materialsModal.hide();
        
        // Tampilkan modal tambah item
        const addItemModal = new bootstrap.Modal(document.getElementById('addItemModal'));
        addItemModal.show();
    });

    // 4. HITUNG SUBTOTAL ITEM
    function calculateItemSubtotal() {
        const quantity = parseFloat($('#itemQuantity').val()) || 1; // Default 1 jika kosong
        const unitPrice = parseFloat($('#itemUnitPrice').val()) || 0;
        const subtotal = quantity * unitPrice;
        
        $('#itemSubtotal').val(subtotal.toLocaleString('id-ID'));
    }

    // 5. EVENT INPUT QUANTITY DAN HARGA
    $('#itemQuantity, #itemUnitPrice').on('input', function() {
        calculateItemSubtotal();
    });

    // 6. TOMBOL TAMBAH KE PO DI MODAL
    $('#modalAddBtn').on('click', function() {
        console.log('Tombol Tambah ke PO diklik');
        
        const materialSelect = $('#materialSelect');
        const selectedOption = materialSelect.find('option:selected');
        
        if (!selectedOption.val()) {
            alert('Pilih material terlebih dahulu!');
            return;
        }
        
        const materialId = selectedOption.val();
        const code = selectedOption.data('code');
        const name = selectedOption.data('name');
        const unit = selectedOption.data('unit');
        const defaultPrice = parseFloat(selectedOption.data('price')) || 0;
        
        // Quantity - default 1 jika kosong/error
        let quantity = parseFloat($('#itemQuantity').val());
        if (isNaN(quantity) || quantity < 1) {
            quantity = 1;
            $('#itemQuantity').val(1); // Reset ke 1
        }
        
        const unitPrice = parseFloat($('#itemUnitPrice').val()) || defaultPrice;
        const description = $('#itemDescription').val();
        
        // Validasi harga
        if (unitPrice <= 0) {
            alert('Harga satuan harus lebih dari 0!');
            $('#itemUnitPrice').focus();
            return;
        }
        
        // Cek apakah material sudah ada di daftar
        const existingIndex = itemsArray.findIndex(item => item.material_id == materialId);
        
        if (existingIndex !== -1) {
            // Jika material sudah ada, tanya user
            if (!confirm(`Material "${code} - ${name}" sudah ada di daftar. Update quantity?`)) {
                return;
            }
            
            // Update quantity yang sudah ada
            itemsArray[existingIndex].quantity += quantity;
            itemsArray[existingIndex].subtotal = itemsArray[existingIndex].quantity * itemsArray[existingIndex].unit_price;
        } else {
            // Tambah item baru
            const item = {
                id: itemCounter++,
                material_id: materialId,
                code: code,
                name: name,
                unit: unit,
                quantity: quantity,
                unit_price: unitPrice,
                description: description,
                subtotal: quantity * unitPrice
            };
            
            itemsArray.push(item);
        }
        
        // Update tampilan keranjang
        updateCartDisplay();
        
        // Tutup modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('addItemModal'));
        if (modal) modal.hide();
        
        // Reset form modal
        $('#materialSelect').val('');
        $('#materialDetails').hide();
        $('#modalAddBtn').prop('disabled', true);
        
        // Tampilkan notifikasi
        alert('Item berhasil ditambahkan ke Purchase Order!');
    });

    // 7. UPDATE TAMPILAN KERANJANG
    function updateCartDisplay() {
        const itemsBody = $('#itemsBody');
        const hiddenInputs = $('#hiddenItemsInputs');
        
        // Kosongkan isi
        itemsBody.empty();
        hiddenInputs.empty();
        
        // Reset total
        totalSubtotal = 0;
        
        // Jika tidak ada item
        if (itemsArray.length === 0) {
            itemsBody.append(`
                <tr id="noItemsRow">
                    <td colspan="7" class="text-center py-4">
                        <div class="text-muted">
                            <i class="fa fa-box-open fa-2x mb-2"></i><br>
                            Belum ada item. Klik "Tambah Item Baru" untuk mulai menambahkan.
                        </div>
                    </td>
                </tr>
            `);
            $('#itemsFooter').hide();
            return;
        }
        
        // Tampilkan setiap item
        itemsArray.forEach((item, index) => {
            // Tambah baris ke tabel
            itemsBody.append(`
                <tr id="itemRow${item.id}">
                    <td>${index + 1}</td>
                    <td>
                        <strong>${item.code}</strong><br>
                        <small>${item.name}</small>
                        ${item.description ? `<br><small class="text-muted"><i>${item.description}</i></small>` : ''}
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control quantity-input" 
                                   value="${item.quantity}" 
                                   min="1" step="1"
                                   onchange="updateItemQuantity(${item.id}, this.value)">
                            <span class="input-group-text">${item.unit}</span>
                        </div>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control price-input" 
                                   value="${item.unit_price}" 
                                   min="0" step="0.01"
                                   onchange="updateItemPrice(${item.id}, this.value)">
                        </div>
                    </td>
                    <td class="text-right fw-bold">
                        Rp ${item.subtotal.toLocaleString('id-ID')}
                    </td>
                    <td class="text-center">
                        <span class="badge bg-secondary">${item.unit}</span>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger" 
                                onclick="removeItemFromCart(${item.id})">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
            
            // Tambah hidden inputs untuk form submission
            hiddenInputs.append(`
                <input type="hidden" name="items[${index}][material_id]" value="${item.material_id}">
                <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                <input type="hidden" name="items[${index}][unit_price]" value="${item.unit_price}">
                <input type="hidden" name="items[${index}][description]" value="${item.description}">
            `);
            
            // Hitung total
            totalSubtotal += item.subtotal;
        });
        
        // Update total keseluruhan
        updateTotals();
        
        // Tampilkan footer (subtotal, tax, grand total)
        $('#itemsFooter').show();
    }

    // 8. UPDATE TOTAL KESELURUHAN
    function updateTotals() {
        const tax = totalSubtotal * 0.11; // PPN 11%
        const grandTotal = totalSubtotal + tax;
        
        $('#subtotal').val(totalSubtotal.toLocaleString('id-ID'));
        $('#tax').val(tax.toLocaleString('id-ID'));
        $('#grandTotal').val(grandTotal.toLocaleString('id-ID'));
    }

    // 9. FUNGSI UPDATE QUANTITY ITEM (GLOBAL)
    window.updateItemQuantity = function(itemId, newQuantity) {
        newQuantity = parseFloat(newQuantity) || 1;
        
        if (newQuantity < 1) {
            alert('Quantity minimal 1!');
            // Reset ke nilai sebelumnya
            const itemIndex = itemsArray.findIndex(item => item.id === itemId);
            if (itemIndex !== -1) {
                $(`#itemRow${itemId} .quantity-input`).val(itemsArray[itemIndex].quantity);
            }
            return;
        }
        
        const itemIndex = itemsArray.findIndex(item => item.id === itemId);
        if (itemIndex !== -1) {
            itemsArray[itemIndex].quantity = newQuantity;
            itemsArray[itemIndex].subtotal = newQuantity * itemsArray[itemIndex].unit_price;
            updateCartDisplay();
        }
    }

    // 10. FUNGSI UPDATE HARGA ITEM (GLOBAL)
    window.updateItemPrice = function(itemId, newPrice) {
        newPrice = parseFloat(newPrice) || 0;
        
        if (newPrice < 0) {
            alert('Harga tidak boleh negatif!');
            const itemIndex = itemsArray.findIndex(item => item.id === itemId);
            if (itemIndex !== -1) {
                $(`#itemRow${itemId} .price-input`).val(itemsArray[itemIndex].unit_price);
            }
            return;
        }
        
        const itemIndex = itemsArray.findIndex(item => item.id === itemId);
        if (itemIndex !== -1) {
            itemsArray[itemIndex].unit_price = newPrice;
            itemsArray[itemIndex].subtotal = itemsArray[itemIndex].quantity * newPrice;
            updateCartDisplay();
        }
    }

    // 11. FUNGSI HAPUS ITEM (GLOBAL)
    window.removeItemFromCart = function(itemId) {
        if (!confirm('Hapus item ini dari daftar?')) {
            return;
        }
        
        const itemIndex = itemsArray.findIndex(item => item.id === itemId);
        if (itemIndex !== -1) {
            const itemName = itemsArray[itemIndex].name;
            itemsArray.splice(itemIndex, 1);
            updateCartDisplay();
            console.log(`Item "${itemName}" dihapus`);
        }
    }

    // 12. VALIDASI FORM SEBELUM SUBMIT
    $('#poForm').on('submit', function(e) {
        // Validasi vendor
        if (!$('#vendorSelect').val()) {
            e.preventDefault();
            alert('Pilih vendor terlebih dahulu!');
            $('#vendorSelect').focus();
            return false;
        }
        
        // Validasi items
        if (itemsArray.length === 0) {
            e.preventDefault();
            alert('Tambahkan minimal satu item pada Purchase Order!');
            return false;
        }
        
        // Konfirmasi
        const isSubmit = $('button[name="action"][value="submit"]').is(':focus');
        const poStatus = isSubmit ? 'Submit untuk Approval' : 'Draft';
        
        if (!confirm(`Buat Purchase Order sebagai ${poStatus}?`)) {
            e.preventDefault();
            return false;
        }
        
        return true;
    });

    // 13. FUNGSI TAMBAH ITEM CEPAT
    window.addMaterialQuick = function(id, code, name, unit, price, stock) {
        // Cek apakah material sudah ada
        const existingIndex = itemsArray.findIndex(item => item.material_id == id);
        
        if (existingIndex !== -1) {
            // Update quantity
            itemsArray[existingIndex].quantity += 1;
            itemsArray[existingIndex].subtotal = itemsArray[existingIndex].quantity * itemsArray[existingIndex].unit_price;
        } else {
            // Tambah item baru
            const item = {
                id: itemCounter++,
                material_id: id,
                code: code,
                name: name,
                unit: unit,
                quantity: 1,
                unit_price: price,
                description: '',
                subtotal: price
            };
            
            itemsArray.push(item);
        }
        
        updateCartDisplay();
        alert(`"${code}" berhasil ditambahkan ke PO!`);
    };

    // 14. FUNGSI TAMPILKAN MODAL DAFTAR MATERIAL
    window.showMaterialsModal = function() {
        const modal = new bootstrap.Modal(document.getElementById('materialsModal'));
        modal.show();
    };

    // 15. INITIALIZE SELECT2 JIKA ADA
    if ($.fn.select2) {
        $('#vendorSelect').select2({
            theme: 'bootstrap',
            placeholder: 'Pilih Vendor'
        });
    }
});

function validateForm() {
    console.log('Form sedang di-submit...');
    
    // Cek vendor
    const vendorId = document.querySelector('[name="vendor_id"]').value;
    if (!vendorId) {
        alert('Pilih vendor terlebih dahulu!');
        return false;
    }
    
    // Cek items
    const items = document.querySelectorAll('.item-row');
    if (items.length === 0) {
        alert('Tambah minimal 1 item!');
        return false;
    }
    
    // Cek apakah semua item valid
    let isValid = true;
    items.forEach((item, index) => {
        const materialId = item.querySelector('[name="items['+index+'][material_id]"]').value;
        const quantity = item.querySelector('[name="items['+index+'][quantity]"]').value;
        const unitPrice = item.querySelector('[name="items['+index+'][unit_price]"]').value;
        
        if (!materialId || !quantity || quantity <= 0 || !unitPrice || unitPrice <= 0) {
            isValid = false;
        }
    });
    
    if (!isValid) {
        alert('Lengkapi semua data item dengan benar!');
        return false;
    }
    
    return true;
}

// Debug: saat form di-submit
document.getElementById('poForm').addEventListener('submit', function(e) {
    console.log('Form submit event triggered');
    console.log('Form data:', new FormData(this));
});

</script>

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
.quantity-input {
    max-width: 120px;
}
.price-input {
    max-width: 150px;
}
</style>

@endsection


@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif