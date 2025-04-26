{{-- resources/views/dashboard/admin_keuangan.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Kinerja - Admin Keuangan</title>
  <link rel="stylesheet" href="/css/style.css">
  <style>
    /* Styling seperti yang sudah dibuat di admin_dashboard.blade.php */
  </style>
</head>
<body>
  <div class="container">
    <aside class="sidebar">
      <img src="/images/logoPLN.png" alt="Logo PLN" />
      <ul class="sidebar-menu">
        <li><a href="{{ route('dashboard') }}"><img src="/images/dashboard.png" /> Dashboard</a></li>
        <li><a href="{{ route('realisasi.index') }}"><img src="/images/dataKinerja.png" /> Input Realisasi</a></li>
        <li><a href="{{ route('eksporPdf.index') }}"><img src="/images/pdf.png" /> Ekspor PDF</a></li>
      </ul>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn">
          <img src="/images/logout.png" alt="Logout" /> Log Out
        </button>
      </form>
    </aside>

    <main class="main">
      <div class="dashboard-header">
        <h2>Hi, Admin Keuangan!</h2>
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
          <div class="progress-circle">30%</div>
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
            <div class="task">Audit Financial Reports</div>
            <div class="task">Prepare Quarterly Budget</div>
          </div>
        </div>
        <div class="card">
          <h3>Last Projects</h3>
          <div class="tasks">
            <div class="task black">Yearly Financial Planning</div>
            <div class="task">Cash Flow Management</div>
            <div class="task">Investment Strategy</div>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
