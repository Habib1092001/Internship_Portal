<?php
session_start();
include 'db.php';

$error = '';

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['categories'];

    /* ADMIN LOGIN (admins table) */
    if ($role === 'admin') {

        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // ADMIN PASSWORD IS PLAIN TEXT (manual insert)
        if ($admin && $password === $admin['password']) {

            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['role'] = 'admin';

            header("Location: admin_dashboard.php");
            exit;
        } else {
            $error = "Invalid admin email or password";
        }
    }

    /*  USER & COMPANY LOGIN */
    else {

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
        $stmt->execute([$email, $role]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // USER
            if ($role === 'user') {
                header("Location: User_dashboard.php");
                exit;
            }

            // COMPANY
            if ($role === 'company') {

                $stmt2 = $pdo->prepare("SELECT id FROM companies WHERE user_id = ?");
                $stmt2->execute([$user['id']]);
                $company = $stmt2->fetch(PDO::FETCH_ASSOC);

                if (!$company) {
                    header("Location: company_info.php");
                    exit;
                } else {
                    $_SESSION['company_id'] = $company['id'];
                    header("Location: company_dashboard.php");
                    exit;
                }
            }
        } else {
            $error = "Invalid email, password, or role";
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Internship Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

<div class="bg-white p-8 rounded-xl shadow-xl w-96">
    <h2 class="text-3xl font-bold text-center mb-6 text-blue-600">Sign In</h2>

    <?php if(!empty($error)): ?>
        <p class="bg-red-200 text-red-700 p-2 rounded mb-4 text-center">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <form action="" method="POST" class="space-y-5">

        <div>
            <input type="email" name="email" placeholder="Email" required
                   class="border border-gray-400 w-full px-4 py-2 rounded-md focus:ring-2
                          focus:ring-blue-500 focus:outline-none">
        </div>

        <div>
            <input type="password" name="password" placeholder="Password" required
                   class="border border-gray-400 w-full px-4 py-2 rounded-md focus:ring-2
                          focus:ring-blue-500 focus:outline-none">
        </div>

        <div>
            <select name="categories" required
                    class="border border-gray-400 w-full px-4 py-2 rounded-md text-gray-600
                           focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="" disabled selected>Select Role</option>
                <option value="user">User</option>
                <option value="company">Company</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button type="submit" name="login"
                class="w-full bg-green-600 text-white py-2 rounded-md text-lg font-semibold
                       hover:bg-green-700 transition">
            Login
        </button>
    </form>

    <p class="text-center mt-5">
        Don't have an account? 
        <a href="registration.php" class="text-blue-600 hover:underline">Register</a>
    </p>
</div>

</body>
</html>
