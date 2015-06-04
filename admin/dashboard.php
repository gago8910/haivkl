<?php
if (!isset($_SESSION)) session_start();
	if(!isset($_SESSION['admin_vmp']))
	header('Location: ./login.php');
	include "header.php";
	include "../functions.php";
	function change_status($id,$status)
	{
		$select_order = "select orderid from media where id=" . $id;
		$order_query = mysql_query($select_order);
		$fetch_order = mysql_fetch_array($order_query);
		if($fetch_order['orderid']==0)
		$order = gen_order_id(1);
		else
		$order = 0;
		$sql = "update media set approved=" . $status . ", orderid=" . $order . " where id=" . $id;
		mysql_query($sql);
	}
function delete_media($id)
{
	$sql = "select file,thumb from media where id=" . $id;
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	if($fetch['thumb']!="")
	{
		unlink('../uploads/thumbs/' . $fetch['thumb']);
	}
	else
	{
	unlink('../uploads/' . $fetch['file']);
	unlink('../uploads/thumbs/' . $fetch['file']);
	}
	$sql = "delete from media where id=" . $id;
	mysql_query($sql);
}
if(isset($_GET['delete']) && is_numeric($_GET['delete']))
{
	delete_media($_GET['delete']);
	$is_deleted = true;
}
if(isset($_GET['approve']) && is_numeric($_GET['approve']))
	change_status($_GET['approve'],1);

function count_all_votes()
{
	
	//==========================================Query Changing====================================================
					if((allow_pictures()) && (!allow_videos()) && (!allow_gifs())) 
					{
						$sql = "select sum(votes) as total_votes from media where type=0 ";
					}
					if((!allow_pictures()) && (allow_videos()) && (!allow_gifs())) 
					{
						$sql = "select sum(votes) as total_votes from media where type=1 ";
					}
					if((!allow_videos()) && (!allow_pictures()) && (allow_gifs())) 
					{
						$sql = "select sum(votes) as total_votes from media where type=2 ";
					}
					if((allow_videos()) && (allow_pictures()) && (!allow_gifs())) 
					{
						$sql = "select sum(votes) as total_votes from media where type=0 OR type=1 ";
					}
					if((!allow_videos()) && (allow_pictures()) && (allow_gifs())) 
					{
						$sql = "select sum(votes) as total_votes from media where type=0 OR type=2  ";
					}
					if((allow_videos()) && (!allow_pictures()) && (allow_gifs())) 
					{
						$sql = "select sum(votes) as total_votes from media where type=1 OR type=2 ";
					}
					if((allow_pictures()) && (allow_videos()) && (allow_gifs())) 
					{
						$sql = "select sum(votes) as total_votes from media ";
					}
	
	
	//==============================================================================================
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	if($fetch['total_votes'])
	return $fetch['total_votes'];
	else
	return 0;
}
function count_all_views()
{
	
		
	//==========================================Query Changing====================================================
					if((allow_pictures()) && (!allow_videos()) && (!allow_gifs())) 
					{
						$sql = "select sum(views) as total_views from media where type=0 ";
					}
					if((!allow_pictures()) && (allow_videos()) && (!allow_gifs())) 
					{
						$sql = "select sum(views) as total_views from media where type=1 ";
					}
					if((!allow_videos()) && (!allow_pictures()) && (allow_gifs())) 
					{
						$sql = "select sum(views) as total_views from media where type=2 ";
					}
					if((allow_videos()) && (allow_pictures()) && (!allow_gifs())) 
					{
						$sql = "select sum(views) as total_views from media where type=0 OR type=1 ";
					}
					if((!allow_videos()) && (allow_pictures()) && (allow_gifs())) 
					{
						$sql = "select sum(views) as total_views from media where type=0 OR type=2  ";
					}
					if((allow_videos()) && (!allow_pictures()) && (allow_gifs())) 
					{
						$sql = "select sum(views) as total_views from media where type=1 OR type=2 ";
					}
					if((allow_pictures()) && (allow_videos()) && (allow_gifs())) 
					{
						$sql = "select sum(views) as total_views from media ";
					}
	
	
	//==============================================================================================
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	if($fetch['total_views'])
	return $fetch['total_views'];
	else
	return 0;
}

function count_all()
{
	
			
	//==========================================Query Changing====================================================
					if((allow_pictures()) && (!allow_videos()) && (!allow_gifs())) 
					{
						$sql = "select count(id) as total from media where type=0 ";
					}
					if((!allow_pictures()) && (allow_videos()) && (!allow_gifs())) 
					{
						$sql = "select count(id) as total from media where type=1 ";
					}
					if((!allow_videos()) && (!allow_pictures()) && (allow_gifs())) 
					{
						$sql = "select count(id) as total from media where type=2 ";
					}
					if((allow_videos()) && (allow_pictures()) && (!allow_gifs())) 
					{
						$sql = "select count(id) as total from media where type=0 OR type=1 ";
					}
					if((!allow_videos()) && (allow_pictures()) && (allow_gifs())) 
					{
						$sql = "select count(id) as total from media where type=0 OR type=2  ";
					}
					if((allow_videos()) && (!allow_pictures()) && (allow_gifs())) 
					{
						$sql = "select count(id) as total from media where type=1 OR type=2 ";
					}
					if((allow_pictures()) && (allow_videos()) && (allow_gifs())) 
					{
						$sql = "select count(id) as total from media";
					}
	
	
	//==============================================================================================
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
function count_pictures()
{
	$sql = "select count(id) as total from media where type=0";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
function count_videos()
{
	$sql = "select count(id) as total from media where type=1";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
function count_gifs()
{
	$sql = "select count(id) as total from media where type=2";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
if(isset($_POST['action']))
{
	if($_POST['action'] == 'Publish') {
	   foreach ($_POST['checkboxvar'] as $val)
	   change_status($val,1);
	} 
	else if ($_POST['action'] == 'Delete'){
	   foreach ($_POST['checkboxvar'] as $val)
	delete_media($val);
	$is_deleted = true;
	}
}
?>
		<title>Dashboard - <?php echo(get_title()) ?></title>
		
		<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		<link rel="stylesheet" href="/resources/demos/style.css" />
						
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
						<?php if((allow_pictures()) || (allow_videos()) || (allow_gifs())) { ?>	
							<li><a href="./" class="active">All (<b><?php echo(count_all()) ?></b>)</a></li>
						<?php } if(allow_pictures()) { ?>
							<li><a href="./pictures.php">Pictures (<b><?php echo(count_pictures()) ?></b>)</a></li>
						<?php } if(allow_videos()) { ?>
							<li><a href="./videos.php">Videos (<b><?php echo(count_videos()) ?></b>)</a></li>
						<?php } if(allow_gifs()) { ?>
							<li><a href="./animated-gifs.php">Animated Gifs (<b><?php echo(count_gifs()) ?></b>)</a></li>
						<?php }?>
                    </ul>
                    <!-- // .sideNav -->
                </div>    
                <!-- // #sidebar -->
                
                <!-- h2 stays for breadcrumbs -->
                <h2>Dashboard</h2>
                
                <div id="main">
					<h3>Pending Approval</h3>
					<form action="./dashboard.php" method="post">
                    	<table cellpadding="0" cellspacing="0">
						<tr><td><input type="checkbox" id="selectall"><b>Select all</b></input></td><td class="action"></td></tr>
						<?php
						$sql = "select id,title,permalink from media where approved=0 order by id desc limit 15";
						$query = mysql_query($sql);
						$i=0;
						while($fetch = mysql_fetch_array($query))
						{
						if($i%2==0)
						echo('<tr>');
						else
						echo('<tr class="odd">');
						echo('<td><input class="selectedId" type="checkbox" name="checkboxvar[]" value="' . $fetch['id'] . '"/><a href="' . rootpath() . '/view.php?id=' . $fetch['id'] . '">' . $fetch['title'] . '</a></td><td class="action"><a href="./dashboard.php?approve=' . $fetch['id'] .'" class="view">Approve</a><a class="delete_dialog" onclick="show_dialog('.$fetch['id'].')">Delete</a></td>');
							echo '</tr>';
							$i+=1;
							}
							?>
						
						  <script type="text/javascript">
						 $(document).ready(function(){$('.dialog-confirm').hide();});
							function show_dialog(intValue){
										var id=intValue;
										$(".dialog-confirm" ).dialog({
										  resizable: false,
										  height:140,
										  modal: true,
										  buttons: {
											"Delete": function() {
											  document.location.href='./dashboard.php?delete='+id;
											},
											Cancel: function() {
											  $( this ).dialog( "close" );
											}
										  }
							});
						  };
						</script>
						
					</table>
						<br />
						<div align="right">
						<input type="submit" name="action" class="approve_btn" value="Publish">
					<input type="button" name="action" class="del_btn" id="del_btn" value="Delete">
					<input type="submit" name="action" class="del_btn" id="sub" value="Delete">
					
					<script type="text/javascript">
						 $(document).ready(function(){$('.dialog-confirm').hide();});
						 $(document).ready(function(){$('#sub').hide();});
						 
						 
							$(document).ready(function(){
								$('#del_btn').click(function(e) {
										$( ".dialog-confirm" ).dialog({
										  resizable: false,
										  height:140,
										  modal: true,
										  buttons: {
											"Delete": function() {
														 $( "#sub" ).click();
											},
											Cancel: function() {
											  $( this ).dialog( "close" );
											}
										  }
							});});
						  });
  
                   
					</script>
					</div>
					</form>
					<h3>Website Stats</h3>
					<table>
					<tr><td>Total Submissions : <?php echo(count_all()) ?></td></tr>
					<tr><td>Total Views : <?php echo(count_all_views()) ?></td></tr>
					<tr><td>Total Votes : <?php echo(count_all_votes()) ?></td></tr>
					</table>
					<br />
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
<div class="dialog-confirm" title="Delete?">
							<p><span class="ui-icon ui-icon-alert"  style="float: left; margin: 0 7px 20px 0;"></span>Are you sure to delete? </p>
	</div>
