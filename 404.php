<?php
	include "functions.php";
	include "header.php";
?>
	<title>404 Page Not Found - <?php echo(get_title()) ?></title>
	<meta name="description" content=" Looks like you have taken a wrong turn :(. The page you are looking for doesn't exist">
	</head>
	<?php
	include "inc/header_under.php";
	?>     
    <div id="content-holder"> 
		<div id="content" >  
			<div class="checkbacksoon">
				<p>
					<a href="./index.php">
						<span class="go3d">4</span>
						<span class="go3d">0</span>
						<span class="go3d">4</span>
					</a>
				</p>        
				<p class="error" style="padding-bottom: 100px;">
					Looks like you have taken a wrong turn :( The page you are looking for doesn't exist
				</p>		
			</div>
		</div>
	</div>
<?php include "footer.php"; ?>
