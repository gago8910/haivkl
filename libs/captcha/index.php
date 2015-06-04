<?php
session_start();

if(!isset($_POST['secure']))
	{
		$_SESSION['secure'] = rand(1000,9999);
	}
else
	{
	if($_SESSION['secure']==$_POST['secure'])
		{
			echo 'Matched';
			$_SESSION['secure'] = rand(1000,9999);
		}
	else
		{
			echo 'Incorrect, Try Again';
			$_SESSION['secure'] = rand(1000,9999);
		}
	}
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">

$(document).ready(function()
	{
		var jArray= <?php echo json_encode($_SESSION['secure']); ?>;
		alert(jArray);
	});
</script>	


</br>

<img src="gener.php" /></br>

<form action="index.php" method="POST">
	Type the value you see:
		<input type="text" size="6" name="secure" />
        <input type="submit" value="submit" />
</form>


