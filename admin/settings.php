<?php
if (!isset($_SESSION)) session_start();
	if(!isset($_SESSION['admin_vmp']))
	header('Location: ./login.php');
	include "header.php";
	include "../functions.php";
	error_reporting(0);
function valid_favicon_extension($ext)
{
	$allowedExts = array("ico", "png");
	if (!in_array($ext, $allowedExts))
	{
	return false;
	}
	return true;
}
function update_settings($title,$description,$keywords,$captcha,$path,$logo_format,$logo_text,$submission,$logo,$favicon,$email)
{
	if(mb_check_encoding($title,"UTF-8"))
	$title=preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $title);
	if(mb_check_encoding($description,"UTF-8"))
	$description=preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $description);
	if(mb_check_encoding($keywords,"UTF-8"))
	$keywords=preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $keywords);
	if(mb_check_encoding($logo_text,"UTF-8"))
	$logo_text=preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $logo_text);
	$update_query = "UPDATE settings SET rootpath='" . $path . "',logo_format=" . $logo_format . ",logo_text='" . $logo_text . "',title='". mysql_real_escape_string($title)."',description='".mysql_real_escape_string($description)."',meta_tags='".mysql_real_escape_string($keywords) ."',captcha=" . $captcha . ",enable_submission=" . $submission . ",logo='" . $logo . "',favicon='" . $favicon . "',contact_email='" . $email . "'";
	mysql_query($update_query);
}
$error = "";
if(isset($_POST['name']))
{
	$title = $_POST["name"];
	$title=mysql_real_escape_string($title);
	
	$description = $_POST["description"];
	$description=mysql_real_escape_string($description);
	
	$keywords = $_POST["keywords"];
	$keywords=mysql_real_escape_string($keywords);
	
	$captcha = $_POST["captcha"];
	
	$path = rtrim($_POST["path"], "/");
	$path=mysql_real_escape_string($path);
	
	$logo_format = $_POST["logo_format"];
	$logo_text = $_POST["logotext"];
	$submission = $_POST["submission"];
	$email = $_POST["email"];
	if(!checkEmail($email))
	$error .="o. Invalid Email<br />";
	if(trim($_FILES["mylogo"]["name"])!="")
	{
		$ext = end(explode(".",strtolower(basename($_FILES["mylogo"]["name"]))));
		if(valid_file_extension($ext))
		{
		$logo = "logo." . $ext;
		unlink("../images/" . get_logo());
		move_uploaded_file($_FILES["mylogo"]["tmp_name"],"../images/" . $logo);
		}
		else
		{
		$logo = get_logo();
		$error .="o. Invalid Uploaded Logo. Discarded<br />";
		}
	}
	else
	{
		$logo = get_logo();
	}
	if(trim($_FILES["myfavicon"]["name"])!="")
	{
		$ext = end(explode(".",strtolower(basename($_FILES["myfavicon"]["name"]))));
		if(valid_favicon_extension($ext))
		{
		$favicon = "favicon." . $ext;
		unlink("../images/" . get_favicon());
		move_uploaded_file($_FILES["myfavicon"]["tmp_name"],"../images/" . $favicon);
		}
		else
		{
		$favicon = get_favicon();
		$error .="o. Invalid Favicon. Discarded<br />";
		}
	}
	else
	{
		$favicon = get_favicon();
	}
	if(!valid_url($path))
		$error .="o. Invalid Installation Path<br />";
	if(strlen($title)>70)
		$error .="o. Website Name Must Be Less than 70 Characters<br />";
	if(strlen($description)>160)
		$error .="o. Description Must Be Less than 160 Characters<br />";
	if(strlen($keywords)>160)
		$error .="o. Keywords Must Be Less than 160 Characters<br />";
	if($error=="")
		update_settings($title,$description,$keywords,$captcha,$path,$logo_format,$logo_text,$submission,$logo,$favicon,$email);
	}
?>
<title>Website Settings - <?php echo(get_title()) ?></title>
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
                    	<li><a href="./settings.php" class="active">Website</a></li>
						<li><a href="./thumbnails.php">Thumbnails</a></li>
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
                <h2>Website Settings</h2>
                
                <div id="main">
				<br />
				<form action="./settings.php" method="post" enctype="multipart/form-data">
					<fieldset>
					<p><label><b>Installation Path</b></label><input style="width:65%" name="path" value="<?php echo(rootpath()) ?>" type="text" class="text-long">* Do not include /</p>
					<p><label><b>Logo Format</b></label><?php if(logo_format()) { ?><input type="radio" name="logo_format" value="0">Text<input type="radio" name="logo_format" value="1" checked>Image <?php } else { ?><input type="radio" name="logo_format" value="0" checked>Text<input type="radio" name="logo_format" value="1">Image<?php } ?></p>
					<p><label><b>Logo Text</b></label><input style="width:50%" name="logotext" value="<?php echo(get_logo_text()) ?>" type="text" class="text-long"></p>
					<p><label><b>Logo</b></label><img src="<?php echo(rootpath() . "/images/" . get_logo()) ?>" /></p>
					<p><label><b>Upload Logo</b></label>
					<input type="file" id="mylogo" name="mylogo"/>* Max Height: 36 Pixels</p>
					<p><label><b>Favicon</b></label><img src="<?php echo(rootpath() . "/images/" . get_favicon()) ?>" /></p>
					<p><label><b>Upload Favicon</b></label>
					<input type="file" id="myfavicon" name="myfavicon" />* Recommended: 32x32 Pixels</p>
					<p><label><b>Website Name</b></label><input style="width:50%" name="name" value="<?php echo(get_title()) ?>" type="text" class="text-long"></p>
					<p><label><b>Keywords</b></label><textarea name="keywords" style="width:98%;height:50px" rows="1" cols="1"><?php echo(get_tags()) ?></textarea></p>
					<p><label><b>Description</b></label><textarea name="description" style="width:98%;height:50px" rows="1" cols="1"><?php echo(get_description()) ?></textarea></p>
					<p><label><b>Contact Email</b></label><input style="width:50%" name="email" value="<?php echo(get_contact_email()) ?>" type="text" class="text-long"></p>
					<p><label><b>Guest Submission</b></label><?php if(guest_submission_enabled()) { ?><input type="radio" name="submission" value="1" checked>Enabled<input type="radio" name="submission" value="0">Disabled <?php } else { ?><input type="radio" name="submission" value="1">Enabled<input type="radio" name="submission" value="0" checked>Disabled<?php } ?></p> 
					<p><label><b>Captcha</b></label><?php if(captcha_status()) { ?><input type="radio" name="captcha" value="1" checked>Enabled<input type="radio" name="captcha" value="0">Disabled <?php } else { ?><input type="radio" name="captcha" value="1">Enabled<input type="radio" name="captcha" value="0" checked>Disabled<?php } ?></p> 
					
					<input type="submit" class="myButton" value="Update Settings">
					</fieldset>
				</form>
					<?php
					if(isset($_POST['name']) && $error=="")
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


