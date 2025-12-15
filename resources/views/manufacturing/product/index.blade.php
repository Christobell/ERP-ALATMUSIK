@extends('layouts.master')

@section('title', 'Material')
@section('content')
    <div class="pcoded-content">
        <div class="pcoded-inner-content">

            <div class="card">
                <div class="card-header">
                    <h5>Tambah Produk</h5>
                </div>

                <div class="card-block">
                    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Nama Produk</label>
                            <div class="col-sm-10">
                                <input type="text" name="name" class="form-control"
                                    placeholder="Masukkan nama produk" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Kode Produk</label>
                            <div class="col-sm-10">
                                <input type="text" name="code" class="form-control" placeholder="Contoh: MAT-001"
                                    required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Harga Produk</label>
                            <div class="col-sm-10">
                                <input type="number" name="price" class="form-control" placeholder="Masukkan harga"
                                    required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Stock</label>
                            <div class="col-sm-10">
                                <input type="number" name="stock" class="form-control" placeholder="Masukkan jumlah stok"
                                    required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Foto Produk</label>
                            <div class="col-sm-10">
                                <input type="file" name="image" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Simpan
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
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
                        <table class="table table-hover align-middle material-table">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nama Produk</th>
                                    <th>Kode</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Foto</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $index => $data)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="fw-semibold">{{ $data->name }}</td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $data->code }}</span>
                                        </td>
                                        <td>Rp {{ number_format($data->price, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge {{ $data->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $data->stock }}
                                            </span>
                                        </td>
                                        <td>
                                            <img src="{{ asset('storage/' . $data->image) }}" class="material-img"
                                                alt="Material Image">
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('product.show', $data->id) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <form action="{{ route('product.destroy', $data->id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Yakin hapus data?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
