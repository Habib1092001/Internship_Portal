<?php
session_start();
include 'db.php';

// Check if company is logged in
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'company'){
    die("Unauthorized access");
}

if(isset($_POST['application_id'], $_POST['status'])){
    $application_id = $_POST['application_id'];
    $status = $_POST['status'];

    // Validate status
    if(!in_array($status, ['pending','accepted','rejected'])){
        die("Invalid status");
    }

    $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $stmt->execute([$status, $application_id]);

    // Redirect back to applicants page
    if(isset($_POST['internship_id'])){
        header("Location: view_applicants.php?internship_id=" . $_POST['internship_id']);
    } else {
        header("Location: view_applicants.php");
    }
    exit;
} else {
    die("Invalid request");
}
