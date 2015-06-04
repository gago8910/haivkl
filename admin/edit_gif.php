<?php
if (!isset($_SESSION)) session_start();

if(!isset($_SESSION['admin_vmp']))
	header('Location: ./login.php');

include "../functions.php";

error_reporting(0);
$error="";
$invalid=false;

function valid_gif_extension($ext) {
	$allowedExts = array("gif");
	
if (!in_array($ext, $allowedExts))
	return false;
	
	return true;
}

if(!(isset($_POST['title']) || isset($_GET['id'])))
	header('Location: ./animated-gifs.php');

function update_gif($id,$title,$description,$permalink,$file,$thumb,$source,$views,$votes,$date,$approved) 
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
		
	$update_sql = "update media set title='" . $title . "',description='" . $description . "',permalink='" . $permalink . "', file='" . $file . "',thumb='" . $thumb . "',source='" . $source . "',views=" . $views . ",votes=" . $votes . ",date='" . date('Y-m-d H:i:s') . "', orderid=" . $order . ",approved=" . $approved . " where id=" . $id;
	
	mysql_query($update_sql);
	
}
if(isset($_GET['rg']) && isset($_GET['id'])) {

	$sql="select file from media where id=" . $_GET['id'] . " and type=2";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	
	unlink("../uploads/thumbs/" . $fetch['file']);
	regen_gif_thumb($_GET['id'],rootpath() . "/uploads/" . $fetch['file'],false);
	
}
if(isset($_POST['title'])) {

	$id = $_POST['id'];
	$sql="select title,file,permalink from media where id=" . $id . " and type=2";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	
	$id = $_POST['id'];
	$title = trim($_POST['title']);
	$description = strip_tags(trim($_POST['description']));
if($title!=$fetch['title'] && $_POST['permalink']==$fetch['permalink'])
	$permalink=gen_permalink($title);
else
	$permalink = trim($_POST['permalink']);
	
	$source = trim($_POST['source']);
	$views = $_POST['views'];
	$votes = $_POST['votes'];
	$date = $_POST['date'];
	$approved = $_POST['approved'];
	
	if(trim($_FILES["myfile"]["name"])!="")
		$file = trim($_FILES["myfile"]["name"]);
	else
		$file = strtolower(basename($_POST['file']));
		
	$thumb = $file;
	
if($file!=$fetch['file'] && valid_gif_extension(end(explode(".",$file)))) 
{

	unlink("../uploads/" . $fetch['file']);
	unlink("../uploads/thumbs/" . $fetch['file']);
	
	if(trim($_FILES["myfile"]["name"])!="") 
	{

		$ext = end(explode(".",strtolower(basename($_FILES["myfile"]["name"]))));
		$temp_name = rand(100,1000) . "." . $ext;
		$file = md5($_POST['id']) . "." . $ext;
		move_uploaded_file($_FILES["myfile"]["tmp_name"],"../uploads/" . $temp_name);
		$temp_file = rootpath() . "/uploads/" . $temp_name;
		
		if(isGIF($temp_file)) 
		{

			unlink("../uploads/" . $fetch['file']);
			unlink("../uploads/thumbs/" . $fetch['file']);
			rename("../uploads/" . $temp_name , "../uploads/" . $file);

			$file = rootpath() . "/uploads/" . $file;
			$file = regen_gif_thumb($_POST['id'],$file,true);
			$thumb = $file;

		}
		else 
		{
			unlink("../uploads/" . $temp_name);
			$file = $fetch['file'];
			$thumb = $file;
			$invalid=true;	
		}
	}
	else 
	{
		$file = httpify(trim($_POST['file']));
		
		if(isGIF($file)) {
			unlink("../uploads/" . $fetch['file']);
			unlink("../uploads/thumbs/" . $fetch['file']);
			$file = regen_gif_thumb($_POST['id'],$file,true);
			$thumb = $file;
		}
		else {
			$file = $fetch['file'];
			$thumb = $file;
			$invalid=true;
		}
	}
}
else 
{
	$file = strtolower(basename($fetch['file']));
	$thumb = $file;
}
		if(trim($title)=="")
			$error .="o. Title must not be empty.<br />";
		if(!valid_gif_extension(end(explode(".",$file))))
			$error .="o. Image must be In GIF Format.<br />";
			$ext = end(explode(".",strtolower(basename($thumb))));
		if(!valid_file_extension($ext))
			$error .="o. Invalid Thumbnail.<br />";
		if(!is_numeric($views))
			$error .="o. Views must be numeric.<br />";
		if(!is_numeric($votes))
			$error .="o. Votes must be numeric.<br />";
		if(!is_date($date))
			$error .="o. Invalid date format.<br />";
		if($error=="" && !$file==0)
		{
			delete_tags($id);
			update_gif($id,$title,$description,$permalink,$file,$thumb,$source,$views,$votes,$date,$approved);
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
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
	$sql="select * from media where id=" . $_GET['id'] . " and type=2";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	
	if($fetch['id']) 
	{
		$id = $fetch['id'];
		$title = $fetch['title'];
		$description = $fetch['description'];
		$permalink = $fetch['permalink'];
		$file = $fetch['file'];
		$thumb = $fetch['file'];
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

function count_all_gifs() {
	$sql = "select count(id) as total from media where type=2";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
function count_pending_gifs() {
	$sql = "select count(id) as total from media where approved=0 and type=2";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
function count_approved_gifs() {
	$sql = "select count(id) as total from media where approved=1 and type=2";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
?>
<title>Edit Animated GIF - <?php echo(get_title()) ?></title>
<?php
include "header_under.php";
?>      
        <div id="containerHolder">
			<div id="container">
        		<div id="sidebar">
                	<ul class="sideNav">
                    	<li><a href="./animated-gifs.php">All <strong>(<?php echo(count_all_gifs()) ?>)</strong></a></li>
                    	<li><a href="./animated-gifs.php?type=approved">Approved <strong>(<?php echo(count_approved_gifs()) ?>)</strong></a></li>
                    	<li><a href="./animated-gifs.php?type=pending">Pending <strong>(<?php echo(count_pending_gifs()) ?>)</strong></a></li>
                    </ul>
                    <!-- // .sideNav -->
                </div>    
                <!-- // #sidebar -->
                
                <!-- h2 stays for breadcrumbs -->
                <h2>Edit Animated GIF</h2>
                
                <div id="main">
				<br />
				<form action="./edit_gif.php" method="post" enctype="multipart/form-data">
					<fieldset>
					
					<input type="hidden" name="id" value="<?php echo($id) ?>">
					
					<p><label><b>GIF Title</b></label>
					<input style="width:40%" name="title" maxlength="<?php echo(post_title_length()) ?>" value="<?php echo($title) ?>" type="text" class="text-long"></p>
					
					<p><label><b>Description</b></label>
						<textarea placeholder="Enter upto 170 characters of description (Optional)" maxlength="170" name="description" style="width:480px;height:50px"><?php echo($description) ?></textarea></p>
					
					<p><label><b>Permalink</b></label>
					<input style="width:40%" name="permalink" value="<?php echo($permalink) ?>" type="text" class="text-long"></p>
					
					<p><label><b>GIF URL</b></label>
					<input style="width:60%" name="file" value="<?php echo(rootpath() . "/uploads/" . $file) ?>" type="text" class="text-long">‎</p>
					
					<p><label><b>Reupload GIF</b></label>
					<input type="file" id="myfile" name="myfile"/>*Upload from your computer</p>
					
					<p><label><b>Thumbnail</b></label>
					<input style="width:70%" name="thumb" value="<?php echo(rootpath() . "/uploads/thumbs/" . $thumb) ?>" type="text" class="text-long"><a href="./edit_gif.php?id=<?php echo($id) ?>&rg=<?php echo($id) ?>" class="myButton">Regenerate</a></p>
					
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
					<a style="padding: 9px 24px;" href="./animated-gifs.php?delete=<?php echo($id) ?>" class="myButton">Delete</a>
					</fieldset>
					</form>
					<?php
					if(isset($_POST['title']) && $error=="" && !$invalid)
						echo('<div class="alert alert-success">GIF Updated Successfully</div>');
					if(isset($_POST['title']) && $error!="")
						echo('<div class="alert alert-error">' . $error . '</div>');
					if(isset($_GET['rg']) && isset($_GET['id']))
						echo('<div class="alert alert-success">Thumbnail Regenerated</div>');
					if($invalid)
						echo('<div class="alert alert-error">Invalid GIF. Not Updated</div>');
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


