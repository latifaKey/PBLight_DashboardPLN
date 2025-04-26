<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Kinerja</title>
  <link rel="stylesheet" href="/css/style.css">
  <style>
    * { box-sizing: border-box; font-family: 'Roboto', sans-serif; }
    body { margin: 0; padding: 0; background: linear-gradient(to bottom, #ffffff, #00566b); color: #000; }
    .container { display: flex; min-height: 100vh; }
    .sidebar {
      width: 220px; background: #fff; padding: 20px; border-right: 1px solid #ccc;
      display: flex; flex-direction: column; align-items: center;
      height: auto; max-height: 100vh; box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }
    .sidebar img { width: 180px; }
    .sidebar ul { list-style: none; padding: 0; }
    .sidebar ul li a {
      display: flex; align-items: center; text-decoration: none;
      color: #000; padding: 10px; border-radius: 8px;
    }
    .main { flex: 1; padding: 30px; }
    .dashboard-header { display: flex; justify-content: space-between; align-items: center; }
    .grid {
      display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px; margin-top: 30px;
    }
    .card {
      background: #fff; border-radius: 16px; padding: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .card.dark { background: #000; color: #fff; }
    .progress-circle {
      width: 100px; height: 100px; border-radius: 50%;
      background: conic-gradient(#000 75%, #eee 0);
      display: flex; align-items: center; justify-content: center;
      font-weight: bold;
    }
    .task { background: #fff; padding: 15px; border-radius: 12px; flex: 1 1 150px; }
    .task.black { background: #000; color: white; }
    .tasks { display: flex; flex-wrap: wrap; gap: 15px; }
    .sidebar-menu img,
    .logout-btn img {
      height: 20px; width: 20px; object-fit: contain;
      vertical-align: middle; margin-right: 8px;
    }
    .logout-btn {
      margin-top: 20px; background: none; border: none;
      display: flex; align-items: center; cursor: pointer; color: #000;
    }
  </style>
</head>
<body>
  <div class="container">
    <aside class="sidebar">
      <img src="/images/logoPLN.png" alt="Logo PLN" />

      <ul class="sidebar-menu">
        <li><a href="{{ route('dashboard') }}"><img src="/images/dashboard.png" /> Dashboard</a></li>

        <ul class="sidebar-menu">
            <li><a href="{{ route('dashboard') }}"><img src="/images/dashboard.png" /> Dashboard</a></li>

            @if($role === 'master_admin')
                <li><a href="{{ route('akun.index') }}"><img src="/images/akun.png" /> Data Akun</a></li>
                <li><a href="{{ route('verifikasi.index') }}"><img src="/images/verifikasi.png" /> Verifikasi</a></li>
                <li><a href="{{ route('tahunPenilaian.index') }}"><img src="/images/tahunP.png" /> Tahun Penilaian</a></li>
                <li><a href="{{ route('dataKinerja.index') }}"><img src="/images/dataKinerja.png" /> Data Kinerja</a></li>
            @elseif(in_array($role, ['pic_keuangan', 'pic_risiko_manajemen', 'pic_skreperusahaan']))
                <li><a href="{{ route('realisasi.index') }}"><img src="/images/dataKinerja.png" /> Input Realisasi</a></li>
            @endif

            <li><a href="{{ route('eksporPdf.index') }}"><img src="/images/pdf.png" /> Ekspor PDF</a></li>
        </ul>


      @auth
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn">
          <img src="/images/logout.png" alt="Logout" />
          Log Out
        </button>
      </form>
    @endauth

    </aside>

    <main class="main">
      <div class="dashboard-header">
        @auth
        <h2>Hi,
            @if(auth()->user()->role === 'asisten_manager')
                Master Admin!
            @elseif(auth()->user()->role === 'admin')
                Admin!
            @else
                Pengguna!
            @endif
        </h2>
        @endauth
        @guest
        <h2>Silakan Login untuk melanjutkan.</h2>
        @endguest
      </div>

      <div class="grid">
        <div class="card dark">
          <h3>Over all information</h3>
          <p>43 Task done | 2 Project stopped</p>
        </div>
        <div class="card">
          <h3>Weekly process</h3>
          <p class="placeholder-text">Chart tidak diaktifkan</p>
        </div>
        <div class="card">
            <h3>Month Progress</h3>

            <!-- Pilihan Bulan -->
            <form id="bulanForm">
              <select id="bulanSelect" name="bulan" onchange="updateProgress()">
                <option value="30">Januari</option>
                <option value="45">Februari</option>
                <option value="60">Maret</option>
                <option value="80">April</option>
                <option value="50">Mei</option>
                <option value="20">Juni</option>
                <!-- Tambahkan lagi kalau mau -->
              </select>
            </form>

            <!-- Progress Bulan -->
            <div id="progressContainer" class="progress-circle" style="background: conic-gradient(#000 30%, #eee 0);">
              30%
            </div>
          </div>

        <div class="card">
          <h3>Month Goal's</h3>
          <ul>
            <li>✓ 1h Meditation</li>
            <li>○ 10m Running</li>
            <li>○ 30m Workout</li>
            <li>○ 30m Reading</li>
          </ul>
        </div>
        <div class="card">
          <h3>Task In Process</h3>
          <div class="tasks">
            <div class="task">Meet HR for Project</div>
            <div class="task">Boss Appointment</div>
          </div>
        </div>
        <div class="card">
          <h3>Last Projects</h3>
          <div class="tasks">
            <div class="task black">New Schedule</div>
            <div class="task">Anime UI Design</div>
            <div class="task">Creative UI Design</div>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
