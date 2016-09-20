<?php
include "admin_app_top.php";
include "checkUser.php";
include "defsTeam.php"; 
$msg = $_GET['msg'];
$lastYear = date('Y') - 1;
$thisYear =  date('Y');
$photo_query=sprintf("SELECT * FROM %s WHERE `teamYear` = %s ORDER BY `teamName`",ITEM_TABLE,$lastYear);	
//echo $photo_query;
$photo_set = mysql_query($photo_query) or die(mysql_error());
$row_photo_set = mysql_fetch_assoc($photo_set);
do {
	$query = sprintf("INSERT INTO %s (teamYear,teamNight,teamName,teamCapo) VALUES('%s','%s','%s','%s')",ITEM_TABLE,mysql_real_escape_string($thisYear),mysql_real_escape_string($row_photo_set['teamNight']),mysql_real_escape_string($row_photo_set['teamName']),mysql_real_escape_string($row_photo_set['teamCapo']));	
	//echo $query;
	//exit;
	mysql_query($query);
	echo mysql_error();
} while ($row_photo_set = mysql_fetch_assoc($photo_set));
echo "done";
?>