<?php
include "admin_app_top.php";
include "checkUser.php";
include "defsTeam.php"; 
$msg = $_GET['msg'];
$lastYear = date('Y') - 1;
$thisYear =  date('Y');
$photo_query=sprintf("SELECT * FROM games WHERE `gameYear` = 2015 ");	
//echo $photo_query;
$photo_set = mysql_query($photo_query) or die(mysql_error());
$row_photo_set = mysql_fetch_assoc($photo_set);
do {
	$query = sprintf("UPDATE games SET `gameDate` = '%s' WHERE `gameID` = '%s'",mysql_real_escape_string($row_photo_set['gameDate'] + 31536000),mysql_real_escape_string($row_photo_set['gameID']));	
	//echo $query;
	//exit;
	mysql_query($query);
	echo mysql_error();
} while ($row_photo_set = mysql_fetch_assoc($photo_set));
echo "done";
?>