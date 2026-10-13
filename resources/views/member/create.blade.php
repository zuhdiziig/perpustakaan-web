@extends(auth()->user()->role === 'admin' ? 'layouts.admin' : 'layouts.petugas')

@section('title', 'Tambah Akun Member - BOOKNEST')

@section('styles')
<style>
    .member-form-container {
        max-width: 820px;
        margin: 0 auto;
        padding-bottom: 40px;
    }

    /* --- BREADCRUMB & HEADER --- */
    .page-header {
        margin-bottom: 24px;
    }

    .breadcrumb {
        font-size: 11.5px;
        font-weight: 700;
        color: #0f766e;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .breadcrumb a {
        color: inherit;
        text-decoration: none;
        transition: color 0.15s;
    }

    .breadcrumb a:hover {
        color: #115e59;
        text-decoration: underline;
    }

    .page-title {
        font-size: 26px;
        font-weight: 800;
        color: var(--text-heading, #0f172a);
        letter-spacing: -0.5px;
        line-height: 1.2;
        margin-bottom: 6px;
    }

    .page-subtitle {
        font-size: 13.5px;
        color: var(--text-muted, #64748b);
        max-width: 650px;
        line-height: 1.5;
    }

    /* --- INFO CARD --- */
    .info-banner {
        background: linear-gradient(135deg, #f0fdfa 0%, #e6fffa 100%);
        border: 1px solid #ccfbf1;
        border-radius: 14px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }

    .info-banner-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #0f766e;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .info-banner-content h4 {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f766e;
        margin-bottom: 2px;
    }

    .info-banner-content p {
        font-size: 12.5px;
        color: #334155;
        line-height: 1.45;
        margin: 0;
    }

    /* --- FORM CARD --- */
    .form-card {
        background: #ffffff;
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        padding: 30px 32px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .form-card-header {
        padding-bottom: 18px;
        margin-bottom: 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-card-header-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: #f0fdfa;
        color: #0f766e;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-card-title {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-group-full {
        grid-column: span 2;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .form-label .required {
        color: #dc2626;
    }

    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon-left {
        position: absolute;
        left: 14px;
        color: #94a3b8;
        pointer-events: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .form-input {
        width: 100%;
        padding: 11px 14px 11px 40px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 13.5px;
        color: #0f172a;
        background: #ffffff;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
        font-family: inherit;
        box-sizing: border-box;
    }

    .form-input.no-icon {
        padding-left: 14px;
    }

    .form-input:focus {
        border-color: #0f766e;
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
    }

    .form-input.is-invalid {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .form-input::placeholder {
        color: #94a3b8;
    }

    .form-textarea {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        font-size: 13.5px;
        color: #0f172a;
        background: #ffffff;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
        font-family: inherit;
        box-sizing: border-box;
        resize: vertical;
        min-height: 85px;
    }

    .form-textarea:focus {
        border-color: #0f766e;
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
    }

    .form-textarea.is-invalid {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .password-toggle-btn {
        position: absolute;
        right: 12px;
        background: none;
        border: none;
        cursor: pointer;
        color: #94a3b8;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.15s;
    }

    .password-toggle-btn:hover {
        color: #475569;
    }

    .form-error {
        font-size: 12px;
        font-weight: 600;
        color: #dc2626;
        margin-top: 2px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .form-helper {
        font-size: 11.5px;
        color: #64748b;
        margin-top: 3px;
    }

    /* --- FORM ACTIONS --- */
    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 30px;
        padding-top: 22px;
        border-top: 1px solid #f1f5f9;
    }

    .btn-submit-save {
        padding: 11px 24px;
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
        padding: 11px 20px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background 0.15s;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    @media (max-width: 768px) {
        .form-card {
            padding: 22px 20px;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .form-group-full {
            grid-column: span 1;
        }

        .form-actions {
            flex-direction: column-reverse;
            gap: 10px;
        }

        .btn-submit-save, .btn-cancel {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<div class="member-form-container">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div class="breadcrumb">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <a href="{{ route('member.index') }}">Direktori Keanggotaan</a>
            <span>/</span>
            <span>Tambah Akun Member</span>
        </div>
        <h1 class="page-title">Tambah Akun Member</h1>
        <p class="page-subtitle">Daftarkan akun anggota perpustakaan baru untuk memberikan akses peminjaman buku katalog dan kartu perpustakaan digital.</p>
    </div>

    <!-- INFO BANNER -->
    <div class="info-banner">
        <div class="info-banner-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                <line x1="7" y1="8" x2="17" y2="8"></line>
                <line x1="7" y1="12" x2="13" y2="12"></line>
                <circle cx="16" cy="16" r="1.5"></circle>
            </svg>
        </div>
        <div class="info-banner-content">
            <h4>Aktivasi Otomatis & Kartu Digital</h4>
            <p>Member yang ditambahkan akan otomatis berstatus <strong>Aktif</strong> dan sistem langsung membuat token QR unik untuk verifikasi identitas di meja sirkulasi.</p>
        </div>
    </div>

    <!-- FORM CARD -->
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-header-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="11" cy="7" r="4"></circle>
                    <line x1="19" y1="8" x2="19" y2="14"></line>
                    <line x1="22" y1="11" x2="16" y2="11"></line>
                </svg>
            </div>
            <h2 class="form-card-title">Informasi Profil Anggota</h2>
        </div>

        <form action="{{ route('member.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <!-- NAMA LENGKAP -->
                <div class="form-group">
                    <label class="form-label" for="name">
                        Nama Lengkap <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon-left">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </span>
                        <input type="text"
                               id="name"
                               name="name"
                               class="form-input @error('name') is-invalid @enderror"
                               placeholder="Contoh: Rian Pratama"
                               value="{{ old('name') }}"
                               required
                               autocomplete="name">
                    </div>
                    @error('name')
                        <span class="form-error">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- EMAIL -->
                <div class="form-group">
                    <label class="form-label" for="email">
                        Alamat Email <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon-left">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                            </svg>
                        </span>
                        <input type="email"
                               id="email"
                               name="email"
                               class="form-input @error('email') is-invalid @enderror"
                               placeholder="anggota@email.com"
                               value="{{ old('email') }}"
                               required
                               autocomplete="email">
                    </div>
                    @error('email')
                        <span class="form-error">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- PASSWORD AWAL -->
                <div class="form-group">
                    <label class="form-label" for="password">
                        Password Awal <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon-left">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </span>
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-input @error('password') is-invalid @enderror"
                               style="padding-right: 42px;"
                               placeholder="Minimal 6 karakter"
                               required
                               autocomplete="new-password">
                        <button type="button"
                                class="password-toggle-btn"
                                onclick="togglePasswordVisibility('password', this)"
                                title="Lihat/Sembunyikan password"
                                aria-label="Lihat kata sandi">
                            <svg class="eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="eye-closed" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <span class="form-error">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            {{ $message }}
                        </span>
                    @else
                        <span class="form-helper">Password dapat diubah sendiri oleh anggota setelah masuk.</span>
                    @enderror
                </div>

                <!-- NOMOR TELEPON -->
                <div class="form-group">
                    <label class="form-label" for="noTelepon">
                        Nomor Telepon / WhatsApp <span class="required">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon-left">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                        </span>
                        <input type="text"
                               id="noTelepon"
                               name="noTelepon"
                               class="form-input @error('noTelepon') is-invalid @enderror"
                               placeholder="Contoh: 081234567890"
                               value="{{ old('noTelepon') }}"
                               required
                               autocomplete="tel">
                    </div>
                    @error('noTelepon')
                        <span class="form-error">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- ALAMAT DOMISILI -->
                <div class="form-group form-group-full">
                    <label class="form-label" for="alamat">
                        Alamat Domisili <span class="required">*</span>
                    </label>
                    <textarea id="alamat"
                              name="alamat"
                              class="form-textarea @error('alamat') is-invalid @enderror"
                              rows="3"
                              placeholder="Masukkan alamat lengkap domisili tempat tinggal anggota..."
                              required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <span class="form-error">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="form-actions">
                <a href="{{ route('member.index') }}" class="btn-cancel">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Batal
                </a>
                <button type="submit" class="btn-submit-save">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Simpan Member
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (!input) return;
        const eyeOpen = btn.querySelector('.eye-open');
        const eyeClosed = btn.querySelector('.eye-closed');
        if (input.type === 'password') {
            input.type = 'text';
            if (eyeOpen) eyeOpen.style.display = 'none';
            if (eyeClosed) eyeClosed.style.display = 'block';
        } else {
            input.type = 'password';
            if (eyeOpen) eyeOpen.style.display = 'block';
            if (eyeClosed) eyeClosed.style.display = 'none';
        }
    }
</script>
@endsection