@extends('layouts.master')

@section('title', 'Vendor')
@section('content')
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        
        {{-- CARD 1: FORM TAMBAH VENDOR --}}
        <div class="card">
            <div class="card-header">
                <h5>Tambah Vendor Baru</h5>
            </div>
            <div class="card-block">
                <form action="{{ route('vendor.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kode Vendor</label>
                        <div class="col-sm-10">
                            <input type="text" name="code" class="form-control" 
                                   placeholder="Contoh: VEND-001" required>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nama Perusahaan</label>
                        <div class="col-sm-10">
                            <input type="text" name="company_name" class="form-control" 
                                   placeholder="Masukkan nama perusahaan vendor" required>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Orang yang Dapat Dihubungi</label>
                        <div class="col-sm-10">
                            <input type="text" name="contact_person" class="form-control" 
                                   placeholder="Nama kontak person">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Email</label>
                                <div class="col-sm-8">
                                    <input type="email" name="email" class="form-control" 
                                           placeholder="email@vendor.com">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Telepon</label>
                                <div class="col-sm-8">
                                    <input type="text" name="phone" class="form-control" 
                                           placeholder="0812-3456-7890">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">NPWP</label>
                        <div class="col-sm-10">
                            <input type="text" name="tax_number" class="form-control" 
                                   placeholder="Nomor Pokok Wajib Pajak">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <textarea name="address" class="form-control" rows="2" 
                                      placeholder="Alamat lengkap vendor"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Syarat Pembayaran</label>
                        <div class="col-sm-10">
                            <textarea name="payment_terms" class="form-control" rows="2" 
                                      placeholder="Contoh: Net 30, DP 50%, dll"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" 
                                       id="is_active" value="1" checked>
                                <label class="form-check-label" for="is_active">
                                    Vendor Aktif
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Simpan Vendor
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        {{-- CARD 2: TABEL DAFTAR VENDOR --}}
        <div class="card">
            <div class="card-header">
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
                    <table class="table table-hover align-middle vendor-table">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Nama Perusahaan</th>
                                <th>Kontak Person</th>
                                <th>Telepon/Email</th>
                                <th>Material</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vendors as $index => $vendor)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $vendor->code }}</span>
                                    </td>
                                    <td class="fw-semibold">{{ $vendor->company_name }}</td>
                                    <td>{{ $vendor->contact_person ?? '-' }}</td>
                                    <td>
                                        <small class="d-block">
                                            <i class="fa fa-phone text-muted me-1"></i> 
                                            {{ $vendor->phone ?? '-' }}
                                        </small>
                                        <small class="d-block">
                                            <i class="fa fa-envelope text-muted me-1"></i> 
                                            {{ $vendor->email ?? '-' }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $vendor->items_count ?? 0 }} material
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $vendor->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $vendor->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{-- Tombol Detail --}}
                                        <a href="{{ route('vendor.show', $vendor->id) }}" 
                                           class="btn btn-outline-info btn-sm"
                                           title="Detail Vendor">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('vendor.edit', $vendor->id) }}" 
                                           class="btn btn-outline-primary btn-sm"
                                           title="Edit Vendor">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        
                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('vendor.destroy', $vendor->id) }}" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('Hapus vendor {{ $vendor->company_name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    title="Hapus Vendor">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                        
                                        {{-- Tombol Toggle Status --}}
                                        <form action="{{ route('vendor.toggle-status', $vendor->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" 
                                                    class="btn btn-sm {{ $vendor->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}"
                                                    title="{{ $vendor->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                <i class="fa {{ $vendor->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    {{-- Pagination --}}
                    @if($vendors->hasPages())
                    <div class="mt-3">
                        {{ $vendors->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
    </div>
</div>

{{-- Modal untuk Tambah Material ke Vendor --}}
<div class="modal fade" id="addMaterialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Material ke Vendor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addMaterialForm">
                <div class="modal-body">
                    <input type="hidden" name="vendor_id" id="modal_vendor_id">
                    
                    <div class="form-group">
                        <label>Pilih Material</label>
                        <select name="material_id" class="form-control" required>
                            <option value="">-- Pilih Material --</option>
                            @foreach($materials ?? [] as $material)
                                <option value="{{ $material->id }}">
                                    {{ $material->code }} - {{ $material->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Harga dari Vendor</label>
                        <input type="number" name="vendor_price" class="form-control" 
                               placeholder="Harga per unit" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Satuan</label>
                                <select name="unit" class="form-control">
                                    <option value="pcs">Pcs</option>
                                    <option value="kg">Kg</option>
                                    <option value="meter">Meter</option>
                                    <option value="unit">Unit</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Minimum Order</label>
                                <input type="number" name="minimum_order" class="form-control" 
                                       value="1" min="1">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Lead Time (hari)</label>
                        <input type="number" name="lead_time" class="form-control" 
                               placeholder="Estimasi pengiriman dalam hari">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- CSS Tambahan --}}
<style>
.vendor-table td {
    vertical-align: middle;
}
.vendor-table .badge {
    font-size: 0.85em;
}
</style>

{{-- JavaScript --}}
<script>
// Fungsi untuk membuka modal tambah material
function openAddMaterialModal(vendorId) {
    document.getElementById('modal_vendor_id').value = vendorId;
    $('#addMaterialModal').modal('show');
}

// Handle submit form material
document.getElementById('addMaterialForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("vendor.add-material") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Material berhasil ditambahkan ke vendor');
            $('#addMaterialModal').modal('hide');
            location.reload();
        } else {
            alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan jaringan');
    });
});
</script>
@endsection