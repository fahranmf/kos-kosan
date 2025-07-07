<?php $title = 'Dashboard Admin - Kos Putra Agan'; ?>
<?php require_once 'views/templates/header_admin.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php include('sidebar.php'); ?>
    <!-- Main content area -->
    <div class="main-content">
        <div class="dashboard-header">
            <h1>Dashboard Admin</h1>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <h2>Jumlah Kos</h2>
                <p><?php echo $totalKos; ?></p>
            </div>
            <div class="stat-card">
                <h2>Jumlah Penyewa</h2>
                <p><?php echo $totalPenyewa; ?></p>
            </div>
            <div class="stat-card">
                <h2>Jumlah Keluhan</h2>
                <p><?php echo $totalKeluhan; ?></p>
            </div>
        </div>

        <div class="chart-wrapper">
            <div class="chart-container">
                <h2>Grafik Jumlah Sewa per Bulan</h2>
                <form method="GET" action="index.php">
                    <input type="hidden" name="page" value="admin_dashboard" />
                    <label class="form-tahun" for="tahun">Pilih Tahun : </label>
                    <select name="tahun" id="tahun" onchange="this.form.submit()">
                        <?php
                        $tahunSaatIni = date('Y');
                        for ($i = $tahunSaatIni; $i >= $tahunSaatIni - 5; $i--) {
                            $selected = (isset($_GET['tahun']) && $_GET['tahun'] == $i) ? 'selected' : '';
                            echo "<option value='$i' $selected>$i</option>";
                        }
                        ?>
                    </select>
                </form>
                <div class="grafik">
                    <canvas id="grafikSewa"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('grafikSewa').getContext('2d');
    const maxY = <?= $maxY; ?>;


    function calculateLinearTrend(data) {
        const x = [];
        const y = [];

        // Ambil hanya titik dengan nilai > 0
        data.forEach((val, idx) => {
            if (val > 0) {
                x.push(idx);
                y.push(val);
            }
        });

        const n = x.length;
        if (n === 0) return data.map(() => 0); // fallback: semua 0

        const sumX = x.reduce((a, b) => a + b, 0);
        const sumY = y.reduce((a, b) => a + b, 0);
        const sumXY = x.reduce((sum, xi, i) => sum + xi * y[i], 0);
        const sumXX = x.reduce((sum, xi) => sum + xi * xi, 0);

        const slope = (n * sumXY - sumX * sumY) / (n * sumXX - sumX * sumX);
        const intercept = (sumY - slope * sumX) / n;

        // Hitung tren lengkap untuk semua bulan (0–11)
        return data.map((_, idx) => parseFloat((slope * idx + intercept).toFixed(2)));
    }


    // Data asli (bar)
    const dataBar = <?= json_encode($jumlah); ?>;
    const dataTrend = calculateLinearTrend(dataBar);



    window.chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels); ?>,
            datasets: [{
                label: ': Jumlah Sewa per Bulan',
                data: <?= json_encode($jumlah); ?>,
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)',
                ],
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: ': Grafik Garis',
                data: <?= json_encode($jumlah); ?>,
                type: 'line',
                fill: false,
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                tension: 0.1,
                pointRadius: 3
            }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // biar height gak tergantung lebar
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            animation: {
                duration: 1000,
                easing: 'easeOut',
                delay: function (context) {
                    return context.dataIndex * 50;
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: <?= $maxY; ?>,
                    ticks: {
                        precision: 0
                    },
                    title: {
                        display: true,
                        text: 'Jumlah Sewa'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Bulan'
                    }
                }
            }
        }
    });
</script>

