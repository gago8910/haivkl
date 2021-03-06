<?php
if (!isset($_SESSION)) session_start();
	include "../functions.php";
	if(!allow_videos())
	{
		header('Location: ./dashboard.php');
	}
	if(!isset($_SESSION['admin_vmp']))
	{
		header('Location: ./login.php');
	}
	
	error_reporting(0);
	$invalid=false;
	$error="";
	if(!(isset($_POST['title']) || isset($_GET['id'])))
	header('Location: ./videos.php');

function update_video($id,$title,$description,$permalink,$url,$thumb,$source,$views,$votes,$date,$approved)
{
	$title=mysql_real_escape_string($title);
	if(mb_check_encoding($title,"UTF-8"))
	$title=preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $title);
	$description=mysql_real_escape_string($description);
	$source=mysql_real_escape_string($source);
	
	$fetch_order = mysql_fetch_array(mysql_query("select orderid from media where id=" . $id));
	if($fetch_order['orderid']==0)
	$order = gen_order_id(1);
	else
	$order = $fetch_order['orderid'];
	$update_sql = "update media set title='" . $title . "',description='" . $description . "', permalink='" . $permalink . "', file='" . $url . "',thumb='" . $thumb . "',source='" . $source . "',views=" . $views . ",votes=" . $votes . ",date='" . $date . "', orderid=" . $order . ",approved=" . $approved . " where id=" . $id;
	mysql_query($update_sql);
}
if(isset($_GET['rg']) && isset($_GET['id']))
{
	$sql="select file,thumb from media where id=" . $_GET['id'] . " and type=1";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	unlink("../uploads/thumbs/" . $fetch['thumb']);
	regen_video_thumb($_GET['id'],$fetch['file']);
}
if(isset($_POST['title']))
{
	$sql="select title,description,file,permalink,thumb from media where id=" . $_POST['id'] . " and type=1";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	$id = $_POST['id'];
	$title = trim($_POST['title']);
	$description = strip_tags(trim($_POST['description']));
	if($title!=$fetch['title'] && $_POST['permalink']==$fetch['permalink'])
	$permalink=gen_permalink($title);
	else
	$permalink = $_POST['permalink'];
	$url = httpify(trim($_POST['url']));
	if($url!=$fetch['file'])
	{
	if(valid_video_url($url))
	{
	unlink("../uploads/thumbs/" . $fetch['thumb']);
	$thumb = regen_video_thumb($_POST['id'],$url);
	if($thumb==0)
	{
	$thumb = regen_video_thumb($_POST['id'],$fetch['file']);
	$invalid=true;
	$url = $fetch['file'];
	}
	}
	else
	{
	$invalid=true;
	$thumb = basename($_POST['thumb']);
	$url = $fetch['file'];
	}
}
else
{
	$thumb = basename($_POST['thumb']);
	$url = $fetch['file'];
}
	$source = trim($_POST['source']);
	$views = $_POST['views'];
	$votes = $_POST['votes'];
	$date = $_POST['date'];
	$approved = $_POST['approved'];
	if(trim($title)=="")
	$error .="o. Title must not be empty.<br />";
	if(!valid_video_url($url))
	$error .="o. Invalid Video URL, Only Youtube and Vimeo Videos Allowed.<br />";
	$ext = end(explode(".",strtolower(basename($thumb))));
	if(!valid_file_extension($ext))
	$error .="o. Invalid Thumbnail.<br />";
	if(!is_numeric($views))
	$error .="o. Views must be numeric.<br />";
	if(!is_numeric($votes))
	$error .="o. Votes must be numeric.<br />";
	if(!is_date($date))
	$error .="o. Invalid date format.<br />";
	if($error=="")
	{
		delete_tags($id);
		update_video($id,$title,$description,$permalink,$url,$thumb,$source,$views,$votes,$date,$approved);
		$tag=trim($_POST['tags']);
		$tag=mysql_real_escape_string($tag);
		$ids=Array();
		if (preg_match('/,/',$tag)) {
		$tags=explode(',',$tag);
			foreach($tags as $tag) 
			{
			if(trim($tag)!="")
			$ids[] = insert_tag($tag); 
			}
		}
		else {
		$tags=trim($tag);
		if(trim($tags)!="")
		$ids[] = insert_tag($tags); 
		}
		add_media_tag($id,$ids);	
	}
	else
	{
			$invalid=true;
	}
	$tags=get_mediatag($id);
	$tags=RTRIM($tags,',');
}
if(isset($_GET['id']) && is_numeric($_GET['id']))
{
	$sql="select * from media where id=" . $_GET['id'] . " and type=1";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	if($fetch['id'])
	{
		$id = $fetch['id'];
		$title = $fetch['title'];
		$description = $fetch['description'];
		$permalink = $fetch['permalink'];
		$url = $fetch['file'];
		$thumb = $fetch['thumb'];
		$source = $fetch['source'];
		$views = $fetch['views'];
		$votes = $fetch['votes'];
		$date = $fetch['date'];
		$approved = $fetch['approved'];
		$tags=get_mediatag($id);
		$tags=RTRIM($tags,',');
	}
}
include "header.php";
function count_all_videos()
{
	$sql = "select count(id) as total from media where type=1";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
function count_pending_videos()
{
	$sql = "select count(id) as total from media where approved=0 and type=1";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
function count_approved_videos()
{
	$sql = "select count(id) as total from media where approved=1 and type=1";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
?>
<title>Edit Video - <?php echo(get_title()) ?></title>
<?php
include "header_under.php";
?>      
        <div id="containerHolder">
			<div id="container">
        		<div id="sidebar">
                	<ul class="sideNav">
                    	<li><a href="./videos.php">All <strong>(<?php echo(count_all_videos()) ?>)</strong></a></li>
                    	<li><a href="./videos.php?type=approved">Approved <strong>(<?php echo(count_approved_videos()) ?>)</strong></a></li>
                    	<li><a href="./videos.php?type=pending">Pending <strong>(<?php echo(count_pending_videos()) ?>)</strong></a></li>
                    </ul>
                    <!-- // .sideNav -->
                </div>    
                <!-- // #sidebar -->
                
                <!-- h2 stays for breadcrumbs -->
                <h2>Edit Video</h2>
                
                <div id="main">
				<br />
				<form action="./edit_vid.php" method="post">
					<fieldset>
					
					<input type="hidden" name="id" value="<?php echo($id) ?>">
					
					<p><label><b>Video Title</b></label>
					<input style="width:40%" name="title" maxlength="<?php echo(post_title_length()) ?>" value="<?php echo($title) ?>" type="text" class="text-long"></p>
					
					<p><label><b>Description</b></label>
						<textarea placeholder="Enter upto 170 characters of description (Optional)" maxlength="170" name="description" style="width:480px;height:50px"><?php echo($description) ?></textarea></p>
					
					<p><label><b>Permalink</b></label>
					<input style="width:40%" name="permalink" value="<?php echo($permalink) ?>" type="text" class="text-long"></p>
					
					<p><label><b>Video URL</b></label>
					<input style="width:60%" name="url" value="<?php echo($url) ?>" type="text" class="text-long">*e.g vimeo, youtube‎</p>
					
					<p><label><b>Thumbnail</b></label>
					<input style="width:70%" name="thumb" value="<?php echo(rootpath() . "/uploads/thumbs/" . $thumb) ?>" type="text" class="text-long"><a href="./edit_vid.php?id=<?php echo($id) ?>&rg=<?php echo($id) ?>" class="myButton">Regenerate</a></p>
					
					<p><label><b>Source</b></label>
					<input style="width:40%" name="source" value="<?php echo($source) ?>" type="text" class="text-long"></p>
					
					<p><label><b>Views</b></label>
					<input style="width:40%" name="views" value="<?php echo($views) ?>" type="text" class="text-long"></p>
					
					<p><label><b>Votes</b></label>
					<input style="width:40%" name="votes" value="<?php echo($votes) ?>" type="text" class="text-long"></p>
					
					<p><label><b>Tags</b></label>
					<input style="width:40%" name="tags" value="<?php echo($tags) ?>" type="text" class="text-long"></p>
					
					<p><label><b>Date Submitted</b></label>
					<input style="width:40%" name="date" value="<?php echo($date) ?>" type="text" class="text-long"></p>
					
					<p><?php if($approved) { ?><input type="radio" name="approved" value="1" checked>Enabled<input type="radio" name="approved" value="0">Disabled</p> <?php } else { ?><input type="radio" name="approved" value="1">Enabled<input type="radio" name="approved" value="0" checked>Disabled<?php } ?></p>
					
					<input type="submit" class="myButton" value="Save"/>
					<a style="padding: 9px 24px;" href="./videos.php?delete=<?php echo($id) ?>" class="myButton">Delete</a>
					</fieldset>
				</form>
					<?php
					if(isset($_POST['title']) && $error=="" && !$invalid)
					echo('<div class="alert alert-success">Video Updated Successfully</div>');
					if(isset($_POST['title']) && $error!="")
					echo('<div class="alert alert-error">' . $error . '</div>');
					if(isset($_GET['rg']) && isset($_GET['id']))
					echo('<div class="alert alert-success">Thumbnail Regenerated</div>');
					if($invalid)
					echo('<div class="alert alert-error">Invalid Video. Not Updated</div>');
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


