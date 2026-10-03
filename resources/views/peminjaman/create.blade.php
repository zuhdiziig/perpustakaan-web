<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Peminjaman Buku</title>
    <style>
        body { font-family: sans-serif; background: #f8fafc; padding: 25px; margin: 0; }
        .card { background: white; max-width: 650px; margin: auto; padding: 25px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 13px; }
        select, input { width: 100%; padding: 9px; box-sizing: border-box; border: 1px solid #cbd5e1; border-radius: 4px; }
        .barcode-entry { display: flex; gap: 8px; margin-bottom: 8px; }
        .btn { padding: 8px 14px; border-radius: 4px; cursor: pointer; border: none; font-weight: bold; }
        .btn-add { background: #e2e8f0; color: #1e293b; font-size: 12px; }
        .btn-submit { background: #2563eb; color: white; width: 100%; padding: 12px; font-size: 14px; margin-top: 10px; }
        .btn-del { background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 4px; margin-bottom: 15px; font-size: 13px; }
        .info-box { background: #eff6ff; color: #1e40af; padding: 10px; border-radius: 4px; font-size: 12px; margin-bottom: 15px; }
    </style>
</head>
<body>

<div class="card">
    <h2 style="margin-top: 0;">Transaksi Peminjaman Buku</h2>

    <div class="info-box">
        <strong>Aturan Peminjaman:</strong>
        Maksimal 7 buku per member. Batas waktu pinjam adalah 1 bulan dari hari ini.
    </div>

    @if ($errors->any())
        <div class="alert-error">
            @foreach ($errors->all() as$err)
                <div>• {{ $err }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('peminjaman.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Pilih Member Peminjam</label>
            <select name="idUserMember" required>
                <option value="">-- Pilih Member Terdaftar --</option>
                @foreach ($members as$m)
                    <option value="{{ $m->id }}" {{ old('idUserMember') == $m->id ? 'selected' : '' }}>
                        {{ $m->name }} ({{$m->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                <label style="margin: 0;">Scan / Masukkan Kode Barcode Buku</label>
                <button type="button" class="btn btn-add" onclick="tambahInputBarcode()">+ Tambah Barcode</button>
            </div>
            <div id="barcodeContainer">
                <div class="barcode-entry">
                    <input type="text" name="barcodes[]" placeholder="Contoh: BK-IT-001" required autofocus>
                </div>
            </div>
            <small style="color: #64748b;">Gunakan scanner barcode fisik atau ketik langsung kode barcode buku.</small>
        </div>

        <button type="submit" class="btn btn-submit">Konfirmasi & Simpan Peminjaman</button>
        <a href="{{ route('peminjaman.index') }}" style="display:block; text-align:center; margin-top:12px; color:#64748b; text-decoration:none; font-size:13px;">Batal</a>
    </form>
</div>

<script>
    function tambahInputBarcode() {
        const container = document.getElementById('barcodeContainer');
        const total = container.getElementsByClassName('barcode-entry').length;
        if (total >= 7) {
            alert('Maksimal peminjaman adalah 7 buku.');
            return;
        }

        const div = document.createElement('div');
        div.className = 'barcode-entry';
        div.innerHTML = `
            <input type="text" name="barcodes[]" placeholder="Contoh: BK-IT-00${total + 1}" required>
            <button type="button" class="btn btn-del" onclick="this.parentElement.remove()">Hapus</button>
        `;
        container.appendChild(div);
    }
</script>

</body>
</html>