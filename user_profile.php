<?php
session_start();

if (empty($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

// DB Connection
$pdo = new PDO("mysql:host=localhost;dbname=internship_portal;charset=utf8", "root", "", [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Fetch user
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Update profile (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $cgpa = $_POST['cgpa'];

    // OPTIONAL: Update profile photo
    $photoName = $user['profile_photo'];

    if (!empty($_FILES['photo']['name'])) {
        $photoName = time() . "_" . $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/" . $photoName);
    }

    $update = $pdo->prepare("
        UPDATE users SET 
            name=?, email=?, phone=?, address=?, cgpa=?, profile_photo=? 
        WHERE id=?
    ");

    $update->execute([$name, $email, $phone, $address, $cgpa, $photoName, $_SESSION['user_id']]);

    header("Location: user_profile.php?updated=true");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="max-w-3xl mx-auto bg-white p-8 mt-10 shadow-lg rounded-2xl">

    <h1 class="text-4xl font-bold text-center text-blue-700 mb-6">My Profile</h1>

    <?php if (!empty($_GET['updated'])): ?>
        <p class="text-green-600 text-center mb-4">Profile Updated Successfully!</p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-6">

        <div class="text-center">
            <img src="<?= $user['profile_photo'] ? 'uploads/'.$user['profile_photo'] : 'default.png' ?>"
                 class="w-28 h-28 rounded-full mx-auto border shadow">
            <input type="file" name="photo" class="mt-2">
        </div>

        <div>
            <label class="font-semibold">Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>"
                   class="w-full border px-4 py-2 rounded-lg">
        </div>

        <div>
            <label class="font-semibold">Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>"
                   class="w-full border px-4 py-2 rounded-lg">
        </div>

        <div>
            <label class="font-semibold">Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>"
                   class="w-full border px-4 py-2 rounded-lg">
        </div>

        <div>
            <label class="font-semibold">Address</label>
            <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>"
                   class="w-full border px-4 py-2 rounded-lg">
        </div>

        <div>
            <label class="font-semibold">CGPA</label>
            <input type="text" name="cgpa" value="<?= htmlspecialchars($user['cgpa']) ?>"
                   class="w-full border px-4 py-2 rounded-lg">
        </div>
        

           <div class="flex gap-4">
                <button class="flex-1 bg-blue-600 text-white py-3 rounded-lg text-xl font-semibold hover:bg-blue-700">
                    Update Profile
                </button>

                <button class="w-40 bg-blue-600 text-white py-3 rounded-lg text-xl font-semibold hover:bg-blue-700">
                    <a href="User_dashboard.php">Back</a>
                </button>
            </div>


    </form>
</div>

</body>
</html>
