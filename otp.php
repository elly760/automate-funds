<!doctype html>
<?php
if(isset($_POST['login'])){
// Authorisation details.
$username = "jukyeelly@gmail.com";
$hash = "08e173a72260c7a99d7017137193bbe52fd98a6e7708a73ea76375bfd810aa16";

// Config variables. Consult http://api.txtlocal.com/docs for more info.
$test = "0";
$name =$_POST['name'];

// Data for text message. This is the text message data.
$sender = "API Test"; // This is who the message appears to be from.
$numbers = $_POST['num']; // A single number or a comma-seperated list of numbers
$otp=mt_rand(100000,999999);
setcookie("otp", $otp);
$message = "Hey ".$name. "your OTP IS ".$otp;
// 612 chars or less
// A single number or a comma-seperated list of numbers
$message = urlencode($message);
$data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
$ch = curl_init('http://api.txtlocal.com/send/?');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch); // This is the result from the API
echo("OTP SEND SUCCESSFULLY");
curl_close($ch);
}
if(isset($_POST['ver'])){
$verotp=$_POST['otp'];
if($verotp==$_COOKIE['otp']){
echo("logined successfully");

}else{
echo("otp worng");
}
}
?>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<form method="post" action="otp.php">
<table align="center">
<tr>
<td>Name</td>
<td><input type="text" name="name" placeholder="Enter your Name"</td>
</tr>
<tr>
<td>Phone Number</td>
<td><input type="text" name="num" placeholder="Valid!with country Code"</td>
</tr>
<tr>
<td></td>
<td><input type="submit" name="login" value="[send otp]"</td>
</tr>
<tr>
<td>Verify OTP:</td>
<td><input type="text" name="otp" placeholder="enter received otp"</td>
</tr>
<tr>
<td></td>
<td><input type="submit" name="ver" value="verify otp"</td>
</tr>
</table>
</form>
</body>
</html>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        form {
            max-width: 400px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            display: inline-block;
            border: 1px solid #ccc;
            box-sizing: border-box;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

    </style>