<?php
	if(!isset($_SESSION)) session_start();
	if(!isset($_SESSION['vote_array']))
	$_SESSION['vote_array'] = array();
	include "functions.php";
	if(isset($_GET['pic']) && trim($_GET['pic'])!="")
		$permalink = trim($_GET['pic']);
	else if(isset($_GET['vid']) && trim($_GET['vid'])!="")
		$permalink = trim($_GET['vid']);
	else if(isset($_GET['gif']) && trim($_GET['gif'])!="")
		$permalink = trim($_GET['gif']);
		$sql = "select * from media where permalink='" . $permalink . "' and approved=1";
		$query = mysql_query($sql);
		$fetch = mysql_fetch_array($query);
		$id = $fetch['id'];
	if($id)
	{
		$title = $fetch['title'];
		$description = trim($fetch['description']);
		$image = $fetch['file'];
		$source = $fetch['source'];
		if(trim($source)=="")
		$source = "None";
		$post_id = $fetch['id'];
		$orderid = $fetch['orderid'];
		$type = $fetch['type'];
		$views = $fetch['views'];
		$datetime = $fetch['date'];
		$description=$fetch['description'];
		$datetime = date('M d, Y. H:i A', strtotime($datetime));
		include "header.php";
		if($type==0) {
			$title_type = "Picture";
			$thumb = $fetch['file'];
		}
		else if($type==1) {
			$title_type = "Video";
			$thumb = $fetch['thumb'];
		}
		else if($type==2) {
			$title_type = "Animated GIF";
			$thumb = $fetch['file'];
		}
		count_views($permalink);
	}
	else
	{
		header('HTTP/1.0 404 Not Found');
		readfile(rootpath() . '/404.php');
		exit();
	}
?>
	<title><?php echo($title . ' - ' . $title_type) ?></title>
	<?php if($description)  { ?>
	<meta name="description" content="<?php echo($description) ?>">
	<?php } ?>
	<script type='text/javascript'>
		$(document).ready(function(){
		<?php if (!in_array($permalink, $_SESSION['vote_array'])) { ?>
		$('#loveit').click(function()
{
		$.ajax({
		type: "POST",
		url: "<?php echo(rootpath()) ?>/vote.php",
		data: { permalink: "<?php echo($permalink) ?>"}
})
			$(this).css({"background" :  "url('../images/thumb_pink.png') no-repeat scroll 0% 0% #333"});
			$(this).unbind();
});
<?php } else { ?>
			$('#loveit').css({"background" :  "url('../images/thumb_pink.png') no-repeat scroll 0% 0% #333"});
			$('#loveit').unbind();
<?php } ?>
		$("body").keydown(function(e) {
		if(e.keyCode == 37) { // left
                 window.location  = $('#prev_p').attr('href');
}
		else if(e.keyCode == 39) { // right
                 window.location  = $('#next_p').attr('href');
}
});


		}); 
	</script>
<meta property="og:image" content="<?php echo(rootpath()) ?>/uploads/thumbs/<?php echo($thumb) ?>"/>
<meta property="og:title" content="<?php echo($title . ' - ' . $title_type) ?>"/>
<meta property="og:url" content="<?php echo(curPageURL()) ?>"/>
<meta property="og:type" content="website"/>
<meta property="og:site_name" content="<?php echo(get_title()) ?>"/>
</head>
	<?php
	include "inc/header_under.php";
	?>     
    <div id="content-holder">
        <div class="post-info-pad">
            <h1><?php echo($title) ?></h1>
            <p>
				<?php if($type==0)
				echo ('<a href="' . rootpath() . '/pictures">Pictures</a>');
                else if($type==1)
				echo ('<a href="' . rootpath() . '/videos">Videos</a>');
				else if($type==2)
				echo ('<a href="' . rootpath() . '/animated-gifs">Animated GIFs</a>');
				?>
                    <span class="seperator">|</span>
                    <?php echo($datetime) ?>
                    <span class="comment"><fb:comments-count href=<?php echo curPageURL() ?>></fb:comments-count> comments</span>
                    <span class="views"><span id="love_count" votes="<?php echo(return_views($permalink)) ?>" ><?php echo(return_views($permalink)) ?> views</span></span>
					<?php if(voting_allowed()) { ?>
					<span class="loved"><span id="love_count" votes="<?php echo(return_votes($permalink)) ?>" ><?php echo(return_votes($permalink)) ?> likes</span></span>
					<?php } ?>
            </p>				
            <ul class="actions">
				<?php if(voting_allowed()) { ?>
                	<li><a class="love" id="loveit">Love</a></li>
					<?php } ?>
            </ul>            
        </div>
        <?php if(social_sharing_enabled()) { ?>
        <div id="post-control-bar" class="spread-bar-wrap">
			<div class="spread-bar">
				<span class='st_facebook_hcount' displayText='Facebook'></span>
				<span class='st_twitter_hcount' displayText='Tweet'></span>
				<span class='st_linkedin_hcount' displayText='LinkedIn'></span>
				<span class='st_pinterest_hcount' displayText='Pinterest'></span>
				<span class='st_stumbleupon_hcount' displayText='Stumble'></span>
				<span class='st_reddit_hcount' displayText='Reddit'></span>
			</div>
        </div>
			<?php } ?>
			
		<div class="buttons_p_n">
   		 <div id="floating-box">
    		 <div id="right-social" style="margin-top: 0px;"></div>
   		 </div>
   		 <?php
		$post_nav = next_prev_link($orderid);
		?>
   		 <a id="prev_p" class="prev_p" title="<?php echo($post_nav[1]) ?>" href="<?php echo(rootpath(). '/' . $post_nav[4] . '/' . $post_nav[0]) ?>"></a>
		  <a id="next_p" class="next_p" title="<?php echo($post_nav[3]) ?>" href="<?php echo(rootpath() . '/' . $post_nav[5] . '/' . $post_nav[2]) ?>"></a>
		</div>
		
        <div id="content">
        
        	
		
            <div class="post-container">
                <div class="img-wrap">
					<?php if($type==0 || $type==2)
                    echo('<img src="' . rootpath() . '/uploads/' . $image . '" alt="' . $title . '" title="' . $title . '"/>');
					else if($type==1)
					echo(get_embed_code($fetch['file']));
					?>
                </div>					
            </div>
            <div class="comment-section">
				<?php if($description) {?>
				<span class="report-and-tags">
					<p style="padding-left: 15px;font-size: 12px;">
						<?php echo($description)?>
					</p>
				</span>
				<?php } ?>
				
				<span class="report-and-tags">
					<p style="padding-left: 15px;"> 
						<?php if(return_tags($id)) { ?>
						Tags : 
						<?php $tagss;
							echo substr(return_tags($id),0,-3);
							
						}?>
					</p>
				</span>
				
                <span class="report-and-source">
					<p>                       
						Source : <?php echo($source) ?> &nbsp; (<a style="color:red" href="<?php echo(rootpath()) ?>/contact.php?report=<?php echo(curPageURL()) ?>">Report This</a>)        
					</p>
				</span>	
				 <h3 class="title" id="comments">Comments</h3>
				
                <div style="margin-left:10px">
					<div class="fb-comments" data-href="<?php echo(curPageURL()) ?>" data-colorscheme="light" data-width="720"></div>			
                 </div>                
            </div>
            <div class="post-may-like">
                <div id="entries-content" class="grid">
					<?php					
					//==============================================Post May Like Query=============================
					if((allow_pictures()) && (!allow_videos()) && (!allow_gifs())) 
					{
						$sql = "select permalink,title,file,type,thumb from media where approved=1 and type=0 and `id`!=".$id." order by RAND() limit 0," . related_posts_count();					
					}
					if((!allow_pictures()) && (allow_videos()) && (!allow_gifs())) 
					{
						$sql = "select permalink,title,file,type,thumb from media where approved=1 and type=1 and `id`!=".$id." order by RAND() limit 0," . related_posts_count();					
					}
					if((!allow_videos()) && (!allow_pictures()) && (allow_gifs())) 
					{
						$sql = "select permalink,title,file,type,thumb from media where approved=1 and type=2 and `id`!=".$id." order by RAND() limit 0," . related_posts_count();
					}
					if((allow_videos()) && (allow_pictures()) && (!allow_gifs())) 
					{
						$sql = "select permalink,title,file,type,thumb from media where approved=1 AND type=0 OR type=1 and `id`!=".$id." order by RAND() limit 0," . related_posts_count();
					}
					if((!allow_videos()) && (allow_pictures()) && (allow_gifs())) 
					{
						$sql = "select permalink,title,file,type,thumb from media where approved=1 and type=0 OR type=2 and `id`!=".$id." order by RAND() limit 0," . related_posts_count();
					}
					if((allow_videos()) && (!allow_pictures()) && (allow_gifs())) 
					{
						$sql = "select permalink,title,file,type,thumb from media where approved=1 and type=1 OR type=2 and `id`!=".$id." order by RAND() limit 0," . related_posts_count();
					}
					if((allow_pictures()) && (allow_videos()) && (allow_gifs())) 
					{
						$sql = "select permalink,title,file,type,thumb from media where approved=1 and `id`!=".$id." order by RAND() limit 0," . related_posts_count();
					}
					
					//==============================================Post May Like Query=============================
					$query = mysql_query($sql);
					$i=1;
					while($fetch = mysql_fetch_array($query))
					{
						if($i==4)
						$i=1;
						if($fetch['type']==0)
						{
							$type="picture";
							$thumb = $fetch['file'];
						}
						else if($fetch['type']==1)
						{
							$type="video";
							$thumb = $fetch['thumb'];
						}
						else if($fetch['type']==2)
						{
							$type="gif";
							$thumb = $fetch['file'];
						}
					?>
					<ul id="grid-col-1" class="col-<?php echo($i) ?>">
						<li style="height:200px">
							<a href="<?php echo(rootpath() . '/' . $type . '/' . $fetch['permalink']) ?>" class="jump_stop">
								<div style="" class="thimage">
								<img src="<?php echo(rootpath()) ?>/uploads/thumbs/<?php echo($thumb) ?>" alt="<?php echo(ucwords($fetch['title'])) ?>" title="<?php echo(ucwords($fetch['title'])) ?>" />
								
							
								<div id="title_m"><?php echo(ucwords($fetch['title'])) ?></div>
							</a>
								</div>
						</li>
					</ul>
					<?php
					$i++;
					}
					?>                  
                </div>
            </div>
        </div>
    </div>
<?php include "footer.php"; ?>