<?php
if (!isset($_SESSION)) session_start();
	if(!isset($_SESSION['admin_vmp']))
	header('Location: ./login.php');
	include "header.php";
	include "../functions.php";
	error_reporting(0);
	
	if(isset($_POST['rss_enable']))
	{
		$enable = $_POST["rss_enable"];
		$limit = $_POST["rss_limit"];
		$desc = $_POST["rss_desc"];
		$cat = $_POST["rss_cat"];
		$tag = $_POST["rss_tag"];

		if($error=="")
		{
			update_rss_settings($enable,$limit,$desc,$cat,$tag);  
		}
	}

	
	function update_rss_settings($enable,$limit,$desc,$cat,$tag)
	{
		$update_query = "UPDATE rss_settings 
					SET enable='". mysql_real_escape_string($enable)."',
						limit_rss='".mysql_real_escape_string($limit)."',
							desc_length='" . mysql_real_escape_string($desc) ."',
								cat_rss_enable='".mysql_real_escape_string($cat)."',
									rss_tags='".mysql_real_escape_string($tag)."'";
		mysql_query($update_query) or die(mysql_error());
	}

	

?>
<title>RSS Settings - <?php echo(get_title()) ?></title>
	
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
						<li><a href="./rss.php" class="active">RSS Settings</a></li>
						<li><a href="./sitemap.php">Sitemap Settings</a></li>
						<li><a href="./comments.php">Comments Setting</a></li>
                    </ul>
                    <!-- // .sideNav -->
                </div>    
                <!-- // #sidebar -->
                
                <!-- h2 stays for breadcrumbs -->
                <h2>RSS Settings</h2>
                
                <div id="main">
				<br />
				<form action="./rss.php" method="post">
					<fieldset>
					<p><label><b>RSS Feeds</b></label>
						<?php if(rss_enable()) { ?>
							<input type="radio" name="rss_enable" value="1" checked>Enabled
							<input type="radio" name="rss_enable" value="0">Disabled <?php } 
							else { ?><input type="radio" name="rss_enable" value="1">Enabled
							<input type="radio" name="rss_enable" value="0" checked>Disabled<?php } 
						?>
					</p> 
						
					<p><label><b>RSS Limit</b></label>
						<select name="rss_limit">
							  <option value="10" <?php if(rss_limit()=='10'){echo"selected";}?> >10</option>
							  <option value="15" <?php if(rss_limit()=='15'){echo"selected";}?>>15</option>
							  <option value="25" <?php if(rss_limit()=='25'){echo"selected";}?>>25</option> 
							  <option value="50" <?php if(rss_limit()=='50'){echo"selected";}?>>50</option>
						</select>
					</p>
					
					<p><label><b>Description Length</b></label><input style="width:40%" name="rss_desc" value="<?php echo(rss_description()) ?>" type="text" class="text-long"></p>
					
					<p><label><b>Category RSS</b></label>
						<?php if(rss_cat_enable()) { ?>
							<input type="radio" name="rss_cat" value="1" checked>Enabled
							<input type="radio" name="rss_cat" value="0">Disabled <?php } 
							else { ?><input type="radio" name="rss_cat" value="1">Enabled
							<input type="radio" name="rss_cat" value="0" checked>Disabled<?php } 
						?>
					</p> 
					
					<p><label><b>Tags RSS</b></label>
						<?php if(rss_tag_enable()) { ?>
							<input type="radio" name="rss_tag" value="1" checked>Enabled
							<input type="radio" name="rss_tag" value="0">Disabled <?php } 
							else { ?><input type="radio" name="rss_tag" value="1">Enabled
							<input type="radio" name="rss_tag" value="0" checked>Disabled<?php } 
						?>
					</p> 
					
					<input type="submit" class="myButton" value="Submit">
					</fieldset>
				</form>
					<?php
					if(isset($_POST['rss_enable']) && $error=="")
					echo('<div class="alert alert-success">Settings Updated Successfully</div>');
					else if(isset($_POST['rss_enable']) && $error!="")
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


