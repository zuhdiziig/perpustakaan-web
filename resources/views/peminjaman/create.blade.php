<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Peminjaman Buku (QR Code & Barcode)</title>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8fafc; padding: 25px; margin: 0; color: #1e293b; }
        .card { background: white; max-width: 800px; margin: auto; padding: 25px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.08); }
        .steps { display: flex; gap: 10px; margin-bottom: 20px; }
        .step { flex: 1; padding: 12px; border-radius: 8px; background: #f1f5f9; text-align: center; font-size: 13px; font-weight: 600; color: #64748b; }
        .step.active { background: #2563eb; color: white; }
        .step.completed { background: #dcfce7; color: #166534; }
        
        .scanner-container { background: #0f172a; border-radius: 10px; overflow: hidden; margin-bottom: 20px; padding: 10px; text-align: center; color: white; }
        #reader { width: 100%; max-width: 450px; margin: auto; border-radius: 8px; overflow: hidden; }
        
        .member-card { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 15px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 20px; font-size: 13px; }
        th, td { border-bottom: 1px solid #e2e8f0; padding: 10px; text-align: left; }
        th { background: #f8fafc; color: #64748b; font-weight: 600; }
        
        .btn { padding: 9px 16px; border-radius: 6px; cursor: pointer; border: none; font-weight: 600; font-size: 13px; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-success { background: #16a34a; color: white; width: 100%; padding: 12px; font-size: 15px; }
        .btn-danger { background: #fee2e2; color: #dc2626; padding: 5px 10px; font-size: 12px; }
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
        <h2 style="margin: 0;">Peminjaman Buku dengan QR Code</h2>
        <a href="{{ route('peminjaman.index') }}" style="color: #64748b; font-size: 13px; text-decoration: none;">&larr; Riwayat Peminjaman</a>
    </div>

    <!-- Indikator Langkah -->
    <div class="steps">
        <div id="stepIndicator1" class="step active">1. Scan QR Member</div>
        <div id="stepIndicator2" class="step">2. Scan QR Buku (Maks 7)</div>
        <div id="stepIndicator3" class="step">3. Konfirmasi</div>
    </div>

    <div id="alertBox" style="display: none;"></div>

    @if ($errors->any())
        <div class="alert-error">
            @foreach ($errors->all() as $err)
                <div>• {{ $err }}</div>
            @endforeach
        </div>
    @endif

    <!-- Kotak Kamera Scanner -->
    <div class="scanner-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; padding: 0 10px;">
            <span id="scannerStatusText" style="font-size: 13px; font-weight: 600;">📷 Arahkan Kamera ke QR Code Member</span>
            <div>
                <button type="button" id="btnToggleCamera" class="btn btn-secondary" style="font-size: 11px; padding: 5px 10px;" onclick="toggleCamera()">Matikan Kamera</button>
            </div>
        </div>
        <div id="reader"></div>
        <div style="margin-top: 10px; font-size: 12px; color: #94a3b8;">
            Mode saat ini: <strong id="scanModeLabel" style="color: #38bdf8;">Scan Member</strong>
        </div>
    </div>

    <!-- Input Manual / Scanner Fisik Alternatif -->
    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 20px;">
        <label id="manualInputLabel" style="display: block; font-weight: 600; font-size: 12px; color: #475569; margin-bottom: 6px;">
            Input Manual / Scan Barcode Reader Fisik (Member):
        </label>
        <div class="input-group" style="margin-bottom: 0;">
            <input type="text" id="manualInput" placeholder="Ketik token QR atau scan dengan barcode scanner..." autofocus>
            <button type="button" class="btn btn-primary" onclick="submitManualInput()">Proses</button>
        </div>
    </div>

    <!-- Data Member Teridentifikasi -->
    <div id="memberSection" style="display: none;" class="member-card">
        <div>
            <div style="font-size: 11px; text-transform: uppercase; color: #2563eb; font-weight: bold; margin-bottom: 2px;">Member Teridentifikasi</div>
            <h3 id="memberName" style="margin: 0 0 4px 0; color: #0f172a;">-</h3>
            <div style="font-size: 12px; color: #475569;">
                <span id="memberEmail">-</span> • <span id="memberPhone">-</span>
            </div>
        </div>
        <div style="text-align: right;">
            <div style="margin-bottom: 6px;">
                <span id="memberStatusBadge" class="badge badge-success">AKTIF</span>
            </div>
            <div style="font-size: 12px; color: #1e40af; font-weight: 600;">
                Sisa Kuota: <span id="memberQuota">7</span> Buku
            </div>
            <button type="button" onclick="resetMember()" style="background: none; border: none; color: #dc2626; font-size: 11px; cursor: pointer; text-decoration: underline; margin-top: 4px;">Ganti Member</button>
        </div>
    </div>

    <!-- Form Transaksi Utama -->
    <form id="peminjamanForm" action="{{ route('peminjaman.store') }}" method="POST">
        @csrf
        <input type="hidden" name="idUserMember" id="formUserId" required>

        <!-- Tabel Daftar Buku yang Dipinjam -->
        <div id="booksSection" style="display: none;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h4 style="margin: 0; color: #0f172a;">Daftar Buku Dipinjam (<span id="bookCount">0</span>/7)</h4>
                <span style="font-size: 12px; color: #64748b;">Maksimal 7 buku per transaksi</span>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode QR / Barcode</th>
                        <th>Judul Buku</th>
                        <th>Kategori</th>
                        <th>Stok Tersedia</th>
                        <th style="width: 60px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="bookListTable">
                    <!-- Baris buku dinamis masuk ke sini -->
                </tbody>
            </table>

            <div id="emptyBookMsg" style="text-align: center; color: #94a3b8; padding: 20px 0; font-size: 13px;">
                Belum ada buku yang di-scan. Silakan arahkan kamera atau masukkan kode QR buku.
            </div>

            <button type="submit" id="btnSubmitForm" class="btn btn-success" disabled>
                Konfirmasi & Simpan Transaksi Peminjaman
            </button>
        </div>
    </form>
</div>

<!-- Audio Beep Feedback -->
<audio id="beepSound" preload="auto">
    <source src="data:audio/wav;base64,UklGRl9vT19XQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YU9vT18AAAAAAAEBAgMEBQYHCAkKCwwNDg8QERITFBUWFxgZGhscHR4fICEiIyQlJicoKSorLC0uLzAxMjM0NTY3ODk6Ozw9Pj9AQkNERUZHSElKS0xNTk9QUVJTVFVWV1hZWltcXV5fYGFiY2RlZmdoaWprbG1ub3BxcnN0dXZ3eHl6e3x9fn8=" type="audio/wav">
</audio>

<script>
    const csrfToken = '{{ csrf_token() }}';
    let currentMode = 'member'; // 'member' atau 'book'
    let currentMember = null;
    let selectedBooks = []; // array of book objects
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

    // Inisialisasi Scanner Kamera
    function initScanner() {
        html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        html5QrCode.start({ facingMode: "environment" }, config, onScanSuccess)
            .then(() => { isCameraRunning = true; })
            .catch(err => {
                console.log("Scanner camera not available:", err);
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
            lookupMember(code);
        } else if (currentMode === 'book') {
            lookupBook(code);
        }
    }

    // 1. Lookup Member via API
    function lookupMember(token) {
        fetch('{{ route("api.scan.member") }}', {
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

            if (data.member.sisaKuota <= 0) {
                showAlert(`Gagal: Member ${data.member.name} sudah mencapai batas maksimal 7 buku pinjaman aktif.`, true);
                return;
            }

            // Set current member
            currentMember = data.member;
            document.getElementById('formUserId').value = currentMember.id;
            document.getElementById('memberName').innerText = currentMember.name;
            document.getElementById('memberEmail').innerText = currentMember.email;
            document.getElementById('memberPhone').innerText = currentMember.noTelepon;
            document.getElementById('memberQuota').innerText = currentMember.sisaKuota;
            
            document.getElementById('memberSection').style.display = 'flex';
            document.getElementById('booksSection').style.display = 'block';

            // Switch to Book Scanning Mode
            currentMode = 'book';
            document.getElementById('stepIndicator1').className = 'step completed';
            document.getElementById('stepIndicator2').className = 'step active';
            document.getElementById('scanModeLabel').innerText = 'Scan QR / Barcode Buku';
            document.getElementById('scannerStatusText').innerText = '📷 Arahkan Kamera ke QR Code / Barcode Buku';
            document.getElementById('manualInputLabel').innerText = 'Input Manual / Scan Barcode Reader Fisik (Buku):';
            document.getElementById('manualInput').placeholder = 'Ketik token QR atau barcode buku...';

            showAlert(`Member ${currentMember.name} berhasil teridentifikasi! Silakan scan buku.`, false);
        })
        .catch(err => {
            showAlert('Gagal menghubungi server.', true);
        });
    }

    // 2. Lookup Book via API
    function lookupBook(token) {
        if (!currentMember) {
            showAlert('Silakan scan member terlebih dahulu!', true);
            return;
        }

        // Cek kuota member
        if (selectedBooks.length >= currentMember.sisaKuota) {
            showAlert(`Batas maksimal tercapai! Member ini hanya memiliki sisa kuota ${currentMember.sisaKuota} buku.`, true);
            return;
        }

        // Cek duplikasi di keranjang
        if (selectedBooks.some(b => b.qr_token === token || b.kodeBarcode === token)) {
            showAlert('Buku ini sudah dimasukkan ke dalam daftar peminjaman.', true);
            return;
        }

        fetch('{{ route("api.scan.buku") }}', {
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

            // Masukkan ke array
            selectedBooks.push(data.buku);
            renderBookTable();
            showAlert(`Buku '${data.buku.judul}' berhasil ditambahkan.`, false);
        })
        .catch(err => {
            showAlert('Gagal mencari data buku.', true);
        });
    }

    function removeBook(index) {
        selectedBooks.splice(index, 1);
        renderBookTable();
    }

    function renderBookTable() {
        const tbody = document.getElementById('bookListTable');
        const emptyMsg = document.getElementById('emptyBookMsg');
        const submitBtn = document.getElementById('btnSubmitForm');
        const countSpan = document.getElementById('bookCount');

        tbody.innerHTML = '';
        countSpan.innerText = selectedBooks.length;

        if (selectedBooks.length === 0) {
            emptyMsg.style.display = 'block';
            submitBtn.disabled = true;
            document.getElementById('stepIndicator3').className = 'step';
            return;
        }

        emptyMsg.style.display = 'none';
        submitBtn.disabled = false;
        document.getElementById('stepIndicator3').className = 'step active';

        selectedBooks.forEach((buku, idx) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${idx + 1}</td>
                <td>
                    <code>${buku.qr_token || buku.kodeBarcode}</code>
                    <input type="hidden" name="barcodes[]" value="${buku.qr_token || buku.kodeBarcode}">
                </td>
                <td><strong>${buku.judul}</strong><br><small style="color:#64748b;">${buku.penulis}</small></td>
                <td>${buku.kategori}</td>
                <td><span class="badge badge-success">${buku.stok} eks</span></td>
                <td>
                    <button type="button" class="btn btn-danger" onclick="removeBook(${idx})">Hapus</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function resetMember() {
        currentMember = null;
        selectedBooks = [];
        currentMode = 'member';
        renderBookTable();
        document.getElementById('formUserId').value = '';
        document.getElementById('memberSection').style.display = 'none';
        document.getElementById('booksSection').style.display = 'none';

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
    });
</script>

</body>
</html>