<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* AUTH CHECK */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

include 'db.php';

/* FETCH USER */
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

/* FETCH NOTIFICATIONS */
$notifStmt = $pdo->prepare("
    SELECT message, created_at 
    FROM notifications 
    WHERE user_id=? 
    ORDER BY id DESC 
    LIMIT 5
");
$notifStmt->execute([$_SESSION['user_id']]);
$notifications = $notifStmt->fetchAll(PDO::FETCH_ASSOC);

/* SEARCH FILTERS */
$search = trim($_GET['search'] ?? '');
$location = trim($_GET['location'] ?? '');

$sql = "
    SELECT 
        i.*, 
        c.company_name,
        a.id AS applied_id
    FROM internships i
    JOIN companies c ON i.company_id = c.id
    LEFT JOIN applications a 
        ON a.internship_id = i.id 
        AND a.user_id = ?
    WHERE i.status = 'approved'
";

$params = [$_SESSION['user_id']];

if ($search !== '') {
    $sql .= " AND (
        i.title LIKE ? OR
        c.company_name LIKE ? OR
        i.skills LIKE ? OR
        i.stack LIKE ?
    )";
    $params = array_merge($params, ["%$search%", "%$search%", "%$search%", "%$search%"]);
}

if ($location !== '') {
    $sql .= " AND i.location LIKE ?";
    $params[] = "%$location%";
}

$sql .= " ORDER BY i.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$internships = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100">

<!-- HEADER -->
<header class="sticky top-0 z-50 bg-gradient-to-r from-blue-700 to-indigo-700 shadow-xl text-white">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

        <div class="flex items-center gap-4">
            <div class="bg-white/20 p-2 rounded-lg border border-white/10">
                <svg class="w-6 h-6 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight">Internship<span class="text-indigo-200">Portal</span></h1>
        </div>

        <div class="flex items-center gap-4">
            
            <div class="relative">
                <button onclick="toggleNotif()"
                        class="flex items-center gap-2 bg-white/10 px-4 py-2 rounded-xl border border-white/10 hover:bg-white/20 transition-all font-medium text-sm">
                    <span>🔔</span>
                    <span class="hidden md:inline">Notifications</span>
                    <span class="bg-red-500 text-[10px] px-1.5 py-0.5 rounded-full"><?= count($notifications) ?></span>
                </button>

                <div id="notifBox"
                     class="hidden absolute right-0 mt-3 w-80 bg-white text-gray-800 rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50">
                    <div class="p-4 border-b bg-gray-50 font-bold text-sm">Recent Updates</div>
                    <div class="max-h-64 overflow-y-auto">
                        <?php if ($notifications): ?>
                            <?php foreach ($notifications as $n): ?>
                                <div class="px-4 py-3 border-b hover:bg-gray-50 transition text-sm">
                                    <p class="text-gray-700 leading-snug"><?= htmlspecialchars($n['message']) ?></p>
                                    <div class="text-[10px] text-gray-400 mt-1 uppercase font-bold"><?= $n['created_at'] ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="px-4 py-8 text-center text-gray-400 text-sm italic">No new notifications</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <a href="my_applications.php" 
                class="hidden sm:flex items-center gap-2 bg-white/10 px-4 py-2 rounded-xl border border-white/10 hover:bg-white/20 transition-all font-medium text-sm">
                <span>📋</span> My Applications
            </a>

            <div class="h-8 w-px bg-white/20 mx-1"></div>

            <a href="user_profile.php"
               class="flex items-center gap-3 bg-white/10 pl-1 pr-4 py-1 rounded-full border border-white/10 hover:bg-white/20 transition-all group">
                <img src="<?= $user['profile_photo'] ? 'uploads/'.$user['profile_photo'] : 'default.png' ?>"
                     class="w-8 h-8 rounded-full border-2 border-white/30 object-cover shadow-sm group-hover:scale-105 transition-transform">
                <span class="text-sm font-semibold hidden lg:inline"><?= htmlspecialchars($user['name']) ?></span>
            </a>

            <a href="publicPage.php"
               class="bg-red-600 hover:bg-red-700 px-5 py-2 rounded-xl font-bold text-sm shadow-lg shadow-black/10 transition-all active:scale-95">
                Logout
            </a>

        </div>
    </div>
</header>

<script>
        /* Simple Toggle Logic for Notifications */
        function toggleNotif() {
            const box = document.getElementById('notifBox');
            box.classList.toggle('hidden');
        }

        // Close notification box when clicking outside
        window.onclick = function(event) {
            if (!event.target.closest('.relative')) {
                document.getElementById('notifBox').classList.add('hidden');
            }
        }
</script>

<!-- SEARCH -->
<section class="max-w-7xl mx-auto px-6 py-8">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <input name="search" value="<?= htmlspecialchars($search) ?>"
               placeholder="Search title, skills, company"
               class="border px-4 py-2 rounded-lg">

        <input name="location" value="<?= htmlspecialchars($location) ?>"
               placeholder="Location"
               class="border px-4 py-2 rounded-lg">

        <button class="bg-blue-700 text-white rounded-lg px-4 py-2 hover:bg-blue-800">
            Search
        </button>
    </form>
</section>

<!-- INTERNSHIPS -->
<section class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-10">

<?php if ($internships): foreach ($internships as $i): ?>
    <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">

        <h2 class="text-xl font-bold text-blue-700 mb-1">
            <?= htmlspecialchars($i['title']) ?>
        </h2>

        <p class="text-sm text-gray-600 mb-2">
            <?= htmlspecialchars($i['company_name']) ?>
        </p>

        <p><strong>Skills:</strong> <?= htmlspecialchars($i['skills']) ?></p>
        <p><strong>Stack:</strong> <?= htmlspecialchars($i['stack']) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($i['location']) ?></p>
        <p><strong>Duration:</strong> <?= htmlspecialchars($i['duration']) ?></p>
        <p><strong>Salary:</strong> <?= htmlspecialchars($i['salary']) ?></p>

        <?php if ($i['applied_id']): ?>
            <div class="mt-4 text-center bg-gray-300 text-gray-700 py-2 rounded-lg font-semibold">
                Already Applied
            </div>
        <?php else: ?>
            <a href="apply.php?id=<?= $i['id'] ?>"
               class="mt-4 block text-center bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                Apply Now
            </a>
        <?php endif; ?>

    </div>
<?php endforeach; else: ?>
    <p class="col-span-full text-center text-gray-500 text-lg">
        No approved internships available.
    </p>
<?php endif; ?>

</section>

<script>
function toggleNotif() {
    document.getElementById('notifBox').classList.toggle('hidden');
}
</script>

</body>
</html>
