{{-- ========================================================
     MODAL POP UP NOTIFIKASI REAL-TIME SIRKULASI MEMBER
     (Peminjaman Berhasil & Pengembalian Berhasil)
     ======================================================== --}}
@auth
@if(auth()->user()->role === 'member')
<style>
    .member-realtime-overlay {
        position: fixed;
        inset: 0;
        z-index: 999999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 16px;
        background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .member-realtime-overlay.show {
        display: flex;
        opacity: 1;
    }

    .member-realtime-modal {
        position: relative;
        background: #ffffff;
        border-radius: 24px;
        width: 100%;
        max-width: 440px;
        padding: 28px 24px 24px;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.4), 0 0 0 1px rgba(15, 23, 42, 0.08);
        text-align: center;
        transform: scale(0.92);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: 10;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Plus Jakarta Sans", sans-serif;
    }

    .member-realtime-overlay.show .member-realtime-modal {
        transform: scale(1);
    }

    .member-realtime-close {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #f1f5f9;
        border: none;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .member-realtime-close:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .member-realtime-icon-box {
        position: relative;
        width: 72px;
        height: 72px;
        margin: 0 auto 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .member-realtime-pulse {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: #10b981;
        opacity: 0.25;
        animation: memberRealtimePulse 2s infinite ease-out;
    }

    @keyframes memberRealtimePulse {
        0% { transform: scale(0.95); opacity: 0.5; }
        50% { transform: scale(1.3); opacity: 0; }
        100% { transform: scale(0.95); opacity: 0; }
    }

    .member-realtime-circle {
        position: relative;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 22px rgba(16, 185, 129, 0.4);
    }

    .member-realtime-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        background: #dcfce7;
        color: #15803d;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    .member-realtime-badge .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #16a34a;
    }

    .member-realtime-title {
        font-size: 21px;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.3px;
        margin: 0 0 6px;
        line-height: 1.25;
    }

    .member-realtime-desc {
        font-size: 13px;
        color: #64748b;
        line-height: 1.5;
        margin: 0 0 16px;
    }

    .member-realtime-info {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 13px 15px;
        margin-bottom: 16px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        text-align: left;
    }

    .member-realtime-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 12.5px;
    }

    .member-realtime-row .label {
        color: #64748b;
        font-weight: 500;
    }

    .member-realtime-row .val {
        color: #0f172a;
        font-weight: 700;
    }

    .member-realtime-row .val.highlight {
        color: #0f766e;
    }

    .member-realtime-books-list {
        font-size: 12px;
        color: #334155;
        border-top: 1px dashed #cbd5e1;
        padding-top: 7px;
        margin-top: 2px;
        max-height: 80px;
        overflow-y: auto;
    }

    .member-realtime-countdown-box {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 14px;
    }

    .member-realtime-countdown-box b {
        color: #0f766e;
        font-weight: 800;
    }

    .member-realtime-btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        background: #0f766e;
        color: #ffffff;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
        box-shadow: 0 4px 14px rgba(15, 118, 110, 0.3);
    }

    .member-realtime-btn-primary:hover {
        background: #115e59;
        color: #ffffff;
        transform: translateY(-1px);
    }
</style>

{{-- HTML Modal --}}
<div class="member-realtime-overlay" id="globalMemberSirkulasiModal" aria-modal="true" role="dialog">
    <div class="member-realtime-modal">
        <button type="button" class="member-realtime-close" onclick="closeMemberRealtimeModal()" title="Tutup Notifikasi">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <div class="member-realtime-icon-box">
            <div class="member-realtime-pulse"></div>
            <div class="member-realtime-circle">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
        </div>

        <div class="member-realtime-badge">
            <span class="dot"></span>
            <span id="globalNotifBadgeText">SUKSES DIVERIFIKASI</span>
        </div>

        <h2 class="member-realtime-title" id="globalNotifTitle">
            Peminjaman Berhasil!
        </h2>

        <p class="member-realtime-desc" id="globalNotifDesc">
            Transaksi Anda telah berhasil diproses oleh petugas perpustakaan di meja sirkulasi.
        </p>

        <div class="member-realtime-info">
            <div class="member-realtime-row">
                <span class="label">Kode Transaksi:</span>
                <span class="val highlight" id="globalNotifKode">-</span>
            </div>
            <div class="member-realtime-row">
                <span class="label">Petugas Meja Sirkulasi:</span>
                <span class="val" id="globalNotifPetugas">-</span>
            </div>
            <div class="member-realtime-row">
                <span class="label">Total Buku:</span>
                <span class="val" id="globalNotifTotalBuku">-</span>
            </div>
            <div class="member-realtime-row" id="globalNotifBatasRow">
                <span class="label" id="globalNotifDateLabel">Batas Pengembalian:</span>
                <span class="val highlight" id="globalNotifDateVal">-</span>
            </div>
            <div class="member-realtime-books-list" id="globalNotifDaftarBuku" style="display: none;">
                {{-- Daftar Judul Buku --}}
            </div>
        </div>

        <div class="member-realtime-countdown-box">
            Beralih ke Dashboard Anggota dalam <b id="globalNotifCountdown">5</b> detik...
        </div>

        <a href="{{ route('dashboard') }}" class="member-realtime-btn-primary">
            <span>Buka Dashboard Anggota</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"></line>
                <polyline points="12 5 19 12 12 19"></polyline>
            </svg>
        </a>
    </div>
</div>

<script>
(function() {
    let globalNotifTimer = null;
    let globalNotifCountdownInterval = null;
    let isPolling = false;
    const displayedBookingApprovalNotifications = new Set();
    const pageOpenTime = Math.floor(Date.now() / 1000);

    // Audio Chime (C5 -> E5 -> G5)
    function playGlobalSuccessChime() {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const audioCtx = new AudioCtx();
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
            const playTone = (freq, start, duration) => {
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = 'sine';
                osc.frequency.value = freq;
                gain.gain.setValueAtTime(0.12, audioCtx.currentTime + start);
                gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + start + duration);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start(audioCtx.currentTime + start);
                osc.stop(audioCtx.currentTime + start + duration);
            };
            playTone(523.25, 0, 0.16);
            playTone(659.25, 0.12, 0.16);
            playTone(783.99, 0.24, 0.45);
        } catch (e) {
            // ignore audio restriction
        }
    }

    function showGlobalSirkulasiPopup(type, data) {
        playGlobalSuccessChime();
        const overlay = document.getElementById('globalMemberSirkulasiModal');
        if (!overlay) return;

        const titleEl = document.getElementById('globalNotifTitle');
        const descEl = document.getElementById('globalNotifDesc');
        const kodeEl = document.getElementById('globalNotifKode');
        const petugasEl = document.getElementById('globalNotifPetugas');
        const totalBukuEl = document.getElementById('globalNotifTotalBuku');
        const dateLabelEl = document.getElementById('globalNotifDateLabel');
        const dateValEl = document.getElementById('globalNotifDateVal');
        const daftarBukuEl = document.getElementById('globalNotifDaftarBuku');
        const countdownEl = document.getElementById('globalNotifCountdown');

        if (type === 'peminjaman') {
            titleEl.textContent = 'Peminjaman Berhasil!';
            descEl.textContent = 'Buku fisik telah diserahkan oleh petugas di meja sirkulasi dan masa pinjaman Anda kini aktif.';
            kodeEl.textContent = data.kode || ('PJ-' + data.id);
            petugasEl.textContent = data.namaPetugas || 'Petugas Meja Sirkulasi';
            totalBukuEl.textContent = (data.totalBuku || 1) + ' Buku Fisik';
            dateLabelEl.textContent = 'Batas Pengembalian:';
            dateValEl.textContent = data.batasKembali || '-';
        } else {
            titleEl.textContent = 'Pengembalian Berhasil!';
            descEl.textContent = 'Buku fisik telah diterima dan pengembalian Anda telah selesai diverifikasi oleh petugas.';
            kodeEl.textContent = 'KB-' + data.id;
            petugasEl.textContent = data.namaPetugas || 'Petugas Meja Sirkulasi';
            totalBukuEl.textContent = (data.totalBuku || 1) + ' Buku Selesai';
            dateLabelEl.textContent = 'Waktu Verifikasi:';
            dateValEl.textContent = data.waktuSelesai || '-';
        }

        if (data.daftarBuku && data.daftarBuku.length > 0) {
            daftarBukuEl.style.display = 'block';
            daftarBukuEl.replaceChildren(...data.daftarBuku.map(judul => {
                const item = document.createElement('div');
                item.textContent = `• ${judul}`;
                return item;
            }));
        } else {
            daftarBukuEl.style.display = 'none';
        }

        overlay.classList.add('show');

        // Countdown 5 detik lalu redirect ke Dashboard Anggota
        let countdown = 5;
        if (countdownEl) countdownEl.textContent = countdown;
        if (globalNotifCountdownInterval) clearInterval(globalNotifCountdownInterval);
        globalNotifCountdownInterval = setInterval(() => {
            countdown--;
            if (countdownEl) countdownEl.textContent = countdown;
            if (countdown <= 0) {
                clearInterval(globalNotifCountdownInterval);
            }
        }, 1000);

        if (globalNotifTimer) clearTimeout(globalNotifTimer);
        globalNotifTimer = setTimeout(() => {
            window.location.href = "{{ route('dashboard') }}";
        }, 5000);
    }

    function showBookingApprovalToast(notification) {
        const stack = document.getElementById('member-feedback-toasts');
        if (!stack) return false;

        const toast = document.createElement('div');
        toast.className = 'member-feedback-toast';
        toast.dataset.status = 'success';
        toast.setAttribute('role', 'status');

        const icon = document.createElement('span');
        icon.className = 'member-feedback-toast-icon';
        icon.setAttribute('aria-hidden', 'true');
        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.setAttribute('viewBox', '0 0 24 24');
        svg.setAttribute('width', '18');
        svg.setAttribute('height', '18');
        svg.setAttribute('fill', 'none');
        svg.setAttribute('stroke', 'currentColor');
        svg.setAttribute('stroke-width', '2');
        svg.setAttribute('stroke-linecap', 'round');
        svg.setAttribute('stroke-linejoin', 'round');
        const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        path.setAttribute('d', 'M20 6 9 17l-5-5');
        svg.append(path);
        icon.append(svg);

        const message = document.createElement('div');
        message.className = 'member-feedback-toast-message';
        const title = document.createElement('strong');
        title.textContent = notification.title || 'Peminjaman disetujui';
        const details = document.createElement('div');
        const books = Array.isArray(notification.books)
            ? notification.books.filter((book) => typeof book === 'string' && book.trim() !== '')
            : [];
        details.textContent = `Status: ${notification.status || 'Siap Diambil'}${books.length ? ` · ${books.join(', ')}` : ''}`;
        message.append(title, details);

        const close = document.createElement('button');
        close.className = 'member-feedback-toast-close';
        close.type = 'button';
        close.setAttribute('aria-label', 'Tutup notifikasi');
        close.textContent = '×';

        const dismiss = () => {
            toast.classList.add('is-leaving');
            toast.addEventListener('animationend', () => toast.remove(), { once: true });
            window.setTimeout(() => toast.remove(), 250);
        };

        close.addEventListener('click', dismiss);
        toast.append(icon, message, close);
        stack.append(toast);
        window.setTimeout(dismiss, 10000);

        return true;
    }

    function acknowledgeBookingApproval(notificationId) {
        const urlTemplate = "{{ route('api.member.notifikasi-peminjaman.dibaca', ['id' => '__NOTIFICATION_ID__']) }}";
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        return fetch(urlTemplate.replace('__NOTIFICATION_ID__', encodeURIComponent(notificationId)), {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken || '',
            },
        });
    }

    window.closeMemberRealtimeModal = function() {
        if (globalNotifTimer) clearTimeout(globalNotifTimer);
        if (globalNotifCountdownInterval) clearInterval(globalNotifCountdownInterval);
        const overlay = document.getElementById('globalMemberSirkulasiModal');
        if (overlay) {
            overlay.classList.remove('show');
        }
    };

    // Polling setiap 3 detik
    function pollSirkulasiStatus() {
        if (isPolling) return;

        isPolling = true;
        const url = "{{ route('api.member.status-sirkulasi-terbaru') }}";
        fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json())
        .then(res => {
            if (!res || !res.success) return;

            const approval = Array.isArray(res.notifications) ? res.notifications[0] : null;
            if (approval && typeof approval.id === 'string') {
                const seenKey = `booknest_booking_approval_${approval.id}`;
                let seenAt = 0;

                try {
                    seenAt = Number(localStorage.getItem(seenKey) || 0);
                } catch (e) {
                    // Continue with database acknowledgement when browser storage is unavailable.
                }

                const alreadyDisplayed = displayedBookingApprovalNotifications.has(approval.id);
                if (!alreadyDisplayed && (!seenAt || Date.now() - seenAt > 30 * 24 * 60 * 60 * 1000)) {
                    if (!showBookingApprovalToast(approval)) return;
                    displayedBookingApprovalNotifications.add(approval.id);

                    try {
                        localStorage.setItem(seenKey, String(Date.now()));
                    } catch (e) {
                        // Database read state remains the durable duplicate guard.
                    }
                }

                acknowledgeBookingApproval(approval.id)
                    .then(response => {
                        if (!response.ok) throw new Error('Unable to acknowledge notification');
                    })
                    .catch(() => {
                        try {
                            localStorage.removeItem(seenKey);
                        } catch (e) {
                            // The unread notification will be retried on the next poll.
                        }
                    });

                return;
            }

            // 1. Cek Peminjaman Baru
            if (res.peminjaman) {
                const key = 'notif_pj_' + res.peminjaman.id + '_' + res.peminjaman.timestamp;
                const wasNotified = sessionStorage.getItem(key);
                // Trigger jika belum pernah ditampilkan pada sesi ini dan terjadi saat/setelah halaman dibuka (toleransi 10 detik sebelumnya)
                if (!wasNotified && (res.peminjaman.timestamp >= (pageOpenTime - 10))) {
                    sessionStorage.setItem(key, '1');
                    showGlobalSirkulasiPopup('peminjaman', res.peminjaman);
                    return;
                }
            }

            // 2. Cek Pengembalian Baru
            if (res.pengembalian) {
                const key = 'notif_ret_' + res.pengembalian.id + '_' + res.pengembalian.timestamp;
                const wasNotified = sessionStorage.getItem(key);
                if (!wasNotified && (res.pengembalian.timestamp >= (pageOpenTime - 10))) {
                    sessionStorage.setItem(key, '1');
                    showGlobalSirkulasiPopup('pengembalian', res.pengembalian);
                    return;
                }
            }
        })
        .catch(() => {
            // Silent error on network hiccup
        })
        .finally(() => {
            isPolling = false;
        });
    }

    // Mulai polling setelah DOM siap
    document.addEventListener('DOMContentLoaded', function() {
        setInterval(pollSirkulasiStatus, 3000);
    });
})();
</script>
@endif
@endauth
