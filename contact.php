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
                    <h3>Liên hệ</h3>
                    <div id="refresh"></div>
                    <div class="field">
                        <label>
                        	<h4>Họ tên</h4>
                        	<input type="text" class="text" name="name" value="<?php echo($_POST['name']) ?>"/>
                        </label>
						<?php
							if(isset($_POST['name']) && trim($_POST['name'])!="" && !isalpha($_POST['name']))
							echo('<h4>&nbsp;</h4><p class="errormsg">Tên phải là ký tự.</p>');
							else if(isset($_POST['name']) && trim($_POST['name'])=="")
							echo('<h4>&nbsp;</h4><p class="errormsg">Tên không được để trống.</p>');
						?>
                    </div>                       
					<div class="field">
                        <label>
                        	<h4>Email</h4>
                        	<input type="text" class="text" name="email" value="<?php echo($_POST['email']) ?>"/>
                        </label>
						<?php
							if(isset($_POST['email']) && trim($_POST['email'])!="" && !checkEmail($_POST['email']))
							echo('<h4>&nbsp;</h4><p class="errormsg">Email phải là ký tự.</p>');
							else if(isset($_POST['email']) && trim($_POST['email'])=="")
							echo('<h4>&nbsp;</h4><p class="errormsg">Email không được để trống.</p>');
							?>
                    </div>   					
                    <div class="field">
                        <label>
                        	<h4>Details</h4>
							<?php if(isset($_GET['report']) && valid_url(trim('http://' . getdomain($_GET['report'])))) {
								$details = "Xin chào Admin,&#13;&#13;Tôi muốn báo cáo nội dung này trên site của bạn : " . trim($_GET['report']) . " và đây là lý do mà tôi làm vậy (Lý do báo cáo ở đây)&#13;&#13;Regards&#13;Tên bạn"; } else { $details=$_POST['details']; } ?>
                        	<textarea name="details"><?php echo($details) ?></textarea>
                        </label>
                    </div>
					<?php if(captcha_status())
					{ ?>
                    <div class="field">
                        <label>
                        	<h4>Mã xác nhận</h4>
                            <img src="./libs/captcha/captcha.php" id="captcha" />
							<a onclick="document.getElementById('captcha').src='./libs/captcha/captcha.php?'+Math.random();$(this).focus();" id="change-image"><img src="images/refresh.png" title="Refresh image"/></a>                    
					   </label>
                    </div>
					<div class="field">
                        <label>
                        	<h4>&nbsp;</h4>
							<input type="text" class="text" style="width: auto;" name="imagecode" value="" maxlength="5" placeholder="Nhập mã xác nhận vào ô bên dưới."/>
                        </label>
						<?php
							if(isset($_POST['imagecode']))
							{
								if(empty($_SESSION['captcha']) || (trim(strtolower($_POST['imagecode'])) != $_SESSION['captcha']))
								{
									echo('<h4>&nbsp;</h4><p class="errormsg">Mã không hợp lệ.</p>');
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
            		<li><a class="button" onclick="document.submitcontact.submit();">Gủi</a></li>
            	</ul>
            </div>
		</div>
	</div>
<?php include "footer.php"; ?>