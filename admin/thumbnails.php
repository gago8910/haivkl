<?php
if (!isset($_SESSION)) session_start();
if(!isset($_SESSION['admin_vmp']))
	header('Location: ./login.php');
	include "header.php";
	include "../functions.php";
	$error = "";
if(isset($_POST['width']))
{
	$width = $_POST["width"];
	$width=mysql_real_escape_string($width);
	$height = $_POST["height"];
	$height=mysql_real_escape_string($height);
	$quality = $_POST["quality"];
	$watermark_enabled = $_POST["watermark"];
	$gif_watermark_url = $_POST["gif_watermark"];
	$gif_watermark_url=mysql_real_escape_string($gif_watermark_url);
	$vid_watermark_url = $_POST["vid_watermark"];
	$vid_watermark_url=mysql_real_escape_string($vid_watermark_url);

if(!is_numeric($width))
{
$error .="o. Width Must be Numeric Value<br />";
}
if(!is_numeric($height))
{
$error .="o. Height Must be Numeric Value<br />";
}
if($error=="")
{
update_thumbs_setting($width,$height,$quality,$watermark_enabled,$gif_watermark_url,$vid_watermark_url);
}
}
?>
<title>Thumbnails Settings - <?php echo(get_title()) ?></title>
<?php
include "header_under.php";
?>      
        <div id="containerHolder">
			<div id="container">
        		<div id="sidebar">
                	<ul class="sideNav">
                    	<li><a href="./settings.php">Website</a></li>
						<li><a href="./thumbnails.php" class="active">Thumbnails</a></li>
                    	<li><a href="./watermark.php">Watermark</a></li>
                    	<li><a href="./media.php">Media</a></li>
						<li><a href="./social.php">Social Media</a></li>
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
                <h2>Thumbnails Settings</h2>
                
                <div id="main">
				<br />
				<form action="./thumbnails.php" method="post">
					<fieldset>
					<label><b>Thumbnails Size (Width x Height)</b></label>
					<p><input style="width:15%" name="width" value="<?php echo(thumb_width())?>" type="text" class="text-long"/> <input style="width:15%" name="height" value="<?php echo(thumb_height())?>" type="text" class="text-long"/></p>
					<br />
					<p><label><b>Thumbnail Quality</b></label>
					<?php if(thumb_quality()==100)
					{ ?>
					<input type="radio" name="quality" value="1" checked>High <br />
					<input type="radio" name="quality" value="2">Medium <br />
					<input type="radio" name="quality" value="3">Low</p>
					<? }
					else if(thumb_quality()==85)
					{ ?>
					<input type="radio" name="quality" value="1">High <br />
					<input type="radio" name="quality" value="2" checked>Medium <br />
					<input type="radio" name="quality" value="3">Low</p>
					<?php } 
					else if(thumb_quality()==70)
					{
					?>
					<input type="radio" name="quality" value="1">High <br />
					<input type="radio" name="quality" value="2">Medium <br />
					<input type="radio" name="quality" value="3" checked>Low</p>
					<?php } ?>
					
					<br />
					<p><label><b>Thumbnails Watermark</b></label>
					<?php if(thumb_watermark_enabled())
					{ ?>
					<input type="radio" name="watermark" value="1" checked>Enabled  
					<input type="radio" name="watermark" value="0">Disabled</p>
					<?php } 
					else
					{
					?>
					<input type="radio" name="watermark" value="1">Enabled  
					<input type="radio" name="watermark" value="0" checked>Disabled</p>
				<?php } ?>
					<p><label><b>Thumbnail Image</b></label></p>
					<p style="line-height: 2;"><b>GIF Label :</b><input style="width:35%;float: right;
margin-right: 325px;" name="gif_watermark" value="<?php echo(gif_watermark_image()) ?>" type="text" class="text-long"/> </p>
					<p style="line-height: 2;"><b>Video Label :</b><input style="width:35%;float: right;
margin-right: 325px;" name="vid_watermark" value="<?php echo(vid_watermark_image()) ?>" type="text" class="text-long"/> </p>
					
					<input type="submit" class="myButton" value="Update Settings">
					</fieldset>
					</form>
					<?php
					if(isset($_POST['width']) && $error=="")
					echo('<div class="alert alert-success">Settings Updated Successfully</div>');
					else if(isset($_POST['width']) && $error!="")
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


