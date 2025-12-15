<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Purchase Order {{ $purchaseOrder->po_number }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; font-size: 12px; }
        .container { width: 210mm; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header h2 { margin: 0; font-size: 18px; color: #666; }
        .company-info { float: left; width: 50%; }
        .po-info { float: right; width: 50%; text-align: right; }
        .clear { clear: both; }
        .section { margin: 20px 0; }
        .section-title { background: #f5f5f5; padding: 8px; font-weight: bold; border-left: 4px solid #007bff; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f8f9fa; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-table { width: 50%; float: right; }
        .footer { margin-top: 50px; border-top: 1px solid #ddd; padding-top: 20px; }
        .signature { width: 30%; float: left; text-align: center; }
        @media print {
            .no-print { display: none; }
            body { font-size: 11px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>PURCHASE ORDER</h1>
            <h2>{{ $purchaseOrder->po_number }}</h2>
        </div>
        
        <div class="company-info">
            <h3>PT. PERUSAHAAN ANDA</h3>
            <p>Jl. Contoh No. 123, Jakarta<br>
               Phone: (021) 123-4567<br>
               Email: company@example.com<br>
               NPWP: 12.345.678.9-012.345</p>
        </div>
        
        <div class="po-info">
            <table>
                <tr>
                    <td><strong>No. PO</strong></td>
                    <td>{{ $purchaseOrder->po_number }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal PO</strong></td>
                    <td>{{ \Carbon\Carbon::parse($purchaseOrder->order_date)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td><strong>Delivery Date</strong></td>
                    <td>
                        @if($purchaseOrder->delivery_date)
                            {{ \Carbon\Carbon::parse($purchaseOrder->delivery_date)->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>Status</strong></td>
                    <td>{{ strtoupper($purchaseOrder->status) }}</td>
                </tr>
            </table>
        </div>
        
        <div class="clear"></div>
        
        <div class="section">
            <div class="section-title">KEPADA:</div>
            <table>
                <tr>
                    <td width="30%">Vendor</td>
                    <td>{{ $purchaseOrder->vendor->company_name }}</td>
                </tr>
                <tr>
                    <td>Kontak Person</td>
                    <td>{{ $purchaseOrder->vendor->contact_person ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>{{ $purchaseOrder->vendor->address ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Telepon/Email</td>
                    <td>
                        @if($purchaseOrder->vendor->phone)
                            {{ $purchaseOrder->vendor->phone }}
                        @endif
                        @if($purchaseOrder->vendor->email)
                            / {{ $purchaseOrder->vendor->email }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>NPWP</td>
                    <td>{{ $purchaseOrder->vendor->tax_number ?? '-' }}</td>
                </tr>
            </table>
        </div>
        
        <div class="section">
            <div class="section-title">DAFTAR ITEM:</div>
            <table>
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="15%">Kode</th>
                        <th width="30%">Nama Material</th>
                        <th width="10%" class="text-center">Qty</th>
                        <th width="10%" class="text-center">Satuan</th>
                        <th width="15%" class="text-right">Harga Satuan</th>
                        <th width="15%" class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseOrder->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->material->code }}</td>
                        <td>{{ $item->material->name }}</td>
                        <td class="text-center">{{ number_format($item->quantity, 2) }}</td>
                        <td class="text-center">{{ $item->material->unit }}</td>
                        <td class="text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="total-table">
                <table>
                    <tr>
                        <td><strong>Subtotal</strong></td>
                        <td class="text-right">Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Pajak (11%)</strong></td>
                        <td class="text-right">Rp {{ number_format($purchaseOrder->tax_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="total-row">
                        <td><strong>GRAND TOTAL</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($purchaseOrder->grand_total, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>
            <div class="clear"></div>
        </div>
        
        @if($purchaseOrder->notes)
        <div class="section">
            <div class="section-title">CATATAN:</div>
            <p>{{ $purchaseOrder->notes }}</p>
        </div>
        @endif
        
        <div class="section">
            <div class="section-title">SYARAT & KETENTUAN:</div>
            <ol>
                <li>Barang harus sesuai dengan spesifikasi yang diminta</li>
                <li>Pengiriman sesuai dengan tanggal yang disepakati</li>
                <li>Pembayaran sesuai dengan syarat yang telah disepakati</li>
                <li>Invoice harus menyertakan nomor PO ini</li>
            </ol>
        </div>
        
        <div class="footer">
            <div class="signature">
                <p>Dibuat oleh,</p>
                <br><br><br>
                <p><strong>{{ $purchaseOrder->creator->name ?? '-' }}</strong></p>
                <p>Purchasing Staff</p>
            </div>
            
            <div class="signature">
                <p>Disetujui oleh,</p>
                <br><br><br>
                <p><strong>{{ $purchaseOrder->approver->name ?? '-' }}</strong></p>
                <p>Purchasing Manager</p>
            </div>
            
            <div class="signature">
                <p>Diterima oleh,</p>
                <br><br><br>
                <p><strong>____________________</strong></p>
                <p>Vendor</p>
            </div>
            <div class="clear"></div>
        </div>
        
        <div class="no-print text-center" style="margin-top: 30px;">
            <button onclick="window.print()" class="btn btn-primary">Print</button>
            <button onclick="window.close()" class="btn btn-secondary">Tutup</button>
        </div>
    </div>
</body>
</html>