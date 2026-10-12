{{-- MODAL POPUP MESSAGE COMPACT UNTUK ANGGOTA (BOOKING PEMINJAMAN & PENGEMBALIAN) --}}
<div id="memberBookingPopupModal" class="booking-popup-overlay" style="display: none;">
    <div class="booking-popup-card" role="dialog" aria-modal="true" aria-labelledby="bookingPopupTitle">
        <button type="button" class="booking-popup-close-x" onclick="closeBookingPopup()" aria-label="Tutup notifikasi">
            ✕
        </button>

        <div class="booking-popup-icon-wrap">
            <div class="booking-popup-icon-pulse"></div>
            <div class="booking-popup-icon-circle">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
        </div>

        <h3 id="bookingPopupTitle" class="booking-popup-title">
            Booking Berhasil
        </h3>

        <p id="bookingPopupMessage" class="booking-popup-message">
            booking peminjaman berhasil, silahkan scan QR saat pengambilan.
        </p>

        <div class="booking-popup-action">
            <button type="button" class="btn-booking-popup-ok" onclick="closeBookingPopup()">
                Oke, Mengerti
            </button>
        </div>
    </div>
</div>

<style>
    /* Styling Pop Up Message Ringkas & Elegan */
    .booking-popup-overlay {
        position: fixed;
        inset: 0;
        z-index: 99999;
        background: rgba(15, 23, 42, 0.45);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.25s ease, visibility 0.25s ease;
    }

    .booking-popup-overlay.show {
        opacity: 1;
        visibility: visible;
        display: flex !important;
    }

    .booking-popup-card {
        position: relative;
        width: 100%;
        max-width: 370px;
        background: #ffffff;
        border-radius: 18px;
        padding: 24px 22px 20px;
        text-align: center;
        box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.22), 0 0 0 1px rgba(0, 0, 0, 0.05);
        transform: scale(0.92) translateY(8px);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .booking-popup-overlay.show .booking-popup-card {
        transform: scale(1) translateY(0);
    }

    .booking-popup-close-x {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        font-size: 13px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: background 0.15s, color 0.15s, transform 0.15s;
    }

    .booking-popup-close-x:hover {
        background: #e2e8f0;
        color: #0f172a;
        transform: scale(1.05);
    }

    .booking-popup-icon-wrap {
        position: relative;
        width: 58px;
        height: 58px;
        margin: 0 auto 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .booking-popup-icon-pulse {
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        background: #ccfbf1;
        opacity: 0.6;
        animation: pulsePopupIcon 2s infinite ease-in-out;
    }

    @keyframes pulsePopupIcon {
        0% { transform: scale(0.95); opacity: 0.7; }
        50% { transform: scale(1.1); opacity: 0.3; }
        100% { transform: scale(0.95); opacity: 0.7; }
    }

    .booking-popup-icon-circle {
        position: relative;
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 18px -4px rgba(15, 118, 110, 0.45);
    }

    .booking-popup-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin: 0 0 8px;
        letter-spacing: -0.3px;
    }

    .booking-popup-message {
        font-size: 13.5px;
        line-height: 1.55;
        color: #475569;
        margin: 0 0 20px;
        padding: 0 6px;
        word-break: break-word;
    }

    .booking-popup-action {
        display: flex;
        justify-content: center;
    }

    .btn-booking-popup-ok {
        width: 100%;
        padding: 10px 18px;
        background: #0f766e;
        color: #ffffff;
        font-size: 13.5px;
        font-weight: 700;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(15, 118, 110, 0.25);
        transition: background 0.15s, transform 0.15s, box-shadow 0.15s;
    }

    .btn-booking-popup-ok:hover {
        background: #115e59;
        box-shadow: 0 6px 16px rgba(15, 118, 110, 0.35);
        transform: translateY(-1px);
    }

    .btn-booking-popup-ok:active {
        transform: translateY(0);
    }
</style>

<script>
    (function() {
        let onCloseCallback = null;

        window.showBookingPopup = function(message, options) {
            options = options || {};
            const title = options.title || 'Booking Berhasil';
            const modal = document.getElementById('memberBookingPopupModal');
            const titleEl = document.getElementById('bookingPopupTitle');
            const msgEl = document.getElementById('bookingPopupMessage');

            if (titleEl) titleEl.innerText = title;
            if (msgEl) msgEl.innerText = message;

            if (typeof options.onClose === 'function') {
                onCloseCallback = options.onClose;
            } else {
                onCloseCallback = null;
            }

            if (modal) {
                modal.classList.add('show');
            }
        };

        window.closeBookingPopup = function() {
            const modal = document.getElementById('memberBookingPopupModal');
            if (modal) {
                modal.classList.remove('show');
            }
            if (typeof onCloseCallback === 'function') {
                const cb = onCloseCallback;
                onCloseCallback = null;
                cb();
            }
        };

        // Tutup modal jika klik di backdrop
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('memberBookingPopupModal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeBookingPopup();
                    }
                });
            }

            @if(session('booking_popup_message'))
                window.showBookingPopup(@json(session('booking_popup_message')), {
                    title: @json(session('booking_popup_title') ?? 'Booking Berhasil')
                });
            @endif
        });
    })();
</script>
