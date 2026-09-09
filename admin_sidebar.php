<?php
// Active page check
if (!isset($activePage)) {
    $activePage = 'dashboard';
}
$adminName = htmlspecialchars($_SESSION['nama_admin'] ?? 'Admin');
$adminInitial = strtoupper(substr($adminName, 0, 1));
?>
<!-- Sidebar -->
<aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-sidebar-brand">
        <a href="dashboardAdmin.php" class="brand-logo">
            <span>T</span>ravel
        </a>
        <span class="admin-badge-role">ADMIN</span>
    </div>

    <ul class="admin-nav">
        <li class="admin-nav-heading">Menu Utama</li>
        <li class="admin-nav-item">
            <a href="dashboardAdmin.php" class="admin-nav-link <?= $activePage === 'dashboard' ? 'active' : '' ?>">
                <i class="fas fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="admin-nav-item">
            <a href="kelolaDestinasi.php" class="admin-nav-link <?= $activePage === 'destinasi' ? 'active' : '' ?>">
                <i class="fas fa-map-marked-alt"></i>
                <span>Kelola Destinasi</span>
            </a>
        </li>
        <li class="admin-nav-item">
            <a href="tambahDestinasi.php" class="admin-nav-link <?= $activePage === 'tambah_destinasi' ? 'active' : '' ?>">
                <i class="fas fa-plus-circle"></i>
                <span>Tambah Destinasi</span>
            </a>
        </li>
        <li class="admin-nav-item">
            <a href="kelolaBooking.php" class="admin-nav-link <?= $activePage === 'booking' ? 'active' : '' ?>">
                <i class="fas fa-ticket-alt"></i>
                <span>Kelola Booking</span>
            </a>
        </li>

        <li class="admin-nav-heading mt-3">Sistem</li>
        <li class="admin-nav-item">
            <a href="index.php" target="_blank" class="admin-nav-link">
                <i class="fas fa-external-link-alt"></i>
                <span>Lihat Website</span>
            </a>
        </li>
        <li class="admin-nav-item">
            <a href="proses.php?logoutAdmin=true" class="admin-nav-link text-danger">
                <i class="fas fa-sign-out-alt text-danger"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>

    <div class="admin-sidebar-footer">
        <div class="admin-avatar"><?= $adminInitial ?></div>
        <div class="admin-user-info">
            <p class="admin-user-name"><?= $adminName ?></p>
            <p class="admin-user-role">Administrator</p>
        </div>
    </div>
</aside>
