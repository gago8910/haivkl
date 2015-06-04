<?php
if (!isset($_SESSION)) session_start();
	if(!isset($_SESSION['admin_vmp']))
	header('Location: ./login.php');
	include "header.php";
	include "../functions.php";
	$error = "";
function add_page($permalink,$title,$content,$status,$order)
{
	if(mb_check_encoding($title,"UTF-8"))
	$title=preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $title);
	$content = mysql_real_escape_string($content);
	$sql = "INSERT INTO pages(permalink,title,content,status,display_order) VALUES('$permalink','$title','$content',$status,$order)";
	mysql_query($sql);
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
	add_page($permalink,$title,$content,$status,$order);
}
else
{
	$title = "";
	$content = "";
	$permalink = "";
	$order = 0;
	$status = 1;
}
?>
	<title>Add New Page - <?php echo(get_title()) ?></title>
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
                <h2>Add New Page</h2>
                
                <div id="main">
					<br />
					<form action="./add_page.php" method="post">
						<fieldset>
						<p><label><b>Page Name</b></label>
						<input style="width:30%" value="<?php echo($title) ?>" name="name" type="text" class="text-long"></p>
						
						<p><label><b>Permalink</b></label>
						<input style="width:30%" value="<?php echo($permalink) ?>" name="permalink" type="text" class="text-long">*Optional</p>
						
						<p><label><b>Display Order</b></label>
						<input style="width:30%" value="<?php echo($order) ?>" name="order" type="text" class="text-long">*Smaller has more Priority</p>
						
						<p><label><b>Content</b></label>
						<textarea name="content" style="width:98%;height:200px" rows="1" cols="1"><?php echo($content) ?></textarea></p>
						
						<p><label><b>Action</b></label>
					<?php if($status) { ?>
					<input type="radio" name="status" value="1" checked>Publish<input type="radio" name="status" value="0">Save</p>
					<?php } else { ?>
					<input type="radio" name="status" value="1">Publish<input type="radio" name="status" value="0" checked>Save</p>
					<?php } ?>
						
						<input type="submit" class="myButton" value="Add">
						</fieldset>
					</form>
					<?php
						if(isset($_POST['name']) && $error=="")
						echo('<div class="alert alert-success">Page Added Successfully</div>');
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