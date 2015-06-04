<?php
	if(!isset($_SESSION)) session_start();
	if(!isset($_SESSION['vote_array']))
	$_SESSION['vote_array'] = array();
	include "functions.php";
	if(isset($_POST['permalink'])) {
	count_votes(trim($_POST['permalink']));
	if (!in_array($_POST['permalink'], $_SESSION['vote_array']))
     array_push($_SESSION['vote_array'],$_POST['permalink']);
	}
?>