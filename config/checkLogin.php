<?php
session_start();
include("db.inc.php");

$photo_query=sprintf("SELECT * FROM admin_x1 WHERE `username` = '%s' AND `password` = '%s'",mysql_real_escape_string($_POST['email']),mysql_real_escape_string(md5($_POST['password'])));
//echo $photo_query;
$photo_set = mysql_query($photo_query) or die(mysql_error());
$row_photo_set = mysql_fetch_assoc($photo_set);

if (mysql_num_rows($photo_set) > 0) {
	$_SESSION['chklogin'] = 'admin';
	$_SESSION['super'] = $row_photo_set['super'];
	$_SESSION['content'] = $row_photo_set['content'];
	$_SESSION['scores'] = $row_photo_set['scores'];
	$_SESSION['isLoggedIn'] = true;
	header("Location: admin_page.php");
	exit;
}
	
header("Location: index.php?msg=2");
exit;

?>

