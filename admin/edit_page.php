<?php
if (!isset($_SESSION)) session_start();
	if(!isset($_SESSION['admin_vmp']))
	header('Location: ./login.php');
	if(isset($_GET['id']) && is_numeric($_GET['id']))
	$_SESSION['page_id'] = $_GET['id'];
	if(!$_SESSION['page_id'])
	header('Location: ./pages.php');
	else
	$id = $_SESSION['page_id'];
	include "header.php";
	include "../functions.php";
	$error = "";

function update_page($id,$permalink,$title,$content,$status,$order)
{
	if(mb_check_encoding($title,"UTF-8"))
	$title=preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $title);
	$content = mysql_real_escape_string($content);
	$sql = "UPDATE pages set permalink='$permalink',title='$title',content='$content',status=$status,display_order=$order where id=$id";
	mysql_query($sql) or die(mysql_error());
}
function total_pages_count()
{
	$sql = "select count(id) as total_pages from pages";
	$query = mysql_query($sql);
	$fecth = mysql_fetch_array($query);
	return $fecth['total_pages'];
}
function published_pages_count()
{
	$sql = "select count(id) as published_pages from pages where status=1";
	$query = mysql_query($sql);
	$fecth = mysql_fetch_array($query);
	return $fecth['published_pages'];
}
function pending_pages_count()
{
	$sql = "select count(id) as pending_pages from pages where status=0";
	$query = mysql_query($sql);
	$fecth = mysql_fetch_array($query);
	return $fecth['pending_pages'];
}
if(isset($_POST['name']))
{
	$title = $_POST["name"];
	$content = $_POST["content"];
	if(isset($_GET["permalink"]) && trim($_GET["permalink"])!="")
	$permalink = gen_permalink($_POST["permalink"]);
	else
	$permalink = gen_permalink($_POST["name"]);
	$order = $_POST["order"];
	if($order=="")
	$order=0;
	$status = $_POST["status"];
	if(strlen($title)>70)
	$error .="o. Page Name Must Not Be Greater than 70 Characters<br />";
	else if(!is_alpha($title))
	$error .="o. Page Name Must Be Alphanumeric<br />";
	if(!is_numeric($order))
	$error .="o. Order must be a Valid Number<br />";
	if($error=="")
	update_page($id,$permalink,$title,$content,$status,$order);
}
if($id)
{
	$sql = "select * from pages where id=" . $id;
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	$title = $fetch['title'];
	$content =$fetch['content'];
	$permalink = $fetch['permalink'];
	$order = $fetch['display_order'];
	$status = $fetch['status'];
}
else if(isset($_POST['name']))
{
	$title = $_POST['name'];
	$content = $_POST['content'];
	$permalink = $_POST['permalink'];
	$order = $_POST['order'];
	$status = $_POST['status'];
}
?>
<title>Edit Page - <?php echo($title) ?></title>
<?php
include "header_under.php";
?>      
        <div id="containerHolder">
			<div id="container">
        		<div id="sidebar">
                	<ul class="sideNav">
                    	<li><a href="pages.php">All <strong>(<?php echo(total_pages_count()) ?>)</strong></a></li>
                    	<li><a href="./pages.php?type=published">Published <strong>(<?php echo(published_pages_count()) ?>)</strong></a></li>
                    	<li><a href="./pages.php?type=pending">Pending Publish <strong>(<?php echo(pending_pages_count()) ?>)</strong></a></li>
                    </ul>
                    <!-- // .sideNav -->
                </div>    
                <!-- // #sidebar -->
                
                <!-- h2 stays for breadcrumbs -->
                <h2>Edit Page</h2>
                
                <div id="main">
				<br />
				<form action="./edit_page.php" method="post">
					<fieldset>
					<p><label><b>Page Name</b></label>
					<input style="width:30%" name="name" type="text" value="<?php echo($title) ?>" class="text-long"></p>
					
					<p><label><b>Permalink</b></label>
					<input style="width:30%" name="permalink" type="text" value="<?php echo($permalink) ?>" class="text-long">*Optional</p>
					
					<p><label><b>Display Order</b></label>
					<input style="width:30%" name="order" type="text" value="<?php echo($order) ?>" class="text-long">*Smaller has more Priority</p>
					
					<p><label><b>Content</b></label>
					<textarea name="content" style="width:98%;height:200px" rows="1" cols="1"><?php echo($content) ?></textarea></p>
					
					<p><label><b>Action</b></label>
					<?php if($status) { ?>
					<input type="radio" name="status" value="1" checked>Publish<input type="radio" name="status" value="0">Save</p>
					<?php } else { ?>
					<input type="radio" name="status" value="1">Publish<input type="radio" name="status" value="0" checked>Save</p>
					<?php } ?>
					<input type="submit" class="myButton" value="Update">
					</fieldset>
				</form>
					<?php
					if(isset($_POST['name']) && $error=="")
					echo('<div class="alert alert-success">Page Modified Successfully</div>');
					else if(isset($_POST['name']) && $error!="")
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


