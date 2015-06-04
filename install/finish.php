<?php
error_reporting(E_ERROR);
if(!isset($_SESSION)) 
	session_start();
	if(!isset($_SESSION['vmp_install_step']))
	$_SESSION['vmp_install_step']=1;
	if($_SESSION['vmp_install_step']!=4)
	header('Location: ./index.php');
	include '../config/config.php';
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
unset($_SESSION['vmp_install_step']);
session_destroy();
include "header.php";
?>
<title>Viral Media Portal - Installation Success</title>
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
                    	<li><a><strong>Database Information</strong></a></li>
                    	<li><a><strong>Website Details</strong></a></li>
                    	<li><a><strong>Admin Details</strong></a></li>
                    </ul>
                    <!-- // .sideNav -->
                </div>    
                <!-- // #sidebar -->
                
                <!-- h2 stays for breadcrumbs -->
                <h2>Installation Successful</h2>
                
                 <div id="main">
				<br />
				Installation Successful to login to admin panel follow bellow url. <br /><br />
				<a href="<?php echo(rootpath() . '/admin') ?>"><strong><?php echo(rootpath() . '/admin') ?></strong></a>
				<br /><br />Or Visit your website from <a href="<?php echo(rootpath()) ?>"><strong>here.</strong></a><br /><br />
				Remember to delete <strong>/install</strong> directory.<br /><br />
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