<?php
$login = $_POST['login'];
$pass = $_POST['pass'];
$pass_rep = $_POST['pass_rep'];
$email = $_POST['email'];
if(isset($login)){
if(($login or $pass or $pass_rep or $email) == null){
	echo 'All fields must be fill!';
} elseif ($pass != $pass_rep) {
	echo 'Your password and its repeat don\'t similar!';
} else {
	mysql_connect('localhost', 'root', '');
	mysql_select_db('api');
	$query1 = "SELECT * FROM api_users WHERE login = '$login'";
	$result = mysql_query($query1);
	while($row = mysql_fetch_array($result)){
		$result1 = $row;
	}
	if(empty($result1)){
	$query2 = "INSERT INTO api_users(login, pass, email) VALUES ('$login', '$pass', '$email')";
	mysql_query($query2) or die(mysql_error());
	header('Location: index.php');
	} else {
		echo 'Login "'.$login.'" already exist';
	}
}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Getting API</title>
<link href="css/bootstrap.css" rel="stylesheet">
<style>
	body {
		background-image: url("css/bg.png");
		background-repeat: repeat-y;
		background-attachment: fixed;
	}
	
	</style>
</head>
<body>
<div class="container">
<br>
<form name="user_register" method="POST">
Please, enter your login here:<br>
<input type="text" name="login"><br>
...then enter your password:<br>
<input type="text" name="pass"><br>
...and, repeat it:<br>
<input type="text" name="pass_rep"><br>
Well, let's enter your e-mail right here:<br>
<input type="text" name="email"><br>
That's all I wanna know, just click "Register" now!<br>
<button type="submit" class="btn btn-primary">Register</button></form>