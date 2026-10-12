<!-- UNIVERSAL SEARCH MODAL (Buku, Peminjaman, Pengembalian, Denda) -->
<div id="universalSearchModal" class="universal-search-backdrop" style="display: none;" onclick="closeUniversalSearchModal(event)">
    <div class="universal-search-card" onclick="event.stopPropagation()">
        <!-- Search Input Bar -->
        <div class="universal-search-input-wrap">
            <svg class="search-input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text"
                   id="universalSearchInput"
                   class="universal-search-input"
                   placeholder="Cari buku, nomor peminjaman, pengembalian, atau denda..."
                   autocomplete="off"
                   oninput="handleUniversalSearchInput(this.value)">
            <button type="button" class="search-btn-clear" id="universalSearchClearBtn" onclick="clearUniversalSearch()" style="display: none;" aria-label="Hapus kata kunci">
                &times;
            </button>
            <button type="button" class="search-btn-close-kbd" onclick="closeUniversalSearchModal()" aria-label="Tutup pencarian">
                ESC
            </button>
        </div>

        <!-- Category Tabs Bar -->
        <div class="universal-search-tabs">
            <button type="button" class="search-tab-pill active" data-tab="semua" onclick="switchUniversalSearchTab('semua', this)">
                <span>Semua</span>
            </button>
            <button type="button" class="search-tab-pill" data-tab="buku" onclick="switchUniversalSearchTab('buku', this)">
                <span class="tab-pill-icon">📚</span><span>Buku</span>
            </button>
            <button type="button" class="search-tab-pill" data-tab="peminjaman" onclick="switchUniversalSearchTab('peminjaman', this)">
                <span class="tab-pill-icon">📋</span><span>Peminjaman</span>
            </button>
            <button type="button" class="search-tab-pill" data-tab="pengembalian" onclick="switchUniversalSearchTab('pengembalian', this)">
                <span class="tab-pill-icon">🔄</span><span>Pengembalian</span>
            </button>
            <button type="button" class="search-tab-pill" data-tab="denda" onclick="switchUniversalSearchTab('denda', this)">
                <span class="tab-pill-icon">💳</span><span>Denda</span>
            </button>
        </div>

        <!-- Search Results / States Body -->
        <div class="universal-search-body" id="universalSearchBody">
            <!-- Initial Empty State -->
            <div id="universalSearchInitial" class="search-empty-state">
                <div class="search-empty-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <h4 style="font-size: 15px; font-weight: 700; color: #0f172a; margin: 0 0 4px;">Pencarian Terpadu Anggota</h4>
                <p style="font-size: 13px; color: #64748b; margin: 0 0 16px;">Ketik kata kunci untuk mencari seluruh data transaksi dan katalog buku Anda.</p>
                <div class="quick-suggestion-chips">
                    <button type="button" onclick="setUniversalSearchQuery('Algoritma')">Algoritma</button>
                    <button type="button" onclick="setUniversalSearchQuery('Booking')">Booking Saya</button>
                    <button type="button" onclick="setUniversalSearchQuery('Denda')">Tagihan Denda</button>
                    <button type="button" onclick="setUniversalSearchQuery('Tersedia')">Buku Tersedia</button>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div id="universalSearchLoading" class="search-loading-state" style="display: none;">
                <div class="search-spinner"></div>
                <p style="font-size: 13px; color: #64748b; margin: 8px 0 0;">Mencari data sistem...</p>
            </div>

            <!-- Results Container -->
            <div id="universalSearchResults" style="display: none;"></div>
        </div>

        <!-- Footer Info -->
        <div class="universal-search-footer">
            <span>Gunakan tombol <kbd>ESC</kbd> untuk menutup</span>
            <a href="{{ route('katalog.index') }}" class="footer-katalog-link">Buka Katalog Lengkap &rarr;</a>
        </div>
    </div>
</div>

<style>
    .universal-search-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.45);
        z-index: 1000;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 60px 16px 20px;
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        animation: fadeIn 0.15s ease;
    }

    .universal-search-card {
        background: #ffffff;
        border-radius: 18px;
        width: 100%;
        max-width: 640px;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        animation: searchModalPop 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        max-height: 80vh;
    }

    @keyframes searchModalPop {
        from { opacity: 0; transform: scale(0.96) translateY(-8px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    .universal-search-input-wrap {
        display: flex;
        align-items: center;
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        gap: 12px;
        background: #ffffff;
    }

    .search-input-icon {
        color: #0f766e;
        flex-shrink: 0;
    }

    .universal-search-input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 15px;
        font-weight: 600;
        color: #0f172a;
        background: transparent;
    }

    .universal-search-input::placeholder {
        color: #94a3b8;
        font-weight: 500;
    }

    .search-btn-clear {
        background: #f1f5f9;
        border: none;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        color: #64748b;
        cursor: pointer;
    }

    .search-btn-close-kbd {
        padding: 3px 8px;
        border-radius: 6px;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        cursor: pointer;
    }

    .universal-search-tabs {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .universal-search-tabs::-webkit-scrollbar {
        display: none;
    }

    .search-tab-pill {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        height: 32px !important;
        line-height: 1 !important;
        padding: 0 14px !important;
        border-radius: 9999px !important;
        font-size: 12.5px !important;
        font-weight: 600 !important;
        color: #475569 !important;
        background: #ffffff !important;
        border: 1px solid #cbd5e1 !important;
        cursor: pointer !important;
        white-space: nowrap !important;
        box-sizing: border-box !important;
        vertical-align: middle !important;
        transition: all 0.15s ease !important;
        text-decoration: none !important;
    }

    .search-tab-pill .tab-pill-icon {
        font-size: 13px !important;
        line-height: 1 !important;
        display: inline-flex !important;
        align-items: center !important;
    }

    .search-tab-pill:hover {
        color: #0f172a !important;
        background: #f1f5f9 !important;
        border-color: #94a3b8 !important;
    }

    .search-tab-pill.active {
        background: #0f766e !important;
        color: #ffffff !important;
        border-color: #0f766e !important;
        font-weight: 700 !important;
        box-shadow: 0 2px 6px rgba(15, 118, 110, 0.2) !important;
    }

    .universal-search-body {
        padding: 16px 20px;
        overflow-y: auto;
        flex: 1;
        min-height: 220px;
    }

    .search-empty-state, .search-loading-state {
        text-align: center;
        padding: 36px 16px;
    }

    .search-empty-icon {
        width: 52px;
        height: 52px;
        border-radius: 16px;
        background: #f0fdfa;
        color: #0f766e;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
    }

    .quick-suggestion-chips {
        display: flex;
        gap: 8px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .quick-suggestion-chips button {
        padding: 5px 12px;
        border-radius: 8px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        font-size: 12px;
        font-weight: 600;
        color: #334155;
        cursor: pointer;
        transition: background 0.15s;
    }

    .quick-suggestion-chips button:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .search-spinner {
        width: 26px;
        height: 26px;
        border: 3px solid #e2e8f0;
        border-top-color: #0f766e;
        border-radius: 50%;
        margin: 0 auto;
        animation: spin 0.7s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .search-category-section {
        margin-bottom: 18px;
    }

    .search-cat-title {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #64748b;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .search-item-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        border-radius: 10px;
        text-decoration: none;
        color: inherit;
        transition: background 0.15s;
        gap: 12px;
    }

    .search-item-link:hover {
        background: #f8fafc;
    }

    .search-item-main {
        min-width: 0;
        flex: 1;
    }

    .search-item-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .search-item-sub {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
    }

    .search-item-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .badge-teal { background: #ccfbf1; color: #0f766e; }
    .badge-amber { background: #fef3c7; color: #b45309; }
    .badge-rose { background: #ffe4e6; color: #be123c; }
    .badge-slate { background: #f1f5f9; color: #475569; }

    .universal-search-footer {
        padding: 12px 20px;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12px;
        color: #94a3b8;
    }

    .universal-search-footer kbd {
        background: #e2e8f0;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: 700;
        color: #475569;
    }

    .footer-katalog-link {
        color: #0f766e;
        font-weight: 700;
        text-decoration: none;
    }

    .footer-katalog-link:hover {
        text-decoration: underline;
    }
</style>

<script>
    let currentUniversalSearchTab = 'semua';
    let universalSearchDebounceTimer = null;

    function openUniversalSearchModal() {
        const modal = document.getElementById('universalSearchModal');
        const input = document.getElementById('universalSearchInput');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            if (input) {
                setTimeout(() => input.focus(), 80);
            }
        }
    }

    function closeUniversalSearchModal(e) {
        if (!e || e.target.id === 'universalSearchModal' || e.target.closest('.search-btn-close-kbd')) {
            const modal = document.getElementById('universalSearchModal');
            if (modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        }
    }

    function clearUniversalSearch() {
        const input = document.getElementById('universalSearchInput');
        if (input) {
            input.value = '';
            input.focus();
            handleUniversalSearchInput('');
        }
    }

    function setUniversalSearchQuery(q) {
        const input = document.getElementById('universalSearchInput');
        if (input) {
            input.value = q;
            input.focus();
            handleUniversalSearchInput(q);
        }
    }

    function switchUniversalSearchTab(tab, btn) {
        currentUniversalSearchTab = tab;
        document.querySelectorAll('.search-tab-pill').forEach(b => b.classList.remove('active'));
        if (btn) btn.classList.add('active');

        const input = document.getElementById('universalSearchInput');
        if (input && input.value.trim().length > 0) {
            executeUniversalSearch(input.value.trim());
        }
    }

    function handleUniversalSearchInput(query) {
        const clearBtn = document.getElementById('universalSearchClearBtn');
        if (clearBtn) {
            clearBtn.style.display = query.length > 0 ? 'flex' : 'none';
        }

        clearTimeout(universalSearchDebounceTimer);
        const q = query.trim();

        if (q.length === 0) {
            document.getElementById('universalSearchInitial').style.display = 'block';
            document.getElementById('universalSearchLoading').style.display = 'none';
            document.getElementById('universalSearchResults').style.display = 'none';
            return;
        }

        universalSearchDebounceTimer = setTimeout(() => {
            executeUniversalSearch(q);
        }, 220);
    }

    async function executeUniversalSearch(q) {
        const initialEl = document.getElementById('universalSearchInitial');
        const loadingEl = document.getElementById('universalSearchLoading');
        const resultsEl = document.getElementById('universalSearchResults');

        initialEl.style.display = 'none';
        loadingEl.style.display = 'block';
        resultsEl.style.display = 'none';

        try {
            const res = await fetch(`{{ route('member.universal-search') }}?q=${encodeURIComponent(q)}&tab=${currentUniversalSearchTab}`);
            const data = await res.json();

            loadingEl.style.display = 'none';
            resultsEl.style.display = 'block';

            let html = '';
            let totalFound = 0;

            // 1. Buku
            if (data.buku && data.buku.length > 0) {
                totalFound += data.buku.length;
                html += `
                    <div class="search-category-section">
                        <div class="search-cat-title">📚 Koleksi Buku (${data.buku.length})</div>
                        ${data.buku.map(b => `
                            <a href="${b.url}" class="search-item-link">
                                <div class="search-item-main">
                                    <div class="search-item-title">${escapeHtml(b.judul)}</div>
                                    <div class="search-item-sub">${escapeHtml(b.penulis)} · ${escapeHtml(b.kategori)} · ${escapeHtml(b.rak)}</div>
                                </div>
                                <span class="search-item-badge ${b.stok > 0 ? 'badge-teal' : 'badge-rose'}">
                                    ${b.stok > 0 ? b.stok + ' Tersedia' : 'Dipinjam'}
                                </span>
                            </a>
                        `).join('')}
                    </div>
                `;
            }

            // 2. Peminjaman
            if (data.peminjaman && data.peminjaman.length > 0) {
                totalFound += data.peminjaman.length;
                html += `
                    <div class="search-category-section">
                        <div class="search-cat-title">📋 Peminjaman Saya (${data.peminjaman.length})</div>
                        ${data.peminjaman.map(p => `
                            <a href="${p.url}" class="search-item-link">
                                <div class="search-item-main">
                                    <div class="search-item-title">${escapeHtml(p.kode)} — ${escapeHtml(p.buku)}</div>
                                    <div class="search-item-sub">${p.batas_kembali ? 'Jatuh tempo: ' + escapeHtml(p.batas_kembali) : 'Booking menunggu ambil'}</div>
                                </div>
                                <span class="search-item-badge ${p.status === 'Dipinjam' ? 'badge-amber' : (p.status === 'Selesai' ? 'badge-teal' : 'badge-slate')}">
                                    ${escapeHtml(p.status)}
                                </span>
                            </a>
                        `).join('')}
                    </div>
                `;
            }

            // 3. Pengembalian
            if (data.pengembalian && data.pengembalian.length > 0) {
                totalFound += data.pengembalian.length;
                html += `
                    <div class="search-category-section">
                        <div class="search-cat-title">🔄 Pengembalian (${data.pengembalian.length})</div>
                        ${data.pengembalian.map(r => `
                            <a href="${r.url}" class="search-item-link">
                                <div class="search-item-main">
                                    <div class="search-item-title">${escapeHtml(r.buku)}</div>
                                    <div class="search-item-sub">Kembali: ${escapeHtml(r.tanggal)} · Kondisi: ${escapeHtml(r.kondisi)}</div>
                                </div>
                                <span class="search-item-badge badge-teal">Dikembalikan</span>
                            </a>
                        `).join('')}
                    </div>
                `;
            }

            // 4. Denda
            if (data.denda && data.denda.length > 0) {
                totalFound += data.denda.length;
                html += `
                    <div class="search-category-section">
                        <div class="search-cat-title">💳 Denda & Tagihan (${data.denda.length})</div>
                        ${data.denda.map(d => `
                            <a href="${d.url}" class="search-item-link">
                                <div class="search-item-main">
                                    <div class="search-item-title">${escapeHtml(d.buku)}</div>
                                    <div class="search-item-sub">${escapeHtml(d.jenis)} · ${escapeHtml(d.nominal)}</div>
                                </div>
                                <span class="search-item-badge ${d.status === 'Lunas' ? 'badge-teal' : 'badge-rose'}">
                                    ${escapeHtml(d.status)}
                                </span>
                            </a>
                        `).join('')}
                    </div>
                `;
            }

            if (totalFound === 0) {
                resultsEl.innerHTML = `
                    <div class="search-empty-state">
                        <p style="font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">Tidak Ada Data Ditemukan</p>
                        <p style="font-size: 13px; color: #64748b; margin: 0;">Tidak ditemukan hasil untuk "${escapeHtml(q)}". Coba kata kunci lain atau pilih tab "Semua".</p>
                    </div>
                `;
            } else {
                resultsEl.innerHTML = html;
            }

        } catch (err) {
            loadingEl.style.display = 'none';
            resultsEl.style.display = 'block';
            resultsEl.innerHTML = `
                <div class="search-empty-state">
                    <p style="font-size: 13px; color: #dc2626;">Gagal memuat hasil pencarian. Silakan coba lagi.</p>
                </div>
            `;
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // Keyboard shortcut Escape to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeUniversalSearchModal();
        }
    });
</script>
