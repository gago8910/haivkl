<?php
include "functions.php";
if(isset($_GET['permalink']) && $_GET['permalink']!='' && is_valid_page(trim($_GET['permalink'])))
{
	$page=$_GET['permalink'];
	$sql = "select * from pages where permalink='" . $page . "' and status=1";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	$id = $fetch['id'];
	$title=$fetch['title'];
	$content=$fetch['content'];
	include "header.php";
	?>
		<title><?php echo($title . ' - ' . get_title()) ?></title>
	</head>
	<?php
	include "inc/header_under.php";
	?>         
    <div id="content-holder"> 
		<div class="nexthon-soft-box static">
            <div class="content contact-container contact-wrapper">
                <form name="submitcontact" class="modal" action="./contact.php" method="POST">
                    <h3><?php echo($title) ?></h3>
                    <div id="entries-content" style="padding-top:25px;padding-bottom:25px">
						<?php echo($content) ?>
					</div>
                     
			</div>
		</div>
	</div>
	<?php 
	include "footer.php"; 
}
else
{
	header('HTTP/1.0 404 Not Found');
	readfile(rootpath() . '/404.php');
	exit();
}
?>