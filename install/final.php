<?php
error_reporting(E_ERROR);
if(!isset($_SESSION)) 
	session_start();
	if(!isset($_SESSION['vmp_install_step']))
	$_SESSION['vmp_install_step']=1;
	if($_SESSION['vmp_install_step']!=3)
	header('Location: ./index.php');
	include '../config/config.php';
	$username = "";
	$password = "";
	$email = "";
	$error = "";
function checkEmail($email)
{
  return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}
if(isset($_POST['username']))
{
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	$email = trim($_POST['email']);
	if($username=="" || strlen($username)>25)
	{
		$error .="o. Username Length Must Less then or Equal to 25 Characters<br />";
	}
	if(!checkEmail($email))
	{
		$error .="o. Invalid Contact Email Address<br />";
	}
	if($password=="" || strlen($password)>25)
	{
		$error .="o. Password Length Must be Less Then or Equal to 25 Characters<br />";
	}
	if($error=="")
	{
		$password = md5($password);
		$update_query = "update settings set username='$username',password='$password',email='$email'";
		mysql_query($update_query);
		$_SESSION['vmp_install_step']=4;
	}
}
include "header.php";
?>
<title>Viral Media Portal - Admin Details</title>
<script type='text/javascript'>
	$(window).load(function(){
	$(document).ready(function () {
		$('#selectall').click(function () {
			$('.selectedId').prop('checked', this.checked);
		});

		$('.selectedId').change(function () {
			var check = ($('.selectedId').filter(":checked").length == $('.selectedId').length);
			$('#selectall').prop("checked", check);
		});
	});
	}); 
</script>
<?php
include "header_under.php";
?>      
        <div id="containerHolder">
			<div id="container">
        		<div id="sidebar">
                	<ul class="sideNav">
                    	<li><a><strong>Database Information</strong></a></li>
                    	<li><a><strong>Website Details</strong></a></li>
                    	<li><a class="active"><strong>Admin Details</strong></a></li>
                    </ul>
                    <!-- // .sideNav -->
                </div>    
                <!-- // #sidebar -->
                
                <!-- h2 stays for breadcrumbs -->
                <h2>Admin Details</h2>
                
                 <div id="main">
				<br />
				<form action="./final.php" method="post" enctype="multipart/form-data">
					<fieldset>
					<p><label><b>Username</b></label>
					<input style="width:45%" name="username" type="text" class="text-long"></p>
					
					<p><label><b>Password</b></label>
					<input style="width:45%" name="password" type="password" class="text-long"></p>
					
					<p><label><b>Email</b></label>
					<input style="width:45%" name="email" type="text" class="text-long"></p>

					<input type="submit" class="myButton" value="Finish">
					</fieldset>
				</form>
							<?php
									if($error!="")
									{ 
									?>
									<div class="alert alert-error"><?php echo($error) ?></div>
									<?php } 
									else
									{
									if(isset($_POST['username']) && $_POST['username']!="")
									{
									?>
									<div class="alert alert-success">Installation Success Please Wait ...</div>
									<?php 
									echo ('<META HTTP-EQUIV="Refresh" Content="2; URL=finish.php">');
									} 
								}?>
                </div>
                <!-- // #main -->
                
                <div class="clear"></div>
            </div>
            <!-- // #container -->
        </div>	
        <!-- // #containerHolder -->
<?php
include "footer.php";
?>


