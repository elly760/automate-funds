<?php
// Include your database connection file (connection.php)
include('connection.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

// Fetch university secretary's email from the database based on their role
$roleSec = "university_sec"; // Assuming role identifier for the university secretary
$roleDean = "faculty_dean"; // Assuming role identifier for the faculty dean

$fetchEmailSql = "SELECT email FROM users WHERE role IN ('$roleSec', '$roleDean')";
$result = mysqli_query($conn, $fetchEmailSql);

// Initialize recipients array
$recipients = [];

while ($row = mysqli_fetch_assoc($result)) {
    // Validate email address
    if (filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
        // Add each valid email to the recipients array
        $recipients[] = $row['email'];
    } else {
        // Handle invalid email address
        // echo "Invalid email address: {$row['email']}. Skipping.\n";
    }
}

// If there are recipients found
if (!empty($recipients)) {
    $subject = "New Fund Request";
    // Link to login site
    $loginLink = "http://localhost/automate%20funds/login.php";
    $message = "A new fund request has been submitted.\n\nAmount: $_POST[amount]\nReason: $_POST[reason]\nDescription: $_POST[description]\nDate Requested: " . date('Y-m-d H:i:s') . "\n\nPlease login <a href=\"$loginLink\">here</a>."; // Added login link

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

        // Sender
        $mail->setFrom('jukyeelly@gmail.com', 'Muni University FIRA'); // Replace with sender's email and name

        // Add recipients
        foreach ($recipients as $recipient) {
            $mail->addAddress($recipient); // Add each recipient
        }

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Send email
        $mail->send();
        echo "Notification sent to the university secretary and faculty dean.";
    } catch (Exception $e) {
        echo "Failed to send notification. Error: {$mail->ErrorInfo}";
    }
} else {
    // No valid email addresses found
    echo "No valid email addresses found for the university secretary and faculty dean.";
}
?>
