<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order {{ $purchaseOrder->po_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header h3 { margin: 5px 0; color: #666; font-size: 18px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .info-table td { padding: 8px; border: 1px solid #ddd; }
        .info-table .label { font-weight: bold; background: #f5f5f5; width: 30%; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; text-align: left; }
        .items-table td { padding: 10px; border: 1px solid #ddd; }
        .footer { margin-top: 50px; }
        .signature { float: right; text-align: center; width: 200px; }
        .total { text-align: right; font-weight: bold; font-size: 16px; }
        .status-badge { 
            padding: 5px 10px; 
            border-radius: 4px; 
            font-weight: bold;
            display: inline-block;
        }
        .approved { background: #d4edda; color: #155724; }
        .pending { background: #fff3cd; color: #856404; }
        .draft { background: #e2e3e5; color: #383d41; }
        .rejected { background: #f8d7da; color: #721c24; }
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PURCHASE ORDER</h1>
        <h3>No: {{ $purchaseOrder->po_number }}</h3>
        <p>Tanggal: {{ $purchaseOrder->order_date->format('d F Y') }}</p>
        <div class="status-badge {{ $purchaseOrder->status }}">
            Status: {{ strtoupper($purchaseOrder->status) }}
        </div>
    </div>
    
    <table class="info-table">
        <tr>
            <td class="label">Kepada (Vendor):</td>
            <td><strong>{{ $purchaseOrder->vendor_name }}</strong></td>
        </tr>
        <tr>
            <td class="label">Kontak Person:</td>
            <td>{{ $purchaseOrder->contact_person ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Telepon:</td>
            <td>{{ $purchaseOrder->vendor_phone ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat Pengiriman:</td>
            <td>{{ $purchaseOrder->delivery_address }}</td>
        </tr>
    </table>
    
    <h3>Daftar Barang/Jasa:</h3>
    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Kode Material</th>
                <th width="25%">Nama Material</th>
                <th width="10%">Qty</th>
                <th width="10%">Satuan</th>
                <th width="10%">Harga Satuan</th>
                <th width="10%">Jumlah</th>
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
                    <td>{{ $item['material_code'] ?? '' }}</td>
                    <td>{{ $item['material_name'] ?? '' }}</td>
                    <td>{{ $item['quantity'] ?? 0 }}</td>
                    <td>{{ $item['material_unit'] ?? '' }}</td>
                    <td>Rp {{ number_format($item['unit_price'] ?? 0, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item['total_price'] ?? 0, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" style="text-align: center;">No items data</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="total">TOTAL:</td>
                <td class="total">Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
    
    @if($purchaseOrder->notes)
    <div style="margin-bottom: 30px;">
        <h4>Catatan:</h4>
        <p>{{ $purchaseOrder->notes }}</p>
    </div>
    @endif
    
    <div class="footer">
        <div class="signature">
            <p>Disetujui oleh,</p>
            <br><br><br>
            <p>_________________________</p>
            <p>Manager/PIC</p>
        </div>
        <div style="clear: both;"></div>
    </div>
    
    <div class="no-print" style="margin-top: 30px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer;">
            🖨️ Print Halaman Ini
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #dc3545; color: white; border: none; cursor: pointer; margin-left: 10px;">
            ✕ Tutup
        </button>
    </div>
    
    <script>
        window.onload = function() {
            // Auto print jika diperlukan
            // setTimeout(function() { window.print(); }, 1000);
        };
    </script>
</body>
</html>