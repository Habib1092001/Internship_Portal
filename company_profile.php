<?php
session_start();
include 'db.php';

// Must be company
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'company') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch company + user email
$stmt = $pdo->prepare("
    SELECT c.*, u.email 
    FROM companies c
    JOIN users u ON c.user_id = u.id
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$company = $stmt->fetch(PDO::FETCH_ASSOC);

// Update profile (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $company_name = $_POST['company_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $website = $_POST['website'];
    $description = $_POST['description'];
    $email = $_POST['email'];

    // Upload logo if selected
    $logo = $company['logo'];
    if (!empty($_FILES['logo']['name'])) {
        $logo = time() . "_" . $_FILES['logo']['name'];
        move_uploaded_file($_FILES['logo']['tmp_name'], "uploads/" . $logo);
    }

    // Update user email
    $pdo->prepare("UPDATE users SET email=? WHERE id=?")
        ->execute([$email, $user_id]);

    // Update company table
    $query = $pdo->prepare("
        UPDATE companies SET 
            company_name=?, address=?, phone=?, website=?, description=?, logo=?
        WHERE user_id=?
    ");

    $query->execute([$company_name, $address, $phone, $website, $description, $logo, $user_id]);

    header("Location: company_profile.php?updated=true");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Company Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-3xl mx-auto bg-white p-8 mt-10 shadow-lg rounded-2xl">

    <h1 class="text-4xl font-bold text-center text-blue-700 mb-6">Company Profile</h1>

    <?php if (!empty($_GET['updated'])): ?>
        <p class="text-green-600 text-center mb-4">Profile Updated Successfully!</p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-6">

        <div class="text-center">
            <img src="<?= $company['logo'] ? 'uploads/'.$company['logo'] : 'default_company.png' ?>"
                 class="w-28 h-28 rounded-full mx-auto border shadow">
            <input type="file" name="logo" class="mt-2">
        </div>

        <div>
            <label class="font-semibold">Company Name</label>
            <input type="text" name="company_name" value="<?= htmlspecialchars($company['company_name']) ?>"
                   class="w-full border px-4 py-2 rounded-lg">
        </div>

        <div>
            <label class="font-semibold">Email (Login Email)</label>
            <input type="email" name="email" value="<?= htmlspecialchars($company['email']) ?>"
                   class="w-full border px-4 py-2 rounded-lg">
        </div>

        <div>
            <label class="font-semibold">Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($company['phone']) ?>"
                   class="w-full border px-4 py-2 rounded-lg">
        </div>

        <div>
            <label class="font-semibold">Address</label>
            <input type="text" name="address" value="<?= htmlspecialchars($company['address']) ?>"
                   class="w-full border px-4 py-2 rounded-lg">
        </div>

        <div>
            <label class="font-semibold">Website</label>
            <input type="text" name="website" value="<?= htmlspecialchars($company['website']) ?>"
                   class="w-full border px-4 py-2 rounded-lg">
        </div>

        <div>
            <label class="font-semibold">Company Description</label>
            <textarea name="description"
                      class="w-full border px-4 py-2 rounded-lg"><?= htmlspecialchars($company['description']) ?></textarea>
        </div>

        <div class="flex gap-4">
            <button class="flex-1 bg-blue-600 text-white py-3 rounded-lg text-xl font-semibold hover:bg-blue-700">
                Update Profile
            </button>

            <button class="w-40 bg-blue-600 text-white py-3 rounded-lg text-xl font-semibold hover:bg-blue-700">
                <a href="Company_dashboard.php">Back</a>
            </button>
        </div>

    </form>
</div>

</body>
</html>
