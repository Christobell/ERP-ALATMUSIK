@extends('layouts.master')

@section('title', 'Buat RFQ Baru')
@section('content')
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        
        <div class="card">
            <div class="card-header">
                <h5>Buat RFQ (Request for Quotation) Baru</h5>
            </div>
            <div class="card-block">
                <form action="{{ route('rfq.store') }}" method="POST" id="rfqForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Nomor RFQ</label>
                                <div class="col-sm-8">
                                    <input type="text" name="rfq_number" class="form-control" 
                                           placeholder="Contoh: RFQ-001" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Judul RFQ</label>
                                <div class="col-sm-8">
                                    <input type="text" name="title" class="form-control" 
                                           placeholder="Judul Request for Quotation" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Deskripsi</label>
                        <div class="col-sm-10">
                            <textarea name="description" class="form-control" rows="2" 
                                      placeholder="Deskripsi kebutuhan"></textarea>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Tanggal Request</label>
                                <div class="col-sm-8">
                                    <input type="date" name="request_date" class="form-control" 
                                           value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Deadline</label>
                                <div class="col-sm-8">
                                    <input type="date" name="deadline_date" class="form-control" 
                                           value="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                       <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Requestor</label>
                                <div class="col-sm-8">
                                    <select name="requested_by" class="form-control" required>
                                        <option value="">-- Pilih Requestor --</option>
                                        @forelse($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                        @empty
                                            <option value="" disabled>Tidak ada data users</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Departemen</label>
                                <div class="col-sm-8">
                                    <select name="department_id" class="form-control" required>
                                        <option value="">-- Pilih Departemen --</option>
                                        @foreach($departments as $dept)
                                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Items/Material</label>
                        <div class="col-sm-10">
                            <div id="items-container">
                                <div class="item-row row mb-2">
                                    <div class="col-md-4">
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
                                    <div class="col-md-3">
                                        <input type="text" name="items[0][specifications]" 
                                               class="form-control" 
                                               placeholder="Spesifikasi tambahan">
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
                                <label class="col-sm-4 col-form-label">Estimated Budget</label>
                                <div class="col-sm-8">
                                    <input type="number" name="estimated_budget" id="estimated_budget" 
                                           class="form-control" placeholder="Estimasi budget" 
                                           min="0" step="0.01" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Status</label>
                                <div class="col-sm-8">
                                    <select name="status" class="form-control" required>
                                        <option value="draft" selected>Draft</option>
                                        <option value="pending">Pending (Send to Vendor)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Catatan</label>
                        <div class="col-sm-10">
                            <textarea name="notes" class="form-control" rows="2" 
                                      placeholder="Catatan tambahan untuk vendor"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Simpan RFQ
                            </button>
                            <a href="{{ route('rfq.index') }}" class="btn btn-default">Batal</a>
                        </div>
                    </div>
                </form>
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
        <div class="col-md-4">
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
        <div class="col-md-3">
            <input type="text" name="items[${itemCounter}][specifications]" 
                   class="form-control" placeholder="Spesifikasi tambahan">
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
    updateBudget();
}

function handleMaterialSelect(event) {
    const select = event.target;
    const selectedOption = select.options[select.selectedIndex];
    const row = select.closest('.item-row');
    
    if (selectedOption.value) {
        const unit = selectedOption.getAttribute('data-unit');
        const price = selectedOption.getAttribute('data-price');
        
        row.querySelector('.item-unit').value = unit || 'pcs';
        updateBudget();
    }
}

function updateBudget() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
        const price = parseFloat(row.querySelector('.select-material').options[row.querySelector('.select-material').selectedIndex]?.getAttribute('data-price')) || 0;
        total += quantity * price;
    });
    
    document.getElementById('estimated_budget').value = total.toFixed(2);
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.select-material').forEach(select => {
        select.addEventListener('change', handleMaterialSelect);
    });
    
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('item-quantity')) {
            updateBudget();
        }
    });
    
    const firstSelect = document.querySelector('.select-material');
    if (firstSelect) {
        firstSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection