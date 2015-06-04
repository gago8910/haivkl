<?php 
if (!isset($_SESSION)) session_start();
if(isset($_SESSION['admin_vmp']))
{
unset($_SESSION['admin_vmp']);
}
header("location:./index.php");
?>