<?php
if (!isset($_SESSION)) session_start();
	include "../functions.php";
	if(!allow_gifs())
	{
		header('Location: ./dashboard.php');
	}
	if(!isset($_SESSION['admin_vmp']))
	{
		header('Location: ./login.php');
	}
	
	include "header.php";
	$is_deleted = false;
	$search_string=$_GET['search'];
function change_gif_status($id,$status)
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
function delete_gif($id)
{
	delete_tags($id);
	$sql = "select file from media where id=" . $id;
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	$file = $fetch['file'];
	unlink('../uploads/' . $file);
	unlink('../uploads/thumbs/' . $file);
	$sql = "delete from media where id=" . $id;
	mysql_query($sql);
}
if(isset($_GET['delete']) && is_numeric($_GET['delete']))
{ 

							delete_gif($_GET['delete']);
							$is_deleted = true;												
						
	
}
function count_all_gifs()
{
	$sql = "select count(id) as total from media where type=2";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
function count_pending_gifs()
{
	$sql = "select count(id) as total from media where approved=0 and type=2";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
function count_approved_gifs()
{
	$sql = "select count(id) as total from media where approved=1 and type=2";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
	$page=1;//Default page
	$limit=15;//Records per page
	$next=2;
	$prev=1;
if(isset($_GET['type']))
{ 
	if(trim($_GET['type'])=="approved")
	$data = mysql_query("select * from media where type=2 and approved=1");
	else if(trim($_GET['type'])=="pending")
	$data = mysql_query("select * from media where type=2 and approved=0");
}
else if(isset($_GET['search']))
{
	$data = mysql_query("select * from media where type=2 and title like '%" . $_GET['search'] . "%'");
}
else
{
	$data = mysql_query("select * from media where type=2");
}
	$rows = mysql_num_rows($data);
	$last = ceil($rows/$limit);
	if(isset($_GET['p']) && $_GET['p']!='' && ($_GET['p']>=1 && $_GET['p']<=$last))
	{
		$page=$_GET['p'];
		if($page>1)
		$prev=$page-1;
		else
		$prev=$page;
		if($page<$last)
		$next=$page+1;
		else
		$next=$page;
	}
	if(isset($_POST['action']))
	{
		if($_POST['action'] == 'Publish')
		{
		   foreach ($_POST['checkboxvar'] as $val)
		   change_gif_status($val,1);
		} 
		else if($_POST['action'] == 'unPublish')
		{
		   foreach ($_POST['checkboxvar'] as $val)
		   change_gif_status($val,0);
		 }
		else if ($_POST['action'] == 'Delete')
		{
		   foreach ($_POST['checkboxvar'] as $val) {
		delete_gif($val);
		$is_deleted = true;
		}
		}
	}
?>
	<title>Animated GIFS - <?php echo(get_title()) ?></title>
	
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
					<?php 
					if(isset($_GET['type']))
					{ 
					if(trim($_GET['type'])=="approved")
					{ ?>
                    	<li><a href="./animated-gifs.php">All <strong>(<?php echo(count_all_gifs()) ?>)</strong></a></li>
                    	<li><a href="./animated-gifs.php?type=approved"  class="active">Approved <strong>(<?php echo(count_approved_gifs()) ?>)</strong></a></li>
                    	<li><a href="./animated-gifs.php?type=pending">Pending <strong>(<?php echo(count_pending_gifs()) ?>)</strong></a></li>
						<?php } else if(trim($_GET['type'])=="pending") { ?>
						<li><a href="./animated-gifs.php">All <strong>(<?php echo(count_all_gifs()) ?>)</strong></a></li>
                    	<li><a href="./animated-gifs.php?type=approved">Approved <strong>(<?php echo(count_approved_gifs()) ?>)</strong></a></li>
                    	<li><a href="./animated-gifs.php?type=pending"  class="active">Pending <strong>(<?php echo(count_pending_gifs()) ?>)</strong></a></li>
						<?php }} else { ?>
						<li><a href="./animated-gifs.php"  class="active">All <strong>(<?php echo(count_all_gifs()) ?>)</strong></a></li>
                    	<li><a href="./animated-gifs.php?type=approved">Approved <strong>(<?php echo(count_approved_gifs()) ?>)</strong></a></li>
                    	<li><a href="./animated-gifs.php?type=pending">Pending <strong>(<?php echo(count_pending_gifs()) ?>)</strong></a></li>
						<?php } 
						if($_GET['search']!="")
						$search_val = $_GET['search'];
						else
						$search_val = "Search...";
						?>
						<li>
							<form action="./animated-gifs.php" method="GET" class="searchform">
								<input type="text" onblur="if (this.value == '') {this.value = 'Search...';}" onfocus="if (this.value == 'Search...') {this.value = '';}" value="<?php echo($search_val) ?>" class="searchfield" name="search">
								<input type="submit" value="Go" class="searchbutton">
							</form>
						</li>
                    </ul>
                    <!-- // .sideNav -->
                </div>    
                <!-- // #sidebar -->
                
                <!-- h2 stays for breadcrumbs -->
                <h2>Animated GIFS</h2>
                <div id="main">
				<?php if(isset($_GET['type']))
					{ 
					if(trim($_GET['type'])=="approved")
					{ ?>
					<h3>Approved GIFS</h3><?php } 
					else if(trim($_GET['type'])=="pending"){?>
					<h3>Pending GIFS</h3>
					<?php } } else { ?>
					<h3>All GIFS</h3>
					<?php } ?>
					<a class="new_btn" href="./add_pic.php">Add New GIF</a>
						<?php if(isset($_GET['type']))
							echo('<form action="./animated-gifs.php?type=' . $_GET['type'] . '" method="post">');
							else if(isset($_GET['search']))
							echo('<form action="./animated-gifs.php?search=' . $_GET['search'] . '" method="post">');
							else
							echo('<form action="./animated-gifs.php" method="post">');
						?>
                    			<table cellpadding="0" cellspacing="0">
						<tr>
							<td><input type="checkbox" id="selectall"><b>Select all</b></input></td><td class="action"></td>
						</tr>
						<?php
					$start_result = ($page-1)*$limit;
					if(isset($_GET['type']))
					{ 
					if(trim($_GET['type'])=="approved")
						$sql = "select * from media where type=2 and approved=1 order by id desc limit " . $start_result. "," .$limit;
						else if(trim($_GET['type'])=="pending")
						$sql = "select * from media where type=2 and approved=0 order by id desc limit ".$start_result.",".$limit;
						}
						else if(isset($_GET['search']))
						{
						$sql = "select * from media where type=2 and title like '%" . $_GET['search'] . "%' order by id desc limit ".$start_result.",".$limit;
						}
						else
						{
						$sql = "select * from media where type=2 order by id desc limit ".$start_result.",".$limit;
						}
						$query = mysql_query($sql);
						$tit;
						while($fetch=mysql_fetch_array($query))
						{
						if($fetch['approved'])
						$img = '<img src="images/tick.png" style="margin-bottom: -4px;"/>';
						else
						$img = '<img src="images/unpub.png" style="margin-bottom: -4px;"/>';
						echo('<tr class="odd"><td><input class="selectedId" type="checkbox" name="checkboxvar[]" value="' . $fetch['id'] .'" ><a href="' . rootpath() . '/view.php?id=' . $fetch['id'] . '">' . $fetch['title'] . '</a></td><td class="action"><a href="#" class="view">' . $img . '</a><a href="./edit_gif.php?id=' . $fetch['id'] . '" class="edit">Edit</a><a class="delete_dialog" class="delete" ><a class="delete_dialog" onclick="show_dialog('.$fetch['id'].')">Delete</a></td></tr>');
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
											  document.location.href='./animated-gifs.php?delete='+id;
											},
											Cancel: function() {
											  $( this ).dialog( "close" );
											}
										  }
							});
						  };
						</script>
						
					</table>
					<div class="controls_bg">
					<br />
					<div class="pagination">
						<?php
						if(isset($_GET['type']))
						{ 
						if(trim($_GET['type'])=="approved")
							echo('<a href="./animated-gifs.php?type=approved" class="page gradient">First</a>');
							else if(trim($_GET['type'])=="pending")
							echo('<a href="./animated-gifs.php?type=pending" class="page gradient">First</a>');
						}
						else if(isset($_GET['search']))
							echo('<a href="./animated-gifs.php?search=' . $_GET['search']  . '" class="page gradient">First</a>');
							else
							echo('<a href="./animated-gifs.php" class="page gradient">First</a>'); 
						if($page>1)
						{
							$i=((int)$page/5)*5-1;
							if(!($i+5>$last))
							{
								$temp_last = $i+5;
							}
							else
							{
								if(($last - 5)>$i)
								$i = $last - 5;
								else
								$i = 1;
								$temp_last = $last;
							}
						}
						else
						{
							$i=1;
							if(!($i+5>$last))
							$temp_last = 5;
							else
							$temp_last = $last;
						}
						for($i;$i<=$temp_last;$i++)
						{
							if(isset($_GET['type']))
							{ 
								$type=$_GET['type'];
								if($i==$page)
								echo('<a href="./animated-gifs.php?p=' . $i . '&type=' . $type . '" class="page active">' . $i . '</a>');
								else
								echo('<a href="./animated-gifs.php?p=' . $i . '&type=' . $type . '" class="page gradient">' . $i . '</a>');
							}
							else if(isset($_GET['search']))
							{
								if($i==$page)
								echo('<a href="./animated-gifs.php?p=' . $i . '&search=' . $search_string . '" class="page active">' . $i . '</a>');
								else
								echo('<a href="./animated-gifs.php?p=' . $i . '&search=' . $search_string . '" class="page gradient">' . $i . '</a>');
							}
							else
							{
								if($i==$page)
								echo('<a href="./animated-gifs.php?p=' . $i . '" class="page active">' . $i . '</a>');
								else
								echo('<a href="./animated-gifs.php?p=' . $i . '" class="page gradient">' . $i . '</a>');
							}
						}
						if(isset($_GET['type']))
						{ 
							if(trim($_GET['type'])=="approved")
							echo('<a href="./animated-gifs.php?p=' . $last . '&type=approved" class="page gradient">Last</a>');
							else if(trim($_GET['type'])=="pending")
							echo('<a href="./animated-gifs.php?p=' . $last . '&type=pending" class="page gradient">Last</a>');
						}
						else if(isset($_GET['search']))
							echo('<a href="./animated-gifs.php?p=' . $last . '&search=' . $search_string . '" class="page gradient">Last</a>');
							else
							echo('<a href="./animated-gifs.php?p=' . $last . '" class="page gradient">Last</a>'); ?>
					</div>
						
						<input type="submit" name="action" class="approve_btn" value="Publish">
						<input type="submit" name="action" class="unapprove_btn" value="unPublish">
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
				</form>
						<br />
						</div>
						<br />
						<?php if((isset($_GET['delete']) || isset($_POST['action'])) && $is_deleted)
						echo('<div class="alert alert-success">Deleted Successfully</div>');
						?>
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