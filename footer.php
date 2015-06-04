<br />
<?php 
	if(trim(show_leaderboard2_ad())) 
	{ 
?>
	<div id="main-bottom-ad-tray">
        <div>
        	<div style='width:728px; height:90px; border:1px solid #DFDFDF;' align='center'><?php echo(show_leaderboard2_ad()) ?></div>
        </div>
    </div>
	<?php } ?>
</div>
	
<?php include "sidebar.php"; ?>	
		<div id="footer" class="">
				<div class="wrap" style="width:1060px">
                    <h4>
						<?php
							if(list_pages())
							{
							echo(list_pages());
						?> 
							| 
							<a href="<?php echo(rootpath()) ?>/contact.php">Contact Us</a>
						<?php } else { ?>
						<a href="<?php echo(rootpath()) ?>/contact.php">Contact Us</a>
						<?php } ?>
						</h4>
							<div style="clear:both"></div>
							<b>&copy; <?php echo(date("Y")) ?> 
								<a href="<?php echo(rootpath()) ?>">
									<?php echo(get_title()) ?>
								</b> 
							</a></li>
                    <b>Shared On NullPHP.com </b><a href="http://www.nullphp.com" target="_blank"><b>NullPHP.com</b></a>
				</div>
		</div>
	</body>
</html>