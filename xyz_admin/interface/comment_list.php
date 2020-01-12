<?php
include("../database/mascms-config.php");


$page = isset($_GET['page_comments']) ? (int)$_GET['page_comments']: 1;
$list_per_page = 2;
$start = ($page > 1) ? ($page * $list_per_page) - $list_per_page : 0;
//SQL
$sql_get_all = "SELECT * FROM user";
$query_total = $mas_class->mas_query($masdb, $sql_get_all);
$total = mysqli_num_rows($query_total);

$sql_get_comments = "SELECT * FROM user ORDER BY id DESC LIMIT $start, $list_per_page";
$query_comments = $mas_class->mas_query($masdb, $sql_get_comments);
$number_of_comments = mysqli_num_rows($query_comments);

$pages = ceil($total/$list_per_page);

if($number_of_comments > 0)
{
	//ACTIONS..
	if(isset($_REQUEST['op']))
	{
		switch($_REQUEST['op'])
		{
			//REPLY COMMENTS
			case "reply":
			{
				$id_comments = $_GET['id_comments'];
				$user_name = $_GET['user_name'];
				$permalink_id = $_GET['id_permalink'];
				
				$time = date("l d F Y h:i:s A");
				
				//REPLY!
				if(isset($_POST['admin_submit_reply']))
				{
					$get_comments_id = mysqli_real_escape_string($masdb, $_POST['comments_id']);
					$get_admin_name = mysqli_real_escape_string($masdb, $_POST['admin_name']);
					$get_admin_reply = mysqli_real_escape_string($masdb, $_POST['admin_reply']);
					$get_permalink_id = mysqli_real_escape_string($masdb, $_POST['permalink_title']);
					
					$sql_update_reply = "INSERT INTO admin_reply (administrator, post_permalink, comments_id, reply, comments_date) VALUES ('$get_admin_name', '$get_permalink_id', '$get_comments_id', '$get_admin_reply', '$time')";
					if($mas_class->mas_query($masdb, $sql_update_reply))
					{
						echo "Success add a reply!";
					}
					else
					{
						echo "Failed add a reply!".mysqli_error($masdb);
					}
				}
				?>
				
				<div class="d-content d-padding">
					<div class="d-head-title">
						<h3 class="h-title">Reply comment to user name <b><?php echo $user_name; ?></b></h3>
					</div>
					<form action="?action=comments&op=reply" method="POST">
						<input type="text" name="admin_name" placeholder="Administrator Name">
						<textarea name="admin_reply" placeholder="Reply..."></textarea>
						<input type="hidden" name="comments_id" value="<?php echo $id_comments; ?>">
						<input type="hidden" name="permalink_title" value="<?php echo $permalink_id; ?>">
						<input type="submit" name="admin_submit_reply" value="Reply">
					</form>
					
				</div>
				
				<?php
			}
			break;
			//DELETE COMMENTS
			case "delete":
			{
				$id_comments = $_GET['id_comments'];
				$user_name = $_GET['user_name'];
				$permalink_id = $_GET['id_permalink'];
				$sql_del_comments = "DELETE FROM user WHERE id='$id_comments'";
				if(empty($_GET['confirm']))
				{
					?>
						<!--NEED SOME CONFIRMATION-->
						<div class="d-content d-padding">
							<div class="d-head-title">
								<h3 class="h-title">Confirm Delete Comments</h3>
							</div>
							<p>Are you sure want to delete comments with id : <b><?php echo $id_comments; ?></b> and name : <b><?php echo $user_name; ?></b> sir?</p>
							<a href="index.php" class="t-green">Cancel delete</a> - <a class="t-red" href="?action=comments&op=delete&id_comments=<?php echo $id_comments."&user_name=".$user_name."&id_permalink=".$permalink_id."&confirm=yes"; ?>">Confirm Delete</a>
						</div>
					<?php
				}
				elseif($_GET['confirm'] == "yes")
				{
					if($mas_class->mas_query($masdb, $sql_del_comments))
					{
						//SUCCESS DELETE
						echo "Delete Success!";
					}
					else
					{
						//FAILED DELETE
						echo "Delete Failed! : ".mysqli_error($masdb);
					}
				}
			}
			break;
		}
	}
	
	?>
		<div class="d-content d-padding">
			<div class="d-head-title">
				<h3 class="h-title">Comments <?php echo $number_of_comments; ?></h3>
			</div>
	<?php
	
	//AVAILABLE COMMENTS
	while($comments = mysqli_fetch_assoc($query_comments))
	{
		$comments_id = $comments['id'];
		$comments_name = $comments['user_name'];
		$comments_permalink = $comments['permalink_title'];
		$comments_fill = $comments['user_comments'];
		$comments_date = $comments['comments_date'];
		
		//IF COMMENTS HAS BEEN REPLY
		$sql_check_reply = "SELECT * FROM admin_reply WHERE comments_id='$comments_id'";
		$query_check_reply = $mas_class->mas_query($masdb, $sql_check_reply);
		$fetch_data = mysqli_fetch_assoc($query_check_reply);
		
		//ANSWER BY
		$admin_name = $fetch_data['administrator'];
		if(mysqli_num_rows($query_check_reply) > 0)
		{
			$status = "<span class=\"t-green\"><b>Answered</b> by <b>$admin_name</b></span>";
		}
		else
		{
			$status = "<span class=\"t-red\"><b>Unanswered</b></span>";
		}
		?>
			
			<div class="d-padding" style="border-bottom: 2px solid black;">
				<span class="t-blue">Name : <b><?php echo $comments_name; ?></b></span><br/>
				<span class="t-dark-blue" >Comments : <b><?php echo $comments_fill; ?></b></span><br/>
				<span class="t-red">Date : <b><?php echo $comments_date; ?></b></span><br/>
				<span class="t-red">ID : <b><?php echo $comments_id; ?></b></span><br/>
				<span class="t-red">Contents : <b><a href="../post.asp?title=<?php echo $comments_permalink; ?>"><?php echo $comments_permalink; ?></a></b></span><br/>
				Status : <?echo $status; ?><br/>
				<a class="t-green" href="?action=comments&op=reply&id_comments=<?php echo $comments_id."&user_name=".$comments_name."&id_permalink=".$comments_permalink; ?>">Reply</a> - <a class="t-red" href="?action=comments&op=delete&id_comments=<?php echo $comments_id."&user_name=".$comments_name."&id_permalink=".$comments_id; ?>">Delete</a>
			</div>
		
		<?php
	}
	if((int)$_GET['page_comments'] == 1)
	{
		?>
		<a class="a-menu" href="#">First</a>
		<?php
	}
	else
	{
		?>
		<a class="a-menu" href="?action=comments&page_comments=<?php echo $page - 1; ?>">Prev</a>
		<?php
	}
	/*for($i = 1; $i <= pages; $i++)
	{
		?>
		<a class="a-menu" href="?action=comments&page_comments=<?php echo $i; ?>"><?php echo $i; ?></a>
		<?php
	}*/
	
	if($_GET['page_comments'] > 0 AND $_GET['page_comments'] <= $pages)
	{
		?>
		<a class="a-menu" href="?action=comments&page_comments=<?php echo $_GET['page_comments']+1; ?>"><?php echo $_GET['page_comments']+1; ?></a>
		<a class="a-menu" href="?action=comments&page_comments=<?php echo $_GET['page_comments']+2; ?>"><?php echo $_GET['page_comments']+2; ?></a>
		<a class="a-menu" href="?action=comments&page_comments=<?php echo $_GET['page_comments']+3; ?>"><?php echo $_GET['page_comments']+3; ?></a>
	<?php
	}
	else
	{
		?>
		<a class="a-menu" href="#"><?php echo $_GET['page_comments']; ?></a>
		<?php
	}
	if((int)$_GET['page_comments'] == $pages)
	{
		?>
		<a class="a-menu" href="#">-</a>
		<?php
	}
	else
	{
		?>
		<a class="a-menu" href="?action=comments&page_comments=<?php echo $_GET['page_comments'] + 1; ?>">Next</a>
		<?php
	}
	?>
	<a class="a-menu" href="?action=comments&page_comments=<?php echo $pages; ?>">Last</a>
		</div>
	<?php
}
else
{
	?>
	<div style="text-position: justify;" class="d-content d-padding">
		<div class="d-head-title">
			<h3 class="h-title">Comments</h3>
			<span class="t-dark-blue">There's no comments available !</span>
		</div>
	</div>
	<?php
}
?>