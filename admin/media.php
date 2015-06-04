<?php
if (!isset($_SESSION)) session_start();
	if(!isset($_SESSION['admin_vmp']))
	header('Location: ./login.php');
	include "header.php";
	include "../functions.php";
	$error = "";
if(isset($_POST['title_length']))
{
	$title_length = $_POST["title_length"];
	$title_length=mysql_real_escape_string($title_length);
	
	$posts_per_page = $_POST["posts_per_page"];
	$posts_per_page=mysql_real_escape_string($posts_per_page);
	
	$related_posts = $_POST["related_posts"];
	$related_posts=mysql_real_escape_string($related_posts);
	
	$allow_voting = $_POST["allow_voting"];
	$auto_approve = $_POST["auto_approve"];
	$allow_pictures=$_POST["allow_pictures"];
	$allow_videos=$_POST["allow_videos"];	
	$allow_gifs=$_POST["allow_gifs"];
	$show_nav=$_POST["show_nav"];
	
	if(!is_numeric($title_length))
	$error .="o. Title Length Must be Numeric Value<br />";
	if(!is_numeric($posts_per_page))
	$error .="o. Posts Per Page Must be Numeric Value<br />";
	if(!is_numeric($related_posts))
	$error .="o. Related Posts Must be Numeric Value<br />";
	if($error=="")
	{
		update_media_settings($title_length,$posts_per_page,$related_posts,$allow_voting,$auto_approve,$allow_pictures,$allow_videos,$allow_gifs,$show_nav);
	}
}
?>
<title>Media Settings - <?php echo(get_title()) ?></title>
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
                    	<li><a href="./media.php" class="active">Media</a></li>
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
                <h2>Media Settings</h2>
                
                <div id="main">
				<br />
				<form action="./media.php" method="post">
					<fieldset>
					<p><label><b>Post title length</b></label>
					*set legth of post titles (45 recommended)<input style="width:15%" name="title_length" value="<?php echo(post_title_length()) ?>" type="text" class="text-long"/> </p>

					<p><label><b>Posts per page</b></label>
					*set number of post per page (12 recommended)<input style="width:15%" name="posts_per_page" value="<?php echo(posts_per_page()) ?>" type="text" class="text-long"/> </p>
					<br />
					
					<p><label><b>Related posts per post</b></label>
					*set number of related posts per post (9 recommended)<input style="width:15%" name="related_posts" value="<?php echo(related_posts_count()) ?>" type="text" class="text-long"/> </p>
					<br />
					
					<p><label><b>Voting Allowed ?</b></label>
					<?php if(voting_allowed()) { ?>
					<input type="radio" name="allow_voting" value="1" checked>Yes    
					<input type="radio" name="allow_voting" value="0">No</p>
					<?php } else { ?>
					<input type="radio" name="allow_voting" value="1">Yes    
					<input type="radio" name="allow_voting" value="0" checked>No</p>
					<?php } ?>
					<br />
					
					<p><label><b>Auto Approve Posts ?</b></label>
						<?php if(auto_approve()) 
						{ ?>
							<input type="radio" name="auto_approve" value="1" checked>Yes    
							<input type="radio" name="auto_approve" value="0">No</p>
							<?php 
						} 
						else 
						{ ?>
							<input type="radio" name="auto_approve" value="1">Yes    
							<input type="radio" name="auto_approve" value="0" checked>No</p>
							<?php 
						} ?>
					<br />
					
					<p><label><b>Show Top Random Posts?</b></label>
						<?php if(show_nav()) 
						{ ?>
							<input type="radio" name="show_nav" value="1" checked>Yes    
							<input type="radio" name="show_nav" value="0">No</p>
							<?php 
						} 
						else 
						{ ?>
							<input type="radio" name="show_nav" value="1">Yes    
							<input type="radio" name="show_nav" value="0" checked>No</p>
							<?php 
						} ?>
					<br />
					
					<p><label><b>Allow Pictures ?</b></label>
						<?php if(allow_pictures()) 
						{ ?>
							<input type="radio" name="allow_pictures" value="1" checked>Yes    
							<input type="radio" name="allow_pictures" value="0">No</p>
							<?php 
						} 
						else 
						{ ?>
							<input type="radio" name="allow_pictures" value="1">Yes    
							<input type="radio" name="allow_pictures" value="0" checked>No</p>
							<?php 
						} ?>
					<br />
					
					<p><label><b>Allow Videos? </b></label>
						<?php if(allow_videos()) 
						{ ?>
							<input type="radio" name="allow_videos" value="1" checked>Yes    
							<input type="radio" name="allow_videos" value="0">No</p>
							<?php 
						} 
						else 
						{ ?>
							<input type="radio" name="allow_videos" value="1">Yes    
							<input type="radio" name="allow_videos" value="0" checked>No</p>
							<?php 
						} ?>
					<br />
					
					<p><label><b>Allow Animated Gifs ?</b></label>
						<?php if(allow_gifs()) 
						{ ?>
							<input type="radio" name="allow_gifs" value="1" checked>Yes    
							<input type="radio" name="allow_gifs" value="0">No</p>
							<?php 
						} 
						else 
						{ ?>
							<input type="radio" name="allow_gifs" value="1">Yes    
							<input type="radio" name="allow_gifs" value="0" checked>No</p>
							<?php 
						} ?>
					<br />
					
					
					<input type="submit" class="myButton" value="Update Settings">
					</fieldset>
					</form>
					<?php
					if(isset($_POST['title_length']) && $error=="")
					echo('<div class="alert alert-success">Settings Updated Successfully</div>');
					else if(isset($_POST['title_length']) && $error!="")
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


