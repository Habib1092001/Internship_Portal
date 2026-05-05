<?php
session_start();
include 'db.php';

/* AUTH CHECK */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* FETCH APPLIED INTERNSHIPS WITH STATUS */
$stmt = $pdo->prepare("
    SELECT 
        a.status AS app_status, 
        a.created_at AS applied_date,
        i.title, 
        c.company_name, 
        i.location
    FROM applications a
    JOIN internships i ON a.internship_id = i.id
    JOIN companies c ON i.company_id = c.id
    WHERE a.user_id = ?
    ORDER BY a.id DESC
");
$stmt->execute([$user_id]);
$my_apps = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Application Status</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100">

<header class="bg-blue-700 p-4 text-white shadow">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold">Application Status</h1>
        <a href="user_dashboard.php"  class="bg-red-600 px-4 py-2 rounded-lg hover:bg-red-700">Back to Dashboard</a>
    </div>
</header>

<main class="max-w-7xl mx-auto p-6">
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-4">Internship</th>
                    <th class="p-4">Company</th>
                    <th class="p-4">Applied Date</th>
                    <th class="p-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php if ($my_apps): foreach ($my_apps as $app): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="p-4 font-semibold"><?= htmlspecialchars($app['title']) ?></td>
                        <td class="p-4"><?= htmlspecialchars($app['company_name']) ?></td>
                        <td class="p-4 text-sm text-gray-500"><?= date('M d, Y', strtotime($app['applied_date'])) ?></td>
                        <td class="p-4 text-center">
                            <?php 
                                $color = 'bg-yellow-400 text-black'; // Default Pending
                                if($app['app_status'] == 'accepted') $color = 'bg-green-600 text-white';
                                if($app['app_status'] == 'rejected') $color = 'bg-red-600 text-white';
                            ?>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase <?= $color ?>">
                                <?= $app['app_status'] ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr>
                        <td colspan="4" class="p-10 text-center text-gray-500 italic">You haven't applied for any internships yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>