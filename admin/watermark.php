<?php
if (!isset($_SESSION)) session_start();
if(!isset($_SESSION['admin_vmp']))
header('Location: ./login.php');
include "header.php";
include "../functions.php";
$error = "";
function update_watermark_setting($enabled,$opacity,$text,$size,$color,$position)
{
$update_query = "UPDATE watermark_setting SET enabled=". $enabled . ",opacity=" . $opacity . ",text='" . $text . "',font_size='" . $size . "',color='" . $color . "',position=" . $position;
mysql_query($update_query);
}
function update_user($username,$password,$email)
{
if($password!="")
$update_query = "UPDATE settings SET username='".$username."',password='".$password."',email='".$email . "'";
else
$update_query = "UPDATE settings SET username='".$username."',email='".$email."'";
$qry = mysql_query($update_query);
return true;
}
if(isset($_POST['enabled']))
{
	$enabled = $_POST["enabled"];
	$opacity = $_POST["opacity"];
	$text = strip_tags($_POST["text"]);
	$text=mysql_real_escape_string($text);
	$size = $_POST["size"];
	$size=mysql_real_escape_string($size);
	$color = $_POST["textcolor"];
	$color=mysql_real_escape_string($color);
	$position = $_POST["position"];
	if(!is_numeric($size))
	$error .="o. Font Size Must be Numeric Value<br />";
	if(!valid_color_code($color))
	$error .="o. Invalid Color Code<br />";

if($error=="")
update_watermark_setting($enabled,$opacity,$text,$size,$color,$position);
}
?>
<title>Watermark Settings - <?php echo(get_title()) ?></title>
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
<link rel="stylesheet" media="screen" type="text/css" href="./style/colorpicker/css/colorpicker.css" />
<script type="text/javascript" src="./style/colorpicker/js/colorpicker.js"></script>
<script type='text/javascript'>
$(window).load(function(){
$(document).ready(function () {
$('#color,#background').ColorPicker({
	onSubmit: function(hsb, hex, rgb, el) {
		$(el).val(hex);
		$(el).ColorPickerHide();
	},
	onBeforeShow: function () {
		$(this).ColorPickerSetColor(this.value);
	}
})
.bind('keyup', function(){
	$(this).ColorPickerSetColor(this.value);
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
                    	<li><a href="./watermark.php" class="active">Watermark</a></li>
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
                <h2>Watermark Settings</h2>
                
                <div id="main">
				<br />
				<form action="./watermark.php" method="post">
					<fieldset>
					<p><label><b>Images Watermark</b></label>
					<?php if(image_watermark_enabled())
					{ ?>
					<input type="radio" name="enabled" value="1" checked>Enabled  
					<input type="radio" name="enabled" value="0">Disabled</p>
					<?php } else
					{
					 ?>
					 <input type="radio" name="enabled" value="1">Enabled  
					<input type="radio" name="enabled" value="0" checked>Disabled</p>
					<?php } ?>
					<br />
					
					<p><label><b>Watermark Opacity</b></label>
					<?php if(image_watermark_opacity()==100)
					{ ?>
					<input type="radio" name="opacity" value="100" checked>100%    
					<input type="radio" name="opacity" value="95">95%
					<input type="radio" name="opacity" value="85">85%
					<input type="radio" name="opacity" value="75">75%
					<input type="radio" name="opacity" value="65">65%</p>
					<?php } else if(image_watermark_opacity()==95)
					{ ?>
						<input type="radio" name="opacity" value="100">100%    
					<input type="radio" name="opacity" value="95" checked>95%
					<input type="radio" name="opacity" value="85">85%
					<input type="radio" name="opacity" value="75">75%
					<input type="radio" name="opacity" value="65">65%</p>
					<?php } else  if(image_watermark_opacity()==85)
					{ ?>
						<input type="radio" name="opacity" value="100">100%    
					<input type="radio" name="opacity" value="95">95%
					<input type="radio" name="opacity" value="85" checked>85%
					<input type="radio" name="opacity" value="75">75%
					<input type="radio" name="opacity" value="65">65%</p>
					<?php } else if(image_watermark_opacity()==75)
					{ ?>
						<input type="radio" name="opacity" value="100">100%    
					<input type="radio" name="opacity" value="95">95%
					<input type="radio" name="opacity" value="85">85%
					<input type="radio" name="opacity" value="75" checked>75%
					<input type="radio" name="opacity" value="65">65%</p>
					<?php } else if (image_watermark_opacity()==65)
					{ ?>
					<input type="radio" name="opacity" value="100">100%    
					<input type="radio" name="opacity" value="95">95%
					<input type="radio" name="opacity" value="85">85%
					<input type="radio" name="opacity" value="75">75%
					<input type="radio" name="opacity" value="65" checked>65%</p>
					<?php } else {?>
					<input type="radio" name="opacity" value="100" checked>100%    
					<input type="radio" name="opacity" value="95">95%
					<input type="radio" name="opacity" value="85">85%
					<input type="radio" name="opacity" value="75">75%
					<input type="radio" name="opacity" value="65">65%</p>
					<?php } ?>
					<br />
					
					<p><label><b>Watermark Position</b></label>
					<?php if(image_watermark_position()==1)
					{ ?>
					<input type="radio" name="position" value="1" checked>Top Left    
					<input type="radio" name="position" value="2">Top Center
					<input type="radio" name="position" value="3">Top Right
					<input type="radio" name="position" value="4">Bottom Left
					<input type="radio" name="position" value="5">Bottom Center
					<input type="radio" name="position" value="6">Bottom Right
					</p>
					<?php } else if(image_watermark_position()==2)
					{ ?>
					<input type="radio" name="position" value="1">Top Left    
					<input type="radio" name="position" value="2" checked>Top Center
					<input type="radio" name="position" value="3">Top Right
					<input type="radio" name="position" value="4">Bottom Left
					<input type="radio" name="position" value="5">Bottom Center
					<input type="radio" name="position" value="6">Bottom Right
					</p>
					<?php } else  if(image_watermark_position()==3)
					{ ?>
					<input type="radio" name="position" value="1">Top Left    
					<input type="radio" name="position" value="2">Top Center
					<input type="radio" name="position" value="3" checked>Top Right
					<input type="radio" name="position" value="4">Bottom Left
					<input type="radio" name="position" value="5">Bottom Center
					<input type="radio" name="position" value="6">Bottom Right
					</p>
					<?php } else if(image_watermark_position()==4)
					{ ?>
					<input type="radio" name="position" value="1">Top Left    
					<input type="radio" name="position" value="2">Top Center
					<input type="radio" name="position" value="3">Top Right
					<input type="radio" name="position" value="4" checked>Bottom Left
					<input type="radio" name="position" value="5">Bottom Center
					<input type="radio" name="position" value="6">Bottom Right
					</p>
					<?php } else if (image_watermark_position()==5)
					{ ?>
					<input type="radio" name="position" value="1">Top Left    
					<input type="radio" name="position" value="2">Top Center
					<input type="radio" name="position" value="3">Top Right
					<input type="radio" name="position" value="4">Bottom Left
					<input type="radio" name="position" value="5" checked>Bottom Center
					<input type="radio" name="position" value="6">Bottom Right
					</p>
					<?php } else if (image_watermark_position()==6) { ?>
					<input type="radio" name="position" value="1">Top Left    
					<input type="radio" name="position" value="2">Top Center
					<input type="radio" name="position" value="3">Top Right
					<input type="radio" name="position" value="4">Bottom Left
					<input type="radio" name="position" value="5">Bottom Center
					<input type="radio" name="position" value="6" checked>Bottom Right
					</p>
					<?php } ?>
					<br />
					
					<p><label><b>Watermark Text</b></label>
					*watermark goes here<input style="width:35%" name="text" value="<?php echo(image_watermark_text()) ?>" type="text" class="text-long"/> </p>
					<p><label><b>Font Size</b></label>
					*Enter Font Size (eg: 25)<input style="width:35%" name="size" value="<?php echo(image_watermark_fontsize()) ?>" type="text" class="text-long"/> </p>
					
					<p><label><b>Text Color</b></label>
					*Enter Text Color (eg: #f5f04) <input style="width:35%" name="textcolor" id="color" value="<?php echo(image_watermark_color()) ?>" type="text" class="text-long"/> </p>
					
					
					<input type="submit" class="myButton" value="Update Settings">
					</fieldset>
					</form>
					<?php
					if(isset($_POST['enabled']) && $error=="")
					echo('<div class="alert alert-success">Settings Updated Successfully</div>');
					else if(isset($_POST['enabled']) && $error!="")
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


