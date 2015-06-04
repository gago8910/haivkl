<?php
include "config/config.php";
	function rootpath()
	{
		$sql = "select rootpath from settings";
		$query = mysql_query($sql);
		$fetch = mysql_fetch_array($query);
		if($fetch['rootpath']!="")
		{
			return $fetch['rootpath'];
		}
		else
		{
			$server = $_SERVER['SERVER_NAME'];
			$root = 'http://' . $server . dirname($_SERVER['SCRIPT_NAME']);
			if(substr($root, -1)=="/")
			return ('http://' . $server);
			else
			return $root;
		}
	}
	function random()
	{
		$sql = "select type,permalink from media order by rand() limit 1";
		$query = mysql_query($sql);
		$fetch = mysql_fetch_array($query);
		if($fetch['type']==0)
		$type = "picture";
		else if($fetch['type']==1)
		$type = "video";
		else if($fetch['type']==2)
		$type = "gif";
		$final = rootpath() . '/' . $type . '/' . $fetch['permalink'];
		return $final;
	}
	header('Location: ' . random());
?>