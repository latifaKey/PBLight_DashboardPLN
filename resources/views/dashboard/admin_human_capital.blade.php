{{-- resources/views/dashboard/admin_human_capital.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Kinerja - Admin Human Capital</title>
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
        <h2>Hi, Admin Human Capital!</h2>
      </div>

      <div class="grid">
        <div class="card dark">
          <h3>Employee Overview</h3>
          <p>100 Employees | 10 Hired This Month</p>
        </div>
        <div class="card">
          <h3>HR Process</h3>
          <p class="placeholder-text">Employee onboarding ongoing...</p>
        </div>
        <div class="card">
          <h3>Training Progress</h3>
          <div class="progress-circle">40%</div>
        </div>
        <div class="card">
          <h3>Employee Engagement</h3>
          <ul>
            <li>✓ Completed Team Building</li>
            <li>○ Onboarding New Hires</li>
            <li>○ Monthly Employee Survey</li>
            <li>○ Leadership Training</li>
          </ul>
        </div>
        <div class="card">
          <h3>Ongoing HR Tasks</h3>
          <div class="tasks">
            <div class="task">Recruitment Campaign</div>
            <div class="task">Employee Welfare Program</div>
          </div>
        </div>
        <div class="card">
          <h3>Last HR Initiatives</h3>
          <div class="tasks">
            <div class="task black">Leadership Development</div>
            <div class="task">Compensation Review</div>
            <div class="task">Employee Benefits Update</div>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
