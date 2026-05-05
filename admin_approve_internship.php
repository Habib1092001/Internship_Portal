<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'];
$status = $_GET['status'];

if (in_array($status, ['approved', 'rejected'])) {
    $stmt = $pdo->prepare("UPDATE internships SET status=? WHERE id=?");
    $stmt->execute([$status, $id]);
    
    // Redirect back with a success message flag
    header("Location: admin_dashboard.php?msg=" . $status);
    exit();
}

header("Location: admin_dashboard.php");
exit();
?>