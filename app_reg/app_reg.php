<?php
session_start();
if($_POST['app_name'] != null or $_POST['app_url'] != null){
	mysql_connect('localhost', 'root', '');
	mysql_select_db('api');
	$app_name = $_POST['app_name'];
	$app_url = $_POST['app_url'];
	$app_key = md5($app_name.$app_url.time().rand());
	$app_owner = $_SESSION['uid'];
	$query1 = "INSERT INTO apps(name, url, owner_id) VALUES ('$app_name', '$app_url', '$app_owner')";
	
	mysql_query($query1) or die(mysql_error());
	$query3 = "SELECT Max(apps.id) FROM apps WHERE apps.owner_id = '$app_owner'";
	$result = mysql_query($query3) or die(mysql_error());
	while($row = mysql_fetch_array($result)){
		$app_id = $row[0];
	}
	$query2 = "INSERT INTO apps_access(private_key, app_id) VALUES ('$app_key', '$app_id')";
	mysql_query($query2) or die(mysql_error());
	header('Location: app_list.php');
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
<script>
function CheckFields() {
var app_url = document.getElementById("app_url").value;
var app_name = document.getElementById("app_name").value;
if(app_url == null){
	document.getElementById("app_name").id = 'inputError';
	return false;
}
if(app_name == null){
	document.getElementById("app_url").id = 'inputError';
	return false;
}
}
</script>
</head>
<body>
<div class="container">
<br>
<div class="navbar">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <div class="brand">GettingAPI</div>

            <div class="nav-collapse">
                <ul class="nav">
					<li><a href="app_reg.php">New application</a></li>
					<li><a href="app_list.php">List of applications</a></li>
					<li><a href="perferences.php">Options</a></li>
				</ul>
				
				<ul class="nav pull-right">
					<li><a href="app_list.php?act=logout">Logout</a></li>
				</ul>
			</div>
        </div>
    </div>
</div>
<br>
<form name="app_reg" method="POST" onsubmit="return CheckFields()">
Your new application's title:<br>
<input type="text" name="app_name" id="app_name"><br>
Your new application's url:<br>
<input type="text" name="app_url" id="app_url"><br>
<button type="submit" class="btn btn-primary">Create application</button>
</form>