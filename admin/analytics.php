<?php
if (!isset($_SESSION)) session_start();
if(!isset($_SESSION['admin_vmp']))
	header('Location: ./login.php');
	include "header.php";
	include "../functions.php";
	$error = "";
	
function update_analytics($analytics)
{
	$analytics = mysql_real_escape_string($analytics);
	$update_query = "UPDATE analytics SET tracking_code='".$analytics."'";
	$qry = mysql_query($update_query);
	return true;
}

if(isset($_POST['analytics']))
{
	$analytics = $_POST["analytics"];
	$analytics=mysql_real_escape_string($analytics);
	update_analytics($analytics);
}

?>
<title>Analytics (Stats Tracking) - <?php echo(get_title()) ?></title>
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
						<li><a href="./admin.php">Admin Settings</a></li>
						<li><a href="./analytics.php" class="active">Analytics (Stats Tracking)</a></li>
						<li><a href="./rss.php">RSS Settings</a></li>
						<li><a href="./sitemap.php">Sitemap Settings</a></li>
						<li><a href="./comments.php">Comments Setting</a></li>
                    </ul>
                    <!-- // .sideNav -->
                </div>    
                <!-- // #sidebar -->
                
                <!-- h2 stays for breadcrumbs -->
                <h2>Analytics (Stats Tracking)</h2>
                
                <div id="main">
					<br />
					<form action="./analytics.php" method="post">
						<fieldset>
						<p><a name="analytics_code"><label><b>Analytics Code :</b></label></a>
						<textarea name="analytics" style="width:98%" rows="1" cols="1"><?php echo(get_analytics_code()) ?></textarea></p>
						
						<input type="submit" class="myButton" value="Update Analytics Code">
						</fieldset>
					</form>
						<?php
						if(isset($_POST['analytics']))
						echo('<div class="alert alert-success">Analytics Code Updated Successfully</div>');
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


