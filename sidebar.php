	<div class="side-bar">
	<?php if(guest_submission_enabled()){ ?>
		
		
								
		<?php if((allow_pictures()) && (!allow_videos()) && (!allow_gifs())) { ?>	
				<li id="side-bar-signup">
					<a class="spcl-button green" href="<?php echo(rootpath()) ?>/submit.php" label="Header">Submit Pictures</a>
				</li>
		<?php } ?>
		
		<?php if((!allow_pictures()) && (allow_videos()) && (!allow_gifs())) { ?>	
				<li id="side-bar-signup">
					<a class="spcl-button green" href="<?php echo(rootpath()) ?>/submit.php" label="Header">Submit Videos</a>
				</li>
		<?php } ?>
		
		<?php if((!allow_videos()) && (!allow_pictures()) && (allow_gifs())) { ?>	
				<li id="side-bar-signup">
					<a class="spcl-button green" href="<?php echo(rootpath()) ?>/submit.php" label="Header">Submit GIFS</a>
				</li>
		<?php } ?>
			
		<?php if((allow_videos()) && (allow_pictures()) && (!allow_gifs())) { ?>	
				<li id="side-bar-signup">
					<a class="spcl-button green" href="<?php echo(rootpath()) ?>/submit.php" label="Header">Submit Pictures or Videos</a>
				</li>
		<?php } ?>
		
		<?php if((!allow_videos()) && (allow_pictures()) && (allow_gifs())) { ?>	
				<li id="side-bar-signup">
					<a class="spcl-button green" href="<?php echo(rootpath()) ?>/submit.php" label="Header">Submit Pictures or GIFS</a>
				</li>
		<?php } ?>
		
		<?php if((allow_videos()) && (!allow_pictures()) && (allow_gifs())) { ?>	
				<li id="side-bar-signup">
					<a class="spcl-button green" href="<?php echo(rootpath()) ?>/submit.php" label="Header">Submit GIFS or Videos</a>
				</li>
		<?php } ?>
		
		<?php if((allow_pictures()) && (allow_videos()) && (allow_gifs())) { ?>	
				<li id="side-bar-signup">
					<a class="spcl-button green" href="<?php echo(rootpath()) ?>/submit.php" label="Header">Submit Pictures, GIFS or Videos</a>
				</li>
		<?php } ?>
		
		
	<?php } 
	if(get_facebook() || get_twitter() || get_google())
	{ ?>
			<div class="social-block">
			<?php if(get_facebook()) { ?>
				<div class="facebook-like">
					<iframe src="//www.facebook.com/plugins/likebox.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo(get_facebook()) ?>&amp;width=300&amp;height=258&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=false&amp;show_border=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:300px; height:248px;" allowTransparency="true"></iframe>
				</div>  
<?php } if(get_twitter() || get_google()) { ?>	
				<center>
				<?php if(get_twitter()) { ?>
					<a href="https://twitter.com/<?php echo(get_twitter()) ?>" class="twitter-follow-button" data-show-count="false">Follow @<?php echo(get_twitter()) ?></a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
					<?php } if(get_google()) { ?>
					<!-- Place this tag where you want the widget to render. -->
					<div class="g-follow" data-annotation="bubble" data-height="20" data-href="https://plus.google.com/<?php echo(get_google()) ?>" data-rel="publisher"></div>
						<!-- Place this tag after the last widget tag. -->
					<script type="text/javascript">
					  (function() {
						var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
						po.src = 'https://apis.google.com/js/plusone.js';
						var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
					  })();
					</script>
					<?php } ?>
				</center>
				<?php } ?>
			</div>
			<?php }
			if(trim(show_rectangle_ad()))
			{ ?>
			<div class="s-300">
				<div style='width:300px; height:300px; border:1px solid #DFDFDF;' align='center'><?php echo(show_rectangle_ad()) ?></div>
			</div> 
			<?php } ?>
	</div>