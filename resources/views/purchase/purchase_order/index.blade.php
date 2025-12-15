@extends('layouts.master')

@section('title', 'Purchase Order')
@section('content')
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        
        {{-- CARD 1: FILTER & STATISTIK --}}
        <div class="card">
            <div class="card-header">
                <h5>Filter & Statistik Purchase Order</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        <li><i class="fa fa fa-wrench open-card-option"></i></li>
                        <li><i class="fa fa-window-maximize full-card"></i></li>
                        <li><i class="fa fa-minus minimize-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block">
                <form action="{{ route('purchase-orders.index') }}" method="GET" id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status PO</label>
                                <select name="status" class="form-control">
                                    <option value="all">Semua Status</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Vendor</label>
                                <select name="vendor_id" class="form-control">
                                    <option value="">Semua Vendor</option>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->code }} - {{ $vendor->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Dari Tanggal</label>
                                <input type="date" name="start_date" class="form-control" 
                                       value="{{ request('start_date') }}">
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control" 
                                       value="{{ request('end_date') }}">
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group" style="padding-top: 25px;">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-filter"></i> Filter
                                </button>
                                <a href="{{ route('purchase-orders.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fa fa-refresh"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
                
                {{-- STATISTIK --}}
                <div class="row mt-3">
                    <div class="col-md-2 col-sm-6">
                        <div class="card stat-card bg-primary text-white">
                            <div class="card-body">
                                <h6 class="card-title">Total PO</h6>
                                <h3 class="card-text">{{ $totalPO }}</h3>
                                <small>Semua Status</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2 col-sm-6">
                        <div class="card stat-card bg-warning text-white">
                            <div class="card-body">
                                <h6 class="card-title">Pending</h6>
                                <h3 class="card-text">{{ $pendingCount }}</h3>
                                <small>Menunggu Approval</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2 col-sm-6">
                        <div class="card stat-card bg-success text-white">
                            <div class="card-body">
                                <h6 class="card-title">Approved</h6>
                                <h3 class="card-text">{{ $approvedCount }}</h3>
                                <small>Disetujui</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2 col-sm-6">
                        <div class="card stat-card bg-info text-white">
                            <div class="card-body">
                                <h6 class="card-title">Completed</h6>
                                <h3 class="card-text">{{ $completedCount }}</h3>
                                <small>Selesai</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2 col-sm-6">
                        <div class="card stat-card bg-danger text-white">
                            <div class="card-body">
                                <h6 class="card-title">Rejected</h6>
                                <h3 class="card-text">{{ $rejectedCount }}</h3>
                                <small>Ditolak</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2 col-sm-6">
                        <div class="card stat-card bg-secondary text-white">
                            <div class="card-body">
                                <h6 class="card-title">Total Nilai</h6>
                                <h3 class="card-text">Rp {{ number_format($totalValue, 0, ',', '.') }}</h3>
                                <small>Grand Total</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- CARD 2: TOMBOL AKSI CEPAT --}}
        <div class="card">
            <div class="card-header">
                <h5>Aksi Cepat</h5>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="col-md-3">
                        <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary btn-block">
                            <i class="fa fa-plus-circle"></i> Buat PO Baru
                        </a>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-info btn-block" onclick="printReport()">
                            <i class="fa fa-print"></i> Cetak Laporan
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-success btn-block" onclick="exportToExcel()">
                            <i class="fa fa-file-excel"></i> Export Excel
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-warning btn-block" onclick="showPendingPO()">
                            <i class="fa fa-clock"></i> PO Pending
                            <span class="badge bg-danger">{{ $pendingCount }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- CARD 3: TABEL DAFTAR PURCHASE ORDER --}}
        <div class="card">
            <div class="card-header">
                <h5>Daftar Purchase Order</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        <li><i class="fa fa fa-wrench open-card-option"></i></li>
                        <li><i class="fa fa-window-maximize full-card"></i></li>
                        <li><i class="fa fa-minus minimize-card"></i></li>
                        <li><i class="fa fa-refresh reload-card" onclick="location.reload()"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block table-border-style">
                <div class="table-responsive">
                    <table class="table table-hover align-middle po-table">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>No. PO</th>
                                <th>Vendor</th>
                                <th>Tanggal PO</th>
                                <th>Delivery Date</th>
                                <th>Status</th>
                                <th>Jumlah Item</th>
                                <th>Total (Rp)</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($purchaseOrders as $index => $po)
                                <tr>
                                    <td>{{ $index + 1 + (($purchaseOrders->currentPage() - 1) * $purchaseOrders->perPage()) }}</td>
                                    <td>
                                        <span class="fw-bold text-primary">{{ $po->po_number }}</span>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fa fa-user"></i> {{ $po->creator->name ?? '-' }}
                                        </small>
                                    </td>
                                    <td>
                                        <strong>{{ $po->vendor->company_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $po->vendor->code }}</small>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($po->order_date)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        @if($po->delivery_date)
                                            {{ \Carbon\Carbon::parse($po->delivery_date)->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
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
                                                'pending' => 'Pending',
                                                'approved' => 'Approved',
                                                'completed' => 'Completed',
                                                'rejected' => 'Rejected',
                                                'cancelled' => 'Cancelled',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$po->status] ?? 'secondary' }}">
                                            {{ $statusLabels[$po->status] ?? $po->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $po->items_count ?? 0 }} item
                                        </span>
                                    </td>
                                    <td class="fw-bold text-right">
                                        Rp {{ number_format($po->grand_total, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        {{-- Tombol Detail --}}
                                        <a href="{{ route('purchase-orders.show', $po->id) }}" 
                                           class="btn btn-outline-info btn-sm"
                                           title="Detail PO">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        
                                        {{-- Tombol Edit (hanya untuk draft & pending) --}}
                                        @if(in_array($po->status, ['draft', 'pending']))
                                            <a href="{{ route('purchase-orders.edit', $po->id) }}" 
                                               class="btn btn-outline-primary btn-sm"
                                               title="Edit PO">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif
                                        
                                        {{-- Tombol Print --}}
                                        <a href="{{ route('purchase-orders.print', $po->id) }}" 
                                           target="_blank"
                                           class="btn btn-outline-secondary btn-sm"
                                           title="Print PO">
                                            <i class="fa fa-print"></i>
                                        </a>
                                        
                                        {{-- Tombol Hapus (hanya untuk draft & pending) --}}
                                        @if(in_array($po->status, ['draft', 'pending']))
                                            <form action="{{ route('purchase-orders.destroy', $po->id) }}" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Hapus PO {{ $po->po_number }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                        title="Hapus PO">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        {{-- Tombol Aksi Status --}}
                                        <div class="btn-group mt-1">
                                            @if($po->status == 'draft')
                                                <form action="{{ route('purchase-orders.submit', $po->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-outline-warning btn-sm"
                                                            title="Submit untuk Approval">
                                                        <i class="fa fa-paper-plane"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if(auth()->user()->can_approve_po && $po->status == 'pending')
                                                <form action="{{ route('purchase-orders.approve', $po->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-outline-success btn-sm"
                                                            title="Approve PO">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('purchase-orders.reject', $po->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-outline-danger btn-sm"
                                                            title="Reject PO">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if($po->status == 'approved')
                                                <form action="{{ route('purchase-orders.complete', $po->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-outline-info btn-sm"
                                                            title="Mark as Completed">
                                                        <i class="fa fa-check-double"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if(in_array($po->status, ['draft', 'pending', 'approved']))
                                                <form action="{{ route('purchase-orders.cancel', $po->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-outline-dark btn-sm"
                                                            title="Cancel PO"
                                                            onclick="return confirm('Batalkan PO {{ $po->po_number }}?')">
                                                        <i class="fa fa-ban"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <div class="alert alert-info">
                                            <i class="fa fa-info-circle"></i> Belum ada Purchase Order
                                        </div>
                                        <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary">
                                            <i class="fa fa-plus"></i> Buat PO Pertama
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    {{-- Pagination --}}
                    @if($purchaseOrders->hasPages())
                    <div class="mt-3">
                        {{ $purchaseOrders->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
    </div>
</div>

{{-- Modal Quick Actions --}}
<div class="modal fade" id="quickActionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aksi Cepat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="list-group">
                    <a href="{{ route('purchase-orders.create') }}" class="list-group-item list-group-item-action">
                        <i class="fa fa-plus-circle text-primary"></i> Buat PO Baru
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" onclick="printReport()">
                        <i class="fa fa-print text-info"></i> Cetak Laporan
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" onclick="exportToExcel()">
                        <i class="fa fa-file-excel text-success"></i> Export ke Excel
                    </a>
                    <a href="{{ route('purchase-orders.index') }}?status=pending" class="list-group-item list-group-item-action">
                        <i class="fa fa-clock text-warning"></i> Lihat PO Pending
                    </a>
                    <a href="{{ route('vendor.index') }}" class="list-group-item list-group-item-action">
                        <i class="fa fa-users text-secondary"></i> Kelola Vendor
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CSS Tambahan --}}
<style>
.po-table td {
    vertical-align: middle;
}
.po-table .badge {
    font-size: 0.85em;
}
.stat-card {
    border-radius: 8px;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}
.stat-card:hover {
    transform: translateY(-5px);
}
.stat-card .card-body {
    padding: 15px;
    text-align: center;
}
.stat-card h3 {
    margin: 10px 0 5px;
    font-weight: bold;
}
.stat-card small {
    opacity: 0.9;
}
.btn-group .btn-sm {
    padding: 0.25rem 0.5rem;
    margin: 0 2px;
}
</style>

{{-- JavaScript --}}
<script>
// Fungsi untuk print laporan
function printReport() {
    const params = new URLSearchParams(window.location.search);
    window.open('{{ route("purchase-orders.index") }}?print=1&' + params.toString(), '_blank');
}

// Fungsi untuk export ke Excel
function exportToExcel() {
    const params = new URLSearchParams(window.location.search);
    params.append('export', 'excel');
    window.location.href = '{{ route("purchase-orders.index") }}?' + params.toString();
}

// Fungsi untuk menampilkan PO pending
function showPendingPO() {
    const filterForm = document.getElementById('filterForm');
    filterForm.querySelector('select[name="status"]').value = 'pending';
    filterForm.submit();
}

// Auto refresh setiap 60 detik untuk PO pending
@if(request('status') == 'pending')
    setTimeout(() => {
        location.reload();
    }, 60000); // 60 detik
@endif

// Initialize tooltips
$(document).ready(function() {
    $('[title]').tooltip();
    
    // DataTable jika diperlukan
    $('.po-table').DataTable({
        "pageLength": 10,
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Berikutnya",
                "previous": "Sebelumnya"
            }
        }
    });
});
</script>
@endsection