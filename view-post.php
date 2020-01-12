<?php
/*
Copyright 09-Januari-2020
MASCMS V1.0 (MAS Content Management System)
Coded by : Mohamad Abdul Sobur
Based on PHP NATIVE
*/

include("header.php");

//TIMEZONE
date_default_timezone_set('Asia/Jakarta');

$getpermalink = mysqli_real_escape_string($masdb, $_GET['title']);
$querylink = "SELECT * FROM post WHERE permalink='$getpermalink'";
//PERMALINK GET
$exec = $mas_class->mas_query($masdb, $querylink);
$permalink = mysqli_fetch_assoc($exec);
$date = $permalink['date'];
$post_permalink = $permalink['permalink'];

//DATE TYPE / TIPE TANGGAL (ID/EN)
if($_SESSION['lang'] == $lang_en)
{
	$date_post = $date;
}
else
{
	$date_post = masDateFormat($date);
}

//GET NAME OF THIS PAGE
$get_pages = $_SERVER['PHP_SELF'];

//COMMENTS
if(isset($_POST['comments_submit']))
{
	$u_name = masHardFilter(masIsRealString(mysqli_real_escape_string($masdb, $_POST['comments_name'])));
	$u_email = masHardFilter(masIsRealString(mysqli_real_escape_string($masdb, $_POST['comments_email'])));
	$u_comments = masHardFilter(masIsRealString(mysqli_real_escape_string($masdb, $_POST['comments_fill'])));
	$u_subs = masHardFilter(masIsRealString(mysqli_real_escape_string($masdb, $_POST['comments_accept_notification'])));
	$u_captcha = masHardFilter(masIsRealString(mysqli_real_escape_string($masdb, $_POST['comments_captcha'])));
	$u_permalink = mysqli_real_escape_string($masdb, $_POST['post_permalink']);
	
	//CHECK IF PERMALINK POST ARE AVAILABLE
	$sql_check_permalink = "SELECT * FROM post WHERE permalink='$u_permalink'";
	$exec_check = $mas_class->mas_query($masdb, $sql_check_permalink);
	if($u_captcha != $_SESSION['captcha'])
	{
		?>
		<div class="alert alert-warning p-2 container">
			<p><?php echo $wrong_captcha_lang; ?></p>
		</div>
		<?php
	}
	elseif(mysqli_num_rows($exec_check) == 0)
	{
		header("location: home.asp");
	}
	else
	{
		//SQL FOR SAVE A USER COMMENT DATA
		$date_time = date("l d F Y h:i:s A");
		//ANTI SPAM
		$count_comments = strlen($u_comments);
		$count_name = strlen($u_name);
		if($u_subs == true)
		{
			$subs = "YES";
			
			//SQL CHECK IF EMAIL ARE AVAILABLE BEFORE
			$check_email = "SELECT * FROM email WHERE email = '$u_email'";
			$check = $mas_class->mas_query($masdb, $check_email);
			if(mysqli_num_rows($check) == 1)
			{
				//EMAIL ARE AVAILABE
				//CANT SAVE EMAIL BECAUSE WILL BE DUPLICATED!
			}
			else
			{
				//INSERT TO SUBSCRIBE EMAIL
				$email_md5 = md5($u_email);
				$sql_insert_email = "INSERT INTO email (email, email_md5) VALUES ('$u_email', '$email_md5')";
				$mas_class->mas_query($masdb, $sql_insert_email);
			}
		}
		else
		{
			$subs = "NO";
		}
		if(empty($u_name) OR empty($u_email) OR empty($u_comments) OR empty($u_permalink))
		{
			?>
			<div class="alert alert-warning p-2 container">
				<p><?php echo $empty_comments_lang; ?></p>
			</div>
			<?php
		}
		elseif($count_comments < 15)
		{
			?>
				<div class="alert alert-warning p-2 container">
					<p><?php echo $comments_rule_lang; ?></p>
				</div>
			<?php
		}
		elseif($count_comments > 300)
		{
			?>
				<div class="alert alert-warning p-2 container">
					<p><?php echo $comments_max_lang; ?></p>
				</div>
			<?php
		}
		elseif($count_name > 15 OR $count_name < 3)
		{
			?>
				<div class="alert alert-warning p-2 container">
					<p><?php echo $name_max_lang; ?></p>
				</div>
			<?php
		}
		else
		{
			$sql_save_comments = "INSERT INTO user (user_email, user_name, user_comments, comments_date, permalink_title, is_subscribe) VALUES ('$u_email', '$u_name', '$u_comments', '$date_time', '$u_permalink', '$subs')";
			if($mas_class->mas_query($masdb, $sql_save_comments))
			{
				header("location: post.asp?title=$u_permalink");
				
			}
			else
			{
				?>
					<div class="alert alert-warning p-2">
						<p><?php echo $comments_error." : ".mysqli_error($masdb); ?></p>
					</div>
				<?php
			}
		}
	}
}

//START
?>
<!--Content-->

<div class="container text-light bg-primary p-2">
    <span style="font-size: 20px;"><b><? echo $permalink['title']; ?></b></span>
    <div class="bg-primary p1">
		<span class="badge badge-light border-bottom-1 text-primary">
			<svg id="i-clock" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="17" height="17" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3">
    			<circle cx="16" cy="16" r="14" />
   			 <path d="M16 8 L16 16 20 20" />
			</svg>
			<?php echo $date_post; ?>
		</span>
	</div>
</div>
<div style="padding-bottom: 5px; margin-bottom: 5px;" class="container">
<?php
//Show Post
$t = $_GET['title'];
$query_view = "SELECT * FROM post WHERE permalink='$t' LIMIT 1";
$query_view2 = "SELECT * FROM post WHERE permalink='$t'";
$title_post = mysqli_real_escape_string($masdb, $t);
$result = $mas_class->mas_query($masdb, $query_view);
$r = $mas_class->mas_query($masdb, $query_view2);
$cat = mysqli_fetch_assoc($r);
$cat_result = $cat['category'];
$cat_result_strip = gantiSpasi($cat_result);

if(mysqli_num_rows($result) < 1)
{
	?>
	<div class="container text-light text-center bg-info p-2">
		<h5><?php echo $not_available_lang; ?></h5>
	</div>
	<?php
}
while($row = mysqli_fetch_assoc($result))
{
	$linkjudul = $row['title'];
    $id = $row['id'];
	$title = $row['title'];
	$time = $row['date'];
	$cat = $row['category'];
	$hits = $row['hits']+1;
	$keyword = $row['keyword'];
	
	//HITS / KUNJUNGAN
	$hits_add = "UPDATE post SET hits='$hits' WHERE id='$id'";
	$mas_class->mas_query($masdb, $hits_add);
	
	if($_SESSION['lang'] == $lang_en)
	{
		$description = $row['description_en'];
	}
	else
	{
		$description = $row['description'];
	}
	?>
	<div>
		<?php echo $description; ?>
	</div>
	<div class="table-responsive-sm">
		<table class="table table-sm table-dark table-bordered">
			<thead>
				<tr>
					<th scope="col"><span class="text-light"><?php echo $category_lang; ?></span></th>
					<th scope="col"><span class="text-light"><?php echo $hits_lang; ?></th>
					<th scope="col"><span class="text-light"><?php echo $keyword_lang; ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="bg-light"><span class="badge badge-sm badge-primary"><b><a class="text-light" href="cat.asp?category=<?php echo gantiSpasi($cat); ?>"><?php echo $cat; ?></a></b></span></td>
					<td class="bg-light"><span class="badge badge-sm badge-primary"><b><?php echo $hits; ?>×</b></span></td>
					<td class="bg-light">
					<?php
					$tag = masKeywordExploder($keyword);
					foreach($tag as $tags)
					{
					?>
						<span class="badge badge-sm badge-primary">
							<b>
								<a href="home.asp?keyword=<?php echo $tags; ?>" class="text-light"><?php echo $tags; ?></a>
							</b>
						</span>
					<?php
					}
					?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php
	//JUST COUNT COMMENTS
	$sql_count_comments = "SELECT COUNT(permalink_title) AS num_comments FROM user WHERE permalink_title='$post_permalink'";
	$query_count_comments = $mas_class->mas_query($masdb, $sql_count_comments);
	$fetch_count_comments = mysqli_fetch_array($query_count_comments);
	$result_count_comments = $fetch_count_comments['num_comments'];
	?>
	<span class="d-block container text-light bg-dark p-2">
		<svg id="i-msg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="17" height="17" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
   		 <path d="M2 4 L30 4 30 22 16 22 8 29 8 22 2 22 Z" />
		</svg>
   	 <b><?php echo $comments_lang." <b>(".$result_count_comments.")</b>"; ?></b>
	</span>
	<div class="container bg-light text-dark p-1 mb-2">
		<?php
		
		//CHECK IF COMMENTS ARE AVAILABE
		$sql_check_comments = "SELECT * FROM user WHERE permalink_title='$post_permalink' ORDER BY id DESC";
		$query_comments = $mas_class->mas_query($masdb, $sql_check_comments);
		if(mysqli_num_rows($query_comments) == 0)
		{
			?>
			<div class="bg-info p-3 text-light text-center rounded">
				<?php echo $comments_not_available_lang; ?>
			</div>
			<?php
		}
		else
		{
			?>
				<div class="container bg-light text-dark p-1 rounded">
			<?php
			while($comments_data = mysqli_fetch_assoc($query_comments))
			{
				$user_id = $comments_data['id'];
				$user_name = $comments_data['user_name'];
				$user_email = $comments_data['user_email'];
				$user_real_date = $comments_data['comments_date'];
				$user_comments = masIsRealString($comments_data['user_comments']);
				
				//DATEFORMAT (ID/EN)
				if($_SESSION['lang'] == $lang_en)
				{
					$user_date = $user_real_date;
				}
				else
				{
					$user_date = masDateFormat($user_real_date);
				}
				?>
					
					<div class="clearfix bg-info">
						<span class="float-left badge badge-primary badge-md p-2"><b><?php echo $user_name; ?></b></span>
						<span class="float-right badge badge-info badge-md p-2"><?php echo $user_date; ?></span>
					</div>
					<div class="text-dark card shadow-sm rounded-bottom mb-3">
						<?php 
						//COMMENTS BY USER
						echo htmlentities($user_comments);
						
						$sql_reply = "SELECT * FROM admin_reply WHERE comments_id='$user_id' AND post_permalink='$post_permalink'";
						$query_reply = $mas_class->mas_query($masdb, $sql_reply);
						if(mysqli_num_rows($query_reply) > 0)
						{
							while($data = mysqli_fetch_assoc($query_reply))
							{
								$admin_name = $data['administrator'];
								$admin_reply = $data['reply'];
								$admin_date = $data['comments_date'];
								if($_SESSION['lang'] == $lang_en)
								{
									$date_type = $admin_date;
								}
								else
								{
									$date_type = masDateFormat($admin_date);
								}
								?>
								<div lass="clearfix">
									<span style="min-width: 180px; max-width: 380px; font-size: 10px;" class="float-right p-2 m-1 text-light bg-info rounded shadow-sm">
										<?php
										echo "<b>$admin_name</b><br/>";
										echo $admin_reply."<br/>";
										echo "<i>$date_type</i>";
										?>
									</span>
								</div>
							<?php
							}
						}
						?>
					</div>
					
				<?php
			}
			?>
				</div>
			<?php
		}
		
		?>
		<div class="clearfix bg-light text-dark">
			<span class="text-primary">
		</div>
	</div>
	<span class="d-block container text-light bg-dark p-2">
		<svg id="i-compose" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="17" height="17" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
   		 <path d="M27 15 L27 30 2 30 2 5 17 5 M30 6 L26 2 9 19 7 25 13 23 Z M22 6 L26 10 Z M9 19 L13 23 Z" />
		</svg>
   	 <b><?php echo $reply_lang; ?></b>
	</span>
	<div class="container bg-light text-dark p-1 form-group card p-3 shadow shadow-bottom-md mb-5">
		<div class="alert alert-danger">
			<?php echo $comments_rules_lang; ?>
		</div>
		<form action="post.asp?title=<?php echo $post_permalink; ?>" method="POST">
			<?php
			
			//IF THERE'S COOKIE AVAILABLE
			$c_names = $_COOKIE['name'];
			$c_emails = $_COOKIE['email'];
			$c_comments = $_COOKIE['comments'];
			
			?>
			<label for="comments_name"><?php echo $yourname_lang; ?></label>
			<input id="comments_name" min="5" max="50" class="form-control mb-2" type="text" name="comments_name" placeholder="Your Name" value="<?php echo $c_names; ?>">
				
			<label for="comments_email"><?php echo $youremail_lang; ?></label>
			<input id="comments_email" class="form-control mb-2" type="email" name="comments_email" placeholder="youremail@example.com" value="<?php echo $c_emails; ?>">
				
			<label for="comments_fill"><?php echo $yourcomments_lang; ?></label>
			<textarea style="height: 200px;" id="comments_fill" class="form-control mb-2" name="comments_fill" placeholder="<?php echo $reply_lang; ?>"><?php echo $c_comments; ?></textarea>
			
			<div class="input-group mb-2>
				<span class="input-group-addon">
					<img src="captcha.png"/>
				</span>
				<input id="comments_captcha" class="form-control" aria-describedby="basic-addon1" type="number" name="comments_captcha" placeholder="Captcha..?">
				<!--<label for="comments_captcha float-right"><?php echo $captcha_lang; ?></label>-->
			</div>
			<div class="input-group mb-2">
				<span class="input-group-addon mr-2">
					<input type="checkbox" id="subscribe_me" name="comments_accept_notification">
				</span>
				<small><?php echo $accept_notification." <b>".$sitename."</b>"; ?>?</small>
			</div>
			
			<input type="hidden" name="post_permalink" value="<?php echo $post_permalink; ?>">
			<input  type="submit" class="form-control btn btn-primary p-1" name="comments_submit" value="<?php echo $send_lang; ?>">
		</form>
	</div>
    <?php
}
?>
</div>

<!--List update content-->
<span class="d-block container text-light bg-dark p-2">
	<svg id="i-lightning" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="17" height="17" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3">
    	<path d="M18 13 L26 2 8 13 14 19 6 30 24 19 Z" />
	</svg>
    <b><?php echo $latest_update_lang; ?></b>
</span>
<div class="container">
	<div class="row">
<?php
//PAGINATION 
$sql_pagination = "SELECT * from post";
$halaman = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
$mulai = ($page > 1) ? ($page * $halaman) - $halaman : 0;
$result = $mas_class->mas_query($masdb, $sql_pagination);
$total = mysqli_num_rows($result);
$pages = ceil($total/$halaman);
$sql_list_post = "SELECT * FROM post ORDER BY id DESC LIMIT $mulai, $halaman";
//LIST POSTINGAN
$hasil = $mas_class->mas_query($masdb, $sql_list_post);
if(mysqli_num_rows($hasil) > 0)
{
	while($list = mysqli_fetch_assoc($hasil))
    {
    	$linkjudul = gantiSpasi($list['title']);
        $title = $list['title'];
        $permalink = $list['permalink'];
        $description = $list['description'];
        $date = $list['date'];
        $cat = $list['category'];
        $urlthumb = $list['urlthumb'];
        $hits = $list['hits'];
        
        //DATE TYPE / TIPE TANGGAL (ID/EN)
		if($_SESSION['lang'] == $lang_en)
		{
			$date_type = $date;
		}
		else
		{
			$date_type = masDateFormat($date);
		}
        ?>
        <div style="width: 100%; heigh: 300px;" class="mb-3 shadow-sm">
        	<div class="card w-100">
        		<a href="post.asp?title=<?php echo $permalink; ?>">
				<div class="card-body">
					<table>
						<tbody>
							<tr>
								<td>
									<img class="img-fluid img-thumbnail" style="min-width: 120px; min-height: 120px; max-width: 130px; max-height: 130px; margin-right: 3px;" src="<?php echo $urlthumb; ?>" alt="<?php echo $title; ?>"/>
								</td>
								<td>
									<h6 style="margin-bottom: 2px;"  class="text-primary"><b><?php echo $title; ?></b></h6>
   								 <span class="badge badge-success">
										<svg id="i-clock" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="17" height="17" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3">
    										<circle cx="16" cy="16" r="14" />
   										 <path d="M16 8 L16 16 20 20" />
										</svg>
										<?php echo $date_type; ?>
									</span><br/>
   								 <span class="badge badge-dark">
										<svg id="i-tag" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="17" height="17" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
  										  <circle cx="24" cy="8" r="2" />
    										<path d="M2 18 L18 2 30 2 30 14 14 30 Z" />
										</svg>
										<?php echo $cat; ?>
									</span><br/>
									<span class="badge badge-primary">
										<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24">
											<path fill="white" d="M15 12c0 1.654-1.346 3-3 3s-3-1.346-3-3 1.346-3 3-3 3 1.346 3 3zm9-.449s-4.252 8.449-11.985 8.449c-7.18 0-12.015-8.449-12.015-8.449s4.446-7.551 12.015-7.551c7.694 0 11.985 7.551 11.985 7.551zm-7 .449c0-2.757-2.243-5-5-5s-5 2.243-5 5 2.243 5 5 5 5-2.243 5-5z"/>
										</svg>
										<?php echo $hits; ?>
									</span>
   							 </td>
							</tr>
						</tbody>
					</table>
  		  	</div>
				</a>
			</div>
   	 </div>
    <?php
    }
}
?>
	</div>
</div>
<!--End Content-->
<?php
include("footer.php");
?>
