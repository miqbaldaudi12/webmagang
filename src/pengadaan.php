<!DOCTYPE html>
<html lang="en">
<?php
require_once 'koneksi.php';
require_once 'query/fetchhistory.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}

// Handle date filter
$filtered_results = $hasil_history;
if (isset($_GET['year']) && $_GET['year'] != '' && isset($_GET['month']) && $_GET['month'] != '') {
  $year = $_GET['year'];
  $month = $_GET['month'];
  $filtered_results = array_filter($hasil_history, function ($item) use ($year, $month) {
    $start_date = new DateTime($item['tanggal_mulai']);
    return $start_date->format('Y') == $year && $start_date->format('m') == $month;
  });
} elseif (isset($_GET['year']) && $_GET['year'] != '') {
  $year = $_GET['year'];
  $filtered_results = array_filter($hasil_history, function ($item) use ($year) {
    $start_date = new DateTime($item['tanggal_mulai']);
    return $start_date->format('Y') == $year;
  });
} elseif (isset($_GET['month']) && $_GET['month'] != '') {
  $month = $_GET['month'];
  $filtered_results = array_filter($hasil_history, function ($item) use ($month) {
    $start_date = new DateTime($item['tanggal_mulai']);
    return $start_date->format('m') == $month;
  });
}
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pendataan Magang - Past Intern</title>
  <link rel="stylesheet" href="styles/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    .status-column {
      width: 120px;
      text-align: left;
    }

    .status-badge {
      width: 100px;
      text-align: center;
      display: inline-block;
    }

    .stats-card {
      position: relative;
      padding: 1.5rem;
      height: 100%;
    }

    .stats-card h3 {
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
      color: var(--dark-color);
    }

    .stats-card .status-label {
      font-size: 0.875rem;
      color: var(--secondary-color);
      margin-bottom: 1rem;
    }

    .venn-diagram {
      position: relative;
      width: 80px;
      height: 80px;
      margin: 1rem 0;
    }

    .circle {
      position: absolute;
      width: 100%;
      height: 100%;
      border-radius: 50%;
      border: 2px solid #eee;
      overflow: hidden;
    }

    .percentage {
      position: absolute;
      width: 100%;
      text-align: center;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 0.875rem;
      font-weight: 600;
      color: var(--dark-color);
    }

    /* Additional card styling */
    .stats-card .bi {
      opacity: 0.8;
      margin-left: 1rem;
    }

    .stats-card:hover .bi {
      opacity: 1;
    }
  </style>
</head>

<body class="def-background">
  <nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand title" href="index.php">
        <img src="/assets/logotel.png" alt="Logo Telkom" />
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="index.php">
              <i class="bi bi-house-door"></i> Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="admin.php">
              <i class="bi bi-person-workspace"></i> Manage Interns
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="pengadaan.php">
              <i class="bi bi-clipboard-data"></i> Past Intern
            </a>
          </li>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="logout.php">
              <i class="bi bi-box-arrow-right"></i> Logout
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container py-4">
    <div class="row mb-4">
      <div class="col-12">
        <h2 class="title mb-4">Past Intern Summary</h2>
      </div>
      <div class="col-md-4">
        <div class="stats-card h-100">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h3><?php echo $completed_count; ?> (<?php echo $completed_percent; ?>%)</h3>
              <p class="status-label mb-3">Selesai</p>
              <div class="venn-diagram">
                <div class="circle" style="background: linear-gradient(90deg, var(--success-color) <?php echo $completed_percent; ?>%, transparent <?php echo $completed_percent; ?>%);"></div>
                <div class="percentage"><?php echo $completed_percent; ?>%</div>
              </div>
            </div>
            <i class="bi bi-check-circle fs-1 text-success"></i>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stats-card h-100">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h3><?php echo $incomplete_count; ?> (<?php echo $incomplete_percent; ?>%)</h3>
              <p class="status-label mb-3">Tidak Selesai</p>
              <div class="venn-diagram">
                <div class="circle" style="background: linear-gradient(90deg, var(--danger-color) <?php echo $incomplete_percent; ?>%, transparent <?php echo $incomplete_percent; ?>%);"></div>
                <div class="percentage"><?php echo $incomplete_percent; ?>%</div>
              </div>
            </div>
            <i class="bi bi-x-circle fs-1 text-danger"></i>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="stats-card h-100">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h3><?php echo $total_history; ?></h3>
              <p class="status-label">Total Past Interns</p>
            </div>
            <i class="bi bi-people fs-1 text-primary"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Past Intern Data</h5>
          <form class="d-flex gap-2" method="GET">
            <select name="year" class="form-select" style="width: auto;">
              <option value="">Select Year</option>
              <?php
              $current_year = date('Y');
              for ($year = $current_year; $year >= $current_year - 5; $year--) {
                $selected = (isset($_GET['year']) && $_GET['year'] == $year) ? 'selected' : '';
                echo "<option value=\"$year\" $selected>$year</option>";
              }
              ?>
            </select>
            <select name="month" class="form-select" style="width: auto;">
              <option value="">Select Month</option>
              <?php
              for ($month = 1; $month <= 12; $month++) {
                $month_name = date('F', mktime(0, 0, 0, $month, 1));
                $selected = (isset($_GET['month']) && $_GET['month'] == $month) ? 'selected' : '';
                echo "<option value=\"$month\" $selected>$month_name</option>";
              }
              ?>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="pengadaan.php" class="btn btn-secondary">Reset</a>
          </form>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Nama Peserta</th>
                <th>Email</th>
                <th>Instansi</th>
                <th>Mentor</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th class="status-column">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($filtered_results)) : ?>
                <tr>
                  <td colspan="7" class="text-center">Tidak ada data history</td>
                </tr>
              <?php else : ?>
                <?php foreach ($filtered_results as $peserta) : ?>
                  <tr>
                    <td>
                      <i class="bi bi-person text-primary"></i>
                      <?php echo htmlspecialchars($peserta['nama_peserta']); ?>
                    </td>
                    <td>
                      <i class="bi bi-envelope"></i>
                      <?php echo htmlspecialchars($peserta['email_peserta']); ?>
                    </td>
                    <td>
                      <i class="bi bi-building"></i>
                      <?php echo htmlspecialchars($peserta['instansi']); ?>
                    </td>
                    <td>
                      <i class="bi bi-person-video3"></i>
                      <?php echo htmlspecialchars($peserta['mentor']); ?>
                    </td>
                    <td><?php echo date('d/m/Y', strtotime($peserta['tanggal_mulai'])); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($peserta['tanggal_selesai'])); ?></td>
                    <td class="status-column">
                      <span class="badge bg-<?php echo $peserta['status'] === 'Selesai' ? 'success' : 'danger'; ?> status-badge">
                        <?php echo $peserta['status']; ?>
                      </span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script>
    const ctx = document.getElementById('statusChart');
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Selesai', 'Tidak Selesai'],
        datasets: [{
          data: [<?php echo $completed_percent; ?>, <?php echo $incomplete_percent; ?>],
          backgroundColor: [
            '#198754',
            '#dc3545'
          ],
          borderWidth: 0
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
            enabled: true,
            callbacks: {
              label: function(context) {
                return context.label + ': ' + context.parsed + '%';
              }
            }
          }
        },
        cutout: '60%',
        layout: {
          padding: 20
        },
        animation: {
          duration: 0
        }
      }
    });
  </script>
</body>

</html>