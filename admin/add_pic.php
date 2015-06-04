<?php
if (!isset($_SESSION)) session_start();
	include "../functions.php";
	if(!allow_pictures())
	{
		header('Location: ./dashboard.php');
	}
	if(!isset($_SESSION['admin_vmp']))
	{
		header('Location: ./login.php');
	}
	include "header.php";
		error_reporting(0);
		$success=false;
		$invalid=false;
		$error="";
if(isset($_POST['title']))
{
	$title=trim($_POST['title']);
	$description = strip_tags(trim($_POST['description']));
	$media="";
	if($_POST['source']!="")
		$source=trim($_POST['source']);
	else
		$source="";
	if($_POST['picurl']!="")
		$media=httpify(trim($_POST['picurl']));
	else if(trim($_FILES["myfile"]["name"])!="")
		$media=$_FILES["myfile"]["name"];
	if($_POST['picurl']!="")
		$ext = end(explode(".",strtolower(basename($_POST['picurl']))));
	else if(trim($_FILES["myfile"]["name"])!="")
		$ext = end(explode(".", strtolower($_FILES["myfile"]["name"])));
	if (!valid_file_extension($ext))
		$error .="o. Only these file types allowed .gif, .jpeg, .jpg, .png<br />";
	if(trim($_POST['title'])=="")
		$error .="o. Title must not be empty.<br />";
	if($error=="")
	{
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
		if(trim($_POST['picurl']!=""))
		{
			$post_id=add_picture_url($title,$description,$media,$source);
			if($post_id)
					{
						add_media_tag($post_id,$ids);
						$success=true;
					}
			else
					{
						$invalid=true;
					}
		}
		else if(trim($_FILES["myfile"]["name"])!="")
		{
			$post_id=add_picture_file($title,$description,$source);					
			if($post_id)
					{
						add_media_tag($post_id,$ids);
						$success=true;
					}
			else
					{
						$invalid=true;
					}
		}
	}
}
include "header.php";
function count_all_pictures()
{
	$sql = "select count(id) as total from media where type=0";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
function count_pending_pictures()
{
	$sql = "select count(id) as total from media where approved=0 and type=0";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
function count_approved_pictures()
{
	$sql = "select count(id) as total from media where approved=1 and type=0";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
?>
	<title>Add New Picture - <?php echo(get_title()) ?></title>
	<script type='text/javascript'>
		$(function(){
			$('#myfile').change(function(){
				var $myfile = $('#myfile');
				var $picurl = $('#picurl');
				$picurl.prop('disabled', $myfile.val());
				document.getElementById('picurl').value='';
			});
		});
		$(function(){
			$('#picurl').keyup(function(){
				var $myfile = $('#myfile');
				var $picurl = $('#picurl');
				$myfile.prop('disabled', $picurl.val());
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
                    	<li><a href="./pictures.php">All <strong>(<?php echo(count_all_pictures()) ?>)</strong></a></li>
                    	<li><a href="./pictures.php?type=approved">Approved <strong>(<?php echo(count_approved_pictures()) ?>)</strong></a></li>
                    	<li><a href="./pictures.php?type=pending">Pending <strong>(<?php echo(count_pending_pictures()) ?>)</strong></a></li>
                    </ul>
                    <!-- // .sideNav -->
                </div>    
                <!-- // #sidebar -->
                
                <!-- h2 stays for breadcrumbs -->
                <h2>Add New Picture or GIF</h2>
                
                <div id="main">
					<br />
					<form action="./add_pic.php" method="post" enctype="multipart/form-data">
						<fieldset>
						<p><label><b>Picture Title</b></label>
						<input style="width:35%" name="title" maxlength="<?php echo(post_title_length()) ?>" value="<?php $_POST['title'] ?>" type="text" class="text-long"></p>
						
						<p><label><b>Description</b></label>
						<textarea placeholder="Enter upto 170 characters of description (Optional)" maxlength="170" name="description" style="width:480px;height:50px"><?php $_POST['description'] ?></textarea></p>
						
						<p><label><b>Upload Picture</b></label>
						<input type="file" id="myfile" name="myfile"/>*Upload from your computer</p>
						
						<p><label><b>Get From URL</b></label>
						<input style="width:40%" id="picurl" value="<?php $_POST['picurl'] ?>" name="picurl" type="text" class="text-long">*Or get from URL</p>
						
						<p><label><b>Source</b></label>
						<input style="width:20%" value="<?php $_POST['source'] ?>" name="source" type="text" class="text-long">*Source if any</p>
						
						<p><label><b>Tags</b></label>
						<input style="width:35%" value="<?php $_POST['tags'] ?>" name="tags" type="text" class="text-long">*Separated By Comma (Optional)</p>
						
						
						<input type="submit" class="myButton" value="Add">
						</fieldset>
					</form>
					<?php
						if(isset($_POST['title']) && $error=="" && $success && !$invalid)
						echo('<div class="alert alert-success">Picture Added Successfully</div>');
						if(isset($_POST['title']) && $error!="")
						echo('<div class="alert alert-error">' . $error . '</div>');
						if($invalid)
						echo('<div class="alert alert-error">Invalid Picture. Not Added</div>');
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