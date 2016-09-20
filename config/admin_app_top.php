<?php
session_start();
include("db.inc.php");
include ("photo_functions.php");
include ("../include/definitions.php");
include ("../include/functions.php");

define (SITE_NAME,'Sonoma County Bocce Club');
if ($_SERVER['SERVER_NAME'] == 'localhost') {
	$path = "http://localhost/bocce2/";
} elseif ($_SERVER['SERVER_NAME'] == 'www.sonomacountybocceclub.org' or $_SERVER['SERVER_NAME'] == 'sonomacountybocceclub.org') {
	//echo $_SERVER['SERVER_NAME']; 
	$path = "http://www.sonomacountybocceclub.org/test/";
}

?>