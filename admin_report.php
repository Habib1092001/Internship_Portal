<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';

/* FETCH COMPANIES */
$companies = $pdo->query("
    SELECT id, company_name, website, address, phone, logo
    FROM companies
")->fetchAll(PDO::FETCH_ASSOC);

/* FETCH APPLICATION STATUS COUNTS */
$appStats = $pdo->query("
    SELECT 
        i.company_id,
        a.status,
        COUNT(*) AS total
    FROM applications a
    JOIN internships i ON a.internship_id = i.id
    GROUP BY i.company_id, a.status
")->fetchAll(PDO::FETCH_ASSOC);

/* ORGANIZE DATA */
$stats = [];
foreach ($appStats as $row) {
    $stats[$row['company_id']][$row['status']] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
</head>

<header class="sticky top-0 z-50 bg-gradient-to-r from-indigo-700 to-blue-600 shadow-xl text-white">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        
        <div class="flex items-center gap-4">
                <div class="bg-white/20 p-2 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                </div>
                    <h1 class="text-2xl font-extrabold tracking-tight">
                        Admin<span class="text-indigo-200">Panel</span>
                    </h1>
            </div>

            <div class="flex items-center gap-6">
                    <a href="admin_dashboard.php" class="bg-red-500 hover:bg-red-600 px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-red-900/20 transition-all active:scale-95">
                                Back to dashboard
                    </a>
            </div>
        </div>
    </div>
</header>

<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900">📊 Company Application Insights</h2>
        <p class="text-gray-500">Real-time breakdown of application statuses across all registered companies.</p>
    </div>

    <?php if (empty($companies)): ?>
        <div class="bg-white p-12 text-center rounded-2xl shadow-sm border border-dashed border-gray-300">
            <p class="text-gray-400 text-lg italic">No company records were found in the database.</p>
        </div>
    <?php endif; ?>

    <?php foreach ($companies as $c): 
        $pending  = $stats[$c['id']]['pending']  ?? 0;
        $accepted = $stats[$c['id']]['accepted'] ?? 0;
        $rejected = $stats[$c['id']]['rejected'] ?? 0;
        $total    = $pending + $accepted + $rejected;
    ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8 grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">

        <div>
            <div class="flex items-center gap-5 mb-6">
                <img src="<?= $c['logo'] ? 'uploads/'.$c['logo'] : 'default.png' ?>" 
                     alt="Logo"
                     class="w-20 h-20 rounded-xl border border-gray-100 object-cover shadow-sm">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($c['company_name']) ?></h2>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars($c['address']) ?></p>
                </div>
            </div>

            <div class="space-y-3 text-sm border-t border-gray-50 pt-4">
                <p class="flex items-center gap-2 text-gray-600">
                    <span class="font-semibold w-16">Phone:</span> <?= htmlspecialchars($c['phone']) ?>
                </p>
                <p class="flex items-center gap-2 text-gray-600">
                    <span class="font-semibold w-16">Website:</span> 
                    <a href="<?= htmlspecialchars($c['website']) ?>" target="_blank" class="text-blue-600 hover:underline">
                        <?= htmlspecialchars($c['website']) ?>
                    </a>
                </p>
            </div>

            <div class="mt-8 grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="p-3 bg-yellow-50 rounded-xl border border-yellow-100">
                    <p class="text-[10px] text-yellow-700 uppercase font-black tracking-wider">Pending</p>
                    <p class="text-xl font-bold text-yellow-600"><?= $pending ?></p>
                </div>
                <div class="p-3 bg-green-50 rounded-xl border border-green-100">
                    <p class="text-[10px] text-green-700 uppercase font-black tracking-wider">Accepted</p>
                    <p class="text-xl font-bold text-green-600"><?= $accepted ?></p>
                </div>
                <div class="p-3 bg-red-50 rounded-xl border border-red-100">
                    <p class="text-[10px] text-red-700 uppercase font-black tracking-wider">Rejected</p>
                    <p class="text-xl font-bold text-red-600"><?= $rejected ?></p>
                </div>
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-200">
                    <p class="text-[10px] text-gray-700 uppercase font-black tracking-wider">Total</p>
                    <p class="text-xl font-bold text-gray-900"><?= $total ?></p>
                </div>
            </div>
        </div>

        <div class="flex flex-col items-center justify-center bg-gray-50/50 rounded-2xl py-6">
            <div class="relative w-[280px] h-[280px]">
                <canvas id="chart<?= $c['id'] ?>"></canvas>
            </div>
        </div>

    </div>

    <script>
    (function() {
        const ctx = document.getElementById('chart<?= $c['id'] ?>');
        
        new Chart(ctx, {
            type: 'pie',
            plugins: [ChartDataLabels],
            data: {
                labels: ['Pending', 'Accepted', 'Rejected'],
                datasets: [{
                    data: [<?= $pending ?>, <?= $accepted ?>, <?= $rejected ?>],
                    backgroundColor: ['#facc15', '#22c55e', '#ef4444'],
                    hoverOffset: 0,
                    borderWidth: 3,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                events: [], // Static chart: no hover/scroll interference
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { size: 12, weight: '600' }
                        }
                    },
                    datalabels: {
                        color: '#fff',
                        font: { weight: 'bold', size: 14 },
                        formatter: (value, context) => {
                            if (value === 0) return '';
                            let sum = 0;
                            let dataArr = context.chart.data.datasets[0].data;
                            dataArr.map(data => { sum += data; });
                            return (value * 100 / sum).toFixed(1) + "%";
                        }
                    }
                }
            }
        });
    })();
    </script>

    <?php endforeach; ?>

</div>

</body>
</html>