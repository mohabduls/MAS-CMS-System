<?php
/*
Copyright 09-Januari-2020
MASCMS V1.0 (MAS Content Management System)
Coded by : Mohamad Abdul Sobur
Based on PHP NATIVE
*/
?>
<!DOCTYPE html>
<html>
  <head>
    <title>
		<?php
		if(isset($_REQUEST['action']))
		{
			switch($_REQUEST['action'])
			{
				case "add":
				{
					echo "Add post";
				}
				break;
				case "find":
				{
					echo "Find post";
				}
				break;
				case "setting":
				{
					echo "Setting";
				}
				break;
				case "delpost":
				{
					echo "Confirm delete post";
				}
				break;
				case "delcat":
				{
					echo "Confirm delete category";
				}
				break;
				case "delshortlink":
				{
					echo "Confirm delete shortlink";
				}
				break;
				case "shortlink":
				{
					echo "Shortlink";
				}
				break;
				case "add_cat":
				{
					echo "Add category";
				}
				break;
				case "sendmail":
				{
					echo "Send a newsletter";
				}
				break;
				
			}
		}
		elseif(isset($_GET['edit_post']))
		{
			echo "Edit post";
		}
		else
		{
			echo "Hola!, Welcome to Admin Panel - ".$version_code;
		}
		?>
	</title>
    <link rel="stylesheet" href="../theme/style.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>
  </head>
  <body>
    <div class="d-head">
      <h1 class="h-title"><?php echo $site_name ?></h1>
      <div class="d-menu">
        <a href="index.php" class="a-menu">Home</a>
        <?php
        if(empty($_SESSION['username']) && empty($_SESSION['password']))
        {
        	
        }
        else
        {
        	?>
        	<a href="?action=add" class="a-menu">Add Post</a>
            <a href="?action=add_cat" class="a-menu">Add Category</a>
            <a href="?action=find" class="a-menu">Find</a>
            <a class="a-menu" href="?action=setting">Setting</a>
            <a class="a-menu" href="?action=shortlink">ShortLink</a>
            <a class="a-menu" href="?action=sendmail">Send Email</a>
            <a class="a-menu" href="?action=comments&page_comments=1">Comments</a>
        	<a class="a-menu" href="?logout=1">Log out</a>
        <?php
            //HITUNG EMAIL
            $hitung_email = "SELECT email FROM email";
            $eksekusi = $mas_class->mas_query($masdb, $hitung_email);
            $hasil = mysqli_num_rows($eksekusi);
            if($hasil > 0)
            {
        	?>
        	    <br/>
        	    <font class="h-title">Email Subscribed: <b><?php echo $hasil; ?></b></font>
            <?php
            }
            //HITUNG POST
            $hitung_post = "SELECT * FROM post";
            $eksekusi2 = $mas_class->mas_query($masdb, $hitung_post);
            $hasil2 = mysqli_num_rows($eksekusi2);
            if($hasil2 > 0)
            {
            	?>
            	<br/>
            	<font class="h-title">Total Post: <b><?php echo $hasil2; ?></b></font>
                <?php
            }
        }
        ?>
      </div>
    </div>
    <!--KONTENT WEBSITE-->
    <div class="d-content-">