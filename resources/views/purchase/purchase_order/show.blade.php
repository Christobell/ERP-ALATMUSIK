@extends('layouts.master')

@section('title', 'Detail Purchase Order: ' . $purchaseOrder->po_number)
@section('content')
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        
        <div class="card">
            <div class="card-header">
                <h5>
                    <i class="fa fa-file-invoice text-primary"></i> 
                    Purchase Order: {{ $purchaseOrder->po_number }}
                </h5>
                <div class="card-header-right">
                    <div class="btn-group">
                        <a href="{{ route('purchase-orders.print', $purchaseOrder->id) }}" 
                           target="_blank" class="btn btn-secondary btn-sm">
                            <i class="fa fa-print"></i> Print
                        </a>
                        <a href="{{ route('purchase-orders.index') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card-block">
                {{-- STATUS & ACTION BUTTONS --}}
                <div class="row mb-4">
                    <div class="col-md-8">
                        @php
                            $statusColors = [
                                'draft' => 'secondary',
                                'pending' => 'warning',
                                'approved' => 'success',
                                'completed' => 'info',
                                'rejected' => 'danger',
                                'cancelled' => 'dark',
                            ];
                            $statusLabels = [
                                'draft' => 'Draft',
                                'pending' => 'Pending Approval',
                                'approved' => 'Approved',
                                'completed' => 'Completed',
                                'rejected' => 'Rejected',
                                'cancelled' => 'Cancelled',
                            ];
                        @endphp
                        
                        <div class="d-flex align-items-center">
                            <h4 class="mb-0">
                                <span class="badge bg-{{ $statusColors[$purchaseOrder->status] }} p-2">
                                    {{ $statusLabels[$purchaseOrder->status] }}
                                </span>
                            </h4>
                            
                            <div class="ms-3">
                                @if($purchaseOrder->status == 'draft')
                                    <form action="{{ route('purchase-orders.submit', $purchaseOrder->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            <i class="fa fa-paper-plane"></i> Submit untuk Approval
                                        </button>
                                    </form>
                                @endif
                                
                                @if(auth()->user()->can_approve_po && $purchaseOrder->status == 'pending')
                                    <form action="{{ route('purchase-orders.approve', $purchaseOrder->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fa fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('purchase-orders.reject', $purchaseOrder->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa fa-times"></i> Reject
                                        </button>
                                    </form>
                                @endif
                                
                                @if($purchaseOrder->status == 'approved')
                                    <form action="{{ route('purchase-orders.complete', $purchaseOrder->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-info btn-sm">
                                            <i class="fa fa-check-double"></i> Mark as Completed
                                        </button>
                                    </form>
                                @endif
                                
                                @if(in_array($purchaseOrder->status, ['draft', 'pending', 'approved']))
                                    <form action="{{ route('purchase-orders.cancel', $purchaseOrder->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-dark btn-sm" 
                                                onclick="return confirm('Batalkan PO ini?')">
                                            <i class="fa fa-ban"></i> Cancel PO
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 text-end">
                        <div class="btn-group">
                            @if(in_array($purchaseOrder->status, ['draft', 'pending']))
                                <a href="{{ route('purchase-orders.edit', $purchaseOrder->id) }}" 
                                   class="btn btn-primary">
                                    <i class="fa fa-edit"></i> Edit PO
                                </a>
                            @endif
                            
                            @if(in_array($purchaseOrder->status, ['draft', 'pending']))
                                <form action="{{ route('purchase-orders.destroy', $purchaseOrder->id) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" 
                                            onclick="return confirm('Hapus PO ini?')">
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                
                {{-- INFORMASI PO --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fa fa-info-circle"></i> Informasi PO</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">No. PO</th>
                                        <td>{{ $purchaseOrder->po_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal PO</th>
                                        <td>{{ \Carbon\Carbon::parse($purchaseOrder->order_date)->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Delivery Date</th>
                                        <td>
                                            @if($purchaseOrder->delivery_date)
                                                {{ \Carbon\Carbon::parse($purchaseOrder->delivery_date)->format('d/m/Y') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Dibuat Oleh</th>
                                        <td>
                                            {{ $purchaseOrder->creator->name ?? '-' }}
                                            <br>
                                            <small class="text-muted">
                                                {{ $purchaseOrder->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fa fa-building"></i> Informasi Vendor</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Vendor</th>
                                        <td>
                                            <strong>{{ $purchaseOrder->vendor->company_name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $purchaseOrder->vendor->code }}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Kontak Person</th>
                                        <td>{{ $purchaseOrder->vendor->contact_person ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Telepon/Email</th>
                                        <td>
                                            @if($purchaseOrder->vendor->phone)
                                                <i class="fa fa-phone text-muted"></i> {{ $purchaseOrder->vendor->phone }}
                                                <br>
                                            @endif
                                            @if($purchaseOrder->vendor->email)
                                                <i class="fa fa-envelope text-muted"></i> {{ $purchaseOrder->vendor->email }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td>{{ $purchaseOrder->vendor->address ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- ITEMS --}}
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fa fa-boxes"></i> Item Purchase Order</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Material</th>
                                                <th>Kode</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-right">Harga Satuan</th>
                                                <th class="text-right">Subtotal</th>
                                                <th>Deskripsi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($purchaseOrder->items as $index => $item)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $item->material->name }}</td>
                                                    <td>
                                                        <span class="badge bg-secondary">{{ $item->material->code }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        {{ number_format($item->quantity, 2) }} 
                                                        <small class="text-muted">{{ $item->material->unit }}</small>
                                                    </td>
                                                    <td class="text-right">
                                                        Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                                    </td>
                                                    <td class="text-right fw-bold">
                                                        Rp {{ number_format($item->total_price, 0, ',', '.') }}
                                                    </td>
                                                    <td>
                                                        @if($item->description)
                                                            <small class="text-muted">{{ $item->description }}</small>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-active">
                                            <tr>
                                                <td colspan="5" class="text-right"><strong>Subtotal</strong></td>
                                                <td class="text-right fw-bold">
                                                    Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right"><strong>Pajak (PPN 11%)</strong></td>
                                                <td class="text-right fw-bold">
                                                    Rp {{ number_format($purchaseOrder->tax_amount, 0, ',', '.') }}
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5" class="text-right"><h5 class="mb-0">Grand Total</h5></td>
                                                <td class="text-right">
                                                    <h4 class="text-primary mb-0">
                                                        Rp {{ number_format($purchaseOrder->grand_total, 0, ',', '.') }}
                                                    </h4>
                                                </td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- CATATAN & APPROVAL INFO --}}
                <div class="row mt-4">
                    @if($purchaseOrder->notes)
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fa fa-sticky-note"></i> Catatan</h6>
                                </div>
                                <div class="card-body">
                                    <p>{{ $purchaseOrder->notes }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if(in_array($purchaseOrder->status, ['approved', 'rejected', 'completed']))
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fa fa-user-check"></i> Informasi Approval</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="40%">Status</th>
                                            <td>
                                                <span class="badge bg-{{ $statusColors[$purchaseOrder->status] }}">
                                                    {{ $statusLabels[$purchaseOrder->status] }}
                                                </span>
                                            </td>
                                        </tr>
                                        @if($purchaseOrder->approver)
                                            <tr>
                                                <th>Disetujui Oleh</th>
                                                <td>{{ $purchaseOrder->approver->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Approval</th>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($purchaseOrder->approved_at)->format('d/m/Y H:i') }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if($purchaseOrder->status == 'rejected')
                                            <tr>
                                                <th>Alasan Penolakan</th>
                                                <td class="text-danger">
                                                    <i class="fa fa-exclamation-circle"></i> 
                                                    PO ditolak oleh approver
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                
                {{-- TIMELINE --}}
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fa fa-history"></i> Timeline Status</h6>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <div class="timeline-item {{ $purchaseOrder->status == 'draft' ? 'active' : '' }}">
                                        <div class="timeline-marker bg-secondary"></div>
                                        <div class="timeline-content">
                                            <h6 class="mb-0">Draft</h6>
                                            <small class="text-muted">
                                                {{ $purchaseOrder->created_at->format('d/m/Y H:i') }}
                                            </small>
                                            <p>PO dibuat oleh {{ $purchaseOrder->creator->name ?? 'User' }}</p>
                                        </div>
                                    </div>
                                    
                                    @if($purchaseOrder->status != 'draft')
                                        <div class="timeline-item {{ $purchaseOrder->status == 'pending' ? 'active' : '' }}">
                                            <div class="timeline-marker bg-warning"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-0">Pending Approval</h6>
                                                <small class="text-muted">
                                                    {{ $purchaseOrder->updated_at->format('d/m/Y H:i') }}
                                                </small>
                                                <p>Menunggu persetujuan</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if(in_array($purchaseOrder->status, ['approved', 'completed']))
                                        <div class="timeline-item {{ $purchaseOrder->status == 'approved' ? 'active' : '' }}">
                                            <div class="timeline-marker bg-success"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-0">Approved</h6>
                                                <small class="text-muted">
                                                    {{ $purchaseOrder->approved_at ? \Carbon\Carbon::parse($purchaseOrder->approved_at)->format('d/m/Y H:i') : '-' }}
                                                </small>
                                                <p>Disetujui oleh {{ $purchaseOrder->approver->name ?? 'Approver' }}</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($purchaseOrder->status == 'completed')
                                        <div class="timeline-item active">
                                            <div class="timeline-marker bg-info"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-0">Completed</h6>
                                                <p>PO telah selesai diproses</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if(in_array($purchaseOrder->status, ['rejected', 'cancelled']))
                                        <div class="timeline-item active">
                                            <div class="timeline-marker bg-danger"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-0">{{ ucfirst($purchaseOrder->status) }}</h6>
                                                <p>PO {{ $purchaseOrder->status == 'rejected' ? 'ditolak' : 'dibatalkan' }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

{{-- CSS --}}
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    padding-bottom: 20px;
}
.timeline-item:last-child {
    padding-bottom: 0;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 0 0 3px #dee2e6;
}
.timeline-item.active .timeline-marker {
    box-shadow: 0 0 0 3px #0d6efd;
}
.timeline-content {
    padding-left: 10px;
}
.table th {
    font-weight: 600;
}
</style>
@endsection