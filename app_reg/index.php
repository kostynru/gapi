<?php
$login = $_POST['login'];
$pass = $_POST['pass'];
mysql_connect('localhost', 'root', '');
mysql_select_db('api');
$query = "SELECT * FROM api_users WHERE login = '$login'";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)){
	$uid = $row[0];
	$r_pass = $row[2];
}
if($pass == $r_pass and $pass!=null){
	setcookie('uid', $uid);
	session_start();
	$_SESSION['uid'] = $uid;
	setcookie('logged_in', true);
	header('Location: app_list.php');
} else {
	setcookie('failed', true);
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
    <form class="form-horizontal" method="POST">
    <div class="control-group">
    <label class="control-label" for="inputEmail" >Please, enter yor login</label>
    <div class="controls">
    <input type="text" id="inputEmail" placeholder="Login" name="login">
    </div>
    </div>
    <div class="control-group">
    <label class="control-label" for="inputPassword">...and your password</label>
    <div class="controls">
    <input type="password" id="inputPassword" placeholder="Password" name="pass">
    </div>
	<div class="controls">
	<label class="control-label" for="inputPassword">...or <a href="user_reg.php">register</a></label>
	</div>
    </div>
    <div class="control-group">
    <div class="controls">
    <button type="submit" class="btn">Sign in</button>
    </div>
	</div>
    </form>


	