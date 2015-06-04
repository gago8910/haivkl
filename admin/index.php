<?php
if (!isset($_SESSION)) session_start();
if(isset($_SESSION['admin_vmp']))
	header('Location: ./dashboard.php');
else
	header('Location: ./login.php');
?>