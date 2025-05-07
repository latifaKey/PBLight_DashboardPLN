{{-- resources/views/dashboard/master_dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Kinerja - Karyawan</title>
  <link rel="stylesheet" href="/css/style.css">
  <style>
    * { box-sizing: border-box; font-family: 'Roboto', sans-serif; }
    body {
        margin: 0;
        padding: 0;
        background: linear-gradient(to bottom, #ffffff, );
        color: #000; }
    .container {
        display: flex;
        min-height: 100vh; }

    .dashboard-header {
        width: 100%;
        padding: 20px;
        background: linear-gradient(.25turn, #9BD7F8, #3D6C86);
        color: rgb(0, 0, 0);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: absolute; /* Agar header tidak mengikuti scroll */
        top: 0;
        left: 220px; /* karena sidebar 220px */
        max-height: 100vh;
        z-index: 10;
        width: calc(100% - 220px); /* Agar header tidak tumpang tindih dengan sidebar */

    }
    .sidebar {
      width: 220px;
      background: linear-gradient(to bottom,  #9BD7F8, #3D6C86);
      padding: 20px;
      border-right: 1px solid #ccc;
      display: flex;
      flex-direction: column;
      align-items: center;
      height: auto;
      max-height: 100vh;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
      top:0;
      z-index: 100;
      position:sticky;
    }
    .sidebar img { width: 180px; }

    .sidebar ul { list-style: none;
        padding: 0; }

    .sidebar ul li a {
      display: flex;
      align-items: center;
      text-decoration: none;
      color: #000;
      padding: 10px;
      border-radius: 8px;
    }
    /* .main { flex: 1; padding: 30px; } */
    .main {
        margin-left: 100px;
        flex: 1;
        margin-top: 90px; /* supaya tidak tertutup header */
        padding: 30px;
    }
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center; }
    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }
    .card {
      background: #fff;
      border-radius: 16px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .card.dark { background: #000;
        color: #fff; }

    .progress-circle {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: conic-gradient(#000 75%, #eee 0);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
    }
    .task {
        background: #fff;
        padding: 15px;
        border-radius: 12px;
        flex: 1 1 150px; }

    .task.black { background: #000;
        color: white; }

    .tasks {
        display: flex;
        flex-wrap: wrap;
        gap: 15px; }

    .sidebar-menu img,

    .logout-btn img {
      height: 20px;
      width: 20px;
      object-fit: contain;
      vertical-align: middle;
      margin-right: 8px;
    }
    .logout-btn {
      margin-top: 20px;
      background: none;
      border: none;
      display: flex;
      align-items: center;
      cursor: pointer;
      color: #000;
    }
    .sidebar-menu a.active {
        background-color: #afdef0;
        color: rgb(0, 0, 0);
        border-radius: 8px;
    }
    /* Styling untuk lingkaran progress */
    .progress-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: conic-gradient(#000 30%, #eee 0);
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto;
        transition: background 0.4s ease-in-out; /* Efek transisi halus saat berubah */
        font-size: 28px;
        font-weight: bold;
        color: #fff;
        text-align: center;
        padding: 10px;
    }

    /* Styling untuk pilihan bulan */
    select {
    padding: 10px;
    font-size: 16px;
    border-radius: 8px;
    width: 100%;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    }

  </style>
</head>
<body>
  <div class="container">

    <aside class="sidebar">
      <img src="/images/logoPLN.png" alt="Logo PLN" />

      <ul class="sidebar-menu">
        <li>
            <a href="{{ route('dashboard.user') }}" class="{{ request()->routeIs('dashboard.user') ? 'active' : '' }}">              <img src="/images/dashboard.png" /> Dashboard
            </a>
          </li>
        <li><a href="{{ route('eksporPdf.index') }}"><img src="/images/pdf.png" /> Ekspor PDF</a></li>
      </ul>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn">
            <img src="/images/logout.png" alt="Logout" />
            Log Out
        </button>
      </form>
    </aside>

    <main class="main">
      <div class="dashboard-header">
        <h2>Hi, Master Admin!</h2>
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
            <form id="bulanForm" style="margin-bottom: 20px;">
              <select id="bulanSelect" name="bulan" onchange="updateProgress()" style="padding: 8px 12px; font-size: 16px; border-radius: 8px; border: 1px solid #ccc; width: 100%; background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <option value="30">Januari</option>
                <option value="45">Februari</option>
                <option value="60">Maret</option>
                <option value="80">April</option>
                <option value="50">Mei</option>
                <option value="20">Juni</option>
              </select>
            </form>

            <!-- Progress Bulan -->
            <div id="progressContainer" class="progress-circle" style="background: conic-gradient(#000 30%, #eee 0); font-size: 24px; font-weight: bold; color: #fff;">
              30%
            </div>
          </div>

          <script>
            function updateProgress() {
              const select = document.getElementById('bulanSelect');
              const progressValue = select.value;
              const progressContainer = document.getElementById('progressContainer');

              progressContainer.style.background = `conic-gradient(#000 ${progressValue}%, #eee 0)`;
              progressContainer.textContent = `${progressValue}%`;
            }
          </script>

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
