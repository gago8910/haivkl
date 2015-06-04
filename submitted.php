<?php
	include "functions.php";
	include "header.php";
?>
	<title><?php echo(strip_tags($_GET['f'])) ?> Submitted Successfully - <?php echo(get_title()) ?></title>
	<meta name="description" content=" Looks like you have taken a wrong turn :(. The page you are looking for doesn't exist">
	</head>
	<?php
		include "inc/header_under.php";
	?>     
    <div id="content-holder"> 
		<div id="content" >  
			<div class="submitted">
				<p>
					<a href="./submit.php">
					<span class="go3d">S</span>
					<span class="go3d">u</span>
					<span class="go3d">b</span>
					<span class="go3d">m</span>
					<span class="go3d">i</span>
					<span class="go3d">t</span>
					<span class="go3d">t</span>
					<span class="go3d">e</span>
					<span class="go3d">d</span>
					</a>
				</p>
			
				<p class="error" style="padding-bottom: 100px;">
					<?php echo(strip_tags($_GET['f'])) ?> Submitted Successfully. <a href="./submit.php">Click Here</a> to Submit more.
				</p>		
			</div>
		</div>
	</div>
<?php include "footer.php"; ?>
