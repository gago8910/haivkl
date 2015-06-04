<?php
if (!isset($_SESSION)) session_start();
	if(!isset($_SESSION['admin_vmp']))
	header('Location: ./login.php');
	include "header.php";
	include "../functions.php";
if(isset($_POST['leaderboard1']))
{
	$leaderboard1 = $_POST["leaderboard1"];
	$leaderboard1=mysql_real_escape_string($leaderboard1);
	
	$leaderboard2 = $_POST["leaderboard2"];
	$leaderboard2=mysql_real_escape_string($leaderboard2);
	
	$medrec = $_POST["medrec"];
	$medrec=mysql_real_escape_string($medrec);
	
	update_ads($leaderboard1,$leaderboard2,$medrec);
}
?>
	<title>Ads - <?php echo(get_title()) ?></title>
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
						<li><a href="./ads.php" class="active">Ad Management</a></li>
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
                <h2>Ad Management</h2>
                
                <div id="main">
					<br />
					<form action="./ads.php" method="post">
						<fieldset>
						<p><a name="top_leaderboard"><label><b>Top : Large leaderboard (728 x 90)</b></label></a><textarea name="leaderboard1" style="width:98%" rows="1" cols="1"><?php echo(show_leaderboard1_ad()) ?></textarea></p>
						<p><a name="bottom_leaderboard"><label><b>Bottom : Large leaderboard (728 x 90)</b></label></a><textarea name="leaderboard2" style="width:98%" rows="1" cols="1"><?php echo(show_leaderboard2_ad()) ?></textarea></p>
						<p><label><a name="med_rec"><b>Sidebar : Medium Rectangle (300 x 250)</b></label></a><textarea name="medrec" style="width:98%" rows="1" cols="1"><?php echo(show_rectangle_ad()) ?></textarea></p>
						<input type="submit" class="myButton" value="Update Ads">
						</fieldset>
					</form>
						<?php
						if(isset($_POST['leaderboard1']))
						echo('<div class="alert alert-success">Ads Updated Successfully</div>');
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


