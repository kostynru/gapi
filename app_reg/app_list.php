<?php
session_start();
if($_GET['act'] == 'logout'){
setcookie('logged_in', false);
$_SESSION = array();
header('Location: index.php');
}
if($_GET['act'] == 'delete'){
mysql_connect('localhost', 'root', '');
mysql_select_db('api');
$app_id = $_GET['app_id'];
if(!is_numeric($app_id)){
header('Location: app_list.php');
} else {
if(!isset($_POST['app_delete'])) {
echo '
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
<form method="POST">
To delete this application retype your password:<br>
<input type="password" name="pass"><br>
<input type="hidden" name="app_delete" value="'.$app_id.'"><br>
<p>
<button class="btn btn-danger" type="submit">Delete application</button>
<a href="app_list.php"><button class="btn btn-success" type="button">Cancel</button></a>
</p>
</form>
';
} else {
$appl_id = $_POST['app_delete'];
$uid = $_SESSION['uid'];
$query1 = "SELECT pass FROM api_users WHERE id = '$uid'";
$result = mysql_query($query1) or die(mysql_error());
while($row = mysql_fetch_array($result)){
	$re_pass = $row[0];
}
if($re_pass = $_POST['pass']){
$query = "DELETE FROM apps WHERE id = '$appl_id'";
mysql_query($query) or die(mysql_error());
header('Location: app_list.php');
} else {
header('Location: app_lisr.php?act=delete');
}
}
}
}
session_start();
$uid = $_SESSION['uid'];
if(!is_numeric($uid)){
	header('Location: index.php');
}
if(empty($uid) or $uid==null or $_COOKIE['logged_in'] == false){
	header('Location: index.php');
}
mysql_connect('localhost', 'root', '');
mysql_select_db('api');
$query = "SELECT * FROM apps JOIN apps_access ON apps_access.app_id = apps.id WHERE owner_id = $uid";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_assoc($result)){
	$apps[] = $row;
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
<table class="table table-hover">
<?php
if(empty($apps)){
echo '<tr><td>There\'s no registered applications<td></tr>';
} else {
echo'<thead>ID</thead>';
foreach($apps as $value){
	echo '<tr>';
	echo '<td>'.$value['app_id'].'</td>';
	echo '<td>'.$value['name'].'</td>';
	echo '<td>'.$value['private_key'].'</td>';
	echo '<td>'.$value['url'].'</td>';
	echo '<td><a href="app_list.php?act=delete&app_id='.$value['app_id'].'">Delete</a></td>';
	echo '</tr>';
}
}
?>
</table>