<?php
if ($_SERVER['SERVER_NAME'] == 'localhost' or $_SERVER['SERVER_NAME'] == '71.198.233.82') {
	$database="bocce2";
	$host="localhost";
	$userid="root";
	$password="cleo1409";
} elseif ($_SERVER['SERVER_NAME'] == 'www.sonomacountybocce.org' or $_SERVER['SERVER_NAME'] == 'sonomacountybocce.org') {
	$database="bocce2";
	$host="bocce2.db.12029260.hostedresource.com";
	$userid="bocce2";
	$password="Pazzi#123";
}

//Connecting to MYSQL
MySQL_connect($host,$userid,$password)or die("Could not connect");
//Select the database we want to use
MySQL_select_db($database) or die("Could not select database");
?>
