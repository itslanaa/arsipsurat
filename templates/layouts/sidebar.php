<!-- Sidebar -->
<aside class="w-64 bg-white shadow-md flex-shrink-0 flex flex-col">
    <div class="p-4 text-center border-b">
        <a href="<?= BASE_URL; ?>/dashboard">
            <img src="<?= BASE_URL; ?>/assets/img/sidebar_logo.png" alt="Logo Kec.Cibungbulang" class="h-12 mx-auto">
        </a>
    </div>

    <nav class="mt-6 flex-grow">
        <a href="<?= BASE_URL; ?>/dashboard" class="nav-link flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 <?= ($judul == 'Dashboard') ? 'active-link' : ''; ?>">
            <i class="fas fa-tachometer-alt fa-fw sidebar-icon mr-3"></i>
            <span>Dashboard</span>
        </a>
        <a href="<?= BASE_URL; ?>/arsip" class="nav-link flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 <?= (str_contains($judul, 'Arsip')) ? 'active-link' : ''; ?>">
            <i class="fas fa-archive fa-fw sidebar-icon mr-3"></i>
            <span>Arsip Digital</span>
        </a>
        <a href="<?= BASE_URL; ?>/suratmasuk" class="nav-link flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 <?= (str_contains($judul, 'Surat Masuk')) ? 'active-link' : ''; ?>">
            <i class="fas fa-inbox fa-fw sidebar-icon mr-3"></i>
            <span>Surat Masuk</span>
        </a>
        <a href="<?= BASE_URL; ?>/surat" class="nav-link flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 <?= (str_contains($judul, 'Surat')) ? 'active-link' : ''; ?>">
            <i class="fas fa-file-signature fa-fw sidebar-icon mr-3"></i>
            <span>Buat Surat</span>
        </a>
        <a href="<?= BASE_URL; ?>/kategori" class="nav-link flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 <?= (str_contains($judul, 'Kategori')) ? 'active-link' : ''; ?>">
            <i class="fas fa-tags fa-fw sidebar-icon mr-3"></i>
            <span>Kategori</span>
        </a>
            <a href="<?= BASE_URL; ?>/panduan" class="nav-link flex items-center px-6 py-3 text-gray-600 hover:bg-gray-100 <?= (str_contains($judul, 'Panduan')) ? 'active-link' : ''; ?>">
                <i class="fas fa-info-circle fa-fw sidebar-icon mr-3"></i>
                <span>Panduan</span>
            </a>
    </nav>

    <div class="p-6 border-t">
         <button type="button" 
                 onclick="showConfirmation('Konfirmasi Logout', 'Apakah Anda yakin ingin keluar dari sistem?', '<?= BASE_URL; ?>/auth/logout', 'Ya, Keluar', 'info')" 
                 class="nav-link flex items-center w-full px-6 py-3 text-red-500 hover:bg-red-50 text-left">
            <i class="fas fa-sign-out-alt fa-fw sidebar-icon mr-3"></i>
            <span>Logout</span>
        </button>
    </div>
</aside>

<!-- Main Content Wrapper -->
<main class="flex-1 flex flex-col overflow-hidden">
    <header class="bg-white shadow-sm p-4 flex justify-between items-center">
        <h2 id="page-title" class="text-2xl font-semibold text-slate-800"><?= htmlspecialchars($judul); ?></h2>
        
        <div class="relative">
            <button id="userMenuButton" class="flex items-center focus:outline-none rounded-full p-1 hover:bg-gray-100 transition">
                <span class="mr-3 hidden sm:inline">Halo, <?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Staf'); ?></span>
                <div class="w-10 h-10 bg-slate-600 rounded-full flex items-center justify-center text-white font-bold">
                    <?= strtoupper(substr($_SESSION['nama_lengkap'] ?? 'S', 0, 1)); ?>
                </div>
            </button>
            
            <!-- Dropdown Menu -->
            <div id="userMenu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 z-50 border">
                <div class="px-4 py-3 border-b">
                    <p class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Staf Kecamatan'); ?></p>
                    <p class="text-sm text-gray-500 truncate"><?= htmlspecialchars($_SESSION['role'] ?? 'Staf'); ?></p>
                </div>
                <a href="<?= BASE_URL; ?>/user/pengaturan" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-user-cog fa-fw w-5 mr-2 text-gray-500"></i> Pengaturan Akun
                </a>
                <a href="<?= BASE_URL; ?>/user/history" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-history fa-fw w-5 mr-2 text-gray-500"></i> History Login
                </a>
                <div class="border-t border-gray-100"></div>
                <button type="button" 
                        onclick="showConfirmation('Konfirmasi Logout', 'Apakah Anda yakin ingin keluar dari sistem?', '<?= BASE_URL; ?>/auth/logout', 'Ya, Keluar', 'info')"
                        class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                    <i class="fas fa-sign-out-alt fa-fw w-5 mr-2"></i> Logout
                </button>
            </div>
        </div>
    </header>

    <div class="flex-1 p-6 overflow-y-auto">
        <!-- Konten dinamis dari setiap halaman akan dimuat di sini -->
