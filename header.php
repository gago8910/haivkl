<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<?php if(fb_admin_id()) 
	{?>
	<meta property="fb:admins" content="<?php echo(fb_admin_id()) ?>"/>
	<?php } if(fb_app_id()) {?>
	<meta property="fb:app_id" content="<?php echo(fb_app_id()) ?>"/>
	<?php } ?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="<?php echo(rootpath()) ?>/css/style.css" media="screen" rel="stylesheet" type="text/css" />	
	<link href="<?php echo(rootpath()) ?>/css/custom.css" media="screen" rel="stylesheet" type="text/css" />
	<link rel="icon" href="<?php echo(rootpath() . "/images/" . get_favicon()) ?>" />
	<link rel="shortcut icon" href="<?php echo(rootpath() . "/images/" . get_favicon()) ?>" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Changa+One' rel='stylesheet' type='text/css'>
	<script type="text/javascript" src="http://www.ajax.googleapis.com/ajax/libs/mootools/1.3.1/mootools-yui-compressed.js"></script>
	<script type="text/javascript" src="http://www.platform.twitter.com/widgets.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo(rootpath()) ?>/js/jquery.scrollTo-1.4.2-min.js"></script>
	<script type='text/javascript'>
		$(window).load(function(){
		var elementPosition_pcb = $('#post-control-bar').offset();
		var elementPosition = $('.s-300').offset();

		$(window).scroll(function(){
		 if($(window).scrollTop() > elementPosition_pcb.top-50){
					  $('#post-control-bar').css('position','fixed').css('top','50px');
				} else {
					$('#post-control-bar').css('position','static');
				}    		
				if($(window).scrollTop() > elementPosition.top-75){
					  $('.s-300').css('position','fixed').css('top','75px');
				} else {
					$('.s-300').css('position','static');
				}
		});
		});
	</script>