@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.petugas')

@section('title', 'Tambah Buku Baru - BOOKNEST')

@section('styles')
<style>
    .page-header {
        margin-bottom: 24px;
    }

    .breadcrumb {
        font-size: 11.5px;
        font-weight: 700;
        color: #0f766e;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .page-title {
        font-size: 26px;
        font-weight: 800;
        color: var(--text-heading, #0f172a);
        letter-spacing: -0.5px;
        line-height: 1.2;
        margin-bottom: 4px;
    }

    .page-subtitle {
        font-size: 13.5px;
        color: var(--text-muted, #64748b);
        max-width: 680px;
    }

    .form-card {
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        padding: 28px 32px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        max-width: 820px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .form-group-full {
        grid-column: span 2;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 14px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 700;
        color: #334155;
    }

    .form-label .required {
        color: #dc2626;
    }

    .form-input, .form-select {
        padding: 11px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 13.5px;
        color: #0f172a;
        background: #ffffff;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
        font-family: inherit;
        width: 100%;
        box-sizing: border-box;
    }

    .form-input:focus, .form-select:focus {
        border-color: #0f766e;
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
    }

    .form-input.is-invalid, .form-select.is-invalid {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .form-error {
        font-size: 12px;
        font-weight: 600;
        color: #dc2626;
        margin-top: 2px;
    }

    .form-helper {
        font-size: 11.5px;
        color: #64748b;
        margin-top: 2px;
    }

    /* --- COVER UPLOAD COMPONENT --- */
    .cover-section-wrap {
        border: 1.5px dashed #cbd5e1;
        border-radius: 14px;
        padding: 20px;
        background: #f8fafc;
        display: flex;
        align-items: center;
        gap: 22px;
        transition: border-color 0.2s, background-color 0.2s;
    }

    .cover-section-wrap:hover {
        border-color: #0f766e;
        background: #f0fdfa;
    }

    .cover-preview-box {
        width: 105px;
        height: 145px;
        border-radius: 10px;
        overflow: hidden;
        background: #e2e8f0;
        border: 1px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
        position: relative;
    }

    .cover-preview-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .cover-placeholder-icon {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        color: #94a3b8;
        font-size: 11px;
        font-weight: 600;
        text-align: center;
        padding: 8px;
    }

    .cover-upload-actions {
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex: 1;
    }

    .btn-choose-cover {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #0f766e;
        color: #ffffff;
        padding: 9px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        width: fit-content;
        transition: background 0.15s;
    }

    .btn-choose-cover:hover {
        background: #115e59;
    }

    .cover-file-info {
        font-size: 12px;
        color: #64748b;
    }

    /* --- FORM ACTIONS --- */
    .form-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 26px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color, #e2e8f0);
    }

    .btn-submit-save {
        padding: 12px 26px;
        background: #0f766e;
        color: #ffffff;
        border: none;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 4px rgba(15, 118, 110, 0.2);
        transition: background 0.15s, transform 0.1s;
    }

    .btn-submit-save:hover {
        background: #115e59;
        transform: translateY(-1px);
    }

    .btn-cancel {
        padding: 12px 20px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.15s;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .form-group-full {
            grid-column: span 1;
        }
        .cover-section-wrap {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endsection

@section('content')
<div class="buku-create-container">
    <div class="page-header">
        <div class="breadcrumb">
            <a href="{{ route('buku.index') }}" style="color: inherit; text-decoration: none;">Kelola Buku</a>
            <span>/</span>
            <span>Tambah Buku Baru</span>
        </div>
        <h1 class="page-title">Tambah Koleksi Buku Baru</h1>
        <p class="page-subtitle">
            Daftarkan master koleksi buku baru, unggah foto cover, buat barcode fisik unik, dan tentukan nilai referensi buku.
        </p>
    </div>

    <div class="form-card">
        <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- COVER BUKU UPLOAD -->
            <div class="form-group form-group-full" style="margin-bottom: 22px;">
                <label class="form-label">Cover Buku (Opsional)</label>
                <div class="cover-section-wrap">
                    <div class="cover-preview-box" id="coverPreviewBox">
                        <img src=""
                             alt="Cover Preview"
                             id="coverPreviewImg"
                             class="cover-preview-img"
                             style="display: none;">
                        <div id="coverPlaceholder" class="cover-placeholder-icon">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                            <span>Belum Ada Cover</span>
                        </div>
                    </div>

                    <div class="cover-upload-actions">
                        <div>
                            <label for="coverInput" class="btn-choose-cover">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                                <span>Pilih Gambar Cover</span>
                            </label>
                            <input type="file"
                                   id="coverInput"
                                   name="cover"
                                   accept="image/jpeg,image/png,image/jpg,image/webp"
                                   style="display: none;"
                                   onchange="handleCoverChange(this)">
                        </div>

                        <div class="cover-file-info" id="coverFileInfo">
                            Format yang didukung: JPG, JPEG, PNG, WEBP. Maksimal 2 MB.
                        </div>

                        @error('cover')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-grid">
                <!-- KODE BARCODE -->
                <div class="form-group">
                    <label class="form-label" for="kodeBarcode">Kode Barcode Buku <span class="required">*</span></label>
                    <input type="text"
                           id="kodeBarcode"
                           name="kodeBarcode"
                           class="form-input @error('kodeBarcode') is-invalid @enderror"
                           placeholder="Contoh: BK-NV-001"
                           value="{{ old('kodeBarcode') }}"
                           required>
                    @error('kodeBarcode')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- KATEGORI -->
                <div class="form-group">
                    <label class="form-label" for="idKategori">Kategori Koleksi <span class="required">*</span></label>
                    <select id="idKategori" name="idKategori" class="form-select @error('idKategori') is-invalid @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoris as $k)
                            <option value="{{ $k->idKategori }}" {{ old('idKategori') == $k->idKategori ? 'selected' : '' }}>
                                {{ $k->namaKategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('idKategori')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- JUDUL BUKU -->
                <div class="form-group form-group-full">
                    <label class="form-label" for="judul">Judul Lengkap Buku <span class="required">*</span></label>
                    <input type="text"
                           id="judul"
                           name="judul"
                           class="form-input @error('judul') is-invalid @enderror"
                           placeholder="Masukkan judul buku..."
                           value="{{ old('judul') }}"
                           required>
                    @error('judul')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- PENULIS -->
                <div class="form-group">
                    <label class="form-label" for="penulis">Nama Penulis / Pengarang <span class="required">*</span></label>
                    <input type="text"
                           id="penulis"
                           name="penulis"
                           class="form-input @error('penulis') is-invalid @enderror"
                           placeholder="Contoh: Pramoedya Ananta Toer"
                           value="{{ old('penulis') }}"
                           required>
                    @error('penulis')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- PENERBIT -->
                <div class="form-group">
                    <label class="form-label" for="penerbit">Penerbit <span class="required">*</span></label>
                    <input type="text"
                           id="penerbit"
                           name="penerbit"
                           class="form-input @error('penerbit') is-invalid @enderror"
                           placeholder="Contoh: Gramedia Pustaka Utama"
                           value="{{ old('penerbit') }}"
                           required>
                    @error('penerbit')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- TAHUN TERBIT -->
                <div class="form-group">
                    <label class="form-label" for="tahunTerbit">Tahun Terbit <span class="required">*</span></label>
                    <input type="number"
                           id="tahunTerbit"
                           name="tahunTerbit"
                           class="form-input @error('tahunTerbit') is-invalid @enderror"
                           placeholder="Contoh: 2024"
                           min="1900"
                           max="2099"
                           value="{{ old('tahunTerbit', date('Y')) }}"
                           required>
                    @error('tahunTerbit')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- HARGA BUKU (FORMAT RUPIAH) -->
                <div class="form-group">
                    <label class="form-label" for="hargaDisplay">Harga <span class="required">*</span></label>
                    <input type="text"
                           id="hargaDisplay"
                           class="form-input @error('harga') is-invalid @enderror"
                           placeholder="Rp 0"
                           autocomplete="off"
                           required>
                    <input type="hidden"
                           id="hargaReal"
                           name="harga"
                           value="{{ old('harga', 0) }}">
                    <span class="form-helper">Format mata uang Rupiah otomatis saat angka dimasukkan.</span>
                    @error('harga')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- STOK FISIK -->
                <div class="form-group">
                    <label class="form-label" for="stok">Jumlah Total Stok Eksemplar <span class="required">*</span></label>
                    <input type="number"
                           id="stok"
                           name="stok"
                           class="form-input @error('stok') is-invalid @enderror"
                           min="0"
                           value="{{ old('stok', 1) }}"
                           required>
                    <span class="form-helper">Eksemplar fisik unik akan langsung dibuat sesuai jumlah stok ini.</span>
                    @error('stok')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- KONDISI -->
                <div class="form-group">
                    <label class="form-label" for="kondisi">Kondisi Fisik Buku <span class="required">*</span></label>
                    <select id="kondisi" name="kondisi" class="form-select @error('kondisi') is-invalid @enderror" required>
                        <option value="Baik" {{ old('kondisi', 'Baik') === 'Baik' ? 'selected' : '' }}>Kondisi Baik</option>
                        <option value="Rusak" {{ old('kondisi') === 'Rusak' ? 'selected' : '' }}>Kondisi Rusak</option>
                        <option value="Hilang" {{ old('kondisi') === 'Hilang' ? 'selected' : '' }}>Hilang</option>
                    </select>
                    @error('kondisi')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- TOMBOL AKSI -->
            <div class="form-actions">
                <button type="submit" class="btn-submit-save">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Simpan Buku
                </button>
                <a href="{{ route('buku.index') }}" class="btn-cancel">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // --- FUNGSI FORMAT RUPIAH ---
    function formatRupiah(val, prefix = 'Rp ') {
        let number_string = val.toString().replace(/[^,\d]/g, '');
        let split = number_string.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? prefix + rupiah : '');
    }

    const hargaDisplay = document.getElementById('hargaDisplay');
    const hargaReal = document.getElementById('hargaReal');

    // Inisialisasi awal saat load jika sudah ada nilai (misal dari old())
    if (hargaReal && hargaReal.value !== '' && hargaReal.value !== '0') {
        const rawInitial = parseInt(hargaReal.value, 10) || 0;
        hargaDisplay.value = formatRupiah(rawInitial.toString(), 'Rp ');
    }

    hargaDisplay.addEventListener('input', function (e) {
        const rawDigits = this.value.replace(/[^0-9]/g, '');
        hargaReal.value = rawDigits ? parseInt(rawDigits, 10) : 0;
        this.value = formatRupiah(rawDigits, 'Rp ');
    });

    // --- PREVIEW FILE COVER ---
    function handleCoverChange(input) {
        const previewImg = document.getElementById('coverPreviewImg');
        const placeholder = document.getElementById('coverPlaceholder');
        const fileInfo = document.getElementById('coverFileInfo');

        if (input.files && input.files[0]) {
            const file = input.files[0];

            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal ukuran gambar adalah 2 MB.');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                placeholder.style.display = 'none';
                fileInfo.innerHTML = `<strong style="color: #0f766e;">File dipilih:</strong> ${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection