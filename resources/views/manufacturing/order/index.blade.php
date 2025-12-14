@extends('layouts.master')

@section('title', 'Manufacturing Order')

@section('content')
    <div class="pcoded-content">
        <div class="pcoded-inner-content">

            {{-- CREATE MO --}}
            <div class="card">
                <div class="card-header">
                    <h5>Buat Manufacturing Order</h5>
                </div>

                <div class="card-block">
                    <form action="{{ route('mo.store') }}" method="POST">
                        @csrf

                        {{-- PRODUCT --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Product</label>
                            <div class="col-sm-10">
                                <select name="product_id" class="form-control" required>
                                    <option value="">-- Pilih Product --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- BOM --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">BOM</label>
                            <div class="col-sm-10">
                                <select name="bom_id" class="form-control" required>
                                    <option value="">-- Pilih BOM --</option>
                                    @foreach ($boms as $bom)
                                        <option value="{{ $bom->id }}">
                                            {{ $bom->product->name }} |
                                            Total: Rp {{ number_format($bom->total_price, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- QUANTITY --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Quantity Produksi</label>
                            <div class="col-sm-10">
                                <input type="number" name="quantity" class="form-control" min="1" required>
                            </div>
                        </div>

                        {{-- SUBMIT --}}
                        <div class="form-group row">
                            <div class="col-sm-10 offset-sm-2">
                                <button class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Buat MO
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- LIST MO --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Daftar Manufacturing Order</h5>
                </div>

                <div class="card-block table-border-style">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Dibuat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($orders as $index => $order)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="fw-semibold">
                                            {{ $order->product->name }}
                                        </td>
                                        <td>{{ $order->quantity }}</td>
                                        <td>
                                            @php
                                                $badge = match ($order->status) {
                                                    'draft' => 'secondary',
                                                    'confirmed' => 'info',
                                                    'in_progress' => 'warning',
                                                    'done' => 'success',
                                                    'cancelled' => 'danger',
                                                    default => 'dark',
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $badge }}">
                                                {{ strtoupper($order->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $order->created_at->format('d M Y') }}
                                        </td>
                                        <td class="text-center">

                                            {{-- DRAFT --}}
                                            @if ($order->status === 'draft')
                                                <form action="{{ route('mo.updateStatus', $order->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="confirmed">
                                                    <button class="btn btn-outline-primary btn-sm">
                                                        Confirm
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- CONFIRMED --}}
                                            @if ($order->status === 'confirmed')
                                                <form action="{{ route('mo.updateStatus', $order->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="in_progress">
                                                    <button class="btn btn-outline-warning btn-sm">
                                                        Start
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- IN PROGRESS --}}
                                            @if ($order->status === 'in_progress')
                                                <form action="{{ route('mo.updateStatus', $order->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="done">
                                                    <button class="btn btn-outline-success btn-sm">
                                                        Finish
                                                    </button>
                                                </form>
                                            @endif

                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            Belum ada Manufacturing Order
                                        </td>
                                    </tr>
                                @endforelse


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
