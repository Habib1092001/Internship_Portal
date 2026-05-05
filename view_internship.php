<?php
session_start();
require_once "config.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid internship.";
    exit;
}

$id = (int)$_GET['id'];

// Fetch internship with company name
$stmt = $pdo->prepare("
    SELECT i.*, u.name AS company_name
    FROM internships i
    LEFT JOIN users u ON i.company_id = u.id
    WHERE i.id = ?
    LIMIT 1
");
$stmt->execute([$id]);
$intern = $stmt->fetch();

if (!$intern) {
    echo "Internship not found.";
    exit;
}
?>




<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($intern['title']) ?> — Internship</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
        <h1 class="text-2xl font-bold"><?= htmlspecialchars($intern['title']) ?></h1>
        <p class="text-gray-600 mt-1"><strong>Company:</strong> <?= htmlspecialchars($intern['company_name'] ?? 'Unknown') ?></p>
        <p class="text-gray-600 mt-1"><strong>Stack:</strong> <?= htmlspecialchars($intern['stack'] ?? 'N/A') ?></p>
        <p class="text-gray-600 mt-1"><strong>Location:</strong> <?= htmlspecialchars($intern['location'] ?? 'N/A') ?></p>
        <p class="text-gray-600 mt-1"><strong>Duration:</strong> <?= htmlspecialchars($intern['duration'] ?? 'N/A') ?></p>
        <p class="text-gray-600 mt-1"><strong>Salary:</strong> <?= htmlspecialchars($intern['salary'] ?? 'Unpaid') ?></p>
        <p class="text-gray-600 mt-1"><strong>Deadline:</strong> <?= htmlspecialchars($intern['deadline'] ?? 'N/A') ?></p>

        <hr class="my-4">

        <div class="prose max-w-none text-gray-800">
            <?= nl2br(htmlspecialchars($intern['description'] ?? '')) ?>
        </div>

        <div class="mt-6">
            <a href="apply.php?id=<?= $intern['id'] ?>" class="bg-green-600 text-white px-4 py-2 rounded">Apply Now</a>
            <a href="user_dashboard.php" class="ml-3 text-blue-600">Back to listings</a>
        </div>
    </div>
</body>
</html>
