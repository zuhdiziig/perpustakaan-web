<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Pengembalian Buku (QR Code & Barcode)</title>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8fafc; padding: 25px; margin: 0; color: #1e293b; }
        .card { background: white; max-width: 800px; margin: auto; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.08); }
        .steps { display: flex; gap: 10px; margin-bottom: 20px; }
        .step { flex: 1; padding: 12px; border-radius: 8px; background: #f1f5f9; text-align: center; font-size: 13px; font-weight: 600; color: #64748b; }
        .step.active { background: #16a34a; color: white; }
        .step.completed { background: #dcfce7; color: #166534; }
        
        .scanner-container { background: #0f172a; border-radius: 10px; overflow: hidden; margin-bottom: 20px; padding: 10px; text-align: center; color: white; }
        #reader { width: 100%; max-width: 450px; margin: auto; border-radius: 8px; overflow: hidden; }
        
        .member-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 15px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .borrowed-book-item { border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 10px; background: #ffffff; cursor: pointer; transition: all 0.2s; }
        .borrowed-book-item:hover { border-color: #16a34a; background: #f0fdf4; }
        .borrowed-book-item.selected { border: 2px solid #16a34a; background: #f0fdf4; }
        
        .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-success { background: #dcfce7; color: #166534; }
        
        .fine-box { background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 16px; margin: 20px 0; }
        .btn { padding: 9px 16px; border-radius: 6px; cursor: pointer; border: none; font-weight: 600; font-size: 13px; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-submit { background: #16a34a; color: white; width: 100%; padding: 12px; font-size: 15px; }
        .btn-secondary { background: #e2e8f0; color: #334155; }
        
        .input-group { display: flex; gap: 8px; margin-bottom: 15px; }
        .input-group input { flex: 1; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; }
        .alert-success { background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; }
    </style>
</head>
<body>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">Pengembalian Buku dengan QR Code</h2>
        <a href="{{ route('pengembalian.index') }}" style="color: #64748b; font-size: 13px; text-decoration: none;">&larr; Riwayat Pengembalian</a>
    </div>

    <!-- Indikator Langkah -->
    <div class="steps">
        <div id="stepIndicator1" class="step active">1. Scan QR Member</div>
        <div id="stepIndicator2" class="step">2. Scan QR Buku Dikembalikan</div>
        <div id="stepIndicator3" class="step">3. Cek Kondisi & Selesai</div>
    </div>

    <div id="alertBox" style="display: none;"></div>

    @if ($errors->any())
        <div class="alert-error">
            @foreach ($errors->all() as $err)
                <div>• {{ $err }}</div>
            @endforeach
        </div>
    @endif

    <!-- Kotak Scanner Kamera -->
    <div class="scanner-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; padding: 0 10px;">
            <span id="scannerStatusText" style="font-size: 13px; font-weight: 600;">📷 Arahkan Kamera ke QR Code Member</span>
            <div>
                <button type="button" id="btnToggleCamera" class="btn btn-secondary" style="font-size: 11px; padding: 5px 10px;" onclick="toggleCamera()">Matikan Kamera</button>
            </div>
        </div>
        <div id="reader"></div>
        <div style="margin-top: 10px; font-size: 12px; color: #94a3b8;">
            Mode saat ini: <strong id="scanModeLabel" style="color: #4ade80;">Scan Member</strong>
        </div>
    </div>

    <!-- Input Manual / Scanner Barcode Fisik -->
    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 20px;">
        <label id="manualInputLabel" style="display: block; font-weight: 600; font-size: 12px; color: #475569; margin-bottom: 6px;">
            Input Manual / Scan Barcode Reader Fisik (Member):
        </label>
        <div class="input-group" style="margin-bottom: 0;">
            <input type="text" id="manualInput" placeholder="Ketik token QR member atau scan kartu..." autofocus>
            <button type="button" class="btn btn-primary" onclick="submitManualInput()">Proses</button>
        </div>
    </div>

    <!-- Data Member Teridentifikasi -->
    <div id="memberBox" style="display: none;" class="member-box">
        <div>
            <div style="font-size: 11px; text-transform: uppercase; color: #166534; font-weight: bold; margin-bottom: 2px;">Member Peminjam</div>
            <h3 id="memberName" style="margin: 0 0 4px 0; color: #064e3b;">-</h3>
            <span id="memberEmail" style="font-size: 12px; color: #475569;">-</span>
        </div>
        <div style="text-align: right;">
            <div style="font-size: 13px; font-weight: bold; color: #166534;">
                <span id="memberActiveBorrowCount">0</span> Buku Sedang Dipinjam
            </div>
            <button type="button" onclick="resetMember()" style="background: none; border: none; color: #dc2626; font-size: 11px; cursor: pointer; text-decoration: underline; margin-top: 4px;">Ganti Member</button>
        </div>
    </div>

    <!-- Daftar Buku yang Sedang Dipinjam oleh Member Tersebut -->
    <div id="borrowedBooksContainer" style="display: none; margin-bottom: 20px;">
        <h4 style="margin: 0 0 10px 0; color: #0f172a;">Pilih atau Scan Buku yang Dibawa Member:</h4>
        <div id="borrowedBooksList">
            <!-- Item buku dinamis -->
        </div>
    </div>

    <!-- Form Pengembalian & Kalkulasi Denda -->
    <div id="returnFormSection" style="display: none;">
        <form action="{{ route('pengembalian.store') }}" method="POST">
            @csrf
            <input type="hidden" name="idPeminjaman" id="formIdPeminjaman" required>
            <input type="hidden" name="idBuku" id="formIdBuku" required>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
                <h4 style="margin: 0 0 6px 0;" id="selectedBookTitle">-</h4>
                <div style="font-size: 12px; color: #64748b;">
                    Harga Penggantian Buku: <strong id="selectedBookPrice" style="color: #0f172a;">Rp 0</strong>
                </div>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 6px;">Pemeriksaan Kondisi Buku yang Dikembalikan</label>
                <select name="kondisiBuku" id="kondisiBukuSelect" required onchange="calculateFine()" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 14px;">
                    <option value="Baik">Baik (Bebas Denda Fisik)</option>
                    <option value="Rusak">Rusak (Denda 100% Harga Buku)</option>
                    <option value="Hilang">Hilang (Denda 100% Harga Buku)</option>
                </select>
            </div>

            <!-- Kalkulasi Tagihan Denda Real-Time -->
            <div class="fine-box" id="fineBox">
                <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 6px;">
                    <span>Denda Keterlambatan:</span>
                    <strong id="fineOverdueText">Rp 0</strong>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 8px;">
                    <span>Denda Kondisi Fisik:</span>
                    <strong id="fineConditionText">Rp 0</strong>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: bold; border-top: 1px solid #fca5a5; padding-top: 8px; color: #991b1b;">
                    <span>Total Denda:</span>
                    <span id="fineTotalText">Rp 0</span>
                </div>
            </div>

            <button type="submit" class="btn btn-submit">
                Konfirmasi & Simpan Pengembalian
            </button>
        </form>
    </div>
</div>

<script>
    const csrfToken = '{{ csrf_token() }}';
    let currentMode = 'member'; // 'member' atau 'book'
    let currentMember = null;
    let borrowedBooks = [];
    let selectedBook = null;
    let html5QrCode = null;
    let isCameraRunning = false;

    function playBeep() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.value = 880;
            gain.gain.value = 0.1;
            osc.start();
            setTimeout(() => { osc.stop(); ctx.close(); }, 120);
        } catch(e) {}
    }

    function showAlert(msg, isError = true) {
        const box = document.getElementById('alertBox');
        box.className = isError ? 'alert-error' : 'alert-success';
        box.innerHTML = msg;
        box.style.display = 'block';
        setTimeout(() => { box.style.display = 'none'; }, 4500);
    }

    function initScanner() {
        html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
            .then(() => { isCameraRunning = true; })
            .catch(err => {
                document.getElementById('scannerStatusText').innerText = "Kamera tidak aktif / tidak diizinkan. Gunakan input manual.";
                document.getElementById('btnToggleCamera').style.display = 'none';
            });
    }

    function toggleCamera() {
        if (!html5QrCode) return;
        const btn = document.getElementById('btnToggleCamera');
        if (isCameraRunning) {
            html5QrCode.stop().then(() => {
                isCameraRunning = false;
                btn.innerText = "Aktifkan Kamera";
            });
        } else {
            html5QrCode.start({ facingMode: "environment" }, { fps: 10, qrbox: { width: 250, height: 250 } }, onScanSuccess)
                .then(() => {
                    isCameraRunning = true;
                    btn.innerText = "Matikan Kamera";
                });
        }
    }

    function onScanSuccess(decodedText) {
        playBeep();
        handleScannedCode(decodedText.trim());
    }

    function submitManualInput() {
        const val = document.getElementById('manualInput').value.trim();
        if (!val) return;
        handleScannedCode(val);
        document.getElementById('manualInput').value = '';
    }

    document.getElementById('manualInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitManualInput();
        }
    });

    function handleScannedCode(code) {
        if (currentMode === 'member') {
            lookupMemberLoans(code);
        } else if (currentMode === 'book') {
            selectBookByCode(code);
        }
    }

    // 1. Ambil Pinjaman Aktif Milik Member
    function lookupMemberLoans(token) {
        fetch('{{ route("api.scan.pengembalian.member") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ token: token })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                showAlert(data.message, true);
                return;
            }

            if (data.totalBukuDipinjam === 0) {
                showAlert(`Member ${data.member.name} tidak memiliki buku yang sedang dipinjam saat ini.`, true);
                return;
            }

            currentMember = data.member;
            borrowedBooks = data.bukuDipinjam;

            document.getElementById('memberName').innerText = currentMember.name;
            document.getElementById('memberEmail').innerText = currentMember.email;
            document.getElementById('memberActiveBorrowCount').innerText = data.totalBukuDipinjam;

            document.getElementById('memberBox').style.display = 'flex';
            document.getElementById('borrowedBooksContainer').style.display = 'block';

            renderBorrowedBooksList();

            // Ganti mode ke Scan Buku
            currentMode = 'book';
            document.getElementById('stepIndicator1').className = 'step completed';
            document.getElementById('stepIndicator2').className = 'step active';
            document.getElementById('scanModeLabel').innerText = 'Scan QR Buku Dikembalikan';
            document.getElementById('scannerStatusText').innerText = '📷 Arahkan Kamera ke QR Code / Barcode Buku yang Dikembalikan';
            document.getElementById('manualInputLabel').innerText = 'Input Manual / Scan Barcode Reader Fisik (Buku):';
            document.getElementById('manualInput').placeholder = 'Ketik token QR atau barcode buku...';

            showAlert(`Data pinjaman member ${currentMember.name} berhasil dimuat. Silakan scan buku.`, false);
        })
        .catch(err => {
            showAlert('Gagal mencari data member.', true);
        });
    }

    function renderBorrowedBooksList() {
        const container = document.getElementById('borrowedBooksList');
        container.innerHTML = '';

        borrowedBooks.forEach((buku, idx) => {
            const div = document.createElement('div');
            div.className = 'borrowed-book-item' + (selectedBook && selectedBook.idBuku === buku.idBuku ? ' selected' : '');
            div.id = 'book-item-' + buku.idBuku;
            div.onclick = () => selectBook(buku);

            const badgeHtml = buku.terlambat 
                ? `<span class="badge badge-danger">Terlambat ${buku.mingguTerlambat} Minggu</span>`
                : `<span class="badge badge-success">Tepat Waktu</span>`;

            div.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <div>
                        <strong style="color: #0f172a; font-size: 14px;">${buku.judul}</strong>
                        <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                            ${buku.penulis} • Kode: <code>${buku.qr_token || buku.kodeBarcode}</code>
                        </div>
                        <div style="font-size: 12px; color: #475569; margin-top: 4px;">
                            Batas Kembali: <strong>${buku.batasKembali}</strong>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        ${badgeHtml}
                        <div style="margin-top: 6px;">
                            <span style="font-size: 11px; color: #2563eb; font-weight: bold;">Klik untuk pilih &rarr;</span>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(div);
        });
    }

    // 2. Pilih Buku Berdasarkan Scan QR
    function selectBookByCode(code) {
        const matched = borrowedBooks.find(b => b.qr_token === code || b.kodeBarcode === code);
        if (!matched) {
            showAlert(`Buku dengan kode '${code}' tidak termasuk dalam transaksi pinjaman aktif member ini!`, true);
            return;
        }

        selectBook(matched);
        showAlert(`Buku '${matched.judul}' berhasil dicocokkan!`, false);
    }

    function selectBook(buku) {
        selectedBook = buku;
        renderBorrowedBooksList();

        document.getElementById('formIdPeminjaman').value = buku.idPeminjaman;
        document.getElementById('formIdBuku').value = buku.idBuku;
        document.getElementById('selectedBookTitle').innerText = buku.judul + " (" + (buku.penulis || '') + ")";
        document.getElementById('selectedBookPrice').innerText = 'Rp ' + Number(buku.harga).toLocaleString('id-ID');

        document.getElementById('returnFormSection').style.display = 'block';
        document.getElementById('stepIndicator2').className = 'step completed';
        document.getElementById('stepIndicator3').className = 'step active';

        calculateFine();
    }

    // 3. Kalkulasi Denda Real-Time
    function calculateFine() {
        if (!selectedBook) return;

        const kondisi = document.getElementById('kondisiBukuSelect').value;
        const hargaBuku = parseFloat(selectedBook.harga) || 0;
        const dendaOverdue = parseFloat(selectedBook.estDendaTelat) || 0;

        let dendaKondisi = 0;
        if (kondisi === 'Rusak' || kondisi === 'Hilang') {
            dendaKondisi = hargaBuku;
        }

        const totalDenda = dendaOverdue + dendaKondisi;

        document.getElementById('fineOverdueText').innerText = 'Rp ' + dendaOverdue.toLocaleString('id-ID');
        document.getElementById('fineConditionText').innerText = 'Rp ' + dendaKondisi.toLocaleString('id-ID');
        document.getElementById('fineTotalText').innerText = 'Rp ' + totalDenda.toLocaleString('id-ID');
    }

    function resetMember() {
        currentMember = null;
        borrowedBooks = [];
        selectedBook = null;
        currentMode = 'member';

        document.getElementById('memberBox').style.display = 'none';
        document.getElementById('borrowedBooksContainer').style.display = 'none';
        document.getElementById('returnFormSection').style.display = 'none';

        document.getElementById('stepIndicator1').className = 'step active';
        document.getElementById('stepIndicator2').className = 'step';
        document.getElementById('stepIndicator3').className = 'step';

        document.getElementById('scanModeLabel').innerText = 'Scan Member';
        document.getElementById('scannerStatusText').innerText = '📷 Arahkan Kamera ke QR Code Member';
        document.getElementById('manualInputLabel').innerText = 'Input Manual / Scan Barcode Reader Fisik (Member):';
        document.getElementById('manualInput').placeholder = 'Ketik token QR member...';
    }

    window.addEventListener('DOMContentLoaded', () => {
        initScanner();

        // Support backward-compatible URL query ?barcode=...
        const urlParams = new URLSearchParams(window.location.search);
        const barcodeParam = urlParams.get('barcode');
        if (barcodeParam) {
            // Coba lookup buku langsung
            document.getElementById('manualInput').value = barcodeParam;
        }
    });
</script>

</body>
</html>