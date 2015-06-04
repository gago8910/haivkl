</head>
<body>
	<div id="wrapper">
        
        <!-- You can name the links with lowercase, they will be transformed to uppercase by CSS, we prefered to name them with uppercase to have the same effect with disabled stylesheet -->
        <ul id="mainNav">
        	<li><a href="./" class="active">DASHBOARD</a></li> <!-- Use the "active" class for the active menu item  -->
			<?php if(allow_pictures()) { ?>
        	<li><a href="./pictures.php">Pictures</a></li>
			<?php } if(allow_videos()) { ?>
        	<li><a href="./videos.php">Videos</a></li>
			<?php }	if(allow_gifs()) { ?>
        	<li><a href="./animated-gifs.php">Animated Gifs</a></li>
			<?php } ?>
			<li><a href="./pages.php">Pages</a></li>
			<li><a href="./settings.php">Settings</a></li>
        	<li class="logout"><a href="./logout.php">LOGOUT</a></li>
        </ul>
        <!-- // #end mainNav -->
		