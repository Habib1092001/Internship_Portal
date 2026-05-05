<?php
session_start();
ob_start(); // Start output buffering to prevent header errors

/* DATABASE CONNECTION */
$host = "localhost";
$dbname = "internship_portal";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

$errors = []; // stores validation errors
$old = []; // stores old input values for form repopulation or it says that if any thing already exists same.

/* FORM SUBMISSION */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name     = trim($_POST["name"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $phone    = trim($_POST["phone"] ?? "");
    $address  = trim($_POST["address"] ?? "");
    $cgpa     = isset($_POST["cgpa"]) && $_POST["cgpa"] !== "" ? (float)$_POST["cgpa"] : null;
    $role     = $_POST["role"] ?? "";
    $password = $_POST["password"] ?? "";
    $confirm  = $_POST["confirm_password"] ?? "";

    // Save old values
    $old = [
        "name" => htmlspecialchars($name),
        "email" => htmlspecialchars($email),
        "phone" => htmlspecialchars($phone),
        "address" => htmlspecialchars($address),
        "cgpa" => $cgpa,
        "role" => $role
    ];

    // VALIDATION
    if (empty($name)) $errors[] = "Username is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
    if (empty($phone) || !preg_match("/^\d{10,15}$/", $phone)) $errors[] = "Enter a valid phone number";
    if (empty($role) || !in_array($role, ["user","company","admin"])) $errors[] = "Select a valid role";
    if ($password !== $confirm) $errors[] = "Passwords do not match";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters";
    if ($cgpa !== null && ($cgpa < 0 || $cgpa > 4)) $errors[] = "CGPA must be between 0.0 and 4.0";

    // EMAIL CHECK
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->rowCount() > 0) $errors[] = "Email already registered!";

    // FILE UPLOAD
    $photoPath = null;
    if (!empty($_FILES["profile_photo"]["name"])) {
        $file = $_FILES["profile_photo"];
        $allowedExt = ["jpg", "jpeg", "png", "webp"];
        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file["tmp_name"]);
        finfo_close($finfo);

        $allowedMime = ["image/jpeg", "image/png", "image/webp"];
        if (!in_array($ext, $allowedExt) || !in_array($mime, $allowedMime)) {
            $errors[] = "Only JPG, PNG, WEBP images are allowed!";
        } else {
            $newName = uniqid() . "." . $ext;
            $uploadDir = "uploads/";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $photoPath = $uploadDir . $newName;
            if (!move_uploaded_file($file["tmp_name"], $photoPath)) {
                $errors[] = "Failed to upload profile photo.";
            }
        }
    }

    // INSERT INTO DB IF NO ERRORS
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, address, cgpa, role, password, profile_photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $address, $cgpa, $role, $hash, $photoPath]);

        // SUCCESS REDIRECT
        header("Location: success.html");
        exit();
    }
}
ob_end_flush(); // End buffering/ending the php code
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Registration</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

<div class="bg-white p-8 rounded-xl shadow-xl w-96">
    <h2 class="text-3xl font-bold text-center mb-6 text-blue-600">Create Account</h2>

    <!-- ERROR DISPLAY which part already exists that part showing -->
    <?php if (!empty($errors)): ?>
        <div class="bg-red-200 text-red-700 p-3 rounded mb-4">
            <?php foreach ($errors as $e): ?>
                <p>• <?= $e ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form id="registerForm" action="" method="POST" enctype="multipart/form-data" class="space-y-5">

        <input type="text" name="name" placeholder="Username" value="<?= $old['name'] ?? '' ?>" required class="input border w-full px-4 py-2 rounded cursor-pointer">

        <input type="email" name="email" placeholder="Email" value="<?= $old['email'] ?? '' ?>" required class="input border w-full px-4 py-2 rounded cursor-pointer">

        <input type="text" name="phone" placeholder="Phone" value="<?= $old['phone'] ?? '' ?>" class="input border w-full px-4 py-2 rounded cursor-pointer">

        <input type="text" name="address" placeholder="Address" value="<?= $old['address'] ?? '' ?>" class="input border w-full px-4 py-2 rounded cursor-pointer">

        <input type="number" step="0.01" min="0" max="4" name="cgpa" placeholder="CGPA" value="<?= $old['cgpa'] ?? '' ?>" class="input border w-full px-4 py-2 rounded cursor-pointer">

        <select name="role" required class="border w-full px-4 py-2 rounded cursor-pointer">
            <!-- authenticaating the role -->
            <option value="" disabled <?= empty($old['role']) ? 'selected' : '' ?>>Select Category</option>
            <option value="user" <?= (isset($old['role']) && $old['role']=='user') ? 'selected' : '' ?>>User</option>
            <option value="company" <?= (isset($old['role']) && $old['role']=='company') ? 'selected' : '' ?>>Company</option>
        </select>
        
        <input type="file" name="profile_photo" class="border w-full px-3 py-2 rounded bg-white cursor-pointer">

        <input type="password" name="password" placeholder="Password" required class="border w-full px-4 py-2 rounded cursor-pointer">

        <input type="password" name="confirm_password" placeholder="Confirm Password" required class="border w-full px-4 py-2 rounded cursor-pointer">

        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded text-lg cursor-pointer">Register</button>
    </form>

    <p class="text-center mt-5">
        Already have an account?
        <a href="login.php" class="text-blue-600 underline">Login</a>
    </p>
</div>
</body>
</html>
