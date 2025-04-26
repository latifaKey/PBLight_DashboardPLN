{{-- resources/views/dashboard/admin_risiko.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Kinerja - Admin Risiko</title>
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
        <h2>Hi, Admin Manajemen Risiko!</h2>
      </div>

      <div class="grid">
        <div class="card dark">
          <h3>Over all information</h3>
          <p>20 Risk Mitigated | 5 High Risk</p>
        </div>
        <div class="card">
          <h3>Risk Evaluation</h3>
          <p class="placeholder-text">Risk analysis in progress...</p>
        </div>
        <div class="card">
          <h3>Risk Mitigation Progress</h3>
          <div class="progress-circle">50%</div>
        </div>
        <div class="card">
          <h3>Current Risks</h3>
          <ul>
            <li>✓ Flooding Risk</li>
            <li>○ Financial Market Risk</li>
            <li>○ Operational Shutdown Risk</li>
          </ul>
        </div>
        <div class="card">
          <h3>Task In Process</h3>
          <div class="tasks">
            <div class="task">Risk Assessment Meeting</div>
            <div class="task">Risk Mitigation Plan</div>
          </div>
        </div>
        <div class="card">
          <h3>Last Risk Mitigation Projects</h3>
          <div class="tasks">
            <div class="task black">Crisis Management Plan</div>
            <div class="task">Risk Insurance Strategy</div>
            <div class="task">Emergency Protocols</div>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
