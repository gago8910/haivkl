<?php
error_reporting(E_ERROR);
if(!isset($_SESSION)) 
	session_start();
	if(!isset($_SESSION['vmp_install_step']))
	$_SESSION['vmp_install_step']=1;
	$host = "";
	$username = "";
	$password = "";
	$database = "";
	$error = "";
if(isset($_POST['host']) && $_POST['host']!="")
{
	$host = $_POST['host'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$database = $_POST['database'];
	$connection = mysql_connect($host, $username, $password);
	if(!mysql_select_db($database,$connection))
	$error = "Invalid Details : Unable to Connect To Database";
	if($error=="")
	{
		$connection = mysql_connect($host, $username, $password);
		mysql_select_db($database,$connection);
		$sql_filename = "db.sql";
		$sql_contents = file_get_contents($path.$sql_filename);
		$sql_contents = explode(";", $sql_contents);
		foreach($sql_contents as $query)
		{
			$result = mysql_query($query);
		}
			$config_sample_path = '../config/config.sample';
			$data = file_get_contents($config_sample_path);
			$data = str_replace("dbname",$database,$data);
			$data = str_replace("dbhost",$host,$data);
			$data = str_replace("dbuser",$username,$data);
			$data = str_replace("dbpass",$password,$data);
			$config_path = '../config/config.php';
			file_put_contents($config_path, $data);
			$_SESSION['vmp_install_step']=2;
	}
}
include "header.php";
?>
<title>Viral Media Portal - Database Information</title>
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
                    	<li><a class="active"><strong>Database Information</strong></a></li>
                    	<li><a><strong>Website Details</strong></a></li>
                    	<li><a><strong>Admin Details</strong></a></li>
                    </ul>
                    <!-- // .sideNav -->
                </div>    
                <!-- // #sidebar -->
                
                <!-- h2 stays for breadcrumbs -->
                <h2>Database Information</h2>
                
                 <div id="main">
				<br />
				<form action="./index.php" method="post" enctype="multipart/form-data">
					<fieldset>
					<p><label><b>Database Host</b></label>
					<input style="width:40%" name="host" type="text" class="text-long"></p>
					
					<p><label><b>Database Name</b></label>
					<input style="width:40%" name="database" type="text" class="text-long"></p>
					
					<p><label><b>Database Username</b></label>
					<input style="width:40%" name="username" type="text" class="text-long"></p>
					
					<p><label><b>Database Password</b></label>
					<input style="width:40%" name="password" type="password" class="text-long"></p>

					<input type="submit" class="myButton" value="Next">
					</fieldset>
					</form>
						<?php
						if($error!="")
						{ 
						?>
						<div class="alert alert-error"><?php echo($error) ?></div>
						<?php } 
						else
						{
						if(isset($_POST['host']) && $_POST['host']!="")
						{
						?>
						<div class="alert alert-success">Database Details Added Please Wait ...</div>
						<?php 
						echo ('<META HTTP-EQUIV="Refresh" Content="2; URL=step2.php">');
						} 
						}?>
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


