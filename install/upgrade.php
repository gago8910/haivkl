<?php
	error_reporting(E_ERROR);
	include '../config/config.php';
	$success = false;
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
		if($link && $check)
		{
		$sql_filename = "upgrade.sql";
		$sql_contents = file_get_contents($path.$sql_filename);
		$sql_contents = explode(";", $sql_contents);
		foreach($sql_contents as $query)
		{
			$result = mysql_query($query);
		}
		$success = true;
		}
		else
		{
		$success=false;
		}
include "header.php";
?>
<title>Viral Media Portal - Upgrade</title>
	<script type='text/javascript'>
		$(window).load(function(){
		$(document).ready(function () {
			$('#selectall').click(function () {
				$('.selectedId').prop('checked', this.checked);
			});

			$('.selectedId').change(function () {
				var check = ($('.selectedId').filter(":checked").length == $('.selectedId').length);
				$('#selectall').prop("checked", check);
			});
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
                    	<li><a><strong>Upgrade V 1.3</strong></a></li>
                    </ul>
                    <!-- // .sideNav -->
                </div>    
                <!-- // #sidebar -->
                
                <!-- h2 stays for breadcrumbs -->
				<?php if($success)  {?>
                <h2>Upgrade Successful</h2>
				<?php } else { ?>
                <h2>Upgrade Failed</h2>
				<?php } ?>
                 <div id="main">
				<br />
				<?php if($success)  {?>
				Upgrade Successful please remove install directory from server to login to admin panel follow bellow url. <br /><br />
				<a href="<?php echo(rootpath() . '/admin') ?>"><strong><?php echo(rootpath() . '/admin') ?></strong></a>
				<br /><br />Or Visit your website from <a href="<?php echo(rootpath()) ?>"><strong>here.</strong></a><br /><br />
				<?php } else { ?>
				Upgrade Failed please make sure you have correct database details in config/config.php then try again.
				<?php } ?>
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