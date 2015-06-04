<?php
if (!isset($_SESSION)) session_start();
	include "functions.php";
	if(!allow_pictures())
		header('Location: index.php');
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
		$page=1;
		$limit=posts_per_page();
		$next=2;
		$prev=1;
		$data = mysql_query("SELECT id FROM media where approved=1 and type=0");
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
?>
	<title>Pictures - <?php echo('Page ' . $page) ?></title>
	</head>
	<?php
	include "inc/header_under.php";
	?>         
    <div id="content-holder"> 
        <div class="main-filter ">
            <ul class="content-type">
					<?php if((allow_pictures()) || (allow_videos()) || (allow_gifs())) { ?>	
					<li><a  href="<?php echo(rootpath()) ?>"><strong>All</strong></a></li>
					<?php } if(allow_pictures()) { ?>	
						<li> <a class="current" href="<?php echo(rootpath()) ?>/pictures"><strong>Pictures </strong></a></li>
						<?php if(rss_enable() && rss_cat_enable()) { ?>
							<li><a class="" href="<?php echo(rootpath()) ?>/rss/pictures" style="padding-left: 8px; padding-right: 11px;"><img src="<?php echo(rootpath()) ?>/images/rss_icon.png"  /></a></li>
						<?php } ?>
					<?php } if(allow_gifs()) { ?>
						<li> <a class="" href="<?php echo(rootpath()) ?>/animated-gifs"><strong>Animated GIFs</strong></a></li>
					<?php } if(allow_videos()) { ?>
						<li> <a class="" href="<?php echo(rootpath()) ?>/videos"><strong>Videos</strong></a></li> 
					<?php }?>
            </ul>
			
			<div id="sort">
				
				<label>
					<strong>Sort by : </strong>
					<select onchange="self.location=self.location='<?php echo(rootpath()) ?>/pictures.php?sort_by='+this.options[this.selectedIndex].value">
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
						<?php } ?>
					</select>
				</label>​
				<?php if($_SESSION['sort_order']=="ASC") { ?>
				<a href="<?php echo(rootpath()) ?>/pictures.php?sort_order=DESC"><img src="<?php echo(rootpath()) ?>/images/arrow_up.png" title="Ascending"></a>
				<?php } else if($_SESSION['sort_order']=="DESC") { ?>
				<a href="<?php echo(rootpath()) ?>/pictures.php?sort_order=ASC"><img src="<?php echo(rootpath()) ?>/images/arrow_down.png" title="Descending"></a>
				<?php } else { ?>
				<a href="<?php echo(rootpath()) ?>/pictures.php?sort_order=DESC"><img src="<?php echo(rootpath()) ?>/images/arrow_up.png" title="Ascending"></a>
				<?php } ?>
			</div>
        </div>
		
        <div id="content" listPage="hot">                       
            <div id="entries-content" class="grid"> 
				<?php
					$start_result = ($page-1)*$limit;
					$sql = "select permalink,title,file from media where type=0 and approved=1 order by " . $sort_by . " " . $_SESSION['sort_order'] . " limit ".$start_result.",".$limit;
					$query = mysql_query($sql);
					$i=1;
					if(mysql_num_rows($query)>0)
					{
					while($fetch = mysql_fetch_array($query))
					{
					if($i==4)
					$i=1;
				?>
				<ul id="grid-col-1" class="col-<?php echo($i) ?>">
                    <li style="height:200px">
                        <a href="<?php echo(rootpath()) ?>/picture/<?php echo($fetch['permalink']) ?>" class="jump_stop">
                            <div style="" class="thimage">
								<img style="width: auto;height: 100%;" src="<?php echo(rootpath()) ?>/uploads/thumbs/<?php echo($fetch['file']) ?>" alt="<?php echo(ucwords($fetch['title'])) ?>" title="<?php echo(ucwords($fetch['title'])) ?>" />                          
								<div id="title_m"><?php echo(ucwords($fetch['title'])) ?></div>
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
				<?php } ?>
			</div>
			<div id="paging-buttons" class="paging-buttons">
				<?php
				$path = rootpath() . '/pictures';
				pagination($page,$prev,$next,$last,$path);
				?>
			</div>			
        </div>
    </div>
	<?php include "footer.php"; ?>