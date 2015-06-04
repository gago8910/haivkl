<?php
if (!isset($_SESSION)) session_start();
	if(!isset($_SESSION['admin_vmp']))
	header('Location: ./login.php');
	include "header.php";
	include "../functions.php";
	error_reporting(0);
	$regenerated = false;

function update_sitemap_settings($categories,$pages,$contact_form,$posts,$tags,$output_path)
{
$update_query = "UPDATE sitemaps SET cats_status='". mysql_real_escape_string($categories)."',pages_status='".mysql_real_escape_string($pages)."',cont_form_status='".mysql_real_escape_string($contact_form) ."',posts_status='" . mysql_real_escape_string($posts) ."',tags_status='" . $tags . "',output_path='".mysql_real_escape_string($output_path)."'";
mysql_query($update_query);
}

if(isset($_GET['rg']) && trim($_GET['rg'])=="true")
{
function gen_products_sitemap()
{
$sitemap = "";
$match = "select * from media order by orderid DESC"; 
$qry = mysql_query($match);
while($fetch=mysql_fetch_array($qry))
	{
	if($fetch['type']==0)
		$type = "picture";
	else if($fetch['type']==1)
		$type = "video";
	else if($fetch['type']==2)
		$type = "gif";
		
	$sitemap .='<url>' . PHP_EOL;
	$sitemap .="<loc>" . rootpath() . "/" . $type . "/" . $fetch['permalink'] . "</loc>" . PHP_EOL;
	$sitemap .="<priority>0.8</priority>" . PHP_EOL;
	$sitemap .='</url>' . PHP_EOL;
	}
return $sitemap;
}

function gen_cats_sitemap()
{
	$sitemap .='<url>' . PHP_EOL;
	$sitemap .="<loc>" . rootpath() . "/pictures</loc>" . PHP_EOL;
	$sitemap .="<priority>0.9</priority>" . PHP_EOL;
	$sitemap .='</url>' . PHP_EOL;
	
	$sitemap .='<url>' . PHP_EOL;
	$sitemap .="<loc>" . rootpath() . "/videos</loc>" . PHP_EOL;
	$sitemap .="<priority>0.9</priority>" . PHP_EOL;
	$sitemap .='</url>' . PHP_EOL;
	
	$sitemap .='<url>' . PHP_EOL;
	$sitemap .="<loc>" . rootpath() . "/animated-gifs</loc>" . PHP_EOL;
	$sitemap .="<priority>0.9</priority>" . PHP_EOL;
	$sitemap .='</url>' . PHP_EOL;
	return $sitemap;
}

function gen_pages_sitemap()
{
	$sitemap = "";
	$match = "select * from pages order by id DESC"; 
	$qry = mysql_query($match);
	while($array=mysql_fetch_array($qry))
		{
			$permalink = $array['permalink'];
			if(mb_check_encoding($permalink,"UTF-8"))
			$permalink=preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $permalink);
			$sitemap .='<url>' . PHP_EOL;
			$sitemap .="<loc>" . rootpath() . "/page/" . $permalink . "</loc>" . PHP_EOL;
			$sitemap .="<priority>0.6</priority>" . PHP_EOL;
			$sitemap .='</url>' . PHP_EOL;
		}
	return $sitemap;
}

function gen_tags_sitemap()
{
	$sitemap = "";
	$match = "select DISTINCT tag from tags order by tag_id DESC"; 
	$qry = mysql_query($match);
	while($array=mysql_fetch_array($qry))
		{
			$tag = $array['tag'];
			if(mb_check_encoding($tag,"UTF-8"))
			$tag=preg_replace('/^[\pZ\pC]+|[\pZ\pC]+$/u', '', $tag);
			$sitemap .='<url>' . PHP_EOL;
			$sitemap .="<loc>" . rootpath() . "/tags/" . $tag . "</loc>" . PHP_EOL;
			$sitemap .="<priority>0.5</priority>" . PHP_EOL;
			$sitemap .='</url>' . PHP_EOL;
		}
	return $sitemap;
}

function gen_root_sitemap()
{
	$sitemap = "";
	$sitemap .='<url>' . PHP_EOL;
	$sitemap .="<loc>" . rootpath() . "/</loc>" . PHP_EOL;
	$sitemap .="<priority>1.0</priority>" . PHP_EOL;
	$sitemap .='</url>' . PHP_EOL;
	return $sitemap;
}

function gen_contact_sitemap()
{
	$sitemap = "";
	$sitemap .='<url>' . PHP_EOL;
	$sitemap .="<loc>" . rootpath() . "/contact.php</loc>" . PHP_EOL;
	$sitemap .="<priority>0.7</priority>" . PHP_EOL;
	$sitemap .='</url>' . PHP_EOL;
	return $sitemap;
}

$sitemaps = "";
$filename = sitemap_output_path();
$sitemaps .= '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL;
$sitemaps .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
$sitemaps .= gen_root_sitemap();
if(sitemap_cats_status())
$sitemaps .= gen_cats_sitemap();
if(sitemap_posts_status())
$sitemaps .= gen_products_sitemap();
if(sitemap_tags_status())
$sitemaps .= gen_tags_sitemap();
if(sitemap_cont_form_status())
$sitemaps .= gen_contact_sitemap();
if(sitemap_pages_status())
$sitemaps .= gen_pages_sitemap();
$sitemaps .= '</urlset>';
$file = fopen('../' . $filename,"w+");
fwrite($file,$sitemaps);
fclose($file);
$sql = "update sitemaps set last_modified='" . date('Y-m-d H:i:s') . "'";
mysql_query($sql);
$regenerated = true;
$regen_msg = "Sitemap Generated and Saved in " . rootpath() . "/" . $filename;
}

if(isset($_POST['categories']))
{
$categories = $_POST["categories"];
$pages = $_POST["pages"];
$contact_form = $_POST["contact_form"];
$posts = $_POST["posts"];
$tags = $_POST["tags"];
$output_path = $_POST["output_path"];
update_sitemap_settings($categories,$pages,$contact_form,$posts,$tags,$output_path);
}	

?>
<title>Sitemaps Settings - <?php echo(get_title()) ?></title>
	
<?php
include "header_under.php";
?>      
        <div id="containerHolder">
			<div id="container">
        		<div id="sidebar">
                	<ul class="sideNav">
                    	<li><a href="./settings.php">Website</a></li>
						<li><a href="./thumbnails.php">Thumbnails</a></li>
                    	<li><a href="./watermark.php">Watermark</a></li>
                    	<li><a href="./media.php">Media</a></li>
						<li><a href="./social.php">Social Media</a></li>
						<li><a href="./ads.php">Ad Management</a></li>
						<li><a href="./admin.php">Admin Settings</a></li>
						<li><a href="./analytics.php">Analytics (Stats Tracking)</a></li>
						<li><a href="./rss.php" >RSS Settings</a></li>
						<li><a href="./sitemap.php" class="active">Sitemap Settings</a></li>
						<li><a href="./comments.php">Comments Setting</a></li>
						
                    </ul>
                    <!-- // .sideNav -->
                </div>    
                <!-- // #sidebar -->
                
                <!-- h2 stays for breadcrumbs -->
                <h2>Sitemaps Settings</h2>
                
                <div id="main">
				<br />
				<form action="./sitemap.php" method="post">
					<fieldset>
					<p><label><b>Posts</b></label>
						<?php if(sitemap_posts_status()) { ?>
							<input type="radio" name="posts" value="1" checked>Include
							<input type="radio" name="posts" value="0">Exclude <?php } 
							else { ?><input type="radio" name="posts" value="1">Include
							<input type="radio" name="posts" value="0" checked>Exclude<?php } 
						?>
					</p>
					<p><label><b>Category</b></label>
						<?php if(sitemap_cats_status()) { ?>
							<input type="radio" name="categories" value="1" checked>Include
							<input type="radio" name="categories" value="0">Exclude <?php } 
							else { ?><input type="radio" name="categories" value="1">Include
							<input type="radio" name="categories" value="0" checked>Exclude<?php } 
						?>
					</p>
					<p><label><b>Tags</b></label>
						<?php if(sitemap_tags_status()) { ?>
							<input type="radio" name="tags" value="1" checked>Include
							<input type="radio" name="tags" value="0">Exclude <?php } 
							else { ?><input type="radio" name="tags" value="1">Include
							<input type="radio" name="tags" value="0" checked>Exclude<?php } 
						?>
					</p> 
					<p><label><b>Pages</b></label>
						<?php if(sitemap_pages_status()) { ?>
							<input type="radio" name="pages" value="1" checked>Include
							<input type="radio" name="pages" value="0">Exclude <?php } 
							else { ?><input type="radio" name="pages" value="1">Include
							<input type="radio" name="pages" value="0" checked>Exclude<?php } 
						?>
					</p> 
					<p><label><b>Contact Form</b></label>
						<?php if(sitemap_cont_form_status()) { ?>
							<input type="radio" name="contact_form" value="1" checked>Include
							<input type="radio" name="contact_form" value="0">Exclude <?php } 
							else { ?><input type="radio" name="contact_form" value="1">Include
							<input type="radio" name="contact_form" value="0" checked>Exclude<?php } 
						?>
					</p> 
					<p>
						<label><b>File Name</b></label>
							<input style="width:65%" name="output_path" value="<?php echo(sitemap_output_path()); ?>" type="text" class="text-long">
					</p>
					
					<input type="submit" class="myButton" value="Submit">
					<a href="./sitemap.php?rg=true" class="myButton" style="padding: 9px 24px;">Generate</a>
					</fieldset>
				</form>
					<p><label><b>Last Generated</b></label></p><br />
					<div class="alert alert-success"><?php echo(date('M d, Y. H:i:s', strtotime(sitemap_last_modified()))); ?></div>
					<?php
					if(isset($_POST['categories']) && $error=="")
					echo('<div class="alert alert-success">Settings Updated Successfully</div>');
					else if(isset($_GET['rg']) && trim($_GET['rg'])=="true" && $regenerated)
					echo '<div class="alert alert-success">' . $regen_msg . '</div>';
					else if(isset($_POST['categories']) && $error!="")
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


