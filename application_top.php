<?php 
session_start();
include "config/db.inc.php";
include "include/functions.php";
include "include/definitions.php";
define ('SITE_NAME','Sonoma County Bocce Club');
if ($_SERVER['SERVER_NAME'] == 'localhost') {
	$path = "http://localhost/bocce2/";
} elseif ($_SERVER['SERVER_NAME'] == '71.198.233.82') {
	$path = "http://71.198.233.82/bocce2/";
} elseif ($_SERVER['SERVER_NAME'] == 'www.sonomacountybocce.org' or $_SERVER['SERVER_NAME'] == 'sonomacountybocce.org') {
	//echo $_SERVER['SERVER_NAME']; 
	$path = "http://www.sonomacountybocce.org/";
}
?>