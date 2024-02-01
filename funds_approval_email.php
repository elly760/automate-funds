<?php
// Include your database connection file (connection.php)
include('connection.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

// Fetch university secretary's email from the database based on their role
$role = "depart_member"; // Assuming role identifier for the university secretary
$fetchEmailSql = "SELECT email FROM users WHERE role = '$role'";
$result = mysqli_query($conn, $fetchEmailSql);
if ($row = mysqli_fetch_assoc($result)) {
    $depart_memberEmail = $row['email'];
    $subject = "Funds request status";
    $message = "Please login to the system to check the status of the funds " . date('Y-m-d H:i:s');

    // Initialize PHPMailer
    $mail = new PHPMailer(true);
    try {
        // SMTP configuration for Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jukyeelly@gmail.com'; // Replace with sender's email address
        $mail->Password = 'mnvgbglfmhcmbvcd'; // Replace with sender's email password or app password
        $mail->Port = 465;
        $mail->SMTPSecure = 'ssl';

        // Sender and recipient
        $mail->setFrom('jukyeelly@gmail.com', 'Muni University FIRA'); // Replace with sender's email and name
        $mail->addAddress($depart_memberEmail); // Replace with recipient's email address

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Send email
        $mail->send();
        echo "Notification sent to the member of the department.";
    } catch (Exception $e) {
        echo "Failed to send notification to the memebr of the department. Error: {$mail->ErrorInfo}";
    }
} else {
    // University secretary email not found
    echo "Department member email not found in the database.";
}
?>
