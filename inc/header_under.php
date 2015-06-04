<div class="random_thumbs">
<?php
function random_thumbs()
{
	//==========================================Query Changing====================================================
					if((allow_pictures()) && (!allow_videos()) && (!allow_gifs())) 
					{
						$sql = "select permalink,title,file,thumb,type from media where approved=1 and type=0 order by RAND() limit 0,7";
					}
					if((!allow_pictures()) && (allow_videos()) && (!allow_gifs())) 
					{
						$sql = "select permalink,title,file,thumb,type from media where approved=1 and type=1 order by RAND() limit 0,7";
					}
					if((!allow_videos()) && (!allow_pictures()) && (allow_gifs())) 
					{
						$sql = "select permalink,title,file,thumb,type from media where approved=1 and type=2 order by RAND() limit 0,7";
					}
					if((allow_videos()) && (allow_pictures()) && (!allow_gifs())) 
					{
						$sql = "select permalink,title,file,thumb,type from media where approved=1 AND type=0 OR type=1 order by RAND() limit 0,7";
					}
					if((!allow_videos()) && (allow_pictures()) && (allow_gifs())) 
					{
						$sql = "select permalink,title,file,thumb,type from media where approved=1 and type=0 OR type=2 order by RAND() limit 0,7";
					}
					if((allow_videos()) && (!allow_pictures()) && (allow_gifs())) 
					{
						$sql = "select permalink,title,file,thumb,type from media where approved=1 and type=1 OR type=2 order by RAND() limit 0,7";
					}
					if((allow_pictures()) && (allow_videos()) && (allow_gifs())) 
					{
						$sql = "select permalink,title,file,thumb,type from media where approved=1 order by RAND() limit 0,7";
					}
	
	
	//==============================================================================================
	
	
	$query = mysql_query($sql);
	$rootpath = rootpath();
	if(mysql_num_rows($query)>0)
	{
	echo '<div class="nexthon-bar"><ul>';
	while($fetch = mysql_fetch_array($query))
	{
	if($fetch['type']==0)
	echo('<a href="' . $rootpath . '/picture/' . $fetch['permalink'] . '"><img style="width: auto;height: 100%;" src="' . $rootpath . '/uploads/thumbs/' . $fetch['file'] . '"/><span class="title">' . ucwords($fetch['title']) . '</span></a>');
	else if($fetch['type']==1)
	echo('<a href="' . $rootpath . '/video/' . $fetch['permalink'] . '"><img style="width: auto;height: 100%;" src="' . $rootpath . '/uploads/thumbs/' . $fetch['thumb'] . '"/><span class="title">' . ucwords($fetch['title']) . '</span></a>');
	else if($fetch['type']==2)
	echo('<a href="' . $rootpath . '/gif/' . $fetch['permalink'] . '"><img style="width: auto;height: 100%;" src="' . $rootpath . '/uploads/thumbs/' . $fetch['thumb'] . '"/><span class="title">' . ucwords($fetch['title']) . '</span></a>');
	}
	}
	else
	{
	echo '<div class="nexthon-bar" style="padding: 0px 0px;margin-top: 40px;"><ul>';
	echo '.';
	}
		echo '</ul></div>';
	return true;
}
?>
</div>
<body>
<?php echo(get_analytics_code()) ?>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=490642984337841";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<script type="text/javascript">var switchTo5x=true;</script>
	<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
	<script type="text/javascript">stLight.options({publisher: "72d0e931-330a-4a87-96b1-4491c82db79a", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
	<div id="fb-root"></div>
	<div id="tmp-img" style="display:none"></div>

<div id="head-wrapper">
	<div id="head-bar">
		 <h1>
		<?php if(logo_format())
		{ ?>
		<a href="<?php echo(rootpath()) ?>/"><img src="<?php echo(rootpath() . "/images/" . get_logo()) ?>"/></a>
		<?php } else { ?>
		   <a href="<?php echo(rootpath()) ?>/"><?php echo(get_logo_text()) ?></a>
			<?php } ?> 
			</h1>
			
			
			<ul class="main-menu" style="overflow:visible"> 
				<?php if((allow_pictures()) || (allow_videos()) || (allow_gifs())) { ?>	
					<li><a class="" href="<?php echo(rootpath()) ?>/">All</a></li>
					
				<?php } if(allow_pictures()) { ?>				
					<li><a class="" href="<?php echo(rootpath()) ?>/pictures">Pictures</a></li>	
				
				<?php } if(allow_gifs()) { ?>	
					<li><a class="" href="<?php echo(rootpath()) ?>/animated-gifs">Animated GIFs</a></li>
				
				<?php } if(allow_videos()) { ?>	
					<li><a class="" href="<?php echo(rootpath()) ?>/videos">Videos</a></li>
				<?php }?>	
				<?php if((allow_pictures()) || (allow_videos()) || (allow_gifs())) { ?>	
					<li><a class="random-button" href="<?php echo(rootpath())?>/random.php" id="rand-but"><strong>Shuffle</strong></a></li> 
				<?php } ?>	 
			</ul>
			
			
			<ul class="main-2-menu">
				<?php if((allow_pictures()) || (allow_videos()) || (allow_gifs())) { ?>	
					<?php if(rss_enable()){ ?>
						<li><a class="add-rss" href="<?php echo(rootpath()) ?>/rss">RSS</a></li>
					<?php } if(guest_submission_enabled())
					{ ?>
					<li><a class="add-post" href="<?php echo(rootpath()) ?>/submit.php">Submit</a></li> 	     
					<?php } ?>	
				
					<li><form action="<?php echo(rootpath()) ?>/search.php" method="post">
						<input id="sitebar_search_header" type="text" class="search search_input" name="query" tabindex="1" placeholder="Search"/>
						</form>
					</li>
				<?php } ?>
			</ul>
    </div>
	
        	
</div>
	<link href="<?php echo(rootpath()) ?>/css/nexthon.css" media="screen" rel="stylesheet" type="text/css" />
	<?php
		if(show_nav())
		{
			random_thumbs();
		}
		else
		{
			echo '<div class="nexthon-bar" style="padding: 0px 0px;margin-top: 40px; height:12px;"></div>';
		}
	?>
	<div style="clear:both; margin-top:160px;"></div>
	<div id="container" style="">

		<div id="main">
		<?php if(trim(show_leaderboard1_ad())) { ?>
		<div id="main-bottom-ad-tray">
			<div>
					<div style='width:728px; height:90px; border:1px solid #DFDFDF;' align='center'><?php echo(show_leaderboard1_ad()) ?></div>
			</div>
		</div>
		<?php } ?>