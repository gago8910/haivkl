<?php
if (!isset($_SESSION)) session_start();
if(!isset($_SESSION['admin_vmp']))
	header('Location: ./login.php');
	include "header.php";
	include "../functions.php";
	$error = "";
function update_user($username,$password,$email)
{
	if($password!="")
	$update_query = "UPDATE settings SET username='".$username."',password='".$password."',email='".$email . "'";
	else
	$update_query = "UPDATE settings SET username='".$username."',email='".$email."'";
	$qry = mysql_query($update_query);
	return true;
}
if(isset($_POST['username']))
{
	$username = $_POST["username"];
	$username=mysql_real_escape_string($username);
	$oldpassword = md5($_POST["password"]);
	$oldpassword=mysql_real_escape_string($oldpassword);
	$match = "select password from settings"; 
	$qry = mysql_query($match);
	$num_rows = mysql_num_rows($qry); 
	$row = mysql_fetch_array($qry);
	if($row['password']!=$oldpassword || $_POST['password']=="")
		$error .="o. Invalid Password User Details Can't Be Changed<br />";
	if(isset($_POST['newpassword']) && $_POST['newpassword']!="")
	{
		$password = md5($_POST["newpassword"]);
		$password=mysql_real_escape_string($password);
	}
	else
	{
		$password = "";
	}
		$email = $_POST["email"];
		$email=mysql_real_escape_string($email);
	if(!is_alpha($username))
	{
		$error .="o. Username Can Only Contain Letters a-Z and Numbers 0-9<br />";
	}
	if(strlen($username)<5 || strlen($username)>15)
	{
		$error .="o. Username Length Must Be Between 5 to 15 Characters<br />";
	}
	if(strlen($_POST["password"])<5 || strlen($_POST["password"])>18)
	{
		if($_POST["password"]!="")
			$error .="o. Password Length Must Be Between 5 to 18 Characters<br />";
	}
	if(!checkEmail($email))
	{
		$error .="o. Invalid Email Address<br />";
	}
	if($error=="")
	update_user($username,$password,$email);
}
?>
<title>Admin Settings - <?php echo(get_title()) ?></title>
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
                    	<li><a href="./settings.php">Website</a></li>
						<li><a href="./thumbnails.php">Thumbnails</a></li>
                    	<li><a href="./watermark.php">Watermark</a></li>
                    	<li><a href="./media.php">Media</a></li>
						<li><a href="./social.php">Social Media</a></li>
						<li><a href="./ads.php">Ad Management</a></li>
						<li><a href="./admin.php" class="active">Admin Settings</a></li>
						<li><a href="./analytics.php">Analytics (Stats Tracking)</a></li>
						<li><a href="./rss.php">RSS Settings</a></li>
						<li><a href="./sitemap.php">Sitemap Settings</a></li>
						<li><a href="./comments.php">Comments Setting</a></li>
                    </ul>
					
                    <!-- // .sideNav -->
                </div>    
                <!-- // #sidebar -->
                
                <!-- h2 stays for breadcrumbs -->
                <h2>Admin Settings</h2>
                
                <div id="main">
					<br />
					<form action="./admin.php" method="post">
						<fieldset>
						<p><label><b>Username:</b></label><input style="width:50%" name="username" value="<?php echo(get_admin()) ?>" type="text" class="text-long"></p>
						<p><label><b>Email:</b></label><input style="width:50%" name="email" value="<?php echo(get_admin_email()) ?>" type="text" class="text-long"></p>
						<p></p>
						<p><label><b>Password:</b></label><input style="width:50%" name="password" type="password" type="text" class="text-long"></p>
						<p><label><b>New Password:</b> (Leave blank if you don't want to change)</label><input style="width:50%" name="newpassword" type="password" type="text" class="text-long"></p>
						<input type="submit" class="myButton" value="Update Admin">
						</fieldset>
					</form>
					<?php
						if(isset($_POST['username']) && $error=="")
						echo('<div class="alert alert-success">Admin Details Updated Successfully</div>');
						else if(isset($_POST['username']) && $error!="")
						echo('<div class="alert alert-error">' . $error . '</div>');
					?>
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


