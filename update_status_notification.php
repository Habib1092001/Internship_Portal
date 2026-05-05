<?php
session_start();
include 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

if (!isset($_SESSION['company_id'])) {
    die("Unauthorized access");
}

if (!isset($_POST['application_id'], $_POST['status'], $_POST['internship_id'])) {
    die("Invalid request");
}

$application_id = $_POST['application_id'];
$status = $_POST['status'];
$internship_id = $_POST['internship_id'];

// 1. Update application status in Database
$update = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
$update->execute([$status, $application_id]);

if ($status === 'accepted') {
    // 2. Fetch the specific student's email and company name from the DB
    $stmt = $pdo->prepare("
        SELECT a.user_id, u.email, u.name, i.title, c.company_name 
        FROM applications a
        JOIN users u ON a.user_id = u.id
        JOIN internships i ON a.internship_id = i.id
        JOIN companies c ON i.company_id = c.id
        WHERE a.id = ?
    ");
    $stmt->execute([$application_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        $user_email = $data['email']; // This pulls whatever email is in your DB
        $user_name = $data['name'];
        $internship_title = $data['title'];
        $company_name = $data['company_name'];

        // 3. Create Dashboard Notification
        $message = "Congratulations! $company_name accepted your application for '$internship_title'.";
        $notify = $pdo->prepare("INSERT INTO notifications (user_id, message, is_read) VALUES (?, ?, 0)");
        $notify->execute([$data['user_id'], $message]);

        // 4. Send Email Notification
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            
            // SENDER SETTINGS (Your University or Gmail Account)
            $mail->Username   = '22103258@iubat.edu'; 
            $mail->Password   = 'lcicenppshzuytrt'; // Your 16-digit App Password
            
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // SSL FIX for Localhost/XAMPP
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            // RECIPIENT SETTINGS
            $mail->setFrom('22103258@iubat.edu', 'Internship Portal');
            $mail->addAddress($user_email, $user_name); // Sends to the student's registered email

            // CONTENT
            $mail->isHTML(true);
            $mail->Subject = "Accepted: $internship_title at $company_name";
            $mail->Body    = "
                <div style='font-family: Arial; padding: 20px; border: 1px solid #ddd; border-radius: 8px;'>
                    <h2 style='color: #1d4ed8;'>Good News, {$user_name}!</h2>
                    <p><strong>{$company_name}</strong> has reviewed your application and has <strong>Accepted</strong> you for the <strong>{$internship_title}</strong> internship.</p>
                    <p>Please log in to your dashboard to view more details.</p>
                    <br>
                    <p>Best regards,<br>Internship Portal Team</p>
                </div>";

            $mail->send();
            
        } catch (Exception $e) {
            error_log("Mail Error: " . $mail->ErrorInfo);
        }
    }
}

header("Location: view_applicants.php?internship_id=" . $internship_id . "&success=1");
exit;