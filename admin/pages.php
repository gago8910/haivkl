<?php
if (!isset($_SESSION)) session_start();
	if(!isset($_SESSION['admin_vmp']))
	header('Location: ./login.php');
	include "header.php";
	include "../functions.php";
	$is_deleted = false;
function delete_page($id)
{
	$sql = "delete from pages where id=" . $id;
	mysql_query($sql);
}
function change_page_status($id,$status)
{
	$sql = "update pages set status=" . $status . " where id=" . $id;
	mysql_query($sql);
}
if(isset($_GET['delete']) && is_numeric($_GET['delete']))
{
	delete_page($_GET['delete']);
	$is_deleted = true;
}

function total_pages_count()
{
	$sql = "select count(id) as total_pages from pages";
	$query = mysql_query($sql);
	$fecth = mysql_fetch_array($query);
	return $fecth['total_pages'];
}
function published_pages_count()
{
	$sql = "select count(id) as published_pages from pages where status=1";
	$query = mysql_query($sql);
	$fecth = mysql_fetch_array($query);
	return $fecth['published_pages'];
}
function pending_pages_count()
{
	$sql = "select count(id) as pending_pages from pages where status=0";
	$query = mysql_query($sql);
	$fecth = mysql_fetch_array($query);
	return $fecth['pending_pages'];
}
if(isset($_POST['action']))
{
	if ($_POST['action'] == 'Publish') {
	   foreach ($_POST['checkboxvar'] as $val)
	change_page_status($val,1);
	} else if ($_POST['action'] == 'unPublish') {
		  foreach ($_POST['checkboxvar'] as $val)
	change_page_status($val,0);
	} else if ($_POST['action'] == 'Delete'){
	   foreach ($_POST['checkboxvar'] as $val) {
	delete_page($val);
	$is_deleted = true;
	}
}
}
?>
<title>Pages - <?php echo(get_title()) ?></title>

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
					<?php if(isset($_GET['type']))
					{ 
					if(trim($_GET['type'])=="published")
					{ ?>
                    	<li><a href="pages.php">All <strong>(<?php echo(total_pages_count()) ?>)</strong></a></li>
                    	<li><a href="./pages.php?type=published" class="active">Published <strong>(<?php echo(published_pages_count()) ?>)</strong></a></li>
                    	<li><a href="./pages.php?type=pending">Pending Publish <strong>(<?php echo(pending_pages_count()) ?>)</strong></a></li>
						<? }
						else if(trim($_GET['type'])=="pending"){
						?>
						<li><a href="pages.php">All <strong>(<?php echo(total_pages_count()) ?>)</strong></a></li>
                    	<li><a href="./pages.php?type=published">Published <strong>(<?php echo(published_pages_count()) ?>)</strong></a></li>
                    	<li><a href="./pages.php?type=pending" class="active">Pending Publish <strong>(<?php echo(pending_pages_count()) ?>)</strong></a></li>
						<?php } } else {?>
						<li><a href="pages.php" class="active">All <strong>(<?php echo(total_pages_count()) ?>)</strong></a></li>
                    	<li><a href="./pages.php?type=published">Published <strong>(<?php echo(published_pages_count()) ?>)</strong></a></li>
                    	<li><a href="./pages.php?type=pending">Pending Publish <strong>(<?php echo(pending_pages_count()) ?>)</strong></a></li>
						<?php } ?>
						
						
						
                    </ul>
                    <!-- // .sideNav -->
                </div>    
                <!-- // #sidebar -->
                
                <!-- h2 stays for breadcrumbs -->
                <h2>Pages</h2>
                
                <div id="main">		
					<?php if(isset($_GET['type']))
					{ 
					if(trim($_GET['type'])=="published")
					{ ?>
					<h3>Published Pages</h3><?php } 
					else if(trim($_GET['type'])=="pending"){?>
					<h3>Pending Pages</h3>
					<?php } } else { ?>
					<h3>All Pages</h3>
					<?php } ?> <a href="./add_page.php" class="new_btn">Add New Page</a>
					<form action="pages.php" method="post">
						<table cellpadding="0" cellspacing="0">
							<tr>
								<td><input type="checkbox" id="selectall"><b>Select all</b></input></td><td class="action"></td>
							</tr>
							<?php
						if(isset($_GET['type']))
						{ 
						if(trim($_GET['type'])=="published")
							$sql = "select * from pages where status=1";
							else if(trim($_GET['type'])=="pending")
							$sql = "select * from pages where status=0";
							}
							else
							{
							$sql = "select * from pages";
							}
							$query = mysql_query($sql);
					while($fetch=mysql_fetch_array($query))
					{
							if($fetch['status'])
							$img = '<img src="images/tick.png" style="margin-bottom: -4px;"/>';
							else
							$img = '<img src="images/unpub.png" style="margin-bottom: -4px;"/>';
							echo('<tr class="odd"><td><input class="selectedId" type="checkbox" name="checkboxvar[]" value="' . $fetch['id'] .'" >' . $fetch['title'] . '</td><td class="action"><a href="#" class="view">' . $img . '</a><a href="./edit_page.php?id=' . $fetch['id'] . '" class="edit">Edit</a><a class="delete_dialog" onclick="show_dialog('.$fetch['id'].')">Delete</a></td></tr>');
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
											  document.location.href='./pages.php?delete='+id;
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
					echo('<div class="alert alert-success">Page Deleted Successfully</div>');
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
