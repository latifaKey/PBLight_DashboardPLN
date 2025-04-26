{{-- resources/views/dashboard/admin_pengembangan_bisnis.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Kinerja - Admin Pengembangan Bisnis</title>
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
        <h2>Hi, Admin Pengembangan Bisnis!</h2>
      </div>

      <div class="grid">
        <div class="card dark">
          <h3>Overall Business Performance</h3>
          <p>5 Projects Ongoing | 2 Completed</p>
        </div>
        <div class="card">
          <h3>Project Pipeline</h3>
          <p class="placeholder-text">Business development in process...</p>
        </div>
        <div class="card">
          <h3>Business Growth</h3>
          <div class="progress-circle">75%</div>
        </div>
        <div class="card">
          <h3>Key Goals</h3>
          <ul>
            <li>✓ Develop Strategic Partnership</li>
            <li>○ Identify New Business Opportunities</li>
            <li>○ Launch New Product</li>
            <li>○ Expand Market Share</li>
          </ul>
        </div>
        <div class="card">
          <h3>Current Projects</h3>
          <div class="tasks">
            <div class="task">Market Research</div>
            <div class="task">Business Partnership Negotiation</div>
          </div>
        </div>
        <div class="card">
          <h3>Last Business Initiatives</h3>
          <div class="tasks">
            <div class="task black">New Product Launch</div>
            <div class="task">Brand Positioning Strategy</div>
            <div class="task">Strategic Acquisition</div>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
