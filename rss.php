<?php
	include "functions.php";
	if(!rss_enable())
	header("Location: " . rootpath());
	header("Content-type: text/xml");
	
	if(isset($_GET['mtype']) && trim($_GET['mtype'])!="" && is_valid_type(trim($_GET['mtype'])))
	{
		if(!rss_cat_enable())
		header("Location: " . rootpath());
		echo '<?xml version="1.0" encoding="UTF-8" ?>
	<feed xmlns:atom="http://www.w3.org/2005/Atom" xml:lang="en" xml:base="'.rootpath().'" xmlns="http://www.w3.org/2005/Atom">';
		$type=trim($_GET['mtype']);
		$media_type=0;
		if($type=="pictures") {
			$media_type=0;
		}
		if($type=="videos") {
			$media_type=1;
		}
		if($type=="gifs") {
			$media_type=2;
		}	
		$query ="SELECT * FROM `media` WHERE type='" . $media_type . "' AND approved=1 ORDER BY orderid DESC LIMIT " . rss_limit();
		echo "<title>" . get_title() . " " . ucfirst($type) . " RSS Feeds</title>";
	}
	else if(isset($_GET['tag']) && trim($_GET['tag'])!="" && is_valid_tag(trim($_GET['tag'])))
	{
		if(!rss_tag_enable())
		header("Location: " . rootpath());
		echo '<?xml version="1.0" encoding="UTF-8" ?>
	<feed xmlns:atom="http://www.w3.org/2005/Atom" xml:lang="en" xml:base="'.rootpath().'" xmlns="http://www.w3.org/2005/Atom">';
		$tag=trim($_GET['tag']);
		$tagsql="select `tag_id` from `tags` where `tag`='".$tag."'";
		$tagquery=mysql_query($tagsql);
		$tagfetch=mysql_fetch_array($tagquery);
		$sqll="SELECT id FROM `media_tags` where `tag_id`='".$tagfetch['tag_id']."'";
		if(isset($_GET['type']) && is_valid_type(trim($_GET['type']))) {
		$type=trim($_GET['type']);
		$media_type=0;
		if($type=="pictures") {
			$media_type=0;
		}
		if($type=="videos") {
			$media_type=1;
		}
		if($type=="gifs") {
			$media_type=2;
		}	
		$query ="SELECT * FROM `media` WHERE type='" . $media_type . "' AND id IN (" . $sqll . ") AND approved=1 ORDER BY orderid DESC LIMIT " . rss_limit();
		echo "\n<title>" . get_title() . " " . ucfirst($type) . " Tagged with " . $tag . " RSS Feeds</title>";
		}
		else {
		$query ="SELECT * FROM `media` WHERE id IN (" . $sqll . ") AND approved=1 ORDER BY orderid DESC LIMIT " . rss_limit();
		echo "\n<title>" . get_title() . " Posts Tagged with " . $tag . " RSS Feeds</title>";
		}
	}
	else
	{
		echo '<?xml version="1.0" encoding="UTF-8" ?>
		<feed xmlns:atom="http://www.w3.org/2005/Atom" xml:lang="en" xml:base="'.rootpath().'" xmlns="http://www.w3.org/2005/Atom">';
		$query ="SELECT * FROM `media` WHERE approved=1 ORDER BY orderid DESC LIMIT " . rss_limit();
		echo "\n<title>" . get_title() . " RSS Feeds</title>";
	}
	$result = mysql_query($query) or die(mysql_error()); 
	echo "\n<updated>" . date(DATE_ATOM) . "</updated>";  
		$perma_type="";
		while($array= mysql_fetch_array($result)){
		 $id   = $array['id'];         
		$title = $array['title'];
		$link  = $array['permalink'];
		$old_date = $array['date'];
		if(mb_check_encoding($title,"UTF-8"))
		{
		$title=unicode_escape_sequences($title);
		}
		if(mb_check_encoding($link,"UTF-8"))
		{
		$link=unicode_escape_sequences($link);
		} 
		if($array['type']==0) {
		$perma_type="picture";
		$thumb = $array['file'];
		}
		else if($array['type']==1) {
		$perma_type="video";
		$thumb = $array['thumb'];
		}
		else if($array['type']==2) {
		$perma_type="gif";
		$thumb = $array['file'];
		}
		$image = "<a href='" . rootpath() . "/" . $perma_type . "/" . $link . "'><img src='". rootpath() . "/uploads/thumbs/" . $thumb . "' /></a><br />";
		$description = strip_tags($array['description']);
		if(mb_check_encoding($description,"UTF-8"))
		{
		$description=unicode_escape_sequences($description);
		} 
		$description = htmlentities($image) . substr(preg_replace('/\n+|\t+|\s+/', ' ', $description) ,0,rss_description());
		$pub = $new_date = date(DATE_ATOM , strtotime($old_date));
		echo "
		<entry>
		<title>$title - " . ucfirst($perma_type) . "</title>
		<link rel='alternate' type='text/html' href='" . rootpath() . "/" . $perma_type . "/" . $link . "'/>
		<content type='html'>$description</content>
		<updated>$pub</updated>
		</entry>
		"; 
		}
		echo "</feed>";
		?>