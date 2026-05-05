<?php
session_start();
include 'db.php'; // your PDO connection

// Ensure logged-in company
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'company'){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch existing company info from database
$stmt = $pdo->prepare("SELECT * FROM companies WHERE user_id = ?");
$stmt->execute([$user_id]);
$company = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if(isset($_POST['submit'])){
    $company_name = $_POST['company_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $website = $_POST['website'];
    $description = $_POST['description'];

    if($company){
        // Update existing record
        $stmt = $pdo->prepare("UPDATE companies SET company_name = ?, address = ?, phone = ?, website = ?, description = ? WHERE user_id = ?");
        $stmt->execute([$company_name, $address, $phone, $website, $description, $user_id]);
        $_SESSION['company_id'] = $company['id'];
    } else {
        // Insert new record
        $stmt = $pdo->prepare("INSERT INTO companies (user_id, company_name, address, phone, website, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $company_name, $address, $phone, $website, $description]);
        $_SESSION['company_id'] = $pdo->lastInsertId();
    }

    // Redirect to company dashboard
    header("Location: company_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Company Information</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 30px; }
        form { max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px #ccc; }
        input, textarea { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { padding: 10px 20px; background: #28a745; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #218838; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Company Information</h2>
    <form method="post" action="">
        <label>Company Name:</label>
        <input type="text" name="company_name" value="<?= htmlspecialchars($company['company_name'] ?? '') ?>" required>

        <label>Re-Type Address:</label>
        <input type="text" name="address" value="<?= htmlspecialchars($company['address'] ?? '') ?>">

        <label>Another Phone:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($company['phone'] ?? '') ?>">

        <label>Website:</label>
        <input type="text" name="website" value="<?= htmlspecialchars($company['website'] ?? '') ?>">

        <label>Description:</label>
        <textarea name="description"><?= htmlspecialchars($company['description'] ?? '') ?></textarea>

        <button type="submit" name="submit">Save Information</button>
    </form>
</body>
</html>
