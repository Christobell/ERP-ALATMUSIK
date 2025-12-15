@extends('layouts.master')

@section('title', 'Update Material')

@section('content')
<div class="pcoded-content">
    <div class="pcoded-inner-content">

        <div class="card">
            <div class="card-header">
                <h5>
                    <i class="fa fa-edit text-primary"></i> Update Material: {{ $material->code }}
                </h5>
                <div class="card-header-right">
                    <a href="{{ route('material.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="card-block">
                <form action="{{ route('material.update', $material->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    {{-- VALIDATION ERRORS --}}
                    @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <h6><i class="fa fa-exclamation-triangle"></i> Terdapat kesalahan:</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- SUCCESS MESSAGE --}}
                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            <i class="fa fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nama Material <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Masukkan nama material" 
                                   value="{{ old('name', $material->name) }}" 
                                   required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kode Material <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                   placeholder="Contoh: MAT-001" 
                                   value="{{ old('code', $material->code) }}" 
                                   required>
                            @error('code')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="text-muted">Kode harus unik dan tidak boleh sama dengan material lain</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Harga Material <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="price" 
                                       class="form-control @error('price') is-invalid @enderror" 
                                       placeholder="Masukkan harga" 
                                       value="{{ old('price', $material->price) }}" 
                                       min="0" step="0.01" required>
                            </div>
                            @error('price')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Stok <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                            <input type="number" name="stock" 
                                   class="form-control @error('stock') is-invalid @enderror" 
                                   value="{{ old('stock', $material->stock) }}" 
                                   placeholder="Masukkan jumlah stok" 
                                   min="0" required>
                            @error('stock')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Foto Material</label>
                        <div class="col-sm-10">
                            {{-- Preview foto lama --}}
                            @if ($material->image)
                                <div class="mb-3">
                                    <p class="mb-1"><strong>Foto Saat Ini:</strong></p>
                                    <div class="d-flex align-items-start">
                                        <img src="{{ Storage::disk('public')->exists($material->image) ? asset('storage/' . $material->image) : 'https://via.placeholder.com/120x120?text=No+Image' }}" 
                                             width="120" height="120" 
                                             class="rounded border me-3" 
                                             style="object-fit: cover;"
                                             alt="{{ $material->name }}">
                                        <div>
                                            <p class="mb-1"><small>Path: {{ $material->image }}</small></p>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="deleteImage" name="delete_image" value="1">
                                                <label class="form-check-label text-danger" for="deleteImage">
                                                    <i class="fa fa-trash"></i> Hapus foto ini
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="mb-3">
                                    <p class="text-muted"><i class="fa fa-image"></i> Tidak ada foto material</p>
                                </div>
                            @endif

                            {{-- Input upload baru --}}
                            <div class="mb-2">
                                <label class="form-label"><strong>Upload Foto Baru:</strong></label>
                                <input type="file" name="image" 
                                       class="form-control @error('image') is-invalid @enderror" 
                                       accept="image/jpeg,image/png,image/jpg,image/gif">
                                @error('image')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i>
                                <strong>Catatan:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Kosongkan jika tidak ingin mengganti foto</li>
                                    <li>Format: JPEG, PNG, JPG, GIF</li>
                                    <li>Ukuran maksimal: 2MB</li>
                                    <li>Centang "Hapus foto ini" untuk menghapus foto tanpa upload baru</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Update Material
                            </button>
                            <button type="reset" class="btn btn-warning">
                                <i class="fa fa-redo"></i> Reset
                            </button>
                            <a href="{{ route('material.index') }}" class="btn btn-secondary">
                                <i class="fa fa-times"></i> Batal
                            </a>
                        </div>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

{{-- JS VALIDATION --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const deleteCheckbox = document.getElementById('deleteImage');
    const fileInput = document.querySelector('input[name="image"]');
    
    // Validasi sebelum submit
    form.addEventListener('submit', function(e) {
        const price = document.querySelector('input[name="price"]').value;
        const stock = document.querySelector('input[name="stock"]').value;
        
        if (price <= 0) {
            e.preventDefault();
            alert('Harga harus lebih dari 0!');
            return false;
        }
        
        if (stock < 0) {
            e.preventDefault();
            alert('Stok tidak boleh negatif!');
            return false;
        }
        
        return true;
    });
    
    // Jika centang hapus gambar, disable file input
    if (deleteCheckbox) {
        deleteCheckbox.addEventListener('change', function() {
            if (this.checked) {
                fileInput.disabled = true;
                fileInput.value = '';
            } else {
                fileInput.disabled = false;
            }
        });
    }
    
    // Preview gambar sebelum upload
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validasi ukuran (2MB = 2097152 bytes)
                if (file.size > 2097152) {
                    alert('Ukuran file maksimal 2MB!');
                    this.value = '';
                    return;
                }
                
                // Validasi tipe file
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Format file harus JPEG, PNG, JPG, atau GIF!');
                    this.value = '';
                    return;
                }
            }
        });
    }
});
</script>

<style>
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.card-header h5 {
    margin: 0;
}
.input-group-text {
    background-color: #f8f9fa;
}
.alert ul {
    padding-left: 20px;
}
.form-check-input:checked {
    background-color: #dc3545;
    border-color: #dc3545;
}
</style>
@endsection