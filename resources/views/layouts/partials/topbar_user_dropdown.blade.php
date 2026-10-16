<div class="topbar-user-wrap">
    <button type="button" class="topbar-user-badge" id="topbarUserBtn" aria-label="Menu akun {{ auth()->user()->name }}" aria-expanded="false" aria-haspopup="true">
        @if(auth()->user()->foto ?? false)
            <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Avatar" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
        @else
            {{ auth()->user()->inisial ?? strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
        @endif
    </button>

    <div class="topbar-user-dropdown" id="topbarUserDropdown">
        <div class="topbar-dropdown-header">
            <div class="topbar-dropdown-avatar">
                @if(auth()->user()->foto ?? false)
                    <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Avatar" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                @else
                    {{ auth()->user()->inisial ?? strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                @endif
            </div>
            <div class="topbar-dropdown-user-info">
                <span class="topbar-dropdown-user-name" title="{{ auth()->user()->name }}">{{ auth()->user()->name }}</span>
                <span class="topbar-dropdown-user-email" title="{{ auth()->user()->email }}">{{ auth()->user()->email }}</span>
            </div>
        </div>

        <div class="topbar-dropdown-role-bar">
            @if(auth()->user()->role === 'admin')
                <span class="topbar-role-badge topbar-role-badge--admin">
                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #6366f1; display: inline-block;"></span>
                    Admin Perpustakaan
                </span>
            @elseif(auth()->user()->role === 'petugas')
                <span class="topbar-role-badge topbar-role-badge--petugas">
                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
                    Petugas Sirkulasi
                </span>
            @else
                <span class="topbar-role-badge topbar-role-badge--member">
                    <span style="width: 6px; height: 6px; border-radius: 50%; background: #0f766e; display: inline-block;"></span>
                    Anggota Perpustakaan
                </span>
            @endif
        </div>

        <div class="topbar-dropdown-body">
            <a href="{{ route('profile.edit') }}" class="topbar-dropdown-item">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <span data-i18n="topbar.profile">Pengaturan Profil</span>
            </a>

            <a href="{{ route('profile.edit', ['tab' => 'preferences']) }}" class="topbar-dropdown-item">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
                <span data-i18n="topbar.preferences">Preferensi Aplikasi</span>
            </a>

            @if(auth()->user()->role === 'member')
                <a href="{{ route('member.kartu-saya') }}" class="topbar-dropdown-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    <span data-i18n="topbar.qrcode">QR Code Saya</span>
                </a>
            @endif

            <div class="topbar-dropdown-divider"></div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="topbar-dropdown-item logout">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span data-i18n="topbar.logout">Keluar</span>
                </button>
            </form>
        </div>
    </div>
</div>
