<?php
if (!isset($_SESSION)) session_start();
	if(!isset($_SESSION['admin_vmp']))
	header('Location: ./login.php');
	include "header.php";
	include "../functions.php";
	error_reporting(0);
	$error = "";

	
	function update_comment_setting($admin_id,$app_id)
	{
	$admin_id=mysql_real_escape_string($admin_id);
	$app_id=mysql_real_escape_string($app_id);
	$update_query = "UPDATE comment_setting SET admin_id='".$admin_id."',
												app_id='".$app_id."'";
		mysql_query($update_query) or die(mysql_error());
	}
	if(isset($_POST['admin_id']))
	{
		$admin_id = $_POST["admin_id"];
		$app_id = $_POST["app_id"];		
		update_comment_setting($admin_id,$app_id);
		
	}

?>
<title>Comments Settings - <?php echo(get_title()) ?></title>
	
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
						<li><a href="./admin.php">Admin Settings</a></li>
						<li><a href="./analytics.php">Analytics (Stats Tracking)</a></li>
						<li><a href="./rss.php" >RSS Settings</a></li>
						<li><a href="./sitemap.php" >Sitemap Settings</a></li>
						<li><a href="./comments.php" class="active">Comments Setting</a></li>
						
                    </ul>
                    <!-- // .sideNav -->
                </div>    
                <!-- // #sidebar -->
                
                <!-- h2 stays for breadcrumbs -->
                <h2>Comments Settings</h2>
                
                <div id="main">
				<br />
				<form action="./comments.php" method="post" enctype="multipart/form-data">
					<fieldset>					 
						<p>
							<label><b>Facebook Admin Ids</b></label>
								<input style="width:50%" name="admin_id" value="<?php echo(fb_admin_id()) ?>" type="text" class="text-long">
								* Separated By Commas /
						</p>
						<p>
							<label><b>Facebook App Id</b></label>
								<input style="width:50%" name="app_id" value="<?php echo(fb_app_id()) ?>" type="text" class="text-long">
							
						</p>
					
					
					
					
					<input type="submit" class="myButton" value="Submit">
					</fieldset>
				</form>
					<?php
					if(isset($_POST['admin_id']) && $error=="")
					echo('<div class="alert alert-success">Settings Updated Successfully</div>');
					else if(isset($_POST['name']) && $error!="")
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


