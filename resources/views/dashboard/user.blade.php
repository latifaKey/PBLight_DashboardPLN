{{-- resources/views/dashboard/user.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Kinerja - Karyawan')
@section('page_title', 'Dashboard Karyawan')

@section('styles')
<style>
  .dashboard-content {
    max-width: 1800px;
    margin: 0 auto;
  }

  .grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 30px;
  }

  .card {
    background: var(--pln-accent-bg);
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 12px var(--pln-shadow);
    border: 1px solid var(--pln-border);
    transition: all 0.3s ease;
  }

  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px var(--pln-shadow);
  }

  .card h3 {
    color: var(--pln-light-blue);
    font-size: 18px;
    margin-bottom: 15px;
    border-bottom: 1px solid var(--pln-border);
    padding-bottom: 10px;
  }

  .card p {
    color: var(--pln-text);
    margin-bottom: 20px;
  }

  .progress-circle {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0 auto;
    transition: background 0.4s ease-in-out;
    font-size: 28px;
    font-weight: bold;
    color: var(--pln-text);
    text-align: center;
    padding: 10px;
    position: relative;
  }

  .tasks {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
  }

  .task {
    background: var(--pln-surface);
    padding: 15px;
    border-radius: 12px;
    flex: 1 1 150px;
    color: var(--pln-text);
    border: 1px solid var(--pln-border);
    transition: all 0.3s ease;
  }

  .task:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px var(--pln-shadow);
  }

  /* Styling untuk pilihan bulan */
  select {
    padding: 12px 15px;
    font-size: 15px;
    border-radius: 8px;
    width: 100%;
    border: 1px solid var(--pln-border);
    background-color: var(--pln-accent-bg);
    box-shadow: 0 2px 4px var(--pln-shadow);
    margin-bottom: 20px;
    color: var(--pln-text);
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='white' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
    transition: all 0.3s ease;
  }

  select:focus {
    border-color: var(--pln-light-blue);
    outline: none;
    box-shadow: 0 0 0 2px rgba(0, 156, 222, 0.25);
  }

  [data-theme="light"] select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='black' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
  }

  select option {
    background-color: var(--pln-surface);
    color: var(--pln-text);
  }
</style>
@endsection

@section('content')
<div class="dashboard-content">
  <div class="grid">
    <div class="card">
      <h3>Over all information</h3>
      <p>43 Task done | 2 Project stopped</p>
      <div id="chart-container">
        <canvas id="progressCircle"></canvas>
      </div>
    </div>

    <div class="card">
      <h3>Current Active Task</h3>

      <div class="form-group">
        <select id="monthSelect" onchange="updateProgressCircle(this.value)">
          <option value="januari">Januari</option>
          <option value="februari">Februari</option>
          <option value="maret">Maret</option>
          <option value="april">April</option>
          <option value="mei">Mei</option>
          <option value="juni">Juni</option>
          <option value="juli">Juli</option>
          <option value="agustus">Agustus</option>
          <option value="september">September</option>
          <option value="oktober">Oktober</option>
          <option value="november">November</option>
          <option value="desember">Desember</option>
        </select>
      </div>

      <div class="tasks">
        <div class="task">
          <h4>Task 1</h4>
          <p>Currently in process</p>
        </div>
        <div class="task">
          <h4>Task 2</h4>
          <p>Ready to start</p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Deteksi tema aktif
    const currentTheme = document.body.getAttribute('data-theme') || 'dark';

    // Menyesuaikan warna berdasarkan tema
    const backgroundColor = currentTheme === 'dark' ? '#333' : '#eee';

    // Progress circle chart
    const ctx = document.getElementById('progressCircle').getContext('2d');

    const progressChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        datasets: [{
          data: [75, 25],
          backgroundColor: [
            '#4CAF50',  // Hijau
            backgroundColor
          ],
          borderWidth: 0,
          cutout: '70%'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            enabled: false
          }
        }
      }
    });

    // Menampilkan label persentase di tengah
    const textCenter = {
      id: 'textCenter',
      beforeDraw(chart) {
        const { ctx, width, height } = chart;
        ctx.save();
        ctx.font = 'bold 20px Poppins';
        ctx.fillStyle = currentTheme === 'dark' ? '#fff' : '#333';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('75%', width / 2, height / 2);
        ctx.restore();
      }
    };

    Chart.register(textCenter);

    // Fungsi untuk memperbarui chart berdasarkan bulan yang dipilih
    window.updateProgressCircle = function(month) {
      // Simulasi data persentase berbeda untuk tiap bulan
      const monthData = {
        januari: 75,
        februari: 60,
        maret: 85,
        april: 40,
        mei: 90,
        juni: 65,
        juli: 55,
        agustus: 70,
        september: 80,
        oktober: 75,
        november: 60,
        desember: 95
      };

      const percentage = monthData[month];

      // Update chart
      progressChart.data.datasets[0].data = [percentage, 100 - percentage];

      // Update warna berdasarkan persentase
      let color;
      if (percentage < 30) color = '#F44336'; // Merah
      else if (percentage <= 70) color = '#FFC107'; // Kuning
      else color = '#4CAF50'; // Hijau

      progressChart.data.datasets[0].backgroundColor[0] = color;
      progressChart.update();
    };

    // Mendengarkan perubahan tema
    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
      themeToggle.addEventListener('change', function() {
        // Refresh halaman saat tema berubah agar chart diperbarui
        window.location.reload();
      });
    }
  });
</script>
@endsection
