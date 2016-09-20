<?php
//include("config/db.inc.php");
$teamArray =  array();
for ($y=date('Y'); $y >= 2014; $y--) { 
	$teamArray_query=sprintf("SELECT * FROM teams WHERE `teamYear` = '%s' ORDER by `teamName`",$y);
	$teamArray_set = mysql_query($teamArray_query) or die(mysql_error());
	$row_teamArray_set = mysql_fetch_assoc($teamArray_set);
	
	do {
		$teamArray[$y][$row_teamArray_set['teamID']] = array('teamName' => $row_teamArray_set['teamName'], 'teamCapo' => $row_teamArray_set['teamCapo']);
	} while ($row_teamArray_set = mysql_fetch_assoc($teamArray_set));
}// for team array


$nightArray = array('Monday','Tuesday','Wednesday','Thursday','Friday');
$courtArray = array('1','2','3','4','7','8');
$boardArray = array(
					
					array('name' => 'Ron Misasi', 'title' => 'President', 'email' => 'jrmisasi@sbcglobal.net'),
					array('name' => 'George Golfieri', 'title' => 'Vice President', 'email' => 'george.zena65@gmail.com'),
					array('name' => 'Gerri Goin', 'title' => 'Treasurer', 'email' => 'gmgoin26@yahoo.com'),
					array('name' => 'Dani Foster', 'title' => 'Secretary', 'email' => 'danifoster72@yahoo.com'),
					
					
					array('name' => 'Max Vera', 'title' => 'Board Member', 'email' => 'negromax1000000@gmail.com'),
					array('name' => 'Bob Schneider', 'title' => 'Board Member', 'email' => 'robertcaz@comcast.net'),
					array('name' => 'Jim Kany', 'title' => 'Board Member', 'email' => 'jamespkany@yahoo.com'),
					array('name' => 'Mike Nelson', 'title' => 'Webmaster', 'email' => 'mike.nelson@sonomacountybocce.org')
					);

$album_query=sprintf("SELECT * FROM albums WHERE `publish` = 1 ORDER BY `albumTitle`");
//echo $photo_query;
$album_set = mysql_query($album_query) or die(mysql_error());
$row_album_set = mysql_fetch_assoc($album_set);
$albumArray = array();
if (mysql_num_rows($album_set) > 0) {
	do { 
		$thumb_query=sprintf("SELECT * FROM photos WHERE `albumID` = '%s' ORDER BY `listOrder` LIMIT 1",$row_album_set['albumID']);
		//echo $photo_query;
		$thumb_set = mysql_query($thumb_query) or die(mysql_error());
		$row_thumb_set = mysql_fetch_assoc($thumb_set);
		$albumArray[$row_album_set['albumID']] = array('title' => $row_album_set['albumTitle'], 'thumb' => $row_thumb_set['photoName'], 'description' => $row_album_set['albumDescription']);
	} while ($row_album_set = mysql_fetch_assoc($album_set));
}
?>