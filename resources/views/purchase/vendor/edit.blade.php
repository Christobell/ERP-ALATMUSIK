@extends('layouts.master')

@section('title', 'Edit Vendor')
@section('content')
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        
        <div class="card">
            <div class="card-header">
                <h5>
                    <a href="{{ route('vendor.index') }}" class="text-muted me-2">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                    Edit Vendor: {{ $vendor->company_name }}
                </h5>
            </div>
            <div class="card-block">
                <form action="{{ route('vendor.update', $vendor->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kode Vendor</label>
                        <div class="col-sm-10">
                            <input type="text" name="code" class="form-control" 
                                   value="{{ old('code', $vendor->code) }}" required>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nama Perusahaan</label>
                        <div class="col-sm-10">
                            <input type="text" name="company_name" class="form-control" 
                                   value="{{ old('company_name', $vendor->company_name) }}" required>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Kontak Person</label>
                        <div class="col-sm-10">
                            <input type="text" name="contact_person" class="form-control" 
                                   value="{{ old('contact_person', $vendor->contact_person) }}">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Email</label>
                                <div class="col-sm-8">
                                    <input type="email" name="email" class="form-control" 
                                           value="{{ old('email', $vendor->email) }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Telepon</label>
                                <div class="col-sm-8">
                                    <input type="text" name="phone" class="form-control" 
                                           value="{{ old('phone', $vendor->phone) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">NPWP</label>
                        <div class="col-sm-10">
                            <input type="text" name="tax_number" class="form-control" 
                                   value="{{ old('tax_number', $vendor->tax_number) }}">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <textarea name="address" class="form-control" rows="2">{{ old('address', $vendor->address) }}</textarea>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Syarat Pembayaran</label>
                        <div class="col-sm-10">
                            <textarea name="payment_terms" class="form-control" rows="2">{{ old('payment_terms', $vendor->payment_terms) }}</textarea>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" 
                                       id="is_active" value="1" {{ $vendor->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Vendor Aktif
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('vendor.index') }}" class="btn btn-secondary">
                                <i class="fa fa-times"></i> Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>
@endsection