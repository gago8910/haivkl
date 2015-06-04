<?php 
if (!isset($_SESSION)) session_start();
	include "functions.php";
	if(!guest_submission_enabled())
		header('Location: index.php');
		error_reporting(0);
		$success=false;
		$invalid=false;
		if(captcha_status())
			$captcha = (!empty($_SESSION['captcha']) && trim(strtolower($_POST['imagecode'])) == $_SESSION['captcha']);
		else
			$captcha = true;
		if(isset($_POST['title']) && (strlen($_POST['title']) <= post_title_length()) && $captcha)
		{
			$title=trim($_POST['title']);
			$description = strip_tags(trim($_POST['description']));
			$type=0;
			$media="";
			$tag=trim($_POST['tags']);
			$tag=mysql_real_escape_string($tag);
			$ids=Array();
			if (preg_match('/,/',$tag)) {
			$tags=explode(',',$tag);
			foreach($tags as $tag) 
			{
			if(trim($tag)!="")
			$ids[] = insert_tag($tag); 
			}
		}
		else {
		$tags=trim($tag);
		if(trim($tags)!="")
		$ids[] = insert_tag($tags); 
		}
			if(trim($_POST['source'])!="")
				$source=trim($_POST['source']);
			else
				$source="";
				
				if($_POST['type']=="pic")
				{
					$type=0;
					if(trim($_POST['picurl'])!="")
					{
						$media=httpify(trim($_POST['picurl']));
					}
					else if(trim($_FILES["myfile"]["name"])!="")
					{
						$media=$_FILES["myfile"]["name"];
					}
				}
				else
				{
					$type=1;
					$media=httpify(trim($_POST["vidurl"]));
				}
				
				if($type==1 && valid_video_url(httpify(trim($_POST["vidurl"]))))
				{
					$post_id=add_video($title,$description,$media,$source);
					if($post_id)
					{
						add_media_tag($post_id,$ids);
						
						$success=true;
					}
					else
					{
						$invalid=true;
					}
				}
				else if($type==0 && trim($_POST['picurl']!=""))
				{
					$post_id=add_picture_url($title,$description,$media,$source);
					if($post_id)
					{
						add_media_tag($post_id,$ids);
						$success=true;
					}
					else
					{
						$invalid=true;
					}
				}
				else if($type==0 && trim($_FILES["myfile"]["name"])!="")
				{
					
					$post_id=add_picture_file($title,$description,$source);					
					if($post_id)
					{
						add_media_tag($post_id,$ids);
						$success=true;
					}
					else
					{
						$invalid=true;
					}
				}
		}
		if($success)
			header('Location: submitted.php?f=' . $title);
			include "header.php";
		
		
		$topic;
		if((allow_pictures()) && (!allow_videos()) && (!allow_gifs())) { 
					$topic="Pictures";
		} 
		
		 if((!allow_pictures()) && (allow_videos()) && (!allow_gifs())) { 	
				$topic="Videos";
		} 
		
		 if((!allow_videos()) && (!allow_pictures()) && (allow_gifs())) { 	
				$topic="Animated GIFS";
		 } 
			
		 if((allow_videos()) && (allow_pictures()) && (!allow_gifs())) { 	
				$topic="Pictures or Videos";
		 } 
		
		 if((!allow_videos()) && (allow_pictures()) && (allow_gifs())) { 	
				$topic="Pictures or GIFS";
		 } 
		
		 if((allow_videos()) && (!allow_pictures()) && (allow_gifs())) {	
				$topic="Videos or GIFS";
		 } 
		 if((allow_pictures()) && (allow_videos()) && (allow_gifs())) { 
				$topic="Pictures, GIFS or Videos";
		 } 
		?>
<title>Submit <?php echo($topic) ?></title>
<meta name="description" content="Submit Your GAG, 9GAG Clone Script is the best clone of 9GAG - 9Gag Clone Script">
<meta name="keywords" content="Submit Your GAG, 9gag clone, 9gag script, 9gag clone script, clone, script,9Gag Clone Script">
<script>
$(document).ready(function(){
 var $value = $('#type').val();
		if($value=="vid")
		{
		$('#picblock').slideUp();
		$('#vidblock').slideDown();
		}
		else
		{
		$('#picblock').slideDown();
		$('#vidblock').slideUp();
		}
		var $myfile = $('#myfile');
        var $picurl = $('#picurl');
        $picurl.prop('disabled', $myfile.val());
		$myfile.prop('disabled', $picurl.val());
}); 
$(function(){
    $('#myfile').change(function(){
        var $myfile = $('#myfile');
        var $picurl = $('#picurl');
        $picurl.prop('disabled', $myfile.val());
		document.getElementById('picurl').value='';
    });
});
$(function(){
    $('#picurl').keyup(function(){
        var $myfile = $('#myfile');
        var $picurl = $('#picurl');
        $myfile.prop('disabled', $picurl.val());
    });
});
$(function(){
    $('#type').change(function(){
        var $value = $('#type').val();
		if($value=="vid")
		{
		$('#picblock').slideUp();
		$('#vidblock').slideDown();
		}
		else
		{
		$('#picblock').slideDown();
		$('#vidblock').slideUp();
		}
    });
});
</script>
</head>
<?php
include "inc/header_under.php";
?>     
    <div id="content-holder"> 
		<div class="nexthon-soft-box static">
            <div class="content contact-container contact-wrapper">
                <form name="submitgag" class="modal" action="submit.php" method="POST" enctype="multipart/form-data">
                    <h3>Submit <?php echo($topic) ?></h3>
                    <div id="refresh">
					</div>
					<?php
					if(isset($_POST['title']))
					{
						?>
						<div class="field">
							<label>
								<h4>Title</h4>
								<input type="text" class="text" name="title" value="<? echo($_POST['title']) ?>" maxlength="<?php echo(post_title_length()) ?>" placeholder="Enter Your Title Here."/>
							</label>
							<?php
								if(strlen($_POST['title'])>post_title_length())
								echo('<h4>&nbsp;</h4><p class="errormsg">Title length must be less then or equal to ' . post_title_length() . '.</p>');
								?>
						</div>   
					
						<div class="field">
							<label>
								<h4>Description (Optional)</h4>
								<textarea placeholder="Enter upto 170 characters of description" maxlength="170" name="description" style="width:480px;height:50px"><? echo($_POST['description']) ?></textarea>
							</label>
						</div> 
					
						<div class="field">
							<label>
								<h4>Type</h4>
								<div class="topic">
									<select name="type" id="type">
										<?php
										if(isset($_POST['type']) && $_POST['type']=="pic")
										{ ?>
												<?php if((allow_pictures()) && (!allow_videos()) && (!allow_gifs())) { ?>	
														<option value="pic" selected>Picture</option>														
												<?php } ?>
																																
												<?php if((!allow_videos()) && (!allow_pictures()) && (allow_gifs())) { ?>	
														<option value="pic" selected>GIF</option>								
												<?php } ?>
													
												<?php if((allow_videos()) && (allow_pictures()) && (!allow_gifs())) { ?>	
														<option value="pic" selected>Picture</option>
														<option value="vid">Video</option>				
												<?php } ?>
												
												<?php if((!allow_videos()) && (allow_pictures()) && (allow_gifs())) { ?>	
														<option value="pic" selected>Picture or GIF</option>
																							
												<?php } ?>
												
												<?php if((allow_videos()) && (!allow_pictures()) && (allow_gifs())) { ?>	
														<option value="pic" selected>GIF</option>
														<option value="vid">Video</option>					
												<?php } ?>
														
												<?php if((allow_pictures()) && (allow_videos()) && (allow_gifs())) { ?>	
														<option value="pic" selected>Picture or GIF</option>
														<option value="vid">Video</option>					
												<?php } ?>
										
											<?php 
										} 
										else 
										{?>
												
												<?php if((!allow_pictures()) && (allow_videos()) && (!allow_gifs())) { ?>			
												<option value="vid" selected>Video</option>			
												<?php } ?>
												
												<?php if((allow_videos()) && (allow_pictures()) && (!allow_gifs())) { ?>	
														<option value="pic" >Picture</option>
														<option value="vid" selected>Video</option>				
												<?php } ?>
												
												
												<?php if((allow_videos()) && (!allow_pictures()) && (allow_gifs())) { ?>	
														<option value="pic" >GIF</option>
														<option value="vid" selected>Video</option>					
												<?php } ?>
														
												<?php if((allow_pictures()) && (allow_videos()) && (allow_gifs())) { ?>	
														<option value="pic" >Picture or GIF</option>
														<option value="vid" selected>Video</option>					
												<?php } ?>
										<?php 
										} ?>
									</select>
								</div>
							</label>
							<p class="info" style="visibility:visible">Carefully Select the right type.</p>
						</div>
					
						<div class="field">
							<label>
								<h4>Source (Optional)</h4>
								<input type="text" class="text" name="source" value="<? echo($_POST['source']) ?>" maxlength="255" placeholder="Enter source if any e.g 9gag, damnlol"/>
							</label>
						</div>
						
						<div id="vidblock">
							<div class="field">
								<label>
									 <h4>Video URL</h4>
									<input type="text" class="text" value="<? echo($_POST['vidurl']) ?>" name="vidurl" id="vidurl" maxlength="255" placeholder="Enter Video URL e.g youtube, vimeo etc"/>
									</label>
									<?php
									if($_POST["type"]=="vid" && trim($_POST["vidurl"])!="" && valid_url(httpify($_POST["vidurl"])))
										{
												if(!valid_video_url(httpify($_POST["vidurl"])))
												echo('<label><h4>&nbsp;</h4><p class="errormsg">Invalid Video URL or Not Allowed</p></label>');
										}
									?>
							</div>
						</div>
						<div id="picblock">
							<div class="field">
								<label>
									<h4>Picture URL</h4>
									<input type="text" class="text"  value="<? echo($_POST['picurl']) ?>" name="picurl" id="picurl" maxlength="255" placeholder="Enter Picture URL or upload using Browse button below"/>
							</div>
							<div class="field">
								<label>
									<h4>Upload</h4>
									<input type="file" name="myfile" id="myfile"/>
									</label>
									<?php
									if(isset($_FILES["myfile"]) && trim($_FILES["myfile"]["name"])!="")
										{
									$temp = explode(".", $_FILES["myfile"]["name"]);
									$ext = end($temp);
									if (!valid_file_extension($ext))
										{
										echo('<label><h4>&nbsp;</h4><p class="errormsg">Only these file types allowed .gif, .jpeg, .jpg, .png</p></label>');
										}
										}
									?>
									<label>
									<h4>&nbsp;</h4>
									<a onclick="document.getElementById('myfile').value='';$('#gagurl').prop('disabled',false);"><small>Clear Selection.</small></a>
									</label>
							</div>
						</div>
						<div class="field">
							<label>
								<h4>Tags (Optional)</h4>
								<input type="text" class="text" name="tags"  value="<? echo($_POST['tags']) ?>" placeholder="Enter Tags Comma Separated."/>
							</label>						
						</div> 
						<?php if(captcha_status())
						{ ?>
							<div class="field">
								<label>
									<h4>Image Code</h4>
									<img src="./libs/captcha/captcha.php" id="captcha" /><a onclick="
										document.getElementById('captcha').src='./libs/captcha/captcha.php?'+Math.random();$(this).focus();" id="change-image"><img src="images/refresh.png" title="Refresh image"/></a>                  
							   </label>
							</div>
							<div class="field">
								<label>
									<h4>&nbsp;</h4>
								   <input type="text" style="width: auto;" class="text" name='imagecode' value="" maxlength="5" placeholder="Enter Image code here from above."/>
								</label>
									 <?php
									if(empty($_SESSION['captcha']) || trim(strtolower($_POST['imagecode'])) != $_SESSION['captcha'])
									echo('<h4>&nbsp;</h4><p class="errormsg">Invalid Code.</p>');
									?> 
							</div>
						<?php 
						}
					} 
					else
					{
					?>
						<div class="field">
							<label>
								<h4>Title</h4>
								<input type="text" class="text" name="title" value="" maxlength="<?php echo(post_title_length()) ?>" placeholder="Enter your Title Here"/>
							</label>
						</div>   
                    
						<div class="field">
							<label>
								<h4>Description (Optional)</h4>
								<textarea placeholder="Enter upto 170 characters of description" maxlength="170" name="description" style="width:480px;height:50px"></textarea>
							</label>
						</div> 
					
						<div class="field">
							<label>
								<h4>Type</h4>
								<div class="topic">
									<select name="type" id="type">
										
										<?php if((allow_pictures()) && (!allow_videos()) && (!allow_gifs())) { ?>	
												<option value="pic">Picture</option>														
										<?php } ?>
										
										<?php if((!allow_pictures()) && (allow_videos()) && (!allow_gifs())) { ?>			
												<option value="vid">Video</option>			
										<?php } ?>
										
										<?php if((!allow_videos()) && (!allow_pictures()) && (allow_gifs())) { ?>	
												<option value="pic">GIF</option>								
										<?php } ?>
											
										<?php if((allow_videos()) && (allow_pictures()) && (!allow_gifs())) { ?>	
												<option value="pic">Picture</option>
												<option value="vid">Video</option>				
										<?php } ?>
										
										<?php if((!allow_videos()) && (allow_pictures()) && (allow_gifs())) { ?>	
												<option value="pic">Picture or GIF</option>
																					
										<?php } ?>
										
										<?php if((allow_videos()) && (!allow_pictures()) && (allow_gifs())) { ?>	
												<option value="pic">GIF</option>
												<option value="vid">Video</option>					
										<?php } ?>
												
										<?php if((allow_pictures()) && (allow_videos()) && (allow_gifs())) { ?>	
												<option value="pic">Picture or GIF</option>
												<option value="vid">Video</option>					
										<?php } ?>
										
									</select>
								</div>
							</label>
							<p class="info" style="visibility:visible">Carefully Select the right type.</p>
						</div>
					
						<div class="field">
							<label>
								<h4>Source (Optional)</h4>
								<input type="text" class="text" name="source" maxlength="255" placeholder="Enter source if any e.g 9gag, damnlol"/>
							</label>
							<p class="info" style="visibility:visible"></p>
						</div>
						
						<div id="vidblock">
							<div class="field">
								<label>
									 <h4>Video URL</h4>
									<input type="text" class="text" name="vidurl" id="vidurl" maxlength="255" placeholder="Enter Video URL e.g youtube, vimeo etc"/>
									</label>
							</div>
						</div>
						<div id="picblock">
							<div class="field">
								<label>
									 <h4>Picture URL</h4>
									<input type="text" class="text" name="picurl" id="picurl" maxlength="255" placeholder="Enter URL or upload using Browse button below"/>
									</label>
							</div>
							<div class="field">
							   <label>
									<h4>Upload</h4>
									<input type="file" name="myfile" id="myfile"/>
									</label>
									 <label>
									<h4>&nbsp;</h4>
									<a onclick="document.getElementById('myfile').value='';$('#gagurl').prop('disabled',false);"><small>Clear Selection.</small></a>
									</label>
							</div>  
						</div>
						
						<div class="field">
							<label>
								<h4>Tags (Optional)</h4>
								<input type="text" class="text" name="tags" placeholder="Enter Tags Comma Separated."/>
							</label>						
						</div> 
						<?php if(captcha_status())
						{ ?>
							<div class="field">
								<label>
									<h4>Image Code</h4>
									<img src="./libs/captcha/captcha.php" id="captcha" /><a onclick="
										document.getElementById('captcha').src='./libs/captcha/captcha.php?'+Math.random();$(this).focus();" id="change-image"><img src="images/refresh.png" title="Refresh image"/></a>                    
							   </label>
							</div>
							<div class="field">
								<label>
									<h4>&nbsp;</h4>
								   <input type="text" class="text" style="width: auto;" name='imagecode' value="" maxlength="5" placeholder="Enter Image code here from above."/>
								</label>
							</div>
						<?php } 
					} ?>
                </form>
				<?php
					if($invalid)
					echo('<p style="color: red;font-size: 14px;font-weight: bold;padding-top: 30px;">Invalid Submission Not Added.</p>');
				?>
            </div>
			<div class="actions">
            	<ul class="buttons">
            		<li><a class="button" onclick="document.submitgag.submit();">Submit</a></li>
            	</ul>
            </div>
		</div>
</div>
<?php include "footer.php"; ?>
