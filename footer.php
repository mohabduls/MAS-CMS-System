<!--Footer-->
<div class="container-fluid bg-dark text-light mt-3 p-3">
    <div class="row">
    	<div class="col-sm-4" id="morecat">
            <h4 class="p-1 text-white border border-left-0 border-top-0 border-right-0 border-bottom-1"><?php echo $category_lang; ?></h4>        
            <?php
			$query_cat = "SELECT * FROM category ORDER BY id";
			$result = $mas_class->mas_query($masdb, $query_cat);
			if(mysqli_num_rows($result) < 1)
			{
				?>
				<span class="dropdown-item"><?php echo $no_category_lang; ?></span>
				<?php
			}
			while($row = mysqli_fetch_assoc($result))
			{
				$linkjudul = gantiSpasi($row['category']);
				$name_cat = $row['category'];
				$id_cat = $row['id'];
					
				//Total cat
				$qcat = "SELECT COUNT(category) AS tcat FROM post WHERE category='$name_cat'";
				$q = $mas_class->mas_query($masdb, $qcat);
				$r = mysqli_fetch_array($q);
				$hasil = $r['tcat'];
				?>
				<a class="text-white" href="cat.asp?category=<?php echo $linkjudul; ?>"><?php echo $name_cat; ?> <span class="badge badge-light"><?php echo $hasil; ?></span></a><br/>
				<?php
			}
		?>
			<h4 class="p-1 text-white border border-left-0 border-top-0 border-right-0 border-bottom-1"><?php echo $subscribe_lang; ?></h4>
            <span><?php echo $subscribe_desc_lang; ?></span><br/>
            <form method="POST" class="form-inline my-2 my-lg-0" action="home.asp">
		    	<input class="form-control mr-sm-2" type="email" placeholder="yourname@example.com" name="subscribe">
		    </form>
			<?php
			if(isset($_POST['subscribe']))
			{
				$email = masFilterSearch($_POST['subscribe']);
				$emailmd5 = md5($email);
				$sqlcheck = "SELECT email_md5 FROM email WHERE email_md5='$emailmd5'";
				$sqladd = "INSERT INTO email(email, email_md5) VALUES('$email', '$emailmd5')";
				
				$query_mail = $mas_class->mas_query($masdb, $sqlcheck);
				$result = mysqli_num_rows($query_mail);
				if($result == 1)
				{
					?>
						<span class="text-danger"><?php echo $has_subs_lang; ?></span>
					<?php
				}
				else
				{
					$mas_class->mas_query($masdb, $sqladd);
					?>
						<span class="text-success"><?php echo $subs_success_lang; ?></span>
					<?php
				}
			}
			?>
			<h4 class="p-1 text-white border border-left-0 border-top-0 border-right-0 border-bottom-1"><?php echo $unsubscribe_lang; ?></h4>
            <span><?php echo $unsubscribe_desc_lang; ?></span><br/>
            <form method="GET" class="form-inline my-2 my-lg-0" action="home.asp">
		    	<input class="form-control mr-sm-2" type="email" placeholder="yourname@example.com" name="unsubscribe">
		    </form>
			<?php
			if(isset($_GET['unsubscribe']))
			{
				$getmail = mysqli_real_escape_string($masdb, md5($_GET['unsubscribe']));
				$checkemail = "SELECT * FROM email WHERE email_md5='$getmail'";
				$run = $mas_class->mas_query($masdb, $checkemail);
				if(mysqli_num_rows($run) == 0)
				{
				?>
					<span class="text-danger"><?php echo $unsubs_fail_lang; ?></span>
				<?php
				}
				else
				{
					$delmail = "DELETE FROM email WHERE email_md5='$getmail'";
					$mas_class->mas_query($masdb, $delmail);
					?>
					<span class="text-success"><?php echo $unsubs_lang; ?></span>
				<?php
				}
			}
			?>
        </div>
        <div class="col-sm-4">
            <h4 class="p-1 text-white border border-left-0 border-top-0 border-right-0 border-bottom-1"><?php echo $about_us_lang; ?></h4>        
            <span><?php echo $slogan3_lang; ?></span>
        </div>
        <div class="col-sm-4">
        	<h4 class="p-1 text-white border border-left-0 border-top-0 border-right-0 border-bottom-1">Copyright</h4>
            <span>Copyright © 2020, <b><?php echo $sitename; ?></b>, <br/> Made with Love in Indramayu, Indonesia.<br/> Powered By MASCMS Content Management System.</span>
        </div>
    </div>
</div>
</body>
</html>