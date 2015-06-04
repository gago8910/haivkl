<?php
if (!isset($_SESSION)) session_start();
if(!isset($_SESSION['admin_vmp']))
header('Location: ./login.php');
include "header.php";
include "../functions.php";
$error = "";
if(isset($_POST['facebook']))
{
$facebook = $_POST["facebook"];
$facebook=mysql_real_escape_string($facebook);

$google = $_POST["google"];
$google=mysql_real_escape_string($google);

$twitter = $_POST["twitter"];
$twitter=mysql_real_escape_string($twitter);

$social_sharing = $_POST["social_sharing"];
if($error=="")
{
update_social($facebook,$twitter,$google,$social_sharing);
}
}
?>
<title>Social Profiles Setting - <?php echo(get_title()) ?></title>
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
						<li><a href="./social.php" class="active">Social Media</a></li>
						<li><a href="./ads.php">Ad Management</a></li>
						<li><a href="./admin.php">Admin Settings</a></li>
						<li><a href="./analytics.php">Analytics (Stats Tracking)</a></li>
						<li><a href="./rss.php">RSS Settings</a></li>
						<li><a href="./sitemap.php">Sitemap Settings</a></li>
						<li><a href="./comments.php">Comments Setting</a></li>
                    </ul>
                    <!-- // .sideNav -->
                </div>    
                <!-- // #sidebar -->
                
                <!-- h2 stays for breadcrumbs -->
                <h2>Social Profiles Settings</h2>
                
                <div id="main">
				<br />
				
					<form action="./social.php" method="post">
					<fieldset>
					<br /><br />
					
					<p><label><b>Social Profiles</b></label>
					<p style="line-height: 2;"><b>Facebook Page ID</b><input style="width:25%;float: right;
margin-right: 370px;" name="facebook" value="<?php echo(get_facebook()) ?>" type="text" class="text-long"/> </p>

					<p style="line-height: 2;"><b>Google + Page ID</b><input style="width:25%;float: right;
margin-right: 370px;" name="google" value="<?php echo(get_google()) ?>" type="text" class="text-long"/> </p>

					<p style="line-height: 2;"><b>Twitter Username</b><input style="width:25%;float: right;
margin-right: 370px;" name="twitter" value="<?php echo(get_twitter()) ?>" type="text" class="text-long"/> </p>

					
					<p><br /><label><b>Social Sharing</b></label>
					<?php if(social_sharing_enabled()) { ?>
					<input type="radio" name="social_sharing" value="1" checked>On
					<input type="radio" name="social_sharing" value="0">Off</p>
					<?php } else { ?>
					<input type="radio" name="social_sharing" value="1">On
					<input type="radio" name="social_sharing" value="0" checked>Off</p>
					<?php } ?>
					<input type="submit" class="myButton" value="Update">
					</fieldset>
					</form>
					<?php
					if(isset($_POST['facebook']) && $error=="")
					echo('<div class="alert alert-success">Social Settings Updated Successfully</div>');
					else if(isset($_POST['facebook']) && $error!="")
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


