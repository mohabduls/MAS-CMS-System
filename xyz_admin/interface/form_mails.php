<?php
include("../database/mascms-config.php");
?>
<div class="d">
  <h1 class="h-title">Send Mails</h1>
  <form action="?action=sendmail" method="POST">
	<?php
	$sql_getemail = "SELECT * FROM email";
	$query_mail = $mas_class->mas_query($masdb, $sql_getemail);
	$split = "";
	while($mail = mysqli_fetch_assoc($query_mail))
	{
		$email[] = $mail['email'];
	}
	foreach ($email as $key => $r)
	{
		if($key == 0)
		{
			$split .= $r;
		}
		else
		{
			$split .= ", ".$r;
		}
	}
	?>
    <input type="text" name="email_user" placeholder="Emails" value="<?php echo $split; ?>">
    <input type="text" name="email_subject" placeholder="Subject" value="<?php echo $sitename." -"; ?>">
    <textarea name="email_msg" placeholder="Hello, write your emails!"></textarea>
	<input type="submit" name="send_email" value="Send">
  </form>
</div>