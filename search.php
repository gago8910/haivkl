<?php
if (!isset($_SESSION)) session_start();
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
	include "functions.php";
	if(isset($_POST['query']))
	{
		$_SESSION['searchq'] = strip_tags($_POST['query']);
		header('Location: ' . rootpath() . '/search/all/' . $_SESSION['searchq']);
		exit();
	}
	if(isset($_GET['type']) && $_GET['type']!="")
		$typep=$_GET['type'];
	else
		$typep="all";
	if(isset($_GET['q']) && $_GET['q']!="")
	{
		$searchq = strip_tags($_GET['q']);
	}
	else
	{
	$searchq = "";
	header('HTTP/1.0 404 Not Found');
	readfile(rootpath() . '/404.php');
	exit();
	}
		$page=1;
		$limit=posts_per_page();
		$next=2;
		$prev=1;
	if($typep=="all")
	{
		if((allow_pictures()) && (!allow_videos()) && (!allow_gifs())) 
		{
			$data = mysql_query("SELECT id FROM media where title like '%" . $searchq . "%' and type=0 and approved=1");
			$sql = "select permalink,title,file,thumb,type from media where title like '%" . $searchq . "%' and approved=1 and type=0 order by " . $sort_by . " " . $_SESSION['sort_order'] . " limit ".$start_result.",".$limit;
		}
		if((!allow_pictures()) && (allow_videos()) && (!allow_gifs())) 
		{
			$data = mysql_query("SELECT id FROM media where title like '%" . $searchq . "%' and type=1 and approved=1");
			$sql = "select permalink,title,file,thumb,type from media where title like '%" . $searchq . "%' and approved=1 and type=1 order by " . $sort_by . " " . $_SESSION['sort_order'] . " limit ".$start_result.",".$limit;
		}
		if((!allow_videos()) && (!allow_pictures()) && (allow_gifs())) 
		{
			$data = mysql_query("SELECT id FROM media where title like '%" . $searchq . "%' and type=2 and approved=1");
			$sql = "select permalink,title,file,thumb,type from media where title like '%" . $searchq . "%' and approved=1 and type=2 order by " . $sort_by . " " . $_SESSION['sort_order'] . " limit ".$start_result.",".$limit;
		}
		if((allow_videos()) && (allow_pictures()) && (!allow_gifs())) 
		{
			$data = mysql_query("SELECT id FROM media where title like '%" . $searchq . "%' AND type=0 OR type=1 and approved=1");
			$sql = "select permalink,title,file,thumb,type from media where title like '%" . $searchq . "%' and approved=1 AND type=0 OR type=1 order by " . $sort_by . " " . $_SESSION['sort_order'] . " limit ".$start_result.",".$limit;						
		}
		if((!allow_videos()) && (allow_pictures()) && (allow_gifs())) 
		{
			$data = mysql_query("SELECT id FROM media where title like '%" . $searchq . "%' and type=0 OR type=2 and approved=1");
			$sql = "select permalink,title,file,thumb,type from media where title like '%" . $searchq . "%' and approved=1 and type=0 OR type=2 order by " . $sort_by . " " . $_SESSION['sort_order'] . " limit ".$start_result.",".$limit;						
		}
		if((allow_videos()) && (!allow_pictures()) && (allow_gifs())) 
		{
			$data = mysql_query("SELECT id FROM media where title like '%" . $searchq . "%' and type=1 OR type=2");
			$sql = "select permalink,title,file,thumb,type from media where title like '%" . $searchq . "%' and approved=1 and type=1 OR type=2 order by " . $sort_by . " " . $_SESSION['sort_order'] . " limit ".$start_result.",".$limit;
		}
		if((allow_pictures()) && (allow_videos()) && (allow_gifs())) 
		{
			$data = mysql_query("SELECT id FROM media where title like '%" . $searchq . "%' and approved=1");
		}
	}
	else if($typep=="pictures")
	{
		$data = mysql_query("SELECT id FROM media where title like '%" . $searchq . "%' and type=0 and approved=1");
	}
	else if($typep=="animated-gifs")
	{
		$data = mysql_query("SELECT id FROM media where title like '%" . $searchq . "%' and type=2 and approved=1");
	}
	else if($typep=="videos")
	{
		$data = mysql_query("SELECT id FROM media where title like '%" . $searchq . "%' and type=1 and approved=1");
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
	?>
	<title>Search Results for <?php echo($searchq) ?></title>
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
							echo('<li> <a class="current" href="' . rootpath(). '/search/all/' . $searchq . '"><strong>All</strong></a></li>');
						else
							echo('<li> <a class="" href="' . rootpath(). '/search/all/' . $searchq . '"><strong>All</strong></a></li>');
					} 
					if(allow_pictures()) 
					{	
						if($typep=="pictures")
							echo('<li> <a class="current" href="' . rootpath(). '/search/pictures/' . $searchq . '"><strong>Pictures</strong></a></li>');
						else
							echo('<li> <a class="" href="' . rootpath(). '/search/pictures/' . $searchq . '"><strong>Pictures</strong></a></li>');
					} 
					if(allow_gifs()) 
					{	
						if($typep=="animated-gifs")
							echo('<li> <a class="current" href="' . rootpath(). '/search/animated-gifs/' . $searchq . '"><strong>Animated GIFs</strong></a></li>');
						else
							echo('<li> <a class="" href="' . rootpath(). '/search/animated-gifs/' . $searchq . '"><strong>Animated GIFs</strong></a></li>');
					} 
					if(allow_videos()) 
					{ 	
						if($typep=="videos")
							echo('<li> <a class="current" href="' . rootpath(). '/search/videos/' . $searchq . '"><strong>Videos</strong></a></li>');
						else
							echo('<li> <a class="" href="' . rootpath(). '/search/videos/' . $searchq . '"><strong>Videos</strong></a></li>');
					}
					?>
            </ul>
            <div id="sort">
				<label>
					<strong>Sort by : </strong>
					<select onchange="self.location=self.location='<?php echo(rootpath()) ?>/search.php?sort_by='+this.options[this.selectedIndex].value+'&q=<?php echo($_SESSION['searchq']) ?>'">
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
				<a href="<?php echo(rootpath()) ?>/search.php?sort_order=DESC&q=<?php echo($_SESSION['searchq']) ?>"><img src="<?php echo(rootpath()) ?>/images/arrow_up.png" title="Ascending"></a>
				<?php } else if($_SESSION['sort_order']=="DESC") { ?>
				<a href="<?php echo(rootpath()) ?>/search.php?sort_order=ASC&q=<?php echo($_SESSION['searchq']) ?>"><img src="<?php echo(rootpath()) ?>/images/arrow_down.png" title="Descending"></a>
				 <? } else { ?>
				 <a href="<?php echo(rootpath()) ?>/search.php?sort_order=DESC&q=<?php echo($_SESSION['searchq']) ?>"><img src="<?php echo(rootpath()) ?>/images/arrow_up.png" title="Ascending"></a>
				<?php } ?>
			</div>
        </div>		
		<div id="content" listPage="hot">                        
			<div id="entries-content" class="grid"> 
				<?php
					$start_result = ($page-1)*$limit;
					if($typep=="all")
					{
								if((allow_pictures()) && (!allow_videos()) && (!allow_gifs())) 
								{
									$sql = "select permalink,title,file,thumb,type from media where title like '%" . $searchq . "%' and approved=1 and type=0 order by " . $sort_by . " " . $_SESSION['sort_order'] . " limit ".$start_result.",".$limit;
								}
								if((!allow_pictures()) && (allow_videos()) && (!allow_gifs())) 
								{
									$sql = "select permalink,title,file,thumb,type from media where title like '%" . $searchq . "%' and approved=1 and type=1 order by " . $sort_by . " " . $_SESSION['sort_order'] . " limit ".$start_result.",".$limit;
								}
								if((!allow_videos()) && (!allow_pictures()) && (allow_gifs())) 
								{
									$sql = "select permalink,title,file,thumb,type from media where title like '%" . $searchq . "%' and approved=1 and type=2 order by " . $sort_by . " " . $_SESSION['sort_order'] . " limit ".$start_result.",".$limit;
								}
								if((allow_videos()) && (allow_pictures()) && (!allow_gifs())) 
								{
									$sql = "select permalink,title,file,thumb,type from media where title like '%" . $searchq . "%' and approved=1 AND type=0 OR type=1 order by " . $sort_by . " " . $_SESSION['sort_order'] . " limit ".$start_result.",".$limit;						
								}
								if((!allow_videos()) && (allow_pictures()) && (allow_gifs())) 
								{
									$sql = "select permalink,title,file,thumb,type from media where title like '%" . $searchq . "%' and approved=1 and type=0 OR type=2 order by " . $sort_by . " " . $_SESSION['sort_order'] . " limit ".$start_result.",".$limit;						
								}
								if((allow_videos()) && (!allow_pictures()) && (allow_gifs())) 
								{
									$sql = "select permalink,title,file,thumb,type from media where title like '%" . $searchq . "%' and approved=1 and type=1 OR type=2 order by " . $sort_by . " " . $_SESSION['sort_order'] . " limit ".$start_result.",".$limit;
								}
								if((allow_pictures()) && (allow_videos()) && (allow_gifs())) 
								{
									$sql = "select permalink,title,file,thumb,type from media where title like '%" . $searchq . "%' and approved=1 order by " . $sort_by . " " . $_SESSION['sort_order'] . " limit ".$start_result.",".$limit;
								}
						
					}
					else if($typep=="pictures")
					{
						if(allow_pictures()) 
						{
							$sql = "select permalink,title,file,thumb,type from media where title like '%" . $searchq . "%' and approved=1 and type=0 order by " . $sort_by . " " . $_SESSION['sort_order'] . " limit ".$start_result.",".$limit;
						}
					}
					else if($typep=="videos")
					{	
						if(allow_videos()) 
						{
							$sql = "select permalink,title,file,thumb,type from media where title like '%" . $searchq . "%' and approved=1 and type=1 order by " . $sort_by . " " . $_SESSION['sort_order'] . " limit ".$start_result.",".$limit;
						}
					}
					else if($typep=="animated-gifs")
					{
						if(allow_gifs()) 
						{
							$sql = "select permalink,title,file,thumb,type from media where title like '%" . $searchq . "%' and approved=1 and type=2 order by " . $sort_by . " " . $_SESSION['sort_order'] . " limit ".$start_result.",".$limit;
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
							<li style="height:213px">
								<a href="<?php echo(rootpath(). '/' . $type . '/' . $fetch['permalink']) ?>" class="jump_stop">
									<div style="" class="thimage">
									<img src="<?php echo(rootpath()) ?>/uploads/thumbs/<?php echo($thumb) ?>" alt="<?php echo($fetch['title']) ?>" title="<?php echo($fetch['title']) ?>" />
									  <div id="title_m"><?php echo($fetch['title']) ?></div>
							
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
						{ ?>
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
								Sorry ! Your query doesn't returned any results.
							</p>		
						</div>
						<?php } ?>
			</div>
				
			<div id="paging-buttons" class="paging-buttons">
				<?php
				$path = rootpath() . '/search/' . $typep . '/' . $searchq;
				pagination($page,$prev,$next,$last,$path);
				?>
			</div>			
		</div>
    </div>
	
<?php include "footer.php"; ?>