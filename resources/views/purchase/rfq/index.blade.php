@extends('layouts.master')

@section('title', 'RFQ (Request for Quotation)')
@section('content')
<div class="pcoded-content">
    <div class="pcoded-inner-content">
        
        <div class="card">
            <div class="card-header">
                <h5>RFQ (Request for Quotation)</h5>
                <div class="card-header-right">
                    <a href="{{ route('rfq.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Buat RFQ Baru
                    </a>
                </div>
            </div>
            <div class="card-block table-border-style">
                <div class="table-responsive">
                    <table class="table table-hover align-middle rfq-table">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nomor RFQ</th>
                                <th>Judul</th>
                                <th>Tanggal Request</th>
                                <th>Deadline</th>
                                <th>Budget</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rfqs as $index => $rfq)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $rfq->rfq_number }}</span>
                                    </td>
                                    <td class="fw-semibold">{{ $rfq->title }}</td>
                                    <td>{{ $rfq->request_date->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="{{ $rfq->deadline_date < now() ? 'text-danger' : '' }}">
                                            {{ $rfq->deadline_date->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td>Rp {{ number_format($rfq->estimated_budget, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $rfq->status_color }}">
                                            {{ ucfirst(str_replace('_', ' ', $rfq->status)) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('rfq.show', $rfq->id) }}" class="btn btn-outline-info btn-sm" title="Detail">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('rfq.edit', $rfq->id) }}" class="btn btn-outline-primary btn-sm" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="{{ route('rfq.print', $rfq->id) }}" class="btn btn-outline-secondary btn-sm" title="Print" target="_blank">
                                            <i class="fa fa-print"></i>
                                        </a>
                                        <form action="{{ route('rfq.destroy', $rfq->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus RFQ {{ $rfq->rfq_number }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                        @if($rfq->status == 'draft')
                                        <form action="{{ route('rfq.send', $rfq->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm" title="Kirim ke Vendor">
                                                <i class="fa fa-paper-plane"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    @if($rfqs->hasPages())
                    <div class="mt-3">
                        {{ $rfqs->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection