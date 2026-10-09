@extends('layouts.anggota')

@section('title', 'Profil Saya - BOOKNEST')

@section('styles')
<style>
    /* --- BREADCRUMB & HEADER --- */
    .profile-header {
        margin-bottom: 24px;
    }

    .profile-breadcrumb {
        font-size: 12.5px;
        font-weight: 600;
        color: #0f766e;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 6px;
    }

    .profile-breadcrumb a {
        color: #0f766e;
        transition: color 0.15s;
    }

    .profile-breadcrumb a:hover {
        color: #115e59;
    }

    .profile-breadcrumb .separator {
        color: #94a3b8;
    }

    .profile-breadcrumb .current {
        color: #0f766e;
        font-weight: 700;
    }

    .profile-title {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-heading);
        letter-spacing: -0.6px;
        line-height: 1.2;
        margin-bottom: 4px;
    }

    .profile-subtitle {
        font-size: 13.5px;
        color: var(--text-muted);
    }

    /* --- ALERTS --- */
    .profile-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 22px;
        font-size: 13.5px;
        line-height: 1.5;
        animation: fadeIn 0.25s ease;
    }

    .profile-alert.success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .profile-alert.error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .profile-alert svg {
        flex-shrink: 0;
        margin-top: 2px;
    }

    /* --- PROFILE GRID LAYOUT --- */
    .profile-grid {
        display: grid;
        grid-template-columns: 290px 1fr;
        gap: 24px;
        align-items: start;
    }

    /* --- LEFT COLUMN: SUMMARY CARD --- */
    .summary-card {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 28px 22px 24px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .avatar-wrapper {
        position: relative;
        margin-bottom: 14px;
    }

    .avatar-large {
        width: 86px;
        height: 86px;
        border-radius: 50%;
        background: #ccfbf1;
        color: #0f766e;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        font-weight: 800;
        letter-spacing: 0.5px;
        overflow: hidden;
        border: 2px solid #99f6e4;
        box-shadow: 0 4px 12px rgba(15, 118, 110, 0.12);
    }

    .avatar-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .summary-name {
        font-size: 19px;
        font-weight: 800;
        color: var(--text-heading);
        letter-spacing: -0.3px;
        line-height: 1.3;
        margin-bottom: 3px;
        word-break: break-word;
    }

    .summary-code {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 12px;
    }

    .badge-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 18px;
    }

    .badge-status-pill.aktif {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-status-pill.nonaktif {
        background: #fee2e2;
        color: #b91c1c;
    }

    .badge-status-pill .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .summary-meta-table {
        width: 100%;
        border-top: 1px solid #f1f5f9;
        padding-top: 14px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 18px;
    }

    .summary-meta-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
    }

    .summary-meta-label {
        color: var(--text-muted);
        font-weight: 500;
    }

    .summary-meta-value {
        color: var(--text-heading);
        font-weight: 700;
    }

    .btn-ubah-foto {
        width: 100%;
        padding: 10px 16px;
        background: #2563eb;
        color: #ffffff;
        font-size: 13.5px;
        font-weight: 700;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.15s ease;
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
    }

    .btn-ubah-foto:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-ubah-foto:active {
        transform: translateY(0);
    }

    .btn-hapus-foto {
        margin-top: 8px;
        font-size: 12px;
        color: #ef4444;
        font-weight: 600;
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px;
        transition: color 0.15s;
    }

    .btn-hapus-foto:hover {
        color: #b91c1c;
        text-decoration: underline;
    }

    /* --- LEFT COLUMN: PRIVACY CARD --- */
    .privacy-card {
        margin-top: 18px;
        background: #f0fdfa;
        border: 1px solid #99f6e4;
        border-radius: 16px;
        padding: 18px 20px;
    }

    .privacy-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 6px;
    }

    .privacy-desc {
        font-size: 12.5px;
        color: #475569;
        line-height: 1.6;
    }

    /* --- RIGHT COLUMN: SECTIONS --- */
    .profile-card-section {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 18px;
        padding: 24px 28px 26px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        margin-bottom: 20px;
    }

    .section-header-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-heading);
        margin-bottom: 18px;
        letter-spacing: -0.3px;
    }

    .form-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        font-size: 13.5px;
        color: var(--text-heading);
        background: #ffffff;
        outline: none;
        transition: all 0.15s ease;
        font-family: inherit;
    }

    .form-control::placeholder {
        color: #94a3b8;
    }

    .form-control:focus {
        border-color: #0f766e;
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
    }

    .form-control.is-readonly {
        background: #f8fafc;
        color: #64748b;
        cursor: not-allowed;
        border-color: #e2e8f0;
    }

    .form-error-msg {
        font-size: 12px;
        color: #dc2626;
        margin-top: 4px;
        font-weight: 600;
    }

    .btn-save-primary {
        width: 100%;
        padding: 12px 20px;
        background: #0f766e;
        color: #ffffff;
        font-size: 14px;
        font-weight: 700;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
        box-shadow: 0 4px 12px rgba(15, 118, 110, 0.2);
        margin-top: 6px;
    }

    .btn-save-primary:hover {
        background: #115e59;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(15, 118, 110, 0.28);
    }

    .btn-save-primary:active {
        transform: translateY(0);
    }

    .btn-save-blue {
        padding: 10px 22px;
        background: #2563eb;
        color: #ffffff;
        font-size: 13.5px;
        font-weight: 700;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
        box-shadow: 0 3px 10px rgba(37, 99, 235, 0.2);
    }

    .btn-save-blue:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(37, 99, 235, 0.28);
    }

    .btn-save-blue:active {
        transform: translateY(0);
    }

    /* --- NOTIFICATION PREFERENCES (CHECKBOXES) --- */
    .notif-options-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .notif-checkbox-label {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        user-select: none;
        font-size: 13.5px;
        color: #334155;
        font-weight: 600;
        transition: color 0.15s;
    }

    .notif-checkbox-label:hover {
        color: var(--text-heading);
    }

    .custom-checkbox {
        position: relative;
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .custom-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkbox-mark {
        position: absolute;
        top: 0;
        left: 0;
        height: 18px;
        width: 18px;
        background-color: #ffffff;
        border: 2px solid #cbd5e1;
        border-radius: 5px;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .custom-checkbox input:checked ~ .checkbox-mark {
        background-color: #0f766e;
        border-color: #0f766e;
    }

    .checkbox-mark svg {
        display: none;
        color: #ffffff;
    }

    .custom-checkbox input:checked ~ .checkbox-mark svg {
        display: block;
    }

    .notif-saved-toast {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 700;
        color: #0f766e;
        background: #ccfbf1;
        padding: 4px 10px;
        border-radius: 6px;
        opacity: 0;
        transition: opacity 0.3s ease;
        margin-left: 10px;
    }

    .notif-saved-toast.show {
        opacity: 1;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* --- RESPONSIVE BREAKPOINTS --- */
    @media (max-width: 900px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }

        .summary-card {
            max-width: 480px;
            margin: 0 auto 16px;
            width: 100%;
        }

        .privacy-card {
            max-width: 480px;
            margin: 0 auto 20px;
            width: 100%;
        }
    }

    @media (max-width: 580px) {
        .form-grid-2 {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .profile-card-section {
            padding: 20px 18px;
        }
    }
</style>
@endsection

@section('content')
<div class="profile-container">

    <!-- PAGE HEADER -->
    <div class="profile-header">
        <div class="profile-breadcrumb">
            <a href="{{ route('home') }}">BOOKNEST</a>
            <span class="separator">/</span>
            <span class="current">Anggota</span>
        </div>
        <h1 class="profile-title">Profil Saya</h1>
        <p class="profile-subtitle">Kelola data pribadi, keanggotaan, dan keamanan akunmu.</p>
    </div>

    <!-- MAIN GRID -->
    <div class="profile-grid">

        <!-- ============================================ -->
        <!-- KOLOM KIRI: RINGKASAN AKUN & PRIVASI -->
        <!-- ============================================ -->
        <div>
            <!-- RINGKASAN MEMBER CARD -->
            <div class="summary-card">
                <div class="avatar-wrapper">
                    <div class="avatar-large" id="avatarPreviewBox">
                        @if($user->foto)
                            <img src="{{ asset('storage/' . $user->foto) }}" alt="Avatar {{ $user->name }}" id="avatarImg">
                        @else
                            <span id="avatarInitialsText">{{ $user->inisial }}</span>
                        @endif
                    </div>
                </div>

                <h2 class="summary-name">{{ $user->name }}</h2>
                <p class="summary-code">{{ $user->kode_anggota }}</p>

                <div class="badge-status-pill {{ $user->status === 'aktif' ? 'aktif' : 'nonaktif' }}">
                    <span class="dot"></span>
                    <span>Anggota {{ $user->status ?? 'aktif' }}</span>
                </div>

                <div class="summary-meta-table">
                    <div class="summary-meta-row">
                        <span class="summary-meta-label">Bergabung</span>
                        <span class="summary-meta-value">
                            {{ $user->created_at ? $user->created_at->translatedFormat('d M Y') : '01 Sep 2026' }}
                        </span>
                    </div>
                    <div class="summary-meta-row">
                        <span class="summary-meta-label">Batas pinjam</span>
                        <span class="summary-meta-value">{{ $batasPinjam ?? 3 }} buku</span>
                    </div>
                </div>

                <!-- FORM UPLOAD FOTO -->
                <form id="formUploadFoto" action="{{ route('profile.foto') }}" method="POST" enctype="multipart/form-data" style="width: 100%;">
                    @csrf
                    <input type="file" name="foto" id="fotoInput" accept="image/png,image/jpeg,image/jpg,image/webp" style="display: none;" onchange="submitFotoOtomatis(this)">
                    <button type="button" class="btn-ubah-foto" onclick="document.getElementById('fotoInput').click()">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                            <circle cx="12" cy="13" r="4"></circle>
                        </svg>
                        <span>Ubah Foto</span>
                    </button>
                </form>

                @if($user->foto)
                    <form action="{{ route('profile.foto.destroy') }}" method="POST" style="margin-top: 6px;" onsubmit="return confirm('Hapus foto profil dan gunakan inisial nama?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-hapus-foto">Hapus Foto</button>
                    </form>
                @endif
            </div>

            <!-- PRIVASI DATA ANGGOTA -->
            <div class="privacy-card">
                <h3 class="privacy-title">Privasi data anggota</h3>
                <p class="privacy-desc">
                    NIK hanya digunakan untuk verifikasi. Hubungi petugas untuk memperbarui identitas resmi.
                </p>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- KOLOM KANAN: FORM DATA PRIBADI, KEAMANAN, PREFERENSI -->
        <!-- ============================================ -->
        <div>

            <!-- CARD 1: DATA PRIBADI -->
            <div class="profile-card-section">
                <h2 class="section-header-title">Data pribadi</h2>

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-grid-2">
                        <!-- Nama Lengkap -->
                        <div class="form-group">
                            <label class="form-label" for="inputName">Nama Lengkap</label>
                            <input type="text" id="inputName" name="name" class="form-control" value="{{ old('name', $user->name) }}" required placeholder="Nama Lengkap">
                            @error('name')
                                <span class="form-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- NIK -->
                        <div class="form-group">
                            <label class="form-label" for="inputNik">NIK</label>
                            @if(!empty($user->nik) && $user->role === 'member')
                                <input type="text" id="inputNik" name="nik" class="form-control is-readonly" value="{{ $user->nik }}" readonly title="NIK telah diverifikasi. Hubungi petugas jika ingin mengganti.">
                            @else
                                <input type="text" id="inputNik" name="nik" class="form-control" value="{{ old('nik', $user->nik) }}" placeholder="3273011505980004" maxlength="25">
                            @endif
                            @error('nik')
                                <span class="form-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label class="form-label" for="inputEmail">Email</label>
                            <input type="email" id="inputEmail" name="email" class="form-control" value="{{ old('email', $user->email) }}" required placeholder="rizky.pratama@email.com">
                            @error('email')
                                <span class="form-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Telepon -->
                        <div class="form-group">
                            <label class="form-label" for="inputTelepon">Telepon</label>
                            <input type="text" id="inputTelepon" name="noTelepon" class="form-control" value="{{ old('noTelepon', $user->noTelepon) }}" placeholder="0812 3456 7890">
                            @error('noTelepon')
                                <span class="form-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="form-group">
                            <label class="form-label" for="inputAlamat">Alamat</label>
                            <input type="text" id="inputAlamat" name="alamat" class="form-control" value="{{ old('alamat', $user->alamat) }}" placeholder="Jl. Melati No. 18, Bandung">
                            @error('alamat')
                                <span class="form-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="form-group">
                            <label class="form-label" for="inputTanggalLahir">Tanggal Lahir</label>
                            <input type="date" id="inputTanggalLahir" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', optional($user->tanggal_lahir)->format('Y-m-d')) }}">
                            @error('tanggal_lahir')
                                <span class="form-error-msg">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn-save-primary">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

            <!-- CARD 2: KEAMANAN AKUN -->
            <div class="profile-card-section">
                <h2 class="section-header-title">Keamanan akun</h2>

                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-grid-2">
                        <!-- Password Saat Ini -->
                        <div class="form-group">
                            <label class="form-label" for="inputCurrentPassword">Password Saat Ini</label>
                            <input type="password" id="inputCurrentPassword" name="current_password" class="form-control" required placeholder="••••••••••">
                            @error('current_password')
                                <span class="form-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password Baru -->
                        <div class="form-group">
                            <label class="form-label" for="inputNewPassword">Password Baru</label>
                            <input type="password" id="inputNewPassword" name="password" class="form-control" required placeholder="Minimal 8 karakter">
                            @error('password')
                                <span class="form-error-msg">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password Baru -->
                        <div class="form-group full-width">
                            <label class="form-label" for="inputConfirmPassword">Konfirmasi Password Baru</label>
                            <input type="password" id="inputConfirmPassword" name="password_confirmation" class="form-control" required placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <div style="margin-top: 18px;">
                        <button type="submit" class="btn-save-blue">
                            Ubah Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- CARD 3: PREFERENSI NOTIFIKASI -->
            <div class="profile-card-section">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px;">
                    <h2 class="section-header-title" style="margin-bottom: 0;">Preferensi notifikasi</h2>
                    <span id="notifStatusToast" class="notif-saved-toast">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>Tersimpan</span>
                    </span>
                </div>

                <form id="notifPreferencesForm" action="{{ route('profile.notifikasi') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="notif-options-list">
                        <!-- Checkbox 1: Jatuh tempo -->
                        <label class="notif-checkbox-label">
                            <span class="custom-checkbox">
                                <input type="checkbox" name="notif_jatuh_tempo" id="checkNotifJatuhTempo" value="1" {{ old('notif_jatuh_tempo', $user->notif_jatuh_tempo ?? true) ? 'checked' : '' }} onchange="autoSimpanNotifikasi()">
                                <span class="checkbox-mark">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </span>
                            </span>
                            <span>Pengingat jatuh tempo melalui email</span>
                        </label>

                        <!-- Checkbox 2: Koleksi baru -->
                        <label class="notif-checkbox-label">
                            <span class="custom-checkbox">
                                <input type="checkbox" name="notif_koleksi_baru" id="checkNotifKoleksiBaru" value="1" {{ old('notif_koleksi_baru', $user->notif_koleksi_baru ?? true) ? 'checked' : '' }} onchange="autoSimpanNotifikasi()">
                                <span class="checkbox-mark">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                </span>
                            </span>
                            <span>Informasi koleksi baru dan kegiatan perpustakaan</span>
                        </label>
                    </div>
                </form>
            </div>

        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
    // Submit otomatis saat user memilih file foto
    function submitFotoOtomatis(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Validasi ukuran sisi klien (maksimal 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran foto melebihi batas maksimal 2 MB.');
                input.value = '';
                return;
            }

            // Preview instan di avatar box sebelum refresh
            const reader = new FileReader();
            reader.onload = function(e) {
                const box = document.getElementById('avatarPreviewBox');
                box.innerHTML = `<img src="${e.target.result}" alt="Preview" style="width:100%; height:100%; object-fit:cover;">`;
            };
            reader.readAsDataURL(file);

            // Submit form
            document.getElementById('formUploadFoto').submit();
        }
    }

    // Auto-save preferensi notifikasi via AJAX dengan feedback toast
    function autoSimpanNotifikasi() {
        const form = document.getElementById('notifPreferencesForm');
        const token = form.querySelector('input[name="_token"]').value;
        const jatuhTempo = document.getElementById('checkNotifJatuhTempo').checked ? 1 : 0;
        const koleksiBaru = document.getElementById('checkNotifKoleksiBaru').checked ? 1 : 0;
        const toast = document.getElementById('notifStatusToast');

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'X-HTTP-Method-Override': 'PUT'
            },
            body: JSON.stringify({
                _method: 'PUT',
                notif_jatuh_tempo: jatuhTempo,
                notif_koleksi_baru: koleksiBaru
            })
        })
        .then(response => response.json())
        .then(data => {
            if (toast) {
                toast.classList.add('show');
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 2200);
            }
        })
        .catch(err => {
            console.error('Gagal menyimpan preferensi:', err);
        });
    }
</script>
@endsection
