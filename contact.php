<?php
if (!isset($_SESSION)) session_start();
	include "functions.php";
		error_reporting(0);
			$error = "";
			if(captcha_status())
				$captcha = (!empty($_SESSION['captcha']) && trim(strtolower($_POST['imagecode'])) == $_SESSION['captcha']);
			else
				$captcha = true;
				if(isset($_POST["name"]) && $captcha)
				{
					$name = trim($_POST["name"]);
					$email = trim($_POST["email"]);
					$to = get_contact_email();
					$details = $_POST["details"];
					if($name=="")
						$error .="o. Name must not be empty<br />";
					if(!is_alpha($name))
						$error .="o. You Have Entered Invalid Name<br />";
					if(!checkEmail($email))
						$error .="o. You Have Entered Invalid Email Address<br />";
					if($error=="")
						send_email($email,$to,$name . " sent you a message",$details);
				}
			else if(isset($_POST["name"]) && $captcha==false)
				$error .="o. Invalid Captcha Code<br />";

	include "header.php";
?>
	<title>Contact Us - <?php echo(get_title()) ?></title>
	<meta name="description" content="Question, Query, Anything Else, Contact Us">
</head>
<?php
include "inc/header_under.php";
?>     
    <div id="content-holder"> 
		<div class="nexthon-soft-box static">
            <div class="content contact-container contact-wrapper">
                <form name="submitcontact" class="modal" action="./contact.php" method="POST">
                    <h3>Contact Us</h3>
                    <div id="refresh"></div>
                    <div class="field">
                        <label>
                        	<h4>Your Name</h4>
                        	<input type="text" class="text" name="name" value="<?php echo($_POST['name']) ?>"/>
                        </label>
						<?php
							if(isset($_POST['name']) && trim($_POST['name'])!="" && !isalpha($_POST['name']))
							echo('<h4>&nbsp;</h4><p class="errormsg">Name must be Alphanumeric.</p>');
							else if(isset($_POST['name']) && trim($_POST['name'])=="")
							echo('<h4>&nbsp;</h4><p class="errormsg">Name Field must not be empty.</p>');
						?>
                    </div>                       
					<div class="field">
                        <label>
                        	<h4>Your Email</h4>
                        	<input type="text" class="text" name="email" value="<?php echo($_POST['email']) ?>"/>
                        </label>
						<?php
							if(isset($_POST['email']) && trim($_POST['email'])!="" && !checkEmail($_POST['email']))
							echo('<h4>&nbsp;</h4><p class="errormsg">Name must be Alphanumeric.</p>');
							else if(isset($_POST['email']) && trim($_POST['email'])=="")
							echo('<h4>&nbsp;</h4><p class="errormsg">Email must not be empty.</p>');
							?>
                    </div>   					
                    <div class="field">
                        <label>
                        	<h4>Details</h4>
							<?php if(isset($_GET['report']) && valid_url(trim('http://' . getdomain($_GET['report'])))) {
								$details = "Hello Admin,&#13;&#13;I want to report this media on your website : " . trim($_GET['report']) . " and the reason why i am doing this is (State your reason here)&#13;&#13;Regards&#13;Your Name"; } else { $details=$_POST['details']; } ?>
                        	<textarea name="details"><?php echo($details) ?></textarea>
                        </label>
                    </div>
					<?php if(captcha_status())
					{ ?>
                    <div class="field">
                        <label>
                        	<h4>Captcha</h4>
                            <img src="./libs/captcha/captcha.php" id="captcha" />
							<a onclick="document.getElementById('captcha').src='./libs/captcha/captcha.php?'+Math.random();$(this).focus();" id="change-image"><img src="images/refresh.png" title="Refresh image"/></a>                    
					   </label>
                    </div>
					<div class="field">
                        <label>
                        	<h4>&nbsp;</h4>
							<input type="text" class="text" style="width: auto;" name="imagecode" value="" maxlength="5" placeholder="Enter Image code here from above."/>
                        </label>
						<?php
							if(isset($_POST['imagecode']))
							{
								if(empty($_SESSION['captcha']) || (trim(strtolower($_POST['imagecode'])) != $_SESSION['captcha']))
								{
									echo('<h4>&nbsp;</h4><p class="errormsg">Invalid Code.</p>');
								}
							}
						?>                   
					</div>
					<?php } ?>
                </form>
				<?php
				if(isset($_POST['name']) && $error=="")
				echo('<p style="color: green;font-size: 14px;font-weight: bold;padding-top: 30px;">Your Email Sent We Will Respond You Shortly :)</p>');
				?>
            </div>
			<div class="actions">
            	<ul class="buttons">
            		<li><a class="button" onclick="document.submitcontact.submit();">Submit</a></li>
            	</ul>
            </div>
		</div>
	</div>
<?php include "footer.php"; ?>