@extends('layouts.master')

@section('title', 'Detail Purchase Order')
@section('content')
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        
        <div class="card">
            <div class="card-header">
                <h5>Detail Purchase Order: {{ $purchaseOrder->po_number }}</h5>
                <div class="card-header-right">
                    <a href="{{ route('purchase-order.edit', $purchaseOrder->id) }}" class="btn btn-warning">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <a href="{{ route('purchase-order.print', $purchaseOrder->id) }}" class="btn btn-secondary" target="_blank">
                        <i class="fa fa-print"></i> Print
                    </a>
                    <a href="{{ route('purchase-order.index') }}" class="btn btn-default">
                        <i class="fa fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Nomor PO</th>
                                <td>{{ $purchaseOrder->po_number }}</td>
                            </tr>
                            <tr>
                                <th>Vendor</th>
                                <td>{{ $purchaseOrder->vendor_name }}</td>
                            </tr>
                            <tr>
                                <th>Kontak Person</th>
                                <td>{{ $purchaseOrder->contact_person ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Telepon</th>
                                <td>{{ $purchaseOrder->vendor_phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal PO</th>
                                <td>{{ $purchaseOrder->order_date->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Total Amount</th>
                                <td><strong>Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</strong></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge {{ $purchaseOrder->status == 'approved' ? 'bg-success' : ($purchaseOrder->status == 'pending' ? 'bg-warning' : ($purchaseOrder->status == 'rejected' ? 'bg-danger' : 'bg-secondary')) }}">
                                        {{ ucfirst($purchaseOrder->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Alamat Pengiriman</th>
                                <td>{{ $purchaseOrder->delivery_address }}</td>
                            </tr>
                            <tr>
                                <th>Catatan</th>
                                <td>{{ $purchaseOrder->notes ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <h5 class="mt-4">Daftar Items</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode Material</th>
                                <th>Nama Material</th>
                                <th>Quantity</th>
                                <th>Satuan</th>
                                <th>Harga Satuan</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($purchaseOrder->items)
                                @php
                                    $items = json_decode($purchaseOrder->items, true);
                                @endphp
                                @foreach($items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item['material_code'] ?? '-' }}</td>
                                    <td>{{ $item['material_name'] ?? '-' }}</td>
                                    <td>{{ $item['quantity'] ?? 0 }}</td>
                                    <td>{{ $item['material_unit'] ?? '-' }}</td>
                                    <td>Rp {{ number_format($item['unit_price'] ?? 0, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item['total_price'] ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada items</td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6" class="text-end">Total Amount:</th>
                                <th>Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="mt-3">
                    @if($purchaseOrder->status == 'pending')
                    <form action="{{ route('purchase-order.approve', $purchaseOrder->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-check"></i> Approve PO
                        </button>
                    </form>
                    @endif
                    
                    <form action="{{ route('purchase-order.destroy', $purchaseOrder->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus PO ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-trash"></i> Hapus PO
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection