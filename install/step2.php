<?php
error_reporting(E_ERROR);
if(!isset($_SESSION)) 
	session_start();
	if(!isset($_SESSION['vmp_install_step']))
	$_SESSION['vmp_install_step']=1;
	if($_SESSION['vmp_install_step']!=2)
	header('Location: ./index.php');
	include '../config/config.php';
	$title = "";
	$path = "";
	$email = "";
	$logo = "";
	$error = "";
function checkEmail($email)
{
  return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}
if(isset($_POST['title']))
{
	$title = trim($_POST['title']);
	$path = rtrim(trim($_POST['path']), "/");
	$logo = trim($_POST['logo']);
	$email = trim($_POST['email']);
		if($title=="" || strlen($title)>70)
		{
			$error .="o. Title Length Must Less then or Equal to 70 Characters<br />";
		}
		if(!checkEmail($email))
		{
			$error .="o. Invalid Contact Email Address<br />";
		}
		if($logo=="" || strlen($logo)>25)
		{
			$error .="o. Logo Text Length Must be Less Then or Equal to 25 Characters<br />";
		}
		if($error=="")
		{
			$update_table = "TRUNCATE TABLE settings";
			mysql_query($update_table);
			$title=mysql_real_escape_string($title);
			$update_query = "insert into settings(title,rootpath,logo_text,logo,favicon,contact_email) values('$title','$path','$logo','logo.png','favicon.ico','$email')";
			mysql_query($update_query);
			$_SESSION['vmp_install_step']=3;
		}
}
include "header.php";
?>
<title>Viral Media Portal - Website Details</title>
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
                    	<li><a class="active"><strong>Website Details</strong></a></li>
                    	<li><a><strong>Admin Details</strong></a></li>
                    </ul>
                    <!-- // .sideNav -->
                </div>    
                <!-- // #sidebar -->
                
                <!-- h2 stays for breadcrumbs -->
                <h2>Website Details</h2>
                
                 <div id="main">
				<br />
				<form action="./step2.php" method="post" enctype="multipart/form-data">
					<fieldset>
					<p><label><b>Website Title</b></label>
					<input style="width:45%" name="title" type="text" class="text-long"></p>
					
					<p><label><b>Installation Path</b></label>
					<input style="width:45%" name="path" value="<?php echo('http://' . $_SERVER['SERVER_NAME'] . str_replace("/install","",dirname($_SERVER['SCRIPT_NAME']))); ?>" type="text" class="text-long">* Do not include /</p>
					
					<p><label><b>Contact Email</b></label>
					<input style="width:45%" name="email" type="text" class="text-long"></p>
					
					<p><label><b>Logo Text</b></label>
					<input style="width:45%" name="logo" maxlength="25" type="text" class="text-long"></p>

					<input type="submit" class="myButton" value="Next">
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
						if(isset($_POST['title']) && $_POST['title']!="")
						{
						?>
						<div class="alert alert-success">Website Details Added Please Wait ...</div>
						<?php 
						echo ('<META HTTP-EQUIV="Refresh" Content="2; URL=final.php">');
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


