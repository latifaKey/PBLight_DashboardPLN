<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Dashboard PLN')</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="/css/style.css">
  <style>
    :root {
      /* Common variables for both themes */
      --pln-blue: #0a4d85;
      --pln-light-blue: #009cde;

      /* Dark theme variables (default) */
      --pln-bg: #0f172a;
      --pln-surface: #1e293b;
      --pln-surface-2: #334155;
      --pln-text: #f8fafc;
      --pln-text-secondary: rgba(248, 250, 252, 0.7);
      --pln-border: rgba(248, 250, 252, 0.1);
      --pln-shadow: rgba(0, 0, 0, 0.25);
      --pln-accent-bg: rgba(10, 77, 133, 0.15);
      --pln-header-bg: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
      --sidebar-width: 70px;
      --sidebar-expanded: 260px;
      --sidebar-bg: #0a0f1e;
      --transition-speed: 0.35s;
    }

    /* Light theme variables */
    [data-theme="light"] {
      --pln-bg: #f5f7fa;
      --pln-surface: #ffffff;
      --pln-surface-2: #f0f2f5;
      --pln-text: #333333;
      --pln-text-secondary: rgba(0, 0, 0, 0.6);
      --pln-border: rgba(0, 0, 0, 0.1);
      --pln-shadow: rgba(0, 0, 0, 0.1);
      --pln-accent-bg: rgba(10, 77, 133, 0.05);
      --pln-header-bg: linear-gradient(90deg, var(--pln-blue), var(--pln-light-blue));
      --sidebar-bg: #0a4d85;
    }

    * {
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      margin: 0;
      padding: 0;
      background: var(--pln-bg);
      color: var(--pln-text);
      transition: background-color var(--transition-speed) ease,
                  color var(--transition-speed) ease;
    }

    .container-fluid {
      display: flex;
      min-height: 100vh;
      padding: 0;
      width: 100%;
    }

    .dashboard-header {
      width: 100%;
      padding: 15px 25px;
      background: var(--pln-header-bg);
      color: #fff;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: fixed;
      top: 0;
      left: var(--sidebar-width);
      height: 70px;
      z-index: 10;
      width: calc(100% - var(--sidebar-width));
      box-shadow: 0 2px 15px var(--pln-shadow);
      transition: left var(--transition-speed) ease,
                  width var(--transition-speed) ease;
    }

    /* Sidebar yang lebih modern */
    .sidebar {
      width: var(--sidebar-width);
      background: var(--sidebar-bg);
      position: fixed;
      height: 100%;
      left: 0;
      top: 0;
      z-index: 100;
      transition: all var(--transition-speed) ease;
      overflow: hidden;
      box-shadow: 2px 0 20px var(--pln-shadow);
    }

    .sidebar:hover {
      width: var(--sidebar-expanded);
    }

    .sidebar-logo {
      padding: 15px;
      display: flex;
      align-items: center;
      justify-content: flex-start;
      height: 70px;
      background: rgba(0,0,0,0.2);
      overflow: hidden;
      white-space: nowrap;
      border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .sidebar-logo img {
      height: 40px;
      min-width: 40px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.3);
      margin-right: 15px;
      transition: transform 0.3s ease;
    }

    .sidebar:hover .sidebar-logo img {
      transform: scale(1.05);
    }

    .logo-text {
      opacity: 0;
      transform: translateX(-20px);
      transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .sidebar:hover .logo-text {
      opacity: 1;
      transform: translateX(0);
    }

    .logo-title {
      font-size: 18px;
      font-weight: 600;
      margin: 0;
      color: #fff;
      letter-spacing: 1px;
    }

    .logo-subtitle {
      font-size: 11px;
      margin: 0;
      color: rgba(255,255,255,0.7);
      line-height: 1.2;
    }

    /* Menu sidebar yang lebih baik */
    .sidebar-menu {
      list-style: none;
      padding: 15px 0 0 0;
      margin: 0;
    }

    .sidebar-menu li {
      margin-bottom: 5px;
      padding: 0 10px;
    }

    .sidebar-menu a {
      display: flex;
      align-items: center;
      color: white;
      text-decoration: none;
      padding: 12px;
      white-space: nowrap;
      transition: all 0.3s ease;
      opacity: 0.8;
      border-radius: 10px;
      position: relative;
      overflow: hidden;
    }

    .sidebar-menu a:hover {
      background: rgba(255,255,255,0.15);
      opacity: 1;
    }

    .sidebar-menu a.active {
      background: var(--pln-light-blue);
      opacity: 1;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .sidebar-menu a.active::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 4px;
      height: 100%;
      background: rgba(255,255,255,0.8);
    }

    .sidebar-menu .icon {
      margin-right: 15px;
      width: 20px;
      text-align: center;
      font-size: 1.1rem;
    }

    .sidebar-menu .menu-text {
      opacity: 0;
      transform: translateX(-10px);
      transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .sidebar:hover .menu-text {
      opacity: 1;
      transform: translateX(0);
    }

    /* Date display yang lebih modern */
    .date-display {
      color: white;
      font-size: 14px;
      background: rgba(0,0,0,0.25);
      padding: 8px 15px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      backdrop-filter: blur(5px);
      border: 1px solid rgba(255,255,255,0.1);
    }

    .date-display i {
      margin-right: 8px;
      color: var(--pln-light-blue);
    }

    .header-text {
      line-height: 1.2;
    }

    .header-title {
      font-size: 18px;
      font-weight: 600;
      margin: 0;
      color: white;
      letter-spacing: 0.5px;
    }

    .header-subtitle {
      font-size: 12px;
      margin: 0;
      opacity: 0.9;
      color: white;
    }

    .main {
      margin-top: 70px;
      margin-left: var(--sidebar-width);
      padding: 25px;
      width: calc(100% - var(--sidebar-width));
      transition: margin-left var(--transition-speed) ease,
                  width var(--transition-speed) ease;
    }

    /* Logout button yang lebih modern */
    .logout-btn {
      position: absolute;
      bottom: 20px;
      left: 0;
      width: 100%;
      background: none;
      border: none;
      display: flex;
      align-items: center;
      color: white;
      padding: 12px 15px;
      cursor: pointer;
      transition: all 0.3s ease;
      opacity: 0.8;
    }

    .logout-btn:hover {
      background: linear-gradient(to right, rgba(220, 53, 69, 0.2), rgba(220, 53, 69, 0.3));
      opacity: 1;
    }

    .logout-icon {
      margin-right: 15px;
      width: 20px;
      text-align: center;
    }

    .logout-text {
      opacity: 0;
      transform: translateX(-10px);
      transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .sidebar:hover .logout-text {
      opacity: 1;
      transform: translateX(0);
    }

    .time-display {
      display: block;
      font-weight: 500;
      letter-spacing: 0.5px;
    }

    @media (max-width: 1200px) {
      .pillar-container {
        justify-content: center;
      }
    }

    @media (max-width: 992px) {
      :root {
        --sidebar-width: 0px;
      }

      .sidebar {
        width: 0;
      }

      .sidebar:hover {
        width: var(--sidebar-expanded);
      }

      .main, .dashboard-header {
        margin-left: 0;
        width: 100%;
      }
    }

    @media (max-width: 768px) {
      .date-display {
        display: none;
      }
    }

    /* Toggle switch untuk tema */
    .theme-switch-wrapper {
      display: flex;
      align-items: center;
      margin-right: 15px;
    }

    .theme-switch {
      display: inline-block;
      height: 24px;
      position: relative;
      width: 50px;
    }

    .theme-switch input {
      display: none;
    }

    .slider {
      background-color: #111;
      bottom: 0;
      cursor: pointer;
      left: 0;
      position: absolute;
      right: 0;
      top: 0;
      transition: .4s;
      border-radius: 34px;
      box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
      border: 1px solid rgba(255,255,255,0.1);
    }

    .slider:before {
      background-color: #fff;
      bottom: 3px;
      content: "";
      height: 16px;
      left: 4px;
      position: absolute;
      transition: .4s;
      width: 16px;
      border-radius: 50%;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    input:checked + .slider {
      background-color: var(--pln-light-blue);
    }

    input:checked + .slider:before {
      transform: translateX(26px);
    }

    .theme-icon {
      color: white;
      margin: 0 8px;
      font-size: 16px;
    }

    /* Smooth transition untuk semua elemen */
    * {
      transition-property: background-color, color, border-color, box-shadow;
      transition-duration: var(--transition-speed);
      transition-timing-function: ease;
    }
  </style>
  @yield('styles')
</head>
<body data-theme="dark">
  <div class="container-fluid">
    <!-- Sidebar yang lebih modern -->
    <div class="sidebar">
      <div class="sidebar-logo">
        <img src="/images/logoPLN.jpg" alt="Logo PLN" class="logo-pln">
        <div class="logo-text">
          <h1 class="logo-title">PLN</h1>
          <p class="logo-subtitle">Mandau Cipta Tenaga Nusantara</p>
        </div>
      </div>

      <ul class="sidebar-menu">
        <li>
          <a href="{{ route('dashboard.master') }}" class="{{ request()->routeIs('dashboard.master') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt icon"></i>
            <span class="menu-text">Dashboard</span>
          </a>
        </li>
        <li>
          <a href="{{ route('akun.index') }}" class="{{ request()->routeIs('akun.*') ? 'active' : '' }}">
            <i class="fas fa-users icon"></i>
            <span class="menu-text">Data Akun</span>
          </a>
        </li>
        <li>
          <a href="{{ route('verifikasi.index') }}" class="{{ request()->routeIs('verifikasi.*') ? 'active' : '' }}">
            <i class="fas fa-check-circle icon"></i>
            <span class="menu-text">Verifikasi</span>
          </a>
        </li>
        <li>
          <a href="{{ route('tahunPenilaian.index') }}" class="{{ request()->routeIs('tahunPenilaian.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt icon"></i>
            <span class="menu-text">Tahun Penilaian</span>
          </a>
        </li>
        <li>
          <a href="{{ route('dataKinerja.index') }}" class="{{ request()->routeIs('dataKinerja.*') ? 'active' : '' }}">
            <i class="fas fa-chart-bar icon"></i>
            <span class="menu-text">Data Kinerja</span>
          </a>
        </li>
        <li>
          <a href="{{ route('eksporPdf.index') }}" class="{{ request()->routeIs('eksporPdf.*') ? 'active' : '' }}">
            <i class="fas fa-file-pdf icon"></i>
            <span class="menu-text">Ekspor PDF</span>
          </a>
        </li>
      </ul>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn">
          <i class="fas fa-sign-out-alt logout-icon"></i>
          <span class="logout-text">Log Out</span>
        </button>
      </form>
    </div>

    <!-- Header yang lebih modern -->
    <div class="dashboard-header">
      <div class="header-text">
        <h1 class="header-title">@yield('page_title', 'Dashboard PLN')</h1>
        <p class="header-subtitle">PT PLN MANDAU CIPTA TENAGA NUSANTARA</p>
      </div>

      <div style="display: flex; align-items: center;">
        <div class="theme-switch-wrapper">
          <i class="fas fa-moon theme-icon"></i>
          <label class="theme-switch">
            <input type="checkbox" id="theme-toggle">
            <span class="slider"></span>
          </label>
          <i class="fas fa-sun theme-icon"></i>
        </div>

        @if(!request()->routeIs('akun.*'))
        <div class="date-display">
          <i class="far fa-calendar-alt"></i>
          <span>
            @php
              // Set lokasi waktu ke Indonesia/Jakarta
              date_default_timezone_set('Asia/Jakarta');

              // Array untuk konversi nama hari dan bulan ke Bahasa Indonesia
              $hari = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
              $bulan = array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

              // Format waktu dalam Bahasa Indonesia
              $nama_hari = $hari[date('w')];
              $nama_bulan = $bulan[date('n')-1];
              $tanggal = date('j');
              $tahun = date('Y');

              // Waktu
              $jam = date('H:i');
            @endphp

            <span class="date-info">{{ $nama_hari }}, {{ $tanggal }} {{ $nama_bulan }} {{ $tahun }}</span>
            <span class="time-display" id="live-time">{{ $jam }} WIB</span>
          </span>
        </div>
        @endif
      </div>
    </div>

    <main class="main">
      @yield('content')
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Live clock script yang lebih baik -->
  <script>
    function updateClock() {
      const now = new Date();
      const hours = String(now.getHours()).padStart(2, '0');
      const minutes = String(now.getMinutes()).padStart(2, '0');
      const seconds = String(now.getSeconds()).padStart(2, '0');

      // Update jam secara real-time
      const timeDisplay = document.getElementById('live-time');
      if (timeDisplay) {
        timeDisplay.textContent = `${hours}:${minutes} WIB`;
        // Update every second
        setTimeout(updateClock, 1000);
      }
    }

    // Start the clock when page loads
    document.addEventListener('DOMContentLoaded', function() {
      // Jalankan updateClock jika tidak berada di halaman Data Akun
      @if(!request()->routeIs('akun.*'))
      updateClock();
      @endif

      // Theme Switcher dengan animasi transisi
      const themeToggle = document.getElementById('theme-toggle');
      const body = document.body;

      // Check for saved theme preference or use default (dark)
      const currentTheme = localStorage.getItem('theme') || 'dark';

      // Set the body's data-theme attribute and adjust the toggle state
      body.setAttribute('data-theme', currentTheme);

      // If the current theme is light, check the toggle
      if (currentTheme === 'light') {
        themeToggle.checked = true;
      }

      // Listen for toggle changes with smooth transition
      themeToggle.addEventListener('change', function() {
        if (this.checked) {
          body.setAttribute('data-theme', 'light');
          localStorage.setItem('theme', 'light');
        } else {
          body.setAttribute('data-theme', 'dark');
          localStorage.setItem('theme', 'dark');
        }
      });

      // Mobile sidebar toggle
      const sidebarToggle = document.createElement('button');
      sidebarToggle.className = 'sidebar-toggle';
      sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
      sidebarToggle.style.display = 'none';

      // Add to DOM for mobile
      document.querySelector('.header-text').before(sidebarToggle);

      // Show toggle button on mobile
      if (window.innerWidth < 992) {
        sidebarToggle.style.display = 'block';
        sidebarToggle.style.background = 'transparent';
        sidebarToggle.style.border = 'none';
        sidebarToggle.style.color = 'white';
        sidebarToggle.style.fontSize = '1.5rem';
        sidebarToggle.style.cursor = 'pointer';
        sidebarToggle.style.marginRight = '15px';
      }

      // Toggle sidebar on mobile
      sidebarToggle.addEventListener('click', function() {
        const sidebar = document.querySelector('.sidebar');
        if (sidebar.style.width === '260px') {
          sidebar.style.width = '0';
        } else {
          sidebar.style.width = '260px';
        }
      });

      // Close sidebar when clicking outside on mobile
      document.addEventListener('click', function(event) {
        const sidebar = document.querySelector('.sidebar');
        const sidebarToggle = document.querySelector('.sidebar-toggle');

        if (window.innerWidth < 992 &&
            sidebar.style.width === '260px' &&
            !sidebar.contains(event.target) &&
            event.target !== sidebarToggle) {
          sidebar.style.width = '0';
        }
      });

      // Adjust on resize
      window.addEventListener('resize', function() {
        if (window.innerWidth < 992) {
          sidebarToggle.style.display = 'block';
        } else {
          sidebarToggle.style.display = 'none';
          document.querySelector('.sidebar').style.width = '';
        }
      });
    });
  </script>
  @yield('scripts')
</body>
</html>
