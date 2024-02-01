<?php
session_start();

include 'connection.php';

// Redirect to index.php if temp_user session is not set
if (!isset($_SESSION['temp_user'])) {
    header("Location: index.php");
    exit();
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_otp = $_POST['otp'];
    $stored_otp = $_SESSION['temp_user']['otp'];
    $user_id = $_SESSION['temp_user']['id'];

    $sql = "SELECT * FROM users WHERE id='$user_id' AND otp='$user_otp'";
    $query = mysqli_query($conn, $sql);
    $data = mysqli_fetch_array($query);

    if ($data) {
        $otp_expiry = strtotime($data['otp_expiry']);
        if ($otp_expiry >= time()) {
            $_SESSION['user_id'] = $data['id'];
            unset($_SESSION['temp_user']);
            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "OTP has expired. Please try again.";
        }
    } else {
        $error_message = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <style type="text/css">
        #container{
            border: 1px solid black;
            width: 400px;
            margin-left: 400px;
            margin-top: 50px;
            height: 330px;
        }
        form{
            margin-left: 50px;
        }
        p{
            margin-left: 50px;
        }
        h1{
            margin-left: 50px;
        }
        input[type=number]{
            width: 290px;
            padding: 10px;
            margin-top: 10px;

        }
        button{
            background-color: orange;
            border: 1px solid orange;
            width: 100px;
            padding: 9px;
            margin-left: 100px;
        }
        button:hover{
            cursor: pointer;
            opacity: .9;
        }
        .error-message {
            color: red;
            margin-left: 50px;
        }
    </style>
</head>
<body>
    <div id="container">
        <h1>Complete verification to continue to login</h1>
        <p>Enter the 6 Digit OTP Code that has been sent <br> to your email address: <?php echo $_SESSION['email']; ?></p>
        
        <?php 
        if (!empty($error_message)) {
            echo '<p class="error-message">' . $error_message . '</p>';
        }
        ?>
        
        <form method="post" action="otp_verification.php">
            <label style="font-weight: bold; font-size: 18px;" for="otp">Enter OTP Code:</label><br>
            <input type="number" name="otp" pattern="\d{6}" placeholder="Six-Digit OTP" required><br><br>
            <button type="submit">Verify OTP</button>
        </form>
    </div>
</body>
</html>
