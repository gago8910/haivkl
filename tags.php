<?php
if (!isset($_SESSION)) session_start();
	include "functions.php";
	if(isset($_GET['sort_order']))
	{
		if(trim($_GET['sort_order'])=="ASC" || trim($_GET['sort_order'])=="DESC")
			$_SESSION['sort_order'] = trim($_GET['sort_order']);
	}
	if(isset($_GET['sort_by']))
	{
		if(trim($_GET['sort_by'])=="orderid" || trim($_GET['sort_by'])=="votes" || trim($_GET['sort_by'])=="views")
		if(trim($_GET['sort_by'])=="orderid")
			$_SESSION['sort_by']="date";
		else if(trim($_GET['sort_by'])=="votes")
			$_SESSION['sort_by']="likes";
		else if(trim($_GET['sort_by'])=="views")
			$_SESSION['sort_by']="views";
		else
			$_SESSION['sort_by']="date";
	}
	if(!isset($_SESSION['sort_by']))
		$_SESSION['sort_by'] = "votes";
	if(!isset($_SESSION['sort_order']))
		$_SESSION['sort_order'] = "DESC";
	if($_SESSION['sort_by']=="date")
		$sort_by="orderid";
	else if($_SESSION['sort_by']=="likes")
		$sort_by="votes";
	else if($_SESSION['sort_by']=="views")
		$sort_by="views";
	else
		$sort_by="orderid";
		
	$tag=$_GET['tag'];
	if(!is_valid_tag($tag))
	{
	header('HTTP/1.0 404 Not Found');
	readfile(rootpath() . '/404.php');
	exit();
	}
	if(isset($_GET['type']) && $_GET['type']!="")
		$typep=$_GET['type'];
	else
		$typep="all";		
		
	
	$page=1;
	$limit=posts_per_page();
	$next=2;
	$prev=1;
					$tagsqll="select `tag_id` from `tags` where `tag`='".$tag."'";
					$tagquer=mysql_query($tagsqll);
					$tafetch=mysql_fetch_array($tagquer);
					
					$sq="SELECT * FROM `media_tags` where `tag_id`='".$tafetch['tag_id']."'";
					$quer=mysql_query($sq);
					while($fet = mysql_fetch_array($quer))
					{
						$data = mysql_query("select * from media where id=".$fet['id']."");
					}
	$rows = mysql_num_rows($data);
	$last = ceil($rows/$limit);
	if($last==0)
	$last=1;
	if(isset($_GET['page']) && $_GET['page']!='' && ($_GET['page']>=1 && $_GET['page']<=$last))
	{
		$page=$_GET['page'];
		if($page>1)
		$prev=$page-1;
		else
		$prev=$page;
		if($page<$last)
		$next=$page+1;
		else
		$next=$page;
	}
	include "header.php";
	if($typep=="all")
	$typetitle = "Posts";
	else
	$typetitle = ucfirst($typep);
	if($page>1)
	{
		?>
		<title><?php echo($typetitle) ?> tagged with <?php echo($tag . ' - ' . get_title() . ' - Page ' . $page) ?></title>
		<?php } else { ?>
		<title><?php echo($typetitle) ?> tagged with <?php echo($tag . ' - ' . get_title()) ?></title>
		<?php } ?>
		<meta name="description" content="<?php echo(get_description()) ?>">
		<meta name="keywords" content="<?php echo(get_tags()) ?>">
</head>
	<?php
	include "inc/header_under.php";
	?>         
    <div id="content-holder"> 
		<div class="main-filter ">
            <ul class="content-type">
				<?php 
				if((allow_pictures()) || (allow_videos()) || (allow_gifs())) 
				{
					if($typep=="all")
					{
						echo('<li><a class="current" href="' . rootpath(). '/tags/all/' . $tag . '"><strong>All</strong></a></li>');
						if(rss_enable() && rss_tag_enable()) {
							echo('<li><a class="" href="'.rootpath().'/rss/tag/all/' . $tag . '"  style="padding-left: 8px; padding-right: 11px;"><img src="' . rootpath() . '/images/rss_icon.png" /></a></li>');
						}
					}
					else
					{
						echo('<li> <a class="" href="' . rootpath(). '/tags/all/' . $tag . '"><strong>All</strong></a></li>');
					}
				} 
				if(allow_pictures()) 
				{	
					if($typep=="pictures")
					{
						echo('<li> <a class="current" href="' . rootpath(). '/tags/pictures/' . $tag . '"><strong>Pictures</strong></a></li>');
						if(rss_enable() && rss_tag_enable()  && rss_cat_enable()) {
							echo('<li><a class="" href="'.rootpath().'/rss/tag/pictures/' . $tag . '"  style="padding-left: 8px; padding-right: 11px;"><img src="' . rootpath() . '/images/rss_icon.png" /></a></li>');
						}
					}
					else
					{
						echo('<li> <a class="" href="' . rootpath(). '/tags/pictures/' . $tag . '"><strong>Pictures</strong></a></li>');
					}
				}			
				if(allow_gifs()) 
				{	
					if($typep=="animated-gifs")
					{
						echo('<li> <a class="current" href="' . rootpath(). '/tags/animated-gifs/' . $tag . '/"><strong>Animated GIFs</strong></a></li>');
						if(rss_enable() && rss_tag_enable()  && rss_cat_enable()) {
							echo('<li><a class="" href="'.rootpath().'/rss/tag/gifs/' . $tag . '"  style="padding-left: 8px; padding-right: 11px;"><img src="' . rootpath() . '/images/rss_icon.png" /></a></li>');
						}
					}
					else
					{
						echo('<li> <a class="" href="' . rootpath(). '/tags/animated-gifs/' . $tag . '"><strong>Animated GIFs</strong></a></li>');
					}
				} 
				if(allow_videos()) 
				{	
					if($typep=="videos")
					{
						echo('<li> <a class="current" href="' . rootpath(). '/tags/videos/' . $tag . '"><strong>Videos</strong></a></li>');
						if(rss_enable() && rss_tag_enable()  && rss_cat_enable()) {
							echo('<li><a class="" href="'.rootpath().'/rss/tag/videos/' . $tag . '"  style="padding-left: 8px; padding-right: 11px;"><img src="' . rootpath() . '/images/rss_icon.png" /></a></li>');
						}
					}
					else
					{
						echo('<li> <a class="" href="' . rootpath(). '/tags/videos/' . $tag . '"><strong>Videos</strong></a></li>');
					}
				}
				?>   
				
            </ul>
            <div id="sort">
				<label>
					<strong>Sort by : </strong>
					<select onchange="self.location=self.location='<?php echo(rootpath()) ?>/tags.php?sort_by='+this.options[this.selectedIndex].value">
						<?php if($_SESSION['sort_by']=="date") { ?>
						<option value="orderid" selected>Date</option>
						<option value="votes">Likes</option>
						<option value="views">Views</option>
						<?php } else if($_SESSION['sort_by']=="likes") { ?>
						<option value="orderid">Date</option>
						<option value="votes" selected>Likes</option>
						<option value="views">Views</option>
						<?php } else if($_SESSION['sort_by']=="views") { ?>
						<option value="orderid">Date</option>
						<option value="votes">Likes</option>
						<option value="views" selected>Views</option>
						<?php } else { ?>
						<option value="orderid" selected>Date</option>
						<option value="votes">Likes</option>
						<option value="views">Views</option>
						<? } ?>
					</select>
				</label>​
				<?php if($_SESSION['sort_order']=="ASC") { ?>
				<a href="<?php echo(rootpath()) ?>/tags.php?sort_order=DESC"><img src="<?php echo(rootpath()) ?>/images/arrow_up.png" title="Ascending"></a>
				<?php } else if($_SESSION['sort_order']=="DESC") { ?>
				<a href="<?php echo(rootpath()) ?>/tags.php?sort_order=ASC"><img src="<?php echo(rootpath()) ?>/images/arrow_down.png" title="Descending"></a>
				 <? } else { ?>
				 <a href="<?php echo(rootpath()) ?>/tags.php?sort_order=DESC"><img src="<?php echo(rootpath()) ?>/images/arrow_up.png" title="Ascending"></a>
				<?php } ?>
			</div>
        </div>
		
        <div id="content" listPage="hot">                      
            <div id="entries-content" class="grid"> 
				<?php
					//Tag Searching
					$tagsql="select `tag_id` from `tags` where `tag`='".$tag."'";
					$tagquery=mysql_query($tagsql);
					$tagfetch=mysql_fetch_array($tagquery);
					
					$sqll="SELECT id FROM `media_tags` where `tag_id`='".$tagfetch['tag_id']."'";
						
					$start_result = ($page-1)*$limit;
					
					if($typep=="all")
					{
						if((allow_pictures()) || (allow_videos()) || (allow_gifs())) 
						{
							$sql = "select permalink,title,file,thumb,type from media WHERE approved=1 AND id IN(" . $sqll . ") order by " . $sort_by . " " . $_SESSION['sort_order'] . " limit ".$start_result.",".$limit;
						}
						
					}
					else if($typep=="pictures")
					{
						if(allow_pictures()) 
						{
							$sql = "select permalink,title,file,thumb,type from media WHERE approved=1 AND id IN(" . $sqll . ") AND type=0 order by " . $sort_by . " " . $_SESSION['sort_order'] . " limit ".$start_result.",".$limit;
						}
						
					}
					else if($typep=="videos")
					{
						if(allow_videos()) 
						{
							$sql = "select permalink,title,file,thumb,type from media WHERE approved=1 AND id IN(" . $sqll . ") AND type=1 order by " . $sort_by . " " . $_SESSION['sort_order'] . " limit ".$start_result.",".$limit;
						}
					}
					else if($typep=="animated-gifs")
					{
						if(allow_gifs()) 
						{
							$sql = "select permalink,title,file,thumb,type from media WHERE approved=1 AND id IN(" . $sqll . ") AND type=2 order by " . $sort_by . " " . $_SESSION['sort_order'] . " limit ".$start_result.",".$limit;
						}
					}
					
					
					$query = mysql_query($sql);
					$i=1;
					if(mysql_num_rows($query)>0)
					{
						while($fetch = mysql_fetch_array($query))
						{
							if($fetch['type']==0)
							{
								$thumb = $fetch['file'];
								$type = "picture";
							}
							else if($fetch['type']==1)
							{
								$thumb = $fetch['thumb'];
								$type = "video";
							}
							else if($fetch['type']==2)
							{
								$thumb = $fetch['file'];
								$type = "gif";
							}
							if($i==4)
							$i=1;
							?>
							<ul id="grid-col-1" class="col-<?php echo($i) ?>">
								<li style="height:200px">
									<a href="<?php echo(rootpath(). '/' . $type . '/' . $fetch['permalink']) ?>" class="jump_stop">
										<div style="" class="thimage">
											<img style="width: auto;" src="<?php echo(rootpath()) ?>/uploads/thumbs/<?php echo($thumb) ?>" alt="<?php echo(ucwords($fetch['title'])) ?>" title="<?php echo(ucwords($fetch['title'])) ?>" />                           
									<div id="title_m"><center><?php echo(ucwords($fetch['title'])) ?></center></div>
									</a>
											
										</div>
									<h4></h4>
								</li>
							</ul>
							<?php
							$i++;
						}
					}
				
					else
					{
						?>
						<div class="submitted">
							<p>
							<span class="go3d">N</span>
							<span class="go3d">o</span>
							<span class="go3d">t</span>
							<span class="go3d">&nbsp&nbsp</span>
							<span class="go3d">F</span>
							<span class="go3d">o</span>
							<span class="go3d">u</span>
							<span class="go3d">n</span>
							<span class="go3d">d</span>
							</p>   
							<p class="error" style="padding-bottom: 100px;">
							Sorry ! No Media found to display :(
							</p>
						
						</div>
					<?php } 
				?>
			</div>			
			<div id="paging-buttons" class="paging-buttons">
				<?php
					$path=rootpath() . '/all';
					pagination($page,$prev,$next,$last,$path);
				?>
			</div>		
        </div>
    </div>	
	<?php include "footer.php"; ?>