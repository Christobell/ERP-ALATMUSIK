@extends('layouts.master')

@section('title', 'Detail Vendor')
@section('content')
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        
        {{-- HEADER --}}
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">
                            <a href="{{ route('vendor.index') }}" class="text-muted me-2">
                                <i class="fa fa-arrow-left"></i>
                            </a>
                            Detail Vendor: {{ $vendor->company_name }}
                        </h5>
                        <small class="text-muted">Kode: {{ $vendor->code }}</small>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('vendor.edit', $vendor->id) }}" class="btn btn-warning btn-sm">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('vendor.destroy', $vendor->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus vendor {{ $vendor->company_name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i> Hapus
                            </button>
                        </form>
                        <form action="{{ route('vendor.toggle-status', $vendor->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn {{ $vendor->is_active ? 'btn-secondary' : 'btn-success' }} btn-sm">
                                <i class="fa {{ $vendor->is_active ? 'fa-ban' : 'fa-check' }}"></i>
                                {{ $vendor->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- INFORMASI VENDOR --}}
        <div class="card">
            <div class="card-header">
                <h6>Informasi Vendor</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td width="40%" class="fw-semibold">Kode Vendor:</td>
                                <td>{{ $vendor->code }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Nama Perusahaan:</td>
                                <td>{{ $vendor->company_name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Kontak Person:</td>
                                <td>{{ $vendor->contact_person ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">NPWP:</td>
                                <td>{{ $vendor->tax_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Status:</td>
                                <td>
                                    <span class="badge {{ $vendor->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $vendor->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm">
                            <tr>
                                <td width="40%" class="fw-semibold">Email:</td>
                                <td>
                                    @if($vendor->email)
                                    <i class="fa fa-envelope text-muted me-1"></i>
                                    {{ $vendor->email }}
                                    @else - @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Telepon:</td>
                                <td>
                                    @if($vendor->phone)
                                    <i class="fa fa-phone text-muted me-1"></i>
                                    {{ $vendor->phone }}
                                    @else - @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Alamat:</td>
                                <td>{{ $vendor->address ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Syarat Pembayaran:</td>
                                <td>{{ $vendor->payment_terms ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Terdaftar Sejak:</td>
                                <td>{{ $vendor->created_at->format('d M Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- MATERIAL VENDOR --}}
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Material yang Disediakan</h6>
                    <button class="btn btn-primary btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#addMaterialModal">
                        <i class="fa fa-plus"></i> Tambah Material
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($vendor->items && $vendor->items->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode Material</th>
                                <th>Nama Material</th>
                                <th>Harga Vendor</th>
                                <th>Min. Order</th>
                                <th>Lead Time</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vendor->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $item->material->code ?? '-' }}
                                    </span>
                                </td>
                                <td>{{ $item->material->name ?? '-' }}</td>
                                <td>Rp {{ number_format($item->vendor_price, 0, ',', '.') }}</td>
                                <td>{{ $item->minimum_order }} {{ $item->unit }}</td>
                                <td>{{ $item->lead_time ?? '-' }} hari</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning"
                                            onclick="editMaterialPrice({{ $item->id }})">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <form action="{{ route('vendor.remove-material', ['vendorId' => $vendor->id, 'itemId' => $item->id]) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus material ini dari vendor?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> 
                    Vendor ini belum memiliki material.
                </div>
                @endif
            </div>
        </div>
        
    </div>
</div>

{{-- GANTI SEMUA KODE MODAL DENGAN INI --}}
<div class="modal fade" id="addMaterialModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Material</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('vendor.add-material') }}">
                @csrf
                <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Material</label>
                        <select name="material_id" class="form-control" required>
                            <option value="">Pilih Material</option>
                            @foreach($materials as $mat)
                            <option value="{{ $mat->id }}">{{ $mat->code }} - {{ $mat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label>Harga</label>
                        <input type="number" name="vendor_price" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label>Satuan</label>
                        <select name="unit" class="form-control" required>
                            <option value="pcs">Pcs</option>
                            <option value="kg">Kg</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label>Min. Order</label>
                        <input type="number" name="minimum_order" class="form-control" value="1" required>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editMaterialPrice(itemId) {
    const newPrice = prompt('Masukkan harga baru:');
    if (newPrice && !isNaN(newPrice)) {
        fetch(`/vendor/update-material/${itemId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ vendor_price: newPrice })
        })
        .then(() => location.reload());
    }
}
</script>
@endsection