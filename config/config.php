<?php
$root = 'http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['SCRIPT_NAME']);

if(substr($root, -1)=="/")
	$root = 'http://' . $_SERVER['SERVER_NAME'];
	
$database = "haivkl";
$server = "localhost";
$db_user = "root";
$db_pass = "";

error_reporting(E_ERROR);

$link = mysql_connect($server, $db_user, $db_pass);
$check = mysql_select_db($database,$link);

if(!$check)
	header("location: " . $root . "/install/");
?>