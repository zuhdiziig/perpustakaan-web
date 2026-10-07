<!-- SIDEBAR PANEL ADMIN -->
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
            <span>Panel Admin</span>
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
                <a href="{{ route('buku.index') }}" class="sidebar-link {{ request()->routeIs('buku.*') ? 'active' : '' }}">
                    <div class="sidebar-link-content">
                        <span class="sidebar-link-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                            </svg>
                        </span>
                        <span>Koleksi Buku</span>
                    </div>
                </a>
            </li>

            <li>
                <a href="{{ route('kategori.index') }}" class="sidebar-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                    <div class="sidebar-link-content">
                        <span class="sidebar-link-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                <line x1="7" y1="7" x2="7.01" y2="7"></line>
                            </svg>
                        </span>
                        <span>Kategori Buku</span>
                    </div>
                </a>
            </li>

            <li>
                <a href="{{ route('petugas.index') }}" class="sidebar-link {{ request()->routeIs('petugas.*') ? 'active' : '' }}">
                    <div class="sidebar-link-content">
                        <span class="sidebar-link-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <polyline points="17 11 19 13 23 9"></polyline>
                            </svg>
                        </span>
                        <span>Data Petugas</span>
                    </div>
                </a>
            </li>

            <li>
                <a href="{{ route('member.index') }}" class="sidebar-link {{ request()->routeIs('member.*') ? 'active' : '' }}">
                    <div class="sidebar-link-content">
                        <span class="sidebar-link-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </span>
                        <span>Anggota</span>
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
                <a href="{{ route('laporan.index') }}" class="sidebar-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                    <div class="sidebar-link-content">
                        <span class="sidebar-link-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="20" x2="18" y2="10"></line>
                                <line x1="12" y1="20" x2="12" y2="4"></line>
                                <line x1="6" y1="20" x2="6" y2="14"></line>
                            </svg>
                        </span>
                        <span>Laporan</span>
                    </div>
                </a>
            </li>

            <li>
                <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <div class="sidebar-link-content">
                        <span class="sidebar-link-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                        </span>
                        <span>Pengaturan</span>
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
                    {{ auth()->user()->inisial ?? 'AH' }}
                @endif
            </div>
            <div class="sidebar-user-details">
                <span class="sidebar-user-name" title="{{ auth()->user()->name }}">
                    {{ auth()->user()->name }}
                </span>
                <span class="sidebar-user-role">Admin</span>
            </div>
        </div>

        <button type="button" class="sidebar-user-menu-btn" id="userMenuBtn" aria-label="Menu akun admin" aria-expanded="false">
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
