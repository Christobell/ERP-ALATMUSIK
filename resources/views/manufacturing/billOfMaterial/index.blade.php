@extends('layouts.master')

@section('title', 'Material')
@section('content')
    <div class="pcoded-content">
        <div class="pcoded-inner-content">

            <div class="card">
                <div class="card-header">
                    <h5>Tambah Material ke BOM</h5>
                </div>

                <div class="card-block">
                    <form action="{{ route('bom.store') }}" method="POST">
                        @csrf

                        {{-- PRODUCT --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Product</label>
                            <div class="col-sm-10">
                                <select name="product_id" class="form-control" required
                                    onchange="window.location='?product_id='+this.value">
                                    <option value="">-- Pilih Product --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ $product_id == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="product_id" value="{{ $product_id }}">

                        {{-- MATERIAL --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Material</label>
                            <div class="col-sm-10">
                                <select name="material_id" class="form-control" required>
                                    <option value="">-- Pilih Material --</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}">
                                            {{ $material->name }} - Rp {{ number_format($material->price) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- QUANTITY --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Quantity</label>
                            <div class="col-sm-10">
                                <input type="number" step="0.01" name="quantity" class="form-control"
                                    placeholder="Contoh: 2.5" required>
                            </div>
                        </div>

                        {{-- UNIT --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Unit</label>
                            <div class="col-sm-10">
                                <input type="text" name="unit" class="form-control" placeholder="pcs / kg / meter"
                                    required>
                            </div>
                        </div>

                        {{-- SUBMIT --}}
                        <div class="form-group row">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Tambahkan ke BOM
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            @if ($bom)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Detail Bill Of Material</h5>
                        <span>Product: <strong>{{ $bom->product->name }}</strong></span>
                    </div>

                    <div class="card-block table-border-style">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Material</th>
                                        <th>Qty</th>
                                        <th>Unit</th>
                                        <th>Harga</th>
                                        <th>Subtotal</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($bom->bomItem as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td class="fw-semibold">
                                                {{ $item->material->name }}
                                            </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $item->unit }}
                                                </span>
                                            </td>
                                            <td>
                                                Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <strong>
                                                    Rp {{ number_format($item->subtotal_price, 0, ',', '.') }}
                                                </strong>
                                            </td>
                                            <td class="text-center">
                                                <form action="{{ route('bom.destroy', $item->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus material ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-outline-danger btn-sm">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                Belum ada material
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                                {{-- TOTAL --}}
                                <tfoot>
                                    <tr>
                                        <th colspan="5" class="text-end">Total BOM</th>
                                        <th colspan="2">
                                            Rp {{ number_format($bom->total_price, 0, ',', '.') }}
                                        </th>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
