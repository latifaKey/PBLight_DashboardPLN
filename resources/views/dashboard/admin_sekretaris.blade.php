{{-- resources/views/dashboard/admin_sekretaris.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Kinerja - Admin Sekretaris Perusahaan</title>
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
        <h2>Hi, Admin Sekretaris Perusahaan!</h2>
      </div>

      <div class="grid">
        <div class="card dark">
          <h3>Over all information</h3>
          <p>15 Documents Processed | 3 Pending</p>
        </div>
        <div class="card">
          <h3>Document Status</h3>
          <p class="placeholder-text">Pending approvals...</p>
        </div>
        <div class="card">
          <h3>Document Completion</h3>
          <div class="progress-circle">80%</div>
        </div>
        <div class="card">
          <h3>Monthly Administrative Goals</h3>
          <ul>
            <li>✓ Approve Legal Documents</li>
            <li>○ Organize Board Meetings</li>
            <li>○ Submit Monthly Report</li>
            <li>○ Archive Corporate Files</li>
          </ul>
        </div>
        <div class="card">
          <h3>Task In Process</h3>
          <div class="tasks">
            <div class="task">Board Meeting Scheduling</div>
            <div class="task">Prepare Monthly Report</div>
          </div>
        </div>
        <div class="card">
          <h3>Last Administrative Tasks</h3>
          <div class="tasks">
            <div class="task black">Corporate Document Update</div>
            <div class="task">Annual Report Filing</div>
            <div class="task">Legal Compliance Review</div>
          </div>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
