<!-- SIDEBAR PANEL ANGGOTA -->
<aside class="sidebar" id="sidebarPanel">
    <div class="sidebar-header">
        <div class="sidebar-header-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
            </svg>
        </div>
        <div class="sidebar-header-text">
            <h3>BOOKNEST</h3>
            <span>Panel Anggota</span>
        </div>
    </div>

    <!-- MENU UTAMA -->
    <div>
        <div class="sidebar-section-title">Utama</div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <div class="sidebar-link-content">
                        <span class="sidebar-link-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                        </span>
                        <span>Dasbor</span>
                    </div>
                </a>
            </li>

            <li>
                <a href="{{ route('katalog.index') }}" class="sidebar-link {{ request()->routeIs('katalog.*') ? 'active' : '' }}">
                    <div class="sidebar-link-content">
                        <span class="sidebar-link-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                <line x1="9" y1="6" x2="15" y2="6"></line>
                                <line x1="9" y1="10" x2="15" y2="10"></line>
                            </svg>
                        </span>
                        <span>Katalog Buku</span>
                    </div>
                </a>
            </li>

            <li>
                <a href="{{ route('riwayat.index', ['status' => 'Dipinjam']) }}" class="sidebar-link {{ (request()->routeIs('riwayat.*') && request('status') === 'Dipinjam') || request()->routeIs('peminjaman.konfirmasi') ? 'active' : '' }}">
                    <div class="sidebar-link-content">
                        <span class="sidebar-link-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="16 3 21 3 21 8"></polyline>
                                <line x1="4" y1="20" x2="21" y2="3"></line>
                                <polyline points="21 16 21 21 16 21"></polyline>
                                <line x1="15" y1="15" x2="21" y2="21"></line>
                                <line x1="4" y1="4" x2="9" y2="9"></line>
                            </svg>
                        </span>
                        <span>Peminjaman</span>
                    </div>
                </a>
            </li>

            <li>
                <a href="{{ route('pengembalian.member') }}" class="sidebar-link {{ request()->routeIs('pengembalian.member*') ? 'active' : '' }}">
                    <div class="sidebar-link-content">
                        <span class="sidebar-link-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                                <path d="M21 3v5h-5"></path>
                                <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                                <path d="M3 21v-5h5"></path>
                            </svg>
                        </span>
                        <span>Pengembalian</span>
                    </div>
                </a>
            </li>

            <li>
                <a href="{{ route('riwayat.index') }}" class="sidebar-link {{ request()->routeIs('riwayat.*') && request('status') !== 'Dipinjam' ? 'active' : '' }}">
                    <div class="sidebar-link-content">
                        <span class="sidebar-link-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </span>
                        <span>Riwayat</span>
                    </div>
                </a>
            </li>
        </ul>
    </div>

    <!-- MENU LAINNYA -->
    <div>
        <div class="sidebar-section-title">Lainnya</div>
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('denda.saya') }}" class="sidebar-link {{ request()->routeIs('denda.*', 'bayar.*', 'pembayaran.nota') ? 'active' : '' }}">
                    <div class="sidebar-link-content">
                        <span class="sidebar-link-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                            </svg>
                        </span>
                        <span>Denda</span>
                    </div>
                </a>
            </li>

            <li>
                <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <div class="sidebar-link-content">
                        <span class="sidebar-link-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </span>
                        <span>Profil</span>
                    </div>
                </a>
            </li>
        </ul>
    </div>

    <!-- USER CARD BOTTOM -->
    <div class="sidebar-user">
        <div class="sidebar-user-left">
            <div class="sidebar-avatar">
                @if(auth()->user()->foto ?? false)
                    <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Avatar">
                @else
                    {{ auth()->user()->inisial ?? 'RP' }}
                @endif
            </div>
            <div class="sidebar-user-details">
                <span class="sidebar-user-name" title="{{ auth()->user()->name }}">
                    {{ auth()->user()->name }}
                </span>
                <span class="sidebar-user-role">Anggota</span>
            </div>
        </div>

        <button type="button" class="sidebar-user-menu-btn" id="userMenuBtn" aria-label="Menu akun anggota" aria-expanded="false">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                <circle cx="5" cy="12" r="2"></circle>
                <circle cx="12" cy="12" r="2"></circle>
                <circle cx="19" cy="12" r="2"></circle>
            </svg>
        </button>

        <!-- USER POPUP DROPDOWN -->
        <div class="user-dropdown" id="userDropdown">
            <a href="{{ route('profile.edit') }}" class="user-dropdown-item">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <span>Edit Profil</span>
            </a>

            <a href="{{ route('member.kartu-saya') }}" class="user-dropdown-item">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                <span>QR Code Saya</span>
            </a>

            <div class="user-dropdown-divider"></div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="user-dropdown-item logout">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </div>
</aside>
