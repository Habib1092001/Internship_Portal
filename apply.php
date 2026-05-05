<?php
session_start();
include 'db.php';

/* AUTH CHECK */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Invalid internship.");
}

$internship_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

$success_message = '';
$error_message = '';

/* 🔒 CHECK IF ALREADY APPLIED */
$checkStmt = $pdo->prepare("
    SELECT id FROM applications 
    WHERE internship_id = ? AND user_id = ?
    LIMIT 1
");
$checkStmt->execute([$internship_id, $user_id]);

if ($checkStmt->rowCount() > 0) {
    // Already applied → redirect user
    header("Location: user_dashboard.php");
    exit;
}

/* HANDLE FORM SUBMISSION */
if (isset($_POST['apply'])) {

    if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
        $error_message = "Please upload your CV (PDF only).";
    } else {

        $uploadDir = __DIR__ . '/uploads/cv/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $originalName = $_FILES['cv']['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if ($ext !== 'pdf') {
            $error_message = "Only PDF files are allowed.";
        } else {

            $fileName = time() . '_' . uniqid() . '.pdf';
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['cv']['tmp_name'], $targetFile)) {

                /* INSERT APPLICATION */
                $stmt = $pdo->prepare("
                    INSERT INTO applications 
                    (internship_id, user_id, cv, status) 
                    VALUES (?, ?, ?, 'pending')
                ");
                $stmt->execute([$internship_id, $user_id, $fileName]);

                /* OPTIONAL: NOTIFICATION */
                $notif = $pdo->prepare("
                    INSERT INTO notifications (user_id, message)
                    VALUES (?, ?)
                ");
                $notif->execute([
                    $user_id,
                    "Your application has been submitted successfully."
                ]);

                $success_message = "Application submitted successfully!";
            } else {
                $error_message = "Failed to upload CV. Check folder permissions.";
            }
        }
    }
}

/* FETCH INTERNSHIP DETAILS */
$stmt = $pdo->prepare("
    SELECT i.*, c.company_name 
    FROM internships i 
    JOIN companies c ON i.company_id = c.id 
    WHERE i.id = ?
");
$stmt->execute([$internship_id]);
$internship = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$internship) {
    die("Internship not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Apply for Internship</title>
<script src="https://cdn.tailwindcss.com"></script>

<?php if ($success_message): ?>
<script>
    // ⏳ Auto redirect after success
    setTimeout(() => {
        window.location.href = "user_dashboard.php";
    }, 2000);
</script>
<?php endif; ?>
</head>

<body class="bg-gray-100 p-8">

<div class="max-w-xl mx-auto bg-white shadow-xl rounded-2xl p-6">

    <h2 class="text-2xl font-bold mb-4">
        Apply for <?= htmlspecialchars($internship['title']) ?>
    </h2>

    <p class="mb-6 text-gray-700">
        Company: <?= htmlspecialchars($internship['company_name']) ?>
    </p>

    <?php if ($success_message): ?>
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-center font-semibold">
            <?= $success_message ?><br>
            Redirecting to dashboard...
        </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <?= $error_message ?>
        </div>
    <?php endif; ?>

    <?php if (!$success_message): ?>
    <form method="POST" enctype="multipart/form-data" class="space-y-4">

        <label class="block">
            <p class="text-red-600 font-semibold">
                *** CV Should be in PDF format only ***
            </p>
            <span class="text-gray-700 font-semibold">
                Upload CV (PDF only):
            </span>
            <input type="file" name="cv" accept="application/pdf" required
                   class="mt-1 block w-full text-sm text-gray-700 border rounded-lg p-2">
        </label>

        <div class="flex gap-4">
            <button type="submit" name="apply"
                    class="w-1/2 bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700">
                Submit Application
            </button>

            <a href="user_dashboard.php"
               class="w-1/2 text-center bg-gray-300 text-gray-800 py-3 rounded-lg font-semibold hover:bg-gray-400">
                Back
            </a>
        </div>
    </form>
    <?php endif; ?>

</div>

</body>
</html>
