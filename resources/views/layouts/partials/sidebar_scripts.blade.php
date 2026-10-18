<script>
    // Immediate Theme, Font, Font-Size & Language Initialization (runs before DOM to prevent FOUC)
    (function () {
        try {
            var prefs = null;
            try {
                var raw = localStorage.getItem('booknest_preferences');
                if (raw) prefs = JSON.parse(raw);
            } catch (e) {}

            var themeChoice = (prefs && prefs.theme) ? prefs.theme : (localStorage.getItem('booknest_theme') || 'light');
            var effectiveTheme = themeChoice;
            if (themeChoice === 'system') {
                effectiveTheme = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
            }
            document.documentElement.setAttribute('data-theme', effectiveTheme);
            document.documentElement.setAttribute('data-theme-choice', themeChoice);

            var savedFont = (prefs && prefs.font) ? prefs.font : (localStorage.getItem('booknest_font') || 'Inter');
            document.documentElement.setAttribute('data-font', savedFont);

            var savedSize = (prefs && prefs.fontSize) ? prefs.fontSize : (localStorage.getItem('booknest_font_size') || 'normal');
            document.documentElement.setAttribute('data-font-size', savedSize);

            var savedCat = (prefs && prefs.openingCategory) ? prefs.openingCategory : 'cewek';
            var savedOpening = (prefs && prefs.opening) ? prefs.opening : (localStorage.getItem('booknest_opening') || 'coquette-blush');
            // Legacy opening mapping if needed
            if (savedOpening === 'soft-mint-glow') savedOpening = 'midnight-blue';
            else if (savedOpening === 'pastel-playful') savedOpening = 'sakura-bloom';
            else if (savedOpening === 'candy-gradient') savedOpening = 'strawberry-milk';

            document.documentElement.setAttribute('data-opening-cat', savedCat);
            document.documentElement.setAttribute('data-opening', savedOpening);

            var savedLang = (prefs && prefs.lang) ? prefs.lang : (localStorage.getItem('booknest_lang') || 'id');
            document.documentElement.setAttribute('lang', savedLang);

            if (sessionStorage.getItem('bn_navigated')) {
                document.documentElement.classList.add('bn-navigated');
            }
        } catch (e) {}
    })();

    // Frontend Internationalization Dictionary
    window.BOOKNEST_I18N = {
        id: {
            'nav.main': 'Utama',
            'nav.dashboard': 'Dasbor',
            'nav.books': 'Kelola Buku',
            'nav.categories': 'Kelola Kategori',
            'nav.staff': 'Kelola Petugas',
            'nav.members': 'Kelola Anggota',
            'nav.other': 'Lainnya',
            'nav.reports': 'Laporan',
            'nav.settings': 'Pengaturan',
            'nav.fines': 'Kelola Denda',
            'nav.my_fines': 'Denda',
            'nav.inventory': 'Inventory Buku',
            'nav.history': 'Riwayat',
            'nav.circulation': 'Sirkulasi Buku',
            'nav.borrow': 'Peminjaman',
            'nav.return': 'Pengembalian',
            'nav.booking_cart': 'Keranjang Booking',
            'nav.my_returns': 'Pengembalian Saya',
            'topbar.home': 'Beranda',
            'topbar.catalog': 'Katalog',
            'topbar.about': 'Tentang',
            'topbar.guide': 'Panduan',
            'topbar.profile': 'Pengaturan Profil',
            'topbar.preferences': 'Preferensi Aplikasi',
            'topbar.qrcode': 'QR Code Saya',
            'topbar.logout': 'Keluar',
            'pref.tab_profile': 'Profil Akun',
            'pref.tab_preferences': 'Preferensi Aplikasi',
            'pref.title': 'Preferensi Aplikasi',
            'pref.subtitle': 'Atur tema tampilan antarmuka, tipografi, layar pembuka, dan preferensi bahasa aplikasi BookNest pada peramban ini.',
            'pref.theme_title': 'Tema Tampilan Antarmuka',
            'pref.theme_desc': 'Pilih tampilan visual yang paling nyaman untuk mata Anda saat menggunakan sistem perpustakaan.',
            'pref.light_title': 'Mode Terang (Light Mode)',
            'pref.light_desc': 'Tampilan bersih, cerah, dan kontras tinggi untuk penggunaan di ruangan berpenerangan cukup.',
            'pref.dark_title': 'Mode Gelap (Dark Mode)',
            'pref.dark_desc': 'Tampilan nyaman di mata dengan dominasi warna gelap, meredakan ketegangan mata saat penggunaan malam hari.',
            'pref.system_title': 'Mengikuti Sistem (System)',
            'pref.system_desc': 'Menyesuaikan mode terang atau gelap secara otomatis dengan preferensi tema sistem operasi perangkat Anda.',
            'pref.soft_blush_title': 'Soft Blush',
            'pref.soft_blush_desc': 'Palet dusty rose & warm ivory yang tenang, lembut, elegan, dan nyaman dibaca.',
            'pref.van_gogh_title': 'Van Gogh (Starry Night)',
            'pref.van_gogh_desc': 'Nuansa malam berbintang The Starry Night dengan sapuan biru kobalt, deep navy, dan aksen emas artistik.',
            'pref.midnight_slate_title': 'Midnight Slate',
            'pref.midnight_slate_desc': 'Nuansa slate blue & steel modern, tegas, profesional, dan teduh untuk fokus kerja.',
            'pref.active': 'Sedang Digunakan',
            'pref.use_this': 'Gunakan Pilihan Ini',
            'pref.font_title': 'Font & Tipografi',
            'pref.font_desc': 'Pilih jenis huruf yang Anda sukai untuk seluruh teks dan antarmuka BookNest.',
            'pref.font_size_title': 'Ukuran Teks',
            'pref.font_size_desc': 'Sesuaikan skala ukuran huruf untuk kenyamanan membaca optimal tanpa merusak susunan tata letak.',
            'pref.font_size_small': 'Kecil',
            'pref.font_size_normal': 'Normal (Standar)',
            'pref.font_size_large': 'Besar',
            'pref.preview_title': 'Pratinjau Tipografi Langsung',
            'pref.preview_desc': 'Perubahan jenis dan ukuran huruf dapat dilihat secara langsung pada kotak contoh di bawah ini:',
            'pref.preview_sample': 'BookNest — Ruang Pengetahuan Terbuka, Katalog Eksemplar & Sirkulasi Perpustakaan Cepat.',
            'pref.preview_badge': 'Status Aktif',
            'pref.opening_title': 'Konsep Layar Pembuka (Opening)',
            'pref.opening_desc': 'Pilih konsep animasi visual splash curtain pembuka saat memuat aplikasi BookNest. Pilihan ini hanya memengaruhi splash pembuka, bukan tema dashboard.',
            'pref.opening_cat_label': 'Kategori Gaya Opening:',
            'pref.opening_cat_cewek': 'Feminine',
            'pref.opening_cat_cowo': 'Masculine',
            'pref.opening_cat_artistic': 'Artistik (Van Gogh)',
            'pref.opening_coquette_blush_title': 'Coquette Blush',
            'pref.opening_coquette_blush_desc': 'Baby pink, cream, rose gold dengan kilau mutiara lembut dan nuansa satin elegan.',
            'pref.opening_fairy_dream_title': 'Fairy Dream',
            'pref.opening_fairy_dream_desc': 'Lavender, baby pink, dan pearl white dengan efek dreamy lembut nan magis.',
            'pref.opening_sakura_bloom_title': 'Sakura Bloom',
            'pref.opening_sakura_bloom_desc': 'Sakura pink, putih bersih, dan soft mint dengan kesan hembusan kelopak bunga tenang.',
            'pref.opening_strawberry_milk_title': 'Strawberry Milk',
            'pref.opening_strawberry_milk_desc': 'Strawberry pink, peach, dan milky white dengan sentuhan glossy playful ceria.',
            'pref.opening_pink_garden_title': 'Pink Garden',
            'pref.opening_pink_garden_desc': 'Dusty pink, sage green, dan cream dengan paduan botani yang anggun dan estetik.',
            'pref.opening_midnight_blue_title': 'Midnight Blue',
            'pref.opening_midnight_blue_desc': 'Navy pekat dan biru safir gelap dengan aksen dingin yang bersih dan maskulin.',
            'pref.opening_cyber_neon_title': 'Cyber Neon',
            'pref.opening_cyber_neon_desc': 'Dark slate, electric cyan, dan aksen neon berenergi tinggi yang futuristik.',
            'pref.opening_dark_steel_title': 'Dark Steel',
            'pref.opening_dark_steel_desc': 'Charcoal, abu-abu baja, dan sentuhan silver industrial yang kokoh.',
            'pref.opening_forest_mode_title': 'Forest Mode',
            'pref.opening_forest_mode_desc': 'Deep forest green, pinus, dan sage natural yang meneduhkan dan fokus.',
            'pref.opening_sunset_ember_title': 'Sunset Ember',
            'pref.opening_sunset_ember_desc': 'Burnt orange, amber keemasan, dan charcoal hangat seperti bara senja.',
            'pref.opening_van_gogh_starry_night_title': 'Van Gogh — Starry Night',
            'pref.opening_van_gogh_starry_night_desc': 'Sapuan langit malam biru kobalt, bulan sabit emas, dan pendar bintang penuh inspirasi nan syahdu.',
            'pref.btn_preview_opening': 'Coba Opening Ini',
            'pref.lang_title': 'Bahasa Aplikasi',
            'pref.lang_desc': 'Pilih bahasa antarmuka peramban untuk navigasi menu, tombol aksi, dan kontrol sistem.',
            'pref.lang_id_title': 'Bahasa Indonesia',
            'pref.lang_id_desc': 'Pengaturan bawaan standar untuk seluruh teks antarmuka navigasi dan kontrol BookNest.',
            'pref.lang_en_title': 'English (Beta Interface)',
            'pref.lang_en_desc': 'Translates navigation menus, action buttons, and interface labels into English.',
            'pref.notice_title': 'Informasi Penerapan Bahasa Frontend',
            'pref.notice_desc': 'Pilihan bahasa ini diterapkan pada elemen antarmuka frontend (menu sidebar, navbar, tombol aksi, dan kontrol sistem). Data dinamis server (seperti judul buku, sinopsis, nama kategori, dan catatan sirkulasi) tetap ditampilkan dalam bahasa asli tanpa memodifikasi backend perpustakaan.',
            'pref.btn_reset': 'Reset ke Default',
            'pref.applied_toast': 'Preferensi berhasil diterapkan & disimpan otomatis!',
            'pref.reset_toast': 'Preferensi personalisasi dikembalikan ke setelan awal.',
            'btn.save': 'Simpan Perubahan',
            'btn.change_password': 'Ubah Password',
            'btn.change_photo': 'Ubah Foto',
            'btn.delete_photo': 'Hapus Foto'
        },
        en: {
            'nav.main': 'Main Menu',
            'nav.dashboard': 'Dashboard',
            'nav.books': 'Manage Books',
            'nav.categories': 'Manage Categories',
            'nav.staff': 'Manage Staff',
            'nav.members': 'Manage Members',
            'nav.other': 'Others',
            'nav.reports': 'Reports',
            'nav.settings': 'Settings',
            'nav.fines': 'Manage Fines',
            'nav.my_fines': 'Fines',
            'nav.inventory': 'Book Inventory',
            'nav.history': 'History',
            'nav.circulation': 'Book Circulation',
            'nav.borrow': 'Borrowing',
            'nav.return': 'Returns',
            'nav.booking_cart': 'Booking Cart',
            'nav.my_returns': 'My Returns',
            'topbar.home': 'Home',
            'topbar.catalog': 'Catalog',
            'topbar.about': 'About',
            'topbar.guide': 'User Guide',
            'topbar.profile': 'Profile Settings',
            'topbar.preferences': 'App Preferences',
            'topbar.qrcode': 'My QR Code',
            'topbar.logout': 'Sign Out',
            'pref.tab_profile': 'User Profile',
            'pref.tab_preferences': 'App Preferences',
            'pref.title': 'Application Preferences',
            'pref.subtitle': 'Customize the interface theme, typography, opening splash, and language preferences for BookNest on this browser.',
            'pref.theme_title': 'Interface Theme',
            'pref.theme_desc': 'Choose the visual appearance that is most comfortable for your eyes while using the library system.',
            'pref.light_title': 'Light Mode',
            'pref.light_desc': 'Clean, bright, and high-contrast interface designed for well-lit environments.',
            'pref.dark_title': 'Dark Mode',
            'pref.dark_desc': 'Comfortable dark palette designed to reduce eye fatigue during night-time usage.',
            'pref.system_title': 'Follow System (Auto)',
            'pref.system_desc': 'Automatically adjusts between light and dark modes to match your operating system preferences.',
            'pref.soft_blush_title': 'Soft Blush',
            'pref.soft_blush_desc': 'Gentle dusty rose and warm ivory palette: calming, mature, and easy on the eyes.',
            'pref.van_gogh_title': 'Van Gogh (Starry Night)',
            'pref.van_gogh_desc': 'Post-impressionist starry night aesthetic featuring cobalt blue, deep navy, and artistic golden accents.',
            'pref.midnight_slate_title': 'Midnight Slate',
            'pref.midnight_slate_desc': 'Modern slate blue and steel tones: crisp, structured, professional, and soothing.',
            'pref.active': 'In Use',
            'pref.use_this': 'Select This Option',
            'pref.font_title': 'Font & Typography',
            'pref.font_desc': 'Choose your preferred typeface family for reading menus, cards, and reports.',
            'pref.font_size_title': 'Text Size',
            'pref.font_size_desc': 'Scale typeface dimensions for optimal reading comfort without breaking interface layouts.',
            'pref.font_size_small': 'Compact',
            'pref.font_size_normal': 'Standard (Default)',
            'pref.font_size_large': 'Large',
            'pref.preview_title': 'Live Typography Preview',
            'pref.preview_desc': 'Real-time interactive preview of chosen font family and sizing scale:',
            'pref.preview_sample': 'BookNest — Open Knowledge Hub, Exemplar Catalog & Fast Library Circulation.',
            'pref.preview_badge': 'Active Status',
            'pref.opening_title': 'Opening Splash Concept',
            'pref.opening_desc': 'Select the introductory visual opening animation concept upon loading BookNest. This only affects the opening curtain, not the dashboard theme.',
            'pref.opening_cat_label': 'Opening Style Category:',
            'pref.opening_cat_cewek': 'Feminine',
            'pref.opening_cat_cowo': 'Masculine',
            'pref.opening_cat_artistic': 'Artistic (Van Gogh)',
            'pref.opening_coquette_blush_title': 'Coquette Blush',
            'pref.opening_coquette_blush_desc': 'Baby pink, cream, rose gold with soft satin glow and elegant pearl shimmer.',
            'pref.opening_fairy_dream_title': 'Fairy Dream',
            'pref.opening_fairy_dream_desc': 'Lavender, baby pink, and pearl white with dreamy delicate magic.',
            'pref.opening_sakura_bloom_title': 'Sakura Bloom',
            'pref.opening_sakura_bloom_desc': 'Sakura petals, crisp white, and soft mint with serene floral calmness.',
            'pref.opening_strawberry_milk_title': 'Strawberry Milk',
            'pref.opening_strawberry_milk_desc': 'Strawberry pink, fresh peach, and milky white with playful glossy charm.',
            'pref.opening_pink_garden_title': 'Pink Garden',
            'pref.opening_pink_garden_desc': 'Dusty pink, sage green, and cream with an aesthetic botanical garden feel.',
            'pref.opening_midnight_blue_title': 'Midnight Blue',
            'pref.opening_midnight_blue_desc': 'Deep navy and dark sapphire with crisp, clean, confident masculine lines.',
            'pref.opening_cyber_neon_title': 'Cyber Neon',
            'pref.opening_cyber_neon_desc': 'Dark slate, electric cyan, and controlled high-energy futuristic neon glow.',
            'pref.opening_dark_steel_title': 'Dark Steel',
            'pref.opening_dark_steel_desc': 'Charcoal, gunmetal gray, and industrial silver with solid metallic clarity.',
            'pref.opening_forest_mode_title': 'Forest Mode',
            'pref.opening_forest_mode_desc': 'Deep forest green, evergreen pine, and calming natural sage tones.',
            'pref.opening_sunset_ember_title': 'Sunset Ember',
            'pref.opening_sunset_ember_desc': 'Burnt orange, warm amber, and smoky charcoal evoking evening twilight.',
            'pref.opening_van_gogh_starry_night_title': 'Van Gogh — Starry Night',
            'pref.opening_van_gogh_starry_night_desc': 'Whirling cobalt night sky, golden crescent moon, and serene inspiring celestial stars.',
            'pref.btn_preview_opening': 'Test This Opening',
            'pref.lang_title': 'Application Language',
            'pref.lang_desc': 'Choose your preferred browser display language for navigation menus, action buttons, and system controls.',
            'pref.lang_id_title': 'Bahasa Indonesia',
            'pref.lang_id_desc': 'Default Indonesian language for all interface navigation and controls.',
            'pref.lang_en_title': 'English (Beta Interface)',
            'pref.lang_en_desc': 'Translates navigation menus, action buttons, and interface labels into English.',
            'pref.notice_title': 'Frontend Translation Notice & Scope',
            'pref.notice_desc': 'This language preference applies to frontend interface elements (sidebar menus, navbar, action buttons, and system controls). Dynamic database content (such as book titles, synopses, category names, and circulation records) remains displayed in their original source language without backend changes.',
            'pref.btn_reset': 'Reset to Defaults',
            'pref.applied_toast': 'Preferences applied & automatically saved!',
            'pref.reset_toast': 'Personalization preferences restored to defaults.',
            'btn.save': 'Save Changes',
            'btn.change_password': 'Change Password',
            'btn.change_photo': 'Change Photo',
            'btn.delete_photo': 'Remove Photo'
        }
    };

    // Global Toast Notification Helper
    window.showPrefToast = function (message) {
        let toast = document.getElementById('prefToastNotification');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'prefToastNotification';
            toast.className = 'pref-feedback-toast';
            document.body.appendChild(toast);
        }
        toast.innerHTML = `
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <span>${message}</span>
        `;
        toast.classList.add('show');
        clearTimeout(window.__prefToastTimer);
        window.__prefToastTimer = setTimeout(function () {
            toast.classList.remove('show');
        }, 2600);
    };

    // Safe Preferences Storage Manager
    window.BOOKNEST_PREF_DEFAULTS = {
        theme: 'system',
        font: 'Inter',
        fontSize: 'normal',
        openingCategory: 'cewek',
        opening: 'coquette-blush',
        lang: 'id'
    };

    window.BOOKNEST_OPENING_CONCEPTS = {
        cewek: [
            { id: 'coquette-blush', default: true },
            { id: 'fairy-dream' },
            { id: 'sakura-bloom' },
            { id: 'strawberry-milk' },
            { id: 'pink-garden' }
        ],
        cowo: [
            { id: 'midnight-blue', default: true },
            { id: 'cyber-neon' },
            { id: 'dark-steel' },
            { id: 'forest-mode' },
            { id: 'sunset-ember' }
        ],
        artistic: [
            { id: 'van-gogh-starry-night', default: true }
        ]
    };

    window.getBooknestPreferences = function () {
        const allowedThemes = ['system', 'light', 'dark', 'soft-blush', 'van-gogh', 'midnight-slate'];
        const allowedFonts = ['Inter', 'Poppins', 'Roboto', 'Open Sans', 'Lato'];
        const allowedSizes = ['small', 'normal', 'large'];
        const allowedCategories = ['cewek', 'cowo', 'artistic'];
        const allowedOpenings = [
            'coquette-blush', 'fairy-dream', 'sakura-bloom', 'strawberry-milk', 'pink-garden',
            'midnight-blue', 'cyber-neon', 'dark-steel', 'forest-mode', 'sunset-ember',
            'van-gogh-starry-night'
        ];
        const allowedLangs = ['id', 'en'];

        let prefs = Object.assign({}, window.BOOKNEST_PREF_DEFAULTS);
        try {
            const raw = localStorage.getItem('booknest_preferences');
            if (raw) {
                const parsed = JSON.parse(raw);
                if (parsed && typeof parsed === 'object') {
                    if (allowedThemes.indexOf(parsed.theme) !== -1) prefs.theme = parsed.theme;
                    if (allowedFonts.indexOf(parsed.font) !== -1) prefs.font = parsed.font;
                    if (allowedSizes.indexOf(parsed.fontSize) !== -1) prefs.fontSize = parsed.fontSize;
                    if (allowedCategories.indexOf(parsed.openingCategory) !== -1) prefs.openingCategory = parsed.openingCategory;
                    
                    // Legacy opening key compatibility
                    let op = parsed.opening;
                    if (op === 'soft-mint-glow') op = 'midnight-blue';
                    else if (op === 'pastel-playful') op = 'sakura-bloom';
                    else if (op === 'candy-gradient') op = 'strawberry-milk';

                    if (allowedOpenings.indexOf(op) !== -1) prefs.opening = op;
                    if (allowedLangs.indexOf(parsed.lang) !== -1) prefs.lang = parsed.lang;
                }
            } else {
                // Backward compatibility migration from legacy individual keys
                const legacyTheme = localStorage.getItem('booknest_theme');
                if (legacyTheme && allowedThemes.indexOf(legacyTheme) !== -1) prefs.theme = legacyTheme;
                const legacyLang = localStorage.getItem('booknest_lang');
                if (legacyLang && allowedLangs.indexOf(legacyLang) !== -1) prefs.lang = legacyLang;
                const legacyFont = localStorage.getItem('booknest_font');
                if (legacyFont && allowedFonts.indexOf(legacyFont) !== -1) prefs.font = legacyFont;
                const legacyOpening = localStorage.getItem('booknest_opening');
                if (legacyOpening) {
                    if (legacyOpening === 'soft-mint-glow') prefs.opening = 'midnight-blue';
                    else if (legacyOpening === 'pastel-playful') prefs.opening = 'sakura-bloom';
                    else if (legacyOpening === 'candy-gradient') prefs.opening = 'strawberry-milk';
                    else if (allowedOpenings.indexOf(legacyOpening) !== -1) prefs.opening = legacyOpening;
                }
            }
        } catch (e) {
            prefs = Object.assign({}, window.BOOKNEST_PREF_DEFAULTS);
        }

        // Validate concept belongs to category
        const cewekIds = ['coquette-blush', 'fairy-dream', 'sakura-bloom', 'strawberry-milk', 'pink-garden'];
        const cowoIds = ['midnight-blue', 'cyber-neon', 'dark-steel', 'forest-mode', 'sunset-ember'];
        const artisticIds = ['van-gogh-starry-night'];
        if (prefs.openingCategory === 'cewek' && cewekIds.indexOf(prefs.opening) === -1) {
            prefs.opening = 'coquette-blush';
        } else if (prefs.openingCategory === 'cowo' && cowoIds.indexOf(prefs.opening) === -1) {
            prefs.opening = 'midnight-blue';
        } else if (prefs.openingCategory === 'artistic' && artisticIds.indexOf(prefs.opening) === -1) {
            prefs.opening = 'van-gogh-starry-night';
        }

        return prefs;
    };

    window.saveBooknestPreferences = function (newPrefs) {
        const current = window.getBooknestPreferences();
        const merged = Object.assign({}, current, newPrefs);
        try {
            localStorage.setItem('booknest_preferences', JSON.stringify(merged));
            // Sync backward-compatible individual keys
            var effectiveTheme = merged.theme;
            if (effectiveTheme === 'system') {
                effectiveTheme = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
            }
            localStorage.setItem('booknest_theme', effectiveTheme);
            localStorage.setItem('booknest_lang', merged.lang);
            localStorage.setItem('booknest_font', merged.font);
            localStorage.setItem('booknest_font_size', merged.fontSize);
            localStorage.setItem('booknest_opening_category', merged.openingCategory);
            localStorage.setItem('booknest_opening', merged.opening);
        } catch (e) {}
        return merged;
    };

    // System theme listener reference
    window.__booknestSystemThemeListener = null;

    // Global Theme Setter Function
    window.setBooknestTheme = function (themeChoice, persist, showFeedback) {
        const allowed = ['system', 'light', 'dark', 'soft-blush', 'van-gogh', 'midnight-slate'];
        if (allowed.indexOf(themeChoice) === -1) themeChoice = 'system';

        let effectiveTheme = themeChoice;
        if (themeChoice === 'system') {
            effectiveTheme = (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) ? 'dark' : 'light';
        }

        document.documentElement.setAttribute('data-theme', effectiveTheme);
        document.documentElement.setAttribute('data-theme-choice', themeChoice);

        // Bind or unbind live system theme listener
        if (window.matchMedia) {
            const mq = window.matchMedia('(prefers-color-scheme: dark)');
            if (window.__booknestSystemThemeListener) {
                try {
                    if (mq.removeEventListener) mq.removeEventListener('change', window.__booknestSystemThemeListener);
                    else if (mq.removeListener) mq.removeListener(window.__booknestSystemThemeListener);
                } catch (e) {}
                window.__booknestSystemThemeListener = null;
            }

            if (themeChoice === 'system') {
                window.__booknestSystemThemeListener = function (e) {
                    const newTheme = e.matches ? 'dark' : 'light';
                    document.documentElement.setAttribute('data-theme', newTheme);
                    try { localStorage.setItem('booknest_theme', newTheme); } catch (err) {}
                    window.dispatchEvent(new CustomEvent('booknest:theme-changed', { detail: { theme: newTheme, choice: 'system' } }));
                };
                try {
                    if (mq.addEventListener) mq.addEventListener('change', window.__booknestSystemThemeListener);
                    else if (mq.addListener) mq.addListener(window.__booknestSystemThemeListener);
                } catch (e) {}
            }
        }

        if (persist) {
            window.saveBooknestPreferences({ theme: themeChoice });
        }

        // Update Theme Cards if on Preferences Tab
        const lightCard = document.getElementById('themeCardLight');
        const darkCard = document.getElementById('themeCardDark');
        const systemCard = document.getElementById('themeCardSystem');
        const softBlushCard = document.getElementById('themeCardSoftBlush');
        const vanGoghCard = document.getElementById('themeCardVanGogh');
        const midnightSlateCard = document.getElementById('themeCardMidnightSlate');
        const currentLang = document.documentElement.getAttribute('lang') || 'id';
        const dict = (window.BOOKNEST_I18N && window.BOOKNEST_I18N[currentLang]) ? window.BOOKNEST_I18N[currentLang] : (window.BOOKNEST_I18N ? window.BOOKNEST_I18N['id'] : {});
        const activeText = dict['pref.active'] || 'Sedang Digunakan';
        const useText = dict['pref.use_this'] || 'Gunakan Pilihan Ini';

        [
            { el: lightCard, key: 'light' },
            { el: darkCard, key: 'dark' },
            { el: systemCard, key: 'system' },
            { el: softBlushCard, key: 'soft-blush' },
            { el: vanGoghCard, key: 'van-gogh' },
            { el: midnightSlateCard, key: 'midnight-slate' }
        ].forEach(function (item) {
            if (item.el) {
                const badge = item.el.querySelector('.pref-option-badge');
                if (themeChoice === item.key) {
                    item.el.classList.add('active');
                    if (badge) badge.textContent = activeText;
                } else {
                    item.el.classList.remove('active');
                    if (badge) badge.textContent = useText;
                }
            }
        });

        if (showFeedback && window.showPrefToast) {
            let msg = '';
            if (themeChoice === 'system') {
                msg = (currentLang === 'en') ? 'Theme set to Follow System (Auto).' : 'Mode tema diatur Mengikuti Sistem secara otomatis.';
            } else if (themeChoice === 'dark') {
                msg = (currentLang === 'en') ? 'Dark Mode activated.' : 'Mode Gelap diaktifkan.';
            } else if (themeChoice === 'soft-blush') {
                msg = (currentLang === 'en') ? 'Soft Blush theme activated.' : 'Tema Soft Blush diaktifkan.';
            } else if (themeChoice === 'van-gogh') {
                msg = (currentLang === 'en') ? 'Van Gogh (Starry Night) theme activated.' : 'Tema Van Gogh (Starry Night) diaktifkan.';
            } else if (themeChoice === 'midnight-slate') {
                msg = (currentLang === 'en') ? 'Midnight Slate theme activated.' : 'Tema Midnight Slate diaktifkan.';
            } else {
                msg = (currentLang === 'en') ? 'Light Mode activated.' : 'Mode Terang diaktifkan.';
            }
            window.showPrefToast(msg);
        }

        window.dispatchEvent(new CustomEvent('booknest:theme-changed', { detail: { theme: effectiveTheme, choice: themeChoice } }));
    };

    // Global Font Setter
    window.setBooknestFont = function (fontName, persist, showFeedback) {
        const allowed = ['Inter', 'Poppins', 'Roboto', 'Open Sans', 'Lato'];
        if (allowed.indexOf(fontName) === -1) fontName = 'Inter';

        document.documentElement.setAttribute('data-font', fontName);

        if (persist) {
            window.saveBooknestPreferences({ font: fontName });
        }

        // Update Font cards in Preferences Tab
        allowed.forEach(function (f) {
            const card = document.getElementById('fontCard_' + f.replace(/\s+/g, ''));
            if (card) {
                if (f === fontName) {
                    card.classList.add('active');
                } else {
                    card.classList.remove('active');
                }
            }
        });

        // Update Live Preview box if present
        const previewBox = document.getElementById('prefLivePreviewText');
        if (previewBox) {
            previewBox.style.fontFamily = `'${fontName}', sans-serif`;
        }

        if (showFeedback) {
            const currentLang = document.documentElement.getAttribute('lang') || 'id';
            const msg = (currentLang === 'en') ? `Font changed to ${fontName}.` : `Jenis font diubah ke ${fontName}.`;
            window.showPrefToast(msg);
        }

        window.dispatchEvent(new CustomEvent('booknest:font-changed', { detail: { font: fontName } }));
    };

    // Global Font Size Setter
    window.setBooknestFontSize = function (sizeKey, persist, showFeedback) {
        const allowed = ['small', 'normal', 'large'];
        if (allowed.indexOf(sizeKey) === -1) sizeKey = 'normal';

        document.documentElement.setAttribute('data-font-size', sizeKey);

        if (persist) {
            window.saveBooknestPreferences({ fontSize: sizeKey });
        }

        allowed.forEach(function (s) {
            const btn = document.getElementById('fontSizeBtn_' + s);
            if (btn) {
                if (s === sizeKey) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            }
        });

        if (showFeedback) {
            const currentLang = document.documentElement.getAttribute('lang') || 'id';
            const sizeLabel = (sizeKey === 'small') ? 'Kecil' : (sizeKey === 'large' ? 'Besar' : 'Normal');
            const msg = (currentLang === 'en') ? `Text size set to ${sizeKey}.` : `Ukuran teks diatur ke ${sizeLabel}.`;
            window.showPrefToast(msg);
        }

        window.dispatchEvent(new CustomEvent('booknest:font-size-changed', { detail: { fontSize: sizeKey } }));
    };

    // Global Opening Category & Concept Setters
    window.setBooknestOpeningCategory = function (categoryKey, persist, showFeedback) {
        const allowedCategories = ['cewek', 'cowo', 'artistic'];
        if (allowedCategories.indexOf(categoryKey) === -1) categoryKey = 'cewek';

        document.documentElement.setAttribute('data-opening-cat', categoryKey);

        // Update dropdown element if exists
        const selectEl = document.getElementById('openingCategorySelect');
        if (selectEl && selectEl.value !== categoryKey) {
            selectEl.value = categoryKey;
        }

        // Toggle track visibility
        const cewekTrack = document.getElementById('openingTrack_cewek');
        const cowoTrack = document.getElementById('openingTrack_cowo');
        const artisticTrack = document.getElementById('openingTrack_artistic');
        if (cewekTrack) cewekTrack.style.display = (categoryKey === 'cewek') ? 'flex' : 'none';
        if (cowoTrack) cowoTrack.style.display = (categoryKey === 'cowo') ? 'flex' : 'none';
        if (artisticTrack) artisticTrack.style.display = (categoryKey === 'artistic') ? 'flex' : 'none';

        // Check if current concept belongs to the new category
        const currentPrefs = window.getBooknestPreferences();
        const cewekIds = ['coquette-blush', 'fairy-dream', 'sakura-bloom', 'strawberry-milk', 'pink-garden'];
        const cowoIds = ['midnight-blue', 'cyber-neon', 'dark-steel', 'forest-mode', 'sunset-ember'];
        const artisticIds = ['van-gogh-starry-night'];

        let newConcept = currentPrefs.opening;
        if (categoryKey === 'cewek' && cewekIds.indexOf(newConcept) === -1) {
            newConcept = 'coquette-blush';
        } else if (categoryKey === 'cowo' && cowoIds.indexOf(newConcept) === -1) {
            newConcept = 'midnight-blue';
        } else if (categoryKey === 'artistic' && artisticIds.indexOf(newConcept) === -1) {
            newConcept = 'van-gogh-starry-night';
        }

        if (persist) {
            window.saveBooknestPreferences({ openingCategory: categoryKey, opening: newConcept });
        }

        window.setBooknestOpening(newConcept, persist, showFeedback);
        window.dispatchEvent(new CustomEvent('booknest:opening-category-changed', { detail: { category: categoryKey, concept: newConcept } }));
    };

    window.setBooknestOpening = function (conceptKey, persist, showFeedback) {
        const allowed = [
            'coquette-blush', 'fairy-dream', 'sakura-bloom', 'strawberry-milk', 'pink-garden',
            'midnight-blue', 'cyber-neon', 'dark-steel', 'forest-mode', 'sunset-ember',
            'van-gogh-starry-night'
        ];
        if (allowed.indexOf(conceptKey) === -1) conceptKey = 'coquette-blush';

        // Infer category from concept
        const cowoIds = ['midnight-blue', 'cyber-neon', 'dark-steel', 'forest-mode', 'sunset-ember'];
        const artisticIds = ['van-gogh-starry-night'];
        let categoryKey = 'cewek';
        if (cowoIds.indexOf(conceptKey) !== -1) {
            categoryKey = 'cowo';
        } else if (artisticIds.indexOf(conceptKey) !== -1) {
            categoryKey = 'artistic';
        }

        document.documentElement.setAttribute('data-opening-cat', categoryKey);
        document.documentElement.setAttribute('data-opening', conceptKey);

        if (persist) {
            window.saveBooknestPreferences({ openingCategory: categoryKey, opening: conceptKey });
        }

        // Update cards across all tracks
        allowed.forEach(function (c) {
            const card = document.getElementById('openingCard_' + c.replace(/-/g, '_'));
            if (card) {
                if (c === conceptKey) {
                    card.classList.add('active');
                } else {
                    card.classList.remove('active');
                }
            }
        });

        // Ensure category select reflects this
        const selectEl = document.getElementById('openingCategorySelect');
        if (selectEl && selectEl.value !== categoryKey) {
            selectEl.value = categoryKey;
        }

        const cewekTrack = document.getElementById('openingTrack_cewek');
        const cowoTrack = document.getElementById('openingTrack_cowo');
        const artisticTrack = document.getElementById('openingTrack_artistic');
        if (cewekTrack) cewekTrack.style.display = (categoryKey === 'cewek') ? 'flex' : 'none';
        if (cowoTrack) cowoTrack.style.display = (categoryKey === 'cowo') ? 'flex' : 'none';
        if (artisticTrack) artisticTrack.style.display = (categoryKey === 'artistic') ? 'flex' : 'none';

        if (showFeedback) {
            const currentLang = document.documentElement.getAttribute('lang') || 'id';
            const msg = (currentLang === 'en') ? 'Opening splash concept updated.' : 'Konsep layar pembuka diperbarui.';
            window.showPrefToast(msg);
        }

        window.dispatchEvent(new CustomEvent('booknest:opening-changed', { detail: { opening: conceptKey, category: categoryKey } }));
    };

    // Live Test Opening Splash Preview (Interactive Overlay without Reload)
    window.previewBooknestOpening = function (conceptKey) {
        const allowed = [
            'coquette-blush', 'fairy-dream', 'sakura-bloom', 'strawberry-milk', 'pink-garden',
            'midnight-blue', 'cyber-neon', 'dark-steel', 'forest-mode', 'sunset-ember',
            'van-gogh-starry-night'
        ];
        if (allowed.indexOf(conceptKey) === -1) conceptKey = 'coquette-blush';

        const existing = document.getElementById('booknestOpeningDemoOverlay');
        if (existing && existing.parentNode) {
            existing.parentNode.removeChild(existing);
        }

        // Definitions for Opening Concepts
        const openingMeta = {
            'coquette-blush': {
                bg: 'linear-gradient(135deg, #ffd1dc 0%, #fff0f5 45%, #fce4ec 75%, #f8bbd0 100%)',
                wipe: 'linear-gradient(90deg, #f48fb1, #f8bbd0, #fce4ec, #e0a96d)',
                subtitle: 'Konsep: Coquette Blush (Baby Pink, Cream & Rose Gold)',
                glow: '0 8px 24px rgba(244, 143, 177, 0.35), 0 0 24px rgba(255, 192, 203, 0.7)',
                textColor: '#880e4f',
                boxBg: 'rgba(255, 255, 255, 0.8)',
                boxBorder: 'rgba(244, 143, 177, 0.5)',
                brandColor: '#ad1457'
            },
            'fairy-dream': {
                bg: 'linear-gradient(135deg, #e9d5ff 0%, #f5d0fe 35%, #fed7aa 70%, #ede9fe 100%)',
                wipe: 'linear-gradient(90deg, #c084fc, #f472b6, #e9d5ff, #ffffff)',
                subtitle: 'Konsep: Fairy Dream (Lavender, Baby Pink & Pearl White)',
                glow: '0 8px 24px rgba(192, 132, 252, 0.35), 0 0 24px rgba(244, 114, 182, 0.6)',
                textColor: '#581c87',
                boxBg: 'rgba(255, 255, 255, 0.8)',
                boxBorder: 'rgba(192, 132, 252, 0.5)',
                brandColor: '#6b21a8'
            },
            'sakura-bloom': {
                bg: 'linear-gradient(135deg, #fbcfe8 0%, #ffffff 40%, #ccfbf1 80%, #fce7f3 100%)',
                wipe: 'linear-gradient(90deg, #f472b6, #fb7185, #99f6e4, #fbcfe8)',
                subtitle: 'Konsep: Sakura Bloom (Sakura Pink, Putih & Soft Mint)',
                glow: '0 8px 24px rgba(244, 143, 177, 0.35), 0 0 24px rgba(153, 246, 228, 0.6)',
                textColor: '#9d174d',
                boxBg: 'rgba(255, 255, 255, 0.8)',
                boxBorder: 'rgba(251, 113, 133, 0.5)',
                brandColor: '#be185d'
            },
            'strawberry-milk': {
                bg: 'linear-gradient(135deg, #f43f5e 0%, #fb7185 35%, #fed7aa 70%, #fff1f2 100%)',
                wipe: 'linear-gradient(90deg, #ffffff, #fda4af, #f43f5e, #fed7aa)',
                subtitle: 'Konsep: Strawberry Milk (Strawberry Pink, Peach & Milky White)',
                glow: '0 8px 24px rgba(244, 63, 94, 0.4), 0 0 24px rgba(254, 215, 170, 0.7)',
                textColor: '#ffffff',
                boxBg: 'rgba(255, 255, 255, 0.25)',
                boxBorder: 'rgba(255, 255, 255, 0.4)',
                brandColor: '#ffffff'
            },
            'pink-garden': {
                bg: 'linear-gradient(135deg, #f472b6 0%, #fbcfe8 35%, #d1fae5 70%, #fef3c7 100%)',
                wipe: 'linear-gradient(90deg, #ec4899, #6ee7b7, #fde68a, #f472b6)',
                subtitle: 'Konsep: Pink Garden (Dusty Pink, Sage Green & Cream)',
                glow: '0 8px 24px rgba(236, 72, 153, 0.35), 0 0 24px rgba(110, 231, 183, 0.6)',
                textColor: '#831843',
                boxBg: 'rgba(255, 255, 255, 0.8)',
                boxBorder: 'rgba(236, 72, 153, 0.4)',
                brandColor: '#9d174d'
            },
            'midnight-blue': {
                bg: 'linear-gradient(145deg, #091223 0%, #0f1d3a 50%, #1e293b 100%)',
                wipe: 'linear-gradient(90deg, #1d4ed8, #3b82f6, #60a5fa, #93c5fd)',
                subtitle: 'Konsep: Midnight Blue (Navy & Dark Sapphire Elegan)',
                glow: '0 8px 24px rgba(0, 0, 0, 0.45), 0 0 24px rgba(59, 130, 246, 0.6)',
                textColor: '#f8fafc',
                boxBg: 'rgba(30, 41, 59, 0.7)',
                boxBorder: 'rgba(59, 130, 246, 0.4)',
                brandColor: '#60a5fa'
            },
            'cyber-neon': {
                bg: 'linear-gradient(145deg, #020617 0%, #082f49 50%, #0369a1 100%)',
                wipe: 'linear-gradient(90deg, #06b6d4, #22d3ee, #67e8f9, #a5f3fc)',
                subtitle: 'Konsep: Cyber Neon (Dark Slate & Cyan Glow)',
                glow: '0 8px 24px rgba(0, 0, 0, 0.5), 0 0 28px rgba(6, 182, 212, 0.8)',
                textColor: '#f0fdfa',
                boxBg: 'rgba(8, 47, 73, 0.8)',
                boxBorder: 'rgba(34, 211, 238, 0.6)',
                brandColor: '#22d3ee'
            },
            'dark-steel': {
                bg: 'linear-gradient(145deg, #0f172a 0%, #1e293b 50%, #334155 100%)',
                wipe: 'linear-gradient(90deg, #64748b, #94a3b8, #cbd5e1, #f1f5f9)',
                subtitle: 'Konsep: Dark Steel (Charcoal, Abu Baja & Silver)',
                glow: '0 8px 24px rgba(0, 0, 0, 0.5), 0 0 20px rgba(148, 163, 184, 0.5)',
                textColor: '#f8fafc',
                boxBg: 'rgba(30, 41, 59, 0.8)',
                boxBorder: 'rgba(148, 163, 184, 0.4)',
                brandColor: '#e2e8f0'
            },
            'forest-mode': {
                bg: 'linear-gradient(145deg, #052e16 0%, #14532d 50%, #166534 100%)',
                wipe: 'linear-gradient(90deg, #15803d, #22c55e, #86efac, #bbf7d0)',
                subtitle: 'Konsep: Forest Mode (Forest Green, Pinus & Sage Natural)',
                glow: '0 8px 24px rgba(0, 0, 0, 0.45), 0 0 24px rgba(34, 197, 94, 0.6)',
                textColor: '#f0fdf4',
                boxBg: 'rgba(20, 83, 45, 0.8)',
                boxBorder: 'rgba(34, 197, 94, 0.4)',
                brandColor: '#86efac'
            },
            'sunset-ember': {
                bg: 'linear-gradient(145deg, #451a03 0%, #7c2d12 50%, #9a3412 100%)',
                wipe: 'linear-gradient(90deg, #ea580c, #f97316, #fb923c, #fed7aa)',
                subtitle: 'Konsep: Sunset Ember (Burnt Orange, Amber & Smoky Charcoal)',
                glow: '0 8px 24px rgba(0, 0, 0, 0.45), 0 0 24px rgba(249, 115, 22, 0.7)',
                textColor: '#fff7ed',
                boxBg: 'rgba(124, 45, 18, 0.8)',
                boxBorder: 'rgba(249, 115, 22, 0.5)',
                brandColor: '#fdba74'
            },
            'van-gogh-starry-night': {
                bg: 'radial-gradient(circle at 75% 20%, rgba(233, 185, 73, 0.22) 0%, transparent 35%), linear-gradient(140deg, #0a1726 0%, #102a43 45%, #1d4068 75%, #0d1b2a 100%)',
                wipe: 'linear-gradient(90deg, #245b8f, #e9b949, #5e9bc5, #f6f0df)',
                subtitle: 'Konsep: Van Gogh — Starry Night (Cobalt Night Sky & Golden Moon)',
                glow: '0 8px 28px rgba(16, 42, 67, 0.6), 0 0 30px rgba(233, 185, 73, 0.55)',
                textColor: '#f6f0df',
                boxBg: 'rgba(16, 42, 67, 0.82)',
                boxBorder: 'rgba(233, 185, 73, 0.45)',
                brandColor: '#e9b949',
                isArtistic: true
            }
        };

        const conf = openingMeta[conceptKey] || openingMeta['coquette-blush'];

        const overlay = document.createElement('div');
        overlay.id = 'booknestOpeningDemoOverlay';
        overlay.className = 'bn-opening-demo-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: 9999999;
            background: ${conf.bg};
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            opacity: 0; transition: opacity 0.35s ease;
            cursor: pointer;
        `;

        overlay.innerHTML = `
            <div style="display:flex; flex-direction:column; align-items:center; gap:16px; animation: bnZoomIn 0.5s ease forwards;">
                <div style="width:58px; height:58px; background:${conf.boxBg}; border:1px solid ${conf.boxBorder}; border-radius:16px; display:flex; align-items:center; justify-content:center; color:${conf.brandColor}; box-shadow:${conf.glow};">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                </div>
                <div style="font-size:22px; font-weight:800; color:${conf.textColor}; letter-spacing:1px; font-family:'Plus Jakarta Sans',sans-serif;">BOOKNEST</div>
                <div style="width:140px; height:4px; border-radius:4px; background:rgba(0,0,0,0.15); overflow:hidden;">
                    <div style="width:100%; height:100%; background:${conf.wipe}; animation: bnWipeBar 1.2s ease-in-out infinite;"></div>
                </div>
                <div style="font-size:13.5px; color:${conf.textColor}; font-weight:700; margin-top:4px; text-shadow:0 1px 3px rgba(0,0,0,0.2);">${conf.subtitle}</div>
                <div style="font-size:11.5px; color:${conf.textColor}; opacity:0.85; margin-top:12px; background:rgba(0,0,0,0.25); padding:4px 14px; border-radius:20px; border:1px solid rgba(255,255,255,0.2);">
                    Klik di mana saja untuk menutup pratinjau
                </div>
            </div>
        `;

        document.body.appendChild(overlay);

        requestAnimationFrame(function () {
            overlay.style.opacity = '1';
        });

        function closeOverlay() {
            overlay.style.opacity = '0';
            setTimeout(function () {
                if (overlay.parentNode) overlay.parentNode.removeChild(overlay);
            }, 350);
        }

        overlay.addEventListener('click', closeOverlay);
        setTimeout(closeOverlay, 2600);
    };

    // Global Language Setter Function
    window.setBooknestLang = function (lang, persist, showFeedback) {
        lang = (lang === 'en') ? 'en' : 'id';
        document.documentElement.setAttribute('lang', lang);

        if (persist) {
            window.saveBooknestPreferences({ lang: lang });
        }

        const dict = window.BOOKNEST_I18N[lang] || window.BOOKNEST_I18N['id'];

        // Replace all text elements with data-i18n
        document.querySelectorAll('[data-i18n]').forEach(function (el) {
            const key = el.getAttribute('data-i18n');
            if (dict[key]) {
                el.textContent = dict[key];
            }
        });

        // Replace placeholders
        document.querySelectorAll('[data-i18n-placeholder]').forEach(function (el) {
            const key = el.getAttribute('data-i18n-placeholder');
            if (dict[key]) {
                el.placeholder = dict[key];
            }
        });

        // Replace titles
        document.querySelectorAll('[data-i18n-title]').forEach(function (el) {
            const key = el.getAttribute('data-i18n-title');
            if (dict[key]) {
                el.title = dict[key];
            }
        });

        // Update Language Cards if on Preferences Tab
        const idCard = document.getElementById('langCardId');
        const enCard = document.getElementById('langCardEn');
        const activeText = dict['pref.active'];
        const useText = dict['pref.use_this'];

        if (idCard && enCard) {
            if (lang === 'id') {
                idCard.classList.add('active');
                enCard.classList.remove('active');
                const idBadge = idCard.querySelector('.pref-option-badge');
                const enBadge = enCard.querySelector('.pref-option-badge');
                if (idBadge) idBadge.textContent = activeText;
                if (enBadge) enBadge.textContent = useText;
            } else {
                enCard.classList.add('active');
                idCard.classList.remove('active');
                const idBadge = idCard.querySelector('.pref-option-badge');
                const enBadge = enCard.querySelector('.pref-option-badge');
                if (enBadge) enBadge.textContent = activeText;
                if (idBadge) idBadge.textContent = useText;
            }
        }

        // Refresh badges across all cards
        const currentThemeChoice = document.documentElement.getAttribute('data-theme-choice') || 'system';
        window.setBooknestTheme(currentThemeChoice, false, false);

        if (showFeedback) {
            const msg = (lang === 'en')
                ? 'Interface language switched to English.'
                : 'Bahasa antarmuka diubah ke Bahasa Indonesia.';
            window.showPrefToast(msg);
        }

        window.dispatchEvent(new CustomEvent('booknest:lang-changed', { detail: { lang: lang } }));
    };

    // Auto-apply preferences on DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function () {
        try {
            const prefs = window.getBooknestPreferences();
            window.setBooknestTheme(prefs.theme, false, false);
            window.setBooknestFont(prefs.font, false, false);
            window.setBooknestFontSize(prefs.fontSize, false, false);
            window.setBooknestOpeningCategory(prefs.openingCategory || 'cewek', false, false);
            window.setBooknestOpening(prefs.opening, false, false);
            window.setBooknestLang(prefs.lang, false, false);
        } catch (e) {}
    });


    // Global Mobile Sidebar Controls
    (function () {
        let lastSidebarToggleTime = 0;

        function toggleSidebarMobile(e) {
            if (e) {
                if (typeof e.preventDefault === 'function') e.preventDefault();
                if (typeof e.stopPropagation === 'function') e.stopPropagation();
                if (typeof e.stopImmediatePropagation === 'function') e.stopImmediatePropagation();
            }

            // Anti-bounce / double-trigger guard
            const now = Date.now();
            if (now - lastSidebarToggleTime < 250) {
                return;
            }
            lastSidebarToggleTime = now;

            const sidebar = document.getElementById('sidebarPanel');
            const backdrop = document.getElementById('sidebarBackdrop');
            const toggleBtn = document.getElementById('sidebarToggle');

            if (!sidebar) return;

            const willOpen = !sidebar.classList.contains('mobile-open');

            if (willOpen) {
                sidebar.classList.add('mobile-open');
                if (backdrop) backdrop.classList.add('show');
                if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden';
            } else {
                sidebar.classList.remove('mobile-open');
                if (backdrop) backdrop.classList.remove('show');
                if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            }
        }

        function closeSidebarMobile(e) {
            if (e) {
                if (typeof e.stopPropagation === 'function') e.stopPropagation();
                if (typeof e.stopImmediatePropagation === 'function') e.stopImmediatePropagation();
            }
            const sidebar = document.getElementById('sidebarPanel');
            const backdrop = document.getElementById('sidebarBackdrop');
            const toggleBtn = document.getElementById('sidebarToggle');

            if (sidebar) {
                sidebar.classList.remove('mobile-open');
            }
            if (backdrop) {
                backdrop.classList.remove('show');
            }
            if (toggleBtn) {
                toggleBtn.setAttribute('aria-expanded', 'false');
            }
            document.body.style.overflow = '';
        }

        function openSidebarMobile(e) {
            if (e) {
                if (typeof e.stopPropagation === 'function') e.stopPropagation();
                if (typeof e.stopImmediatePropagation === 'function') e.stopImmediatePropagation();
            }
            const sidebar = document.getElementById('sidebarPanel');
            const backdrop = document.getElementById('sidebarBackdrop');
            const toggleBtn = document.getElementById('sidebarToggle');

            if (sidebar) {
                sidebar.classList.add('mobile-open');
            }
            if (backdrop) {
                backdrop.classList.add('show');
            }
            if (toggleBtn) {
                toggleBtn.setAttribute('aria-expanded', 'true');
            }
            document.body.style.overflow = 'hidden';
        }

        window.toggleSidebarMobile = toggleSidebarMobile;
        window.closeSidebarMobile = closeSidebarMobile;
        window.openSidebarMobile = openSidebarMobile;

        function initSidebarMobileToggle() {
            const toggleBtn = document.getElementById('sidebarToggle');
            const backdrop = document.getElementById('sidebarBackdrop');
            const closeBtn = document.querySelector('.sidebar-close-mobile-btn');

            if (toggleBtn) {
                toggleBtn.onclick = toggleSidebarMobile;
                toggleBtn.setAttribute('aria-controls', 'sidebarPanel');
                if (!toggleBtn.hasAttribute('aria-expanded')) {
                    toggleBtn.setAttribute('aria-expanded', 'false');
                }
            }

            if (backdrop) {
                backdrop.onclick = closeSidebarMobile;
            }

            if (closeBtn) {
                closeBtn.onclick = closeSidebarMobile;
            }

            // Close when clicking any link inside sidebar
            document.querySelectorAll('#sidebarPanel .sidebar-link, #sidebarPanel a').forEach(function (link) {
                link.addEventListener('click', function () {
                    closeSidebarMobile();
                });
            });

            // Close on Escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' || e.key === 'Esc') {
                    closeSidebarMobile();
                }
            });

            // Auto close if resized to desktop breakpoint (> 992px)
            window.addEventListener('resize', function () {
                if (window.innerWidth > 992) {
                    closeSidebarMobile();
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSidebarMobileToggle);
        } else {
            initSidebarMobileToggle();
        }
    })();

    document.addEventListener('DOMContentLoaded', function () {

        // User dropdown in sidebar
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');

        if (userMenuBtn && userDropdown) {
            userMenuBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                const isOpen = userDropdown.classList.contains('show');
                userDropdown.classList.toggle('show');
                userMenuBtn.setAttribute('aria-expanded', !isOpen);
            });

            document.addEventListener('click', function (e) {
                if (!userDropdown.contains(e.target) && !userMenuBtn.contains(e.target)) {
                    userDropdown.classList.remove('show');
                    userMenuBtn.setAttribute('aria-expanded', 'false');
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' || e.key === 'Esc') {
                    userDropdown.classList.remove('show');
                    userMenuBtn.setAttribute('aria-expanded', 'false');
                }
            });
        }

        // Topbar user dropdown (primary account access)
        const topbarUserBtn = document.getElementById('topbarUserBtn');
        const topbarUserDropdown = document.getElementById('topbarUserDropdown');

        if (topbarUserBtn && topbarUserDropdown) {
            topbarUserBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                const isOpen = topbarUserDropdown.classList.contains('show');
                topbarUserDropdown.classList.toggle('show');
                topbarUserBtn.setAttribute('aria-expanded', !isOpen);

                // Close sidebar dropdown if open
                if (userDropdown) {
                    userDropdown.classList.remove('show');
                    if (userMenuBtn) userMenuBtn.setAttribute('aria-expanded', 'false');
                }
            });

            document.addEventListener('click', function (e) {
                if (!topbarUserDropdown.contains(e.target) && !topbarUserBtn.contains(e.target)) {
                    topbarUserDropdown.classList.remove('show');
                    topbarUserBtn.setAttribute('aria-expanded', 'false');
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' || e.key === 'Esc') {
                    topbarUserDropdown.classList.remove('show');
                    topbarUserBtn.setAttribute('aria-expanded', 'false');
                }
            });
        }

        // Complete top progress indicator on page load
        (function() {
            let progress = document.getElementById('pageProgressIndicator');
            if (!progress) {
                progress = document.createElement('div');
                progress.id = 'pageProgressIndicator';
                document.body.appendChild(progress);
            }
            progress.style.width = '100%';
            progress.style.opacity = '1';
            setTimeout(function () {
                progress.style.opacity = '0';
                setTimeout(function () {
                    progress.style.width = '0%';
                }, 200);
            }, 140);
        })();

        // Smooth Page Transition on Sidebar & Navigation Menu Clicks
        let isNavigating = false;

        function handleNavigationClick(e) {
            // Respect special clicks (new tab, background tab, modified click)
            if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) {
                return;
            }

            const targetUrl = this.getAttribute('href');
            if (!targetUrl || targetUrl.startsWith('#') || targetUrl.startsWith('javascript:') || this.target === '_blank' || this.hasAttribute('download')) {
                return;
            }

            // Skip if clicking current URL or anchor
            const currentUrl = window.location.href;
            const currentPath = window.location.pathname + window.location.search;
            if (targetUrl === currentUrl || targetUrl === window.location.pathname || targetUrl === currentPath) {
                return;
            }

            // Prevent multi-triggering while transition is running
            if (isNavigating) {
                e.preventDefault();
                return;
            }

            // Respect prefers-reduced-motion
            if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                return;
            }

            // Intercept navigation to play smooth exit transition
            e.preventDefault();
            isNavigating = true;

            // Remember that internal navigation has occurred to keep sidebar stable
            try {
                sessionStorage.setItem('bn_navigated', 'true');
            } catch (err) {}

            // Visual active highlight on clicked link
            if (this.classList.contains('sidebar-link')) {
                document.querySelectorAll('.sidebar-link').forEach(function (l) {
                    l.classList.remove('navigating-active');
                });
                this.classList.add('navigating-active');
            }

            // Progress bar
            let progress = document.getElementById('pageProgressIndicator');
            if (!progress) {
                progress = document.createElement('div');
                progress.id = 'pageProgressIndicator';
                document.body.appendChild(progress);
            }
            progress.style.opacity = '1';
            progress.style.width = '70%';

            // Smooth exit transition on main content area
            const mainContent = document.querySelector('.content-area') || document.querySelector('.catalog-container') || document.querySelector('.about-page');
            if (mainContent) {
                mainContent.classList.add('page-transitioning-out');
            }

            // After exit transition completes (140ms), navigate to target page
            setTimeout(function () {
                if (progress) {
                    progress.style.width = '92%';
                }
                window.location.href = targetUrl;
            }, 140);

            // Failsafe reset if navigation is interrupted/cancelled
            setTimeout(function () {
                isNavigating = false;
                if (mainContent) {
                    mainContent.classList.remove('page-transitioning-out');
                }
            }, 4000);
        }

        // Event delegation for all sidebar, topbar, and navigation links
        document.addEventListener('click', function (e) {
            const link = e.target.closest('a.sidebar-link, a.topbar-nav-link, a.topbar-brand, a.topbar-icon-btn, a.topbar-user-badge, a.user-dropdown-item:not(.logout), a.topbar-dropdown-item:not(.logout)');
            if (link) {
                handleNavigationClick.call(link, e);
            }
        });

        // BFCache (Back/Forward Cache) Restore Handler
        window.addEventListener('pageshow', function (event) {
            isNavigating = false;
            const progress = document.getElementById('pageProgressIndicator');
            if (progress) {
                progress.style.width = '100%';
                setTimeout(function () {
                    progress.style.opacity = '0';
                    progress.style.width = '0%';
                }, 140);
            }

            document.querySelectorAll('.sidebar-link').forEach(function (l) {
                l.classList.remove('navigating-active');
            });

            const mainContent = document.querySelector('.content-area') || document.querySelector('.catalog-container') || document.querySelector('.about-page');
            if (mainContent) {
                mainContent.classList.remove('page-transitioning-out');
                mainContent.style.opacity = '';
                mainContent.style.transform = '';
            }
        });
    });
</script>
