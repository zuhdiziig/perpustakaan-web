<!-- SIDEBAR PANEL PETUGAS -->
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
            <span>Panel Petugas</span>
        </div>
    </div>

    <!-- MENU UTAMA -->
    <div>
        <div class="sidebar-section-title">Layanan Sirkulasi</div>
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
                    @php
                        $jumlahAntreanBookingSidebar = \App\Models\Peminjaman::whereIn('status', ['Booking', 'Siap Diambil'])->count();
                    @endphp
                    @if($jumlahAntreanBookingSidebar > 0)
                        <span style="display: inline-flex; align-items: center; justify-content: center; min-width: 20px; height: 20px; padding: 0 6px; font-size: 11px; font-weight: 700; border-radius: 9999px; background: #fef3c7; color: #b45309; border: 1px solid #fde68a;" title="{{ $jumlahAntreanBookingSidebar }} Booking Menunggu">
                            {{ $jumlahAntreanBookingSidebar }}
                        </span>
                    @endif
                </a>
            </li>

            <li>
                <a href="{{ route('barcode.scan') }}" class="sidebar-link {{ request()->routeIs('barcode.*') ? 'active' : '' }}">
                    <div class="sidebar-link-content">
                        <span class="sidebar-link-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 7V5a2 2 0 0 1 2-2h2"></path>
                                <path d="M17 3h2a2 2 0 0 1 2 2v2"></path>
                                <path d="M21 17v2a2 2 0 0 1-2 2h-2"></path>
                                <path d="M7 21H5a2 2 0 0 1-2-2v-2"></path>
                                <line x1="7" y1="12" x2="17" y2="12"></line>
                            </svg>
                        </span>
                        <span>Scan Barcode</span>
                    </div>
                </a>
            </li>

            <li>
                <a href="{{ route('peminjaman.create') }}" class="sidebar-link {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
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
                <a href="{{ route('pengembalian.create') }}" class="sidebar-link {{ request()->routeIs('pengembalian.*') ? 'active' : '' }}">
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
                <a href="{{ route('denda.index') }}" class="sidebar-link {{ request()->routeIs('denda.*') ? 'active' : '' }}">
                    <div class="sidebar-link-content">
                        <span class="sidebar-link-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                        </span>
                        <span>Kelola Denda</span>
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
                        <span>Data Anggota</span>
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
        </ul>
    </div>

    <!-- MENU LAINNYA -->
    <div>
        <div class="sidebar-section-title">Lainnya</div>
        <ul class="sidebar-menu">
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
                    {{ auth()->user()->inisial ?? 'PT' }}
                @endif
            </div>
            <div class="sidebar-user-details">
                <span class="sidebar-user-name" title="{{ auth()->user()->name }}">
                    {{ auth()->user()->name }}
                </span>
                <span class="sidebar-user-role">Petugas</span>
            </div>
        </div>

        <button type="button" class="sidebar-user-menu-btn" id="userMenuBtn" aria-label="Menu akun petugas" aria-expanded="false">
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
