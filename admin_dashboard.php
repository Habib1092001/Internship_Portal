<?php
session_start();
include 'db.php';

/* Admin login check */
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

/* STAT COUNTS */
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetchColumn();
$totalCompanies = $pdo->query("SELECT COUNT(*) FROM companies")->fetchColumn();
$totalInternships = $pdo->query("SELECT COUNT(*) FROM internships")->fetchColumn();

/* USERS */
$users = $pdo->query("
    SELECT name, email, phone, created_at
    FROM users
    WHERE role='user'
    ORDER BY created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

/* COMPANIES */
$companies = $pdo->query("
    SELECT c.company_name, u.email, c.phone, c.created_at
    FROM companies c
    JOIN users u ON c.user_id = u.id
    ORDER BY c.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

/* PENDING INTERNSHIPS */
$pendingInternships = $pdo->query("
    SELECT i.id, i.title, i.location, i.duration, c.company_name
    FROM internships i
    JOIN companies c ON i.company_id = c.id
    WHERE i.status = 'pending'
    ORDER BY i.id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

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
            
            <nav class="hidden md:flex items-center gap-4">
                <a href="admin_report.php" class="text-sm font-medium hover:text-indigo-200 transition">Overview</a>
                
            </nav>

            <div class="hidden md:block h-6 w-px bg-white/20"></div>

            <div class="flex items-center gap-4">
                <div class="hidden sm:flex flex-col items-end leading-tight">
                    <span class="text-sm font-semibold">Administrator</span>
                    <span class="text-[10px] uppercase tracking-wider text-indigo-200">Superuser</span>
                </div>
                
                <a href="PublicPage.php" 
                   class="bg-red-500 hover:bg-red-600 px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-red-900/20 transition-all active:scale-95">
                   Logout
                </a>
            </div>

        </div>
    </div>
</header>

<div class="h-2"></div>

<div class="max-w-7xl mx-auto p-6 space-y-10">

    <?php if (isset($_GET['msg'])): ?>
        <div id="statusAlert" class="flex items-center p-4 mb-4 text-white rounded-lg shadow-lg transition-all duration-500 <?= $_GET['msg'] == 'approved' ? 'bg-green-600' : 'bg-red-600' ?>">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-bold uppercase mr-1"><?= htmlspecialchars($_GET['msg']) ?>:</span> 
            Internship status has been updated successfully.
        </div>
        <script>
            // Automatically hide the alert after 4 seconds
            setTimeout(() => {
                const alert = document.getElementById('statusAlert');
                if(alert) {
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            }, 4000);
        </script>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 shadow-lg rounded-xl text-center border-b-4 border-indigo-500">
            <p class="text-gray-500 font-medium">Total Users</p>
            <p class="text-4xl font-extrabold text-indigo-600"><?= $totalUsers ?></p>
        </div>

        <div class="bg-white p-6 shadow-lg rounded-xl text-center border-b-4 border-orange-500">
            <p class="text-gray-500 font-medium">Registered Companies</p>
            <p class="text-4xl font-extrabold text-blue-600"><?= $totalCompanies ?></p>
        </div>

        <div class="bg-white p-6 shadow-lg rounded-xl text-center border-b-4 border-green-500">
            <p class="text-gray-500 font-medium">Internship Posts</p>
            <p class="text-4xl font-extrabold text-indigo-600"><?= $totalInternships ?></p>
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <div class="bg-indigo-50 p-4 border-b border-indigo-100">
            <h2 class="text-xl font-bold text-indigo-700 uppercase tracking-wider">Registered Users</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                    <tr>
                        <th class="p-4">Name</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Phone</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($users as $u): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 font-medium text-gray-800"><?= htmlspecialchars($u['name']) ?></td>
                        <td class="p-4 text-gray-600"><?= htmlspecialchars($u['email']) ?></td>
                        <td class="p-4 text-gray-600"><?= htmlspecialchars($u['phone']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <div class="bg-blue-50 p-4 border-b border-blue-100">
            <h2 class="text-xl font-bold text-blue-700 uppercase tracking-wider">Registered Companies</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                    <tr>
                        <th class="p-4">Company</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Phone</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($companies as $c): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 font-medium text-gray-800"><?= htmlspecialchars($c['company_name']) ?></td>
                        <td class="p-4 text-gray-600"><?= htmlspecialchars($c['email']) ?></td>
                        <td class="p-4 text-gray-600"><?= htmlspecialchars($c['phone']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <div class="bg-orange-50 p-4 border-b border-orange-100">
            <h2 class="text-xl font-bold text-orange-700 uppercase tracking-wider">Pending Internships</h2>
        </div>

        <div class="p-4">
            <?php if ($pendingInternships): ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                        <tr>
                            <th class="p-4">Title</th>
                            <th class="p-4">Company</th>
                            <th class="p-4">Location</th>
                            <th class="p-4">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($pendingInternships as $i): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-4 font-semibold text-gray-800"><?= htmlspecialchars($i['title']) ?></td>
                            <td class="p-4 text-gray-600"><?= htmlspecialchars($i['company_name']) ?></td>
                            <td class="p-4 text-gray-600"><?= htmlspecialchars($i['location']) ?></td>
                            <td class="p-4 flex gap-3">
                                <a href="admin_approve_internship.php?id=<?= $i['id'] ?>&status=approved"
                                   class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 text-sm font-bold transition shadow-sm">
                                   Approve
                                </a>
                                <a href="admin_approve_internship.php?id=<?= $i['id'] ?>&status=rejected"
                                   class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm font-bold transition shadow-sm">
                                   Reject
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <div class="text-center py-10">
                    <p class="text-gray-400 italic text-lg">No pending internships at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>
</body>
</html>