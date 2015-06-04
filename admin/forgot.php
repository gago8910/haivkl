<?php
if (!isset($_SESSION)) session_start();
	include "../functions.php";
	if(isset($_SESSION['admin_vmp']))
	header('Location: ./dashboard.php');
	$success = "";
if(isset($_GET['rid']) && $_GET['rid']!="")
{
	$dec_email = sdecrypt($_GET['rid']);
	if($dec_email!=get_admin_email())
	{
	$error = "Request Session Timeout";
	}
	else
	{
	$success = "Password Generated And Emailed To You";
	reset_pass($dec_email);
	}
}
function sencrypt($text)
{
	return strtr(base64_encode($text), '+/=', '-_,');
} 
function sdecrypt($text)
{
	return base64_decode(strtr($text, '-_,', '+/='));
}
if(isset($_POST['email']))
{
	$email = "";
	if($_POST["email"]!="")
	{
	$email = trim($_POST["email"]);
	if($email!=get_admin_email())
	$error ="Email Address Not Found";
	if(!checkEmail($email))
	$error ="Invalid Email Address";
	}
	else
	$error ="Email Address Can't Be Empty";
	if($email==get_admin_email() && $error=="")
	{
	send_email(get_admin_email(),$email,"Password Reset Request",get_admin() . " please click on the link below to reset your password\n" . rootpath() . "/admin/forgot.php?rid=" . sencrypt($email));
	$success = "Password Reset Request Sent To You";
	}
}

include "header.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>


	<!-- General meta information -->
	<title>Forget Password - <?php echo(get_title()) ?></title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<meta name="robots" content="index, follow" />
	<meta charset="utf-8" />
	<!-- // General meta information -->
	
	
	<!-- Load Javascript -->
	<script type="text/javascript" src="./style/js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="./style/js/jquery.query-2.1.7.js"></script>
	<script type="text/javascript" src="./style/js/rainbows.js"></script>
	<!-- // Load Javascipt -->

	<!-- Load stylesheets -->
	<link type="text/css" rel="stylesheet" href="./style/css/login.css" media="screen" />
	<!-- // Load stylesheets -->
<style type="text/css">
.resetbtn {
	-moz-box-shadow:inset 0px 1px 0px 0px #ffffff;
	-webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;
	box-shadow:inset 0px 1px 0px 0px #ffffff;
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #ededed), color-stop(1, #dfdfdf) );
	background:-moz-linear-gradient( center top, #ededed 5%, #dfdfdf 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#dfdfdf');
	background-color:#ededed;
	-webkit-border-top-left-radius:6px;
	-moz-border-radius-topleft:6px;
	border-top-left-radius:6px;
	-webkit-border-top-right-radius:6px;
	-moz-border-radius-topright:6px;
	border-top-right-radius:6px;
	-webkit-border-bottom-right-radius:6px;
	-moz-border-radius-bottomright:6px;
	border-bottom-right-radius:6px;
	-webkit-border-bottom-left-radius:6px;
	-moz-border-radius-bottomleft:6px;
	border-bottom-left-radius:6px;
	text-indent:0;
	border:1px solid #dcdcdc;
	display:inline-block;
	color:#777777;
	font-family:arial;
	font-size:15px;
	font-weight:bold;
	font-style:normal;
	height:40px;
	line-height:40px;
	width:100px;
	text-decoration:none;
	text-align:center;
	text-shadow:1px 1px 0px #ffffff;
	margin-top: -50px;
	margin-left: 100px;
}
.resetbtn:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #dfdfdf), color-stop(1, #ededed) );
	background:-moz-linear-gradient( center top, #dfdfdf 5%, #ededed 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#dfdfdf', endColorstr='#ededed');
	background-color:#dfdfdf;
}.resetbtn:active {
	position:relative;
	top:1px;
}</style>
<script>


	$(document).ready(function(){
 
	$("#submit1").hover(
	function() {
	$(this).animate({"opacity": "0"}, "slow");
	},
	function() {
	$(this).animate({"opacity": "1"}, "slow");
	});
 	});


</script>
	
</head>
<body>

	<div id="wrapper">
		<div id="wrappertop"></div>
		<?php if($error=="" && $success=="")
		echo('<div id="wrappermiddle" style="height:170px">');
		else
		echo('<div id="wrappermiddle" style="height:200px">');
		?>
			<h2>Forget Password</h2>
			<div id="username_input">
				<div id="username_inputleft"></div>
				<div id="username_inputmiddle">
				 <form action="forgot.php" method="post">
					<input type="text" name="email" id="url" value="Email" onclick="this.value = ''">
					<img id="url_user" src="./style/img/mailicon.png" alt="">
				</div>
				<div id="username_inputright"></div>
			</div>
			<div id="submit">
				<button class="resetbtn" type="submit" name="reset" value="Reset">Reset</button>
			</div>
			</form>
			<div id="links_left" style="margin-top:-50px">

			<a href="./login.php">Or Login Here</a>
			<?php
			if($error!="")
			echo('<p align="center" style="color: red">' . $error . '</p>');
			else if($success!="")
			echo('<p align="center" style="color: green">' . $success . '</p>');
			?>
			</div>
		</div>
		<div id="wrapperbottom"></div>
	</div>

</body>
</html>