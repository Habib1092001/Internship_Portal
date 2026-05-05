<?php
session_start();
include 'db.php';

/* Auth check */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'company') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* Fetch company + email */
$stmt = $pdo->prepare("SELECT c.*, u.email FROM companies c JOIN users u ON c.user_id = u.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$company = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$company) {
    die("Company profile not found.");
}

/* Determine profile completion */
$fields = ['company_name', 'phone', 'address', 'website', 'description', 'logo'];
$filled = 0;
foreach ($fields as $f) {
    if (!empty($company[$f])) $filled++;
}
$completion_percent = intval(($filled / count($fields)) * 100);
$profile_status = $completion_percent === 100 ? 'Complete' : 'Incomplete';
$profile_color = $completion_percent === 100 ? 'bg-green-500' : 'bg-red-500';

/* Post internship (PENDING by default) */
if (isset($_POST['post_internship'])) {
    $stmt = $pdo->prepare("
        INSERT INTO internships 
        (company_id, title, location, duration, salary, skills, stack, description, deadline, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    $stmt->execute([
        $company['id'],
        $_POST['title'],
        $_POST['location'],
        $_POST['duration'],
        $_POST['salary'],
        $_POST['skills'],
        $_POST['stack'],
        $_POST['description'],
        $_POST['deadline']
    ]);
    $success = "Internship submitted for admin approval.";
}

/* Fetch company internships */
$stmt = $pdo->prepare("
    SELECT i.*, 
    (SELECT COUNT(*) FROM applications a WHERE a.internship_id = i.id) AS total_applicants
    FROM internships i
    WHERE i.company_id = ?
    ORDER BY i.id DESC
");
$stmt->execute([$company['id']]);
$internships = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Company Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- Header --> 
<header class="sticky top-0 z-50 bg-gradient-to-r from-blue-800 to-indigo-700 shadow-xl text-white">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        
        <div class="flex items-center gap-4">
            <div class="bg-white/10 p-2 rounded-lg border border-white/20">
                <svg class="w-6 h-6 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold tracking-tight">
                <?= htmlspecialchars($company['company_name']) ?>
            </h1>
        </div>

        <div class="flex items-center gap-6">
            
            <nav class="hidden md:flex items-center gap-5 border-r border-white/20 pr-6">
                <a href="company_dashboard.php" class="text-sm font-medium hover:text-blue-200 transition">Dashboard</a>
            </nav>

            <div class="flex items-center gap-4">
                <a href="company_profile.php" 
                   class="flex items-center gap-3 bg-white/10 pl-1 pr-4 py-1 rounded-full border border-white/10 hover:bg-white/20 transition-all group">
                    <img src="<?= $company['logo'] ? 'uploads/'.$company['logo'] : 'default_company.png' ?>" 
                         class="w-8 h-8 rounded-full border-2 border-white/30 object-cover shadow-sm group-hover:scale-105 transition-transform" 
                         alt="Logo" />
                    <span class="text-sm font-semibold hidden sm:inline">Settings</span>
                </a>

                <a href="PublicPage.php" 
                   class="bg-red-500 hover:bg-red-600 px-5 py-2 rounded-lg font-bold text-sm shadow-lg shadow-black/10 transition-all active:scale-95">
                    Logout
                </a>
            </div>
        </div>
    </div>
</header>

<div class="max-w-7xl mx-auto p-6 space-y-6">

<?php if (!empty($success)): ?>
    <div class="bg-green-100 text-green-800 p-3 rounded shadow">
        <?= $success ?>
    </div>
<?php endif; ?>



<!-- POST INTERNSHIP -->
<div class="bg-white p-6 rounded-2xl shadow">
    <h2 class="text-xl font-semibold mb-4">Post New Internship</h2>

    <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input name="title" required placeholder="Title" class="border p-2 rounded-lg">
        <input name="location" required placeholder="Location" class="border p-2 rounded-lg">
        <input name="duration" required placeholder="Duration" class="border p-2 rounded-lg">
        <input name="salary" required placeholder="Salary" class="border p-2 rounded-lg">
        <input name="skills" required placeholder="Skills" class="border p-2 rounded-lg">
        <input name="stack" required placeholder="Tech Stack" class="border p-2 rounded-lg">
        <input type="date" required name="deadline" class="border p-2 rounded-lg">
        <textarea name="description" placeholder="Description" class="border p-2 rounded-lg md:col-span-2"></textarea>

        <button name="post_internship"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg md:col-span-2 hover:bg-blue-700 transition">
            Submit for Approval
        </button>
    </form>
</div>

<!-- INTERNSHIPS LIST -->
<div class="bg-white p-6 rounded-2xl shadow">
    <h2 class="text-xl font-semibold mb-4">Your Internships</h2>

    <table class="w-full border text-center">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2">Title</th>
                <th class="p-2">Skills</th>
                <th class="p-2">Status</th>
                <th class="p-2">Applicants</th>
                <th class="p-2">Action</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($internships as $i): ?>
            <?php
                $status_color = match($i['status']) {
                    'approved' => 'bg-green-500 text-white',
                    'pending' => 'bg-yellow-400 text-gray-900',
                    'rejected' => 'bg-red-500 text-white',
                    default => 'bg-gray-300 text-gray-700'
                };
            ?>
            <tr class="border-t">
                <td class="p-2"><?= htmlspecialchars($i['title']) ?></td>
                <td class="p-2"><?= htmlspecialchars($i['skills']) ?></td>
                <td class="p-2 font-semibold px-2 py-1 rounded <?= $status_color ?>"><?= ucfirst($i['status']) ?></td>
                <td class="p-2"><?= $i['total_applicants'] ?></td>
                <td class="p-2">
                    <?php if ($i['status'] === 'approved'): ?>
                        <a href="view_applicants.php?internship_id=<?= $i['id'] ?>"
                           class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                           View
                        </a>
                    <?php else: ?>
                        <span class="text-gray-500">Not Available</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</div>
</body>
</html>
