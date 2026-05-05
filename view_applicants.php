<?php
session_start();
include 'db.php';

if(!isset($_GET['internship_id'])){
    die("Invalid request.");
}

$internship_id = $_GET['internship_id'];

// Fetch applicants
$stmt = $pdo->prepare("
    SELECT a.*, u.name, u.email
    FROM applications a
    JOIN users u ON a.user_id = u.id
    WHERE a.internship_id = ?
");
$stmt->execute([$internship_id]);
$applicants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check for success message from URL
$message = isset($_GET['msg']) ? $_GET['msg'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Applicants List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-50 to-purple-100 min-h-screen p-8">
<div class="max-w-6xl mx-auto bg-white shadow-2xl rounded-2xl p-8 border border-gray-200">

    <?php if($message == 'updated'): ?>
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm rounded-r-lg flex items-center justify-between">
            <span class="font-medium">✅ Success: Applicant status has been updated and notification sent!</span>
            <button onclick="this.parentElement.remove()" class="text-green-900 font-bold">&times;</button>
        </div>
    <?php elseif($message == 'error'): ?>
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 shadow-sm rounded-r-lg">
            <span class="font-medium">❌ Error: Something went wrong during the update.</span>
        </div>
    <?php endif; ?>

   <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl font-extrabold text-gray-800 flex items-center gap-3">
            <span class="bg-blue-600 text-white px-4 py-2 rounded-lg">📄</span> 
            Applicants List
        </h2>
        <a href="Company_dashboard.php" 
           class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900 transition shadow-md">
            Back
        </a>
    </div>

    <?php if($applicants): ?>
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                    <th class="p-4 text-left rounded-tl-lg">Name</th>
                    <th class="p-4 text-left">Email</th>
                    <th class="p-4 text-left">CV / Resume</th>
                    <th class="p-4 text-left rounded-tr-lg">Status Action</th>
                </tr>
            </thead>

            <tbody class="bg-white">
            <?php foreach($applicants as $app): ?>
                <tr class="border-b hover:bg-blue-50 transition">
                    <td class="p-4 font-semibold text-gray-800"><?= htmlspecialchars($app['name']) ?></td>
                    <td class="p-4 text-gray-700"><?= htmlspecialchars($app['email']) ?></td>

                    <td class="p-4">
                        <?php
                        $filePath = 'uploads/cv/' . $app['cv'];
                        if(!empty($app['cv']) && file_exists($filePath)){
                            echo "<div class='flex gap-3'>";
                            echo "<a href='$filePath' target='_blank' class='text-blue-600 hover:text-blue-800 font-bold text-sm underline flex items-center gap-1'>View</a>";
                            echo "<a href='$filePath' download class='text-green-600 hover:text-green-800 font-bold text-sm underline flex items-center gap-1'>Download</a>";
                            echo "</div>";
                        } else {
                            echo "<span class='text-red-400 text-sm italic'>No file uploaded</span>";
                        }
                        ?>
                    </td>

                    <td class="p-4">
                        <form method="POST" action="update_status_notification.php" class="flex gap-2 items-center">
                            <input type="hidden" name="application_id" value="<?= $app['id'] ?>">
                            <input type="hidden" name="internship_id" value="<?= $internship_id ?>">
                            <select name="status" class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none transition">
                                <option value="pending" <?= $app['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="accepted" <?= $app['status'] == 'accepted' ? 'selected' : '' ?>>Accepted</option>
                                <option value="rejected" <?= $app['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                            </select>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-1.5 rounded-lg text-sm font-bold shadow-sm transition active:scale-95">
                                Update
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <div class="bg-yellow-50 text-yellow-800 border border-yellow-200 p-8 rounded-2xl text-center italic">
            No applicants have applied for this internship yet.
        </div>
    <?php endif; ?>
</div>
</body>
</html>