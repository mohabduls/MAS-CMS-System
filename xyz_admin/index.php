<?php
/*
Copyright 09-Januari-2020
MASCMS V1.0 (MAS Content Management System)
Coded by : Mohamad Abdul Sobur
Based on PHP NATIVE
*/

//START SESSION
session_start();

//INCLUDE
include("../database/sql.php");
include("../fungsi/fungsi.php");

//TIMEZONE
date_default_timezone_set('Asia/Jakarta');

//OOP CLASS
include("../database/mascms-db.php");
include("../database/mascms-config.php");

//CHECKING SESSION LOGIN
$site_name = "Admin Dashboard";
include("header_.php");

//DEFINE YOUR USERNAME & PASSWORD FOR LOGIN ADMIN PANEL
$username = "admin";
$password = "admin";

//SQLCHECK TABLE ADMIN
$check_admin = "SELECT * FROM admin";

//OPTION FOR HASH
$options = [
    'cost' => 12,
];

//PASSWORD HAS USING PASSWORD_DEFAULT
$password_hash = md5($password);

$sql_default_admin = "INSERT INTO admin (username, password) VALUES ('$username', '$password_hash')";

$query_admin = $mas_class->mas_query($masdb, $check_admin);
if(mysqli_num_rows($query_admin) == 0)
{
	$mas_class->mas_query($masdb, $sql_default_admin);
}
elseif(empty($_SESSION['username']) && empty($_SESSION['password']))
{
	//QUERY GET USERNAME & ADMIN
	$get_login_info = "SELECT * FROM admin";
	$query_login_result = $mas_class->mas_query($masdb, $get_login_info);
	$fetch_login_info = mysqli_fetch_assoc($query_login_result);
	
	//GET USERNAME & PASSWORDS
	$admin_username = $fetch_login_info['username'];
	$admin_password = $fetch_login_info['password'];
	
	//HALAMAN LOGIN
	if(isset($_POST['submit']))
	{
		$user = $_POST['user'];
		$pass = md5($_POST['pass']);
		//MENGECEK APAKAH USERNAME DAN PASSWORD BENAR
		if($user == $admin_username && $pass == $admin_password)
		{
			//SET THE SESSION
			$_SESSION['username'] = $user;
			$_SESSION['password'] = $pass;
			header('location: index.php');
		}
		//JIKA USERNAME DAN PASSWORD SALAH, MAKA AKAN GAGAL!
		else
		{
		?>
			<div class="d-red">
				<h5 class="h-title">Invalid User, Please input again !</h5>
			</div>
		<?php
		login();
		}
	}
	else
	{
		login();
	}
}
else
{
	//CONTROL
	$query = $mas_class->mas_query($masdb, $sql_check_id_admin);
	if(mysqli_num_rows($query) > 0)
	{
		while($row = mysqli_fetch_assoc($query))
		{
			$id = $row['id'];
			$u = $row['username'];
			$p = $row['password'];
			if($id == 1 && $u = $username && $p == $password)
			{
				//TABEL TELAH ADA
			}
			else
			{
				if($mas_class->mas_query($masdb, $sql_insert_admin))
				{

				}
				else
				{
					echo "Failed to add $username & $password to table admin";
				}
			}
		}
	}
	if(isset($_REQUEST['action']))
	{
		switch($_REQUEST['action'])
		{
			case "add":
			{
				add();
			}
			break;
			case "find":
			{
				find();
			}
			break;
			case "setting":
			{
				setting();
			}
			break;
			case "delpost":
			{
				$postid = $_GET['postid'];
				?>
				<div class="d-content d-padding">
					<div class="d-head-title">
						<h3 class="h-title">Confirm Delete Post</h3>
					</div>
					<p>Are you sure want to delete post with id : <b><?php echo $postid; ?></b> sir?</p>
					<a href="index.php" class="t-green">Cancel delete</a> - <a class="t-red" href="?del_post=<?php echo $postid; ?>">Delete this post</a>
				</div>
				<?php
			}
			break;
			case "delcat":
			{
				$catid = $_GET['catid'];
				?>
				<div class="d-content d-padding">
					<div class="d-head-title">
						<h3 class="h-title">Confirm Delete Category</h3>
					</div>
					<p>Are you sure want to delete category with id : <b><?php echo $catid; ?></b> sir?</p>
					<a href="index.php" class="t-green">Cancel delete</a> - <a class="t-red" href="?del_cat=<?php echo $catid; ?>">Delete this category</a>
				</div>
				<?php
			}
			break;
			case "delshortlink":
			{
				$link_id = $_GET['linkid'];
				?>
				<div class="d-content d-padding">
					<div class="d-head-title">
						<h3 class="h-title">Confirm Delete Shortlink</h3>
					</div>
					<p>Are you sure want to delete category with id : <b><?php echo $link_id; ?></b> sir?</p>
					<a href="index.php" class="t-green">Cancel delete</a> - <a class="t-red" href="?action=shortlink&del=<?php echo $link_id; ?>">Delete this shortlink</a>
				</div>
				<?php
			}
			break;
			case "shortlink":
			{
				shortlink();
				if(isset($_GET['del']))
				{
					$link_id = $_GET['del'];
					$del_link = "DELETE FROM shortlink WHERE id='$link_id'";
					if(!$mas_class->mas_query($masdb, $del_link))
					{
						echo "Failed to delete the shortlink!";
					}
					else
					{
						echo "Shortlink with id : $link_id has been deleted successfuly!";
					}
				}
			}
			break;
			case "getcode":
			{
				$link = $_GET['link'];
				?>
				<div class="d-content d-padding">
					<div class="d-head-title">
						<h3 class="h-title">Code Generator</h3>
					</div>
					<textarea><button class="btn btn-success"><a class="text-white" href="<?php echo $link; ?>" target="_blank">Download [Quality]</a></button></textarea>
				</div>
				<?php
			}
			break;
			case "sendmail":
			{
				if(isset($_POST['send_email']))
				{
					$email_user = $_POST['email_user'];
					$email_subject = $_POST['email_subject'];
					$email_msg = $_POST['email_msg'];
					if(newsMail($email_user, $email_subject, $email_msg) == true)
					{
						echo "Email has been send!";
					}
					else
					{
						echo "Email failed to be send!";
					}
				}
				formmails();
			}
			break;
			case "comments":
			{
				comments();
			}
			break;
			case "add_cat":
			{
				cat();
				$query_cat = "SELECT * FROM category ORDER BY id";
				$result = $mas_class->mas_query($masdb, $query_cat);
				?>
				<div class="d">
					<h3 class="h-title">List Category</h3>
				<?php
				if(mysqli_num_rows($result) < 1)
				{
					?>
					<div class="d-red">
						<h5 class="h-title">Nothing found !</h5>
					</div>
					<?php
				}
				while($row = mysqli_fetch_assoc($result))
				{
					$name_cat = $row['category'];
					$id_cat = $row['id'];
					?>
					<div class="d-list">
						<b><?php echo $name_cat; ?></b><br/>
						<a class="t-red" href="?action=delcat&catid=<?php echo $id_cat; ?>">Delete</a>
					</div>
					<?php
				}
				?>
				</div>
				<?php
				if(isset($_GET['save_cat']))
				{
					$c = mysqli_real_escape_string($masdb, $_GET['category']);
					if(empty($c))
					{
						$c = "Default Category";
					}
					$query_save_cat = "INSERT INTO category (category) VALUES ('$c')";
					if($mas_class->mas_query($masdb, $query_save_cat))
					{
						?>
						<div class="d-content d-padding">
							<div class="d-head-title">
								<h3 class="h-title">Category <b><?php echo $c ?></b> Success added!</h3>
							</div>
						</div>
						<?php
					}
					else
					{
						?>
						<div class="d-content d-padding">
							<div class="d-head-title">
								<h3 class="h-title">Category <b><?php echo $c ?></b> failed to added!</h3>
							</div>
						</div>
						<?php
					}
				}
			}
			break;
		}
	}
	//PAGINATION
	$sql_pagination = "SELECT * from post";
    $halaman = 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
    $mulai = ($page > 1) ? ($page * $halaman) - $halaman : 0;
    $result = $mas_class->mas_query($masdb, $sql_pagination);
    $total = mysqli_num_rows($result);
    $pages = ceil($total/$halaman);
    $sql_post = "SELECT * FROM post ORDER BY id DESC LIMIT $mulai, $halaman";
    
	//DAFTAR POSTINGAN
	$query_list_post = $mas_class->mas_query($masdb, $sql_post);
	if(mysqli_num_rows($query_list_post) > 0)
	{
		?>
		<div class="d">
			<h3 class="h-title">List Post</h3>
		<?php
		while($list = mysqli_fetch_assoc($query_list_post))
		{
			//JUDUL DAN DESKRIPSI POSTINGAN
			$linkjudul = gantiSpasi($list['title']);
			$permalink = $list['permalink'];
            $id = $list['id'];
			$title = $list['title'];
			$des = $list['description'];
			$cat = $list['category'];
			$hits = $list['hits'];
			$description = substr($des, 0, 100);
			$t_strip = gantiSpasi($title);
			$t_des = gantiSpasi($des);
			echo "<div class=\"d-list\"><b><a class=\"color-list\" href=\"../post.asp?title=$permalink\">$title</a></b><br/><b>$cat</b> Hits : $hits<br/><a class=\"t-red\" href='?action=delpost&postid=$id'><b>Delete Post</b></a> | <a href='?edit_post=$id'><b>Edit Post</b></a></div>";
		}
		?>
		</div>
		<?php
	}
	else
	{
		"You don't have any posted yet!<br/>Please add some post on form bellow :<br/>";
    }
    //PENCARIAN
    if(isset($_GET['find']))
	{
		$q = $_GET['keyword'];
		$q = mysqli_real_escape_string($masdb, $q);
		$sql_find = "SELECT * FROM post WHERE title LIKE '%{$q}%' OR description LIKE '%{$q}%'";
		$result_find = $mas_class->mas_query($masdb, $sql_find);
		if(mysqli_num_rows($result_find) < 1)
		{
		?>
			<div class="d-red">
				<h5 class="h-title">Nothing found !</h5>
			</div>
		<?php
		}
		?>
		<div class="d">
			<h3 class="h-title">Keyword : <?php echo $q; ?></h3>
			
		<?php
		while($row = mysqli_fetch_assoc($result_find))
		{
			$linkjudul = gantiSpasi($row['title']);
			$id = $row['id'];
			$title = $row['title'];
			$permalink = $row['permalink'];
			$cat = $row['category'];
			$t_strip = gantiSpasi($title);
			$t_des = gantiSpasi($description);
			$hits = $row['hits'];
			?>
			
				<div class="d-list">
					<b><a class="color-list" href="../post?title=<?php echo $permalink; ?>"><?php echo $title; ?></a></b><br/>
					Category : <font class="t-green"><?php echo $cat; ?></font> - Hits: <?php echo $hits; ?><br/>
					<a class="t-red" href="?action=delpost&postid=<?php echo $id;?>"><b>Delete Post</b></a> | <a href="?edit_post=<?php echo $id; ?>"><b>Edit Post</b></a>
				</div>
		<?php
		}
		?>
		</div>
		<?php
	}
    ?>
    <center>
    <?php
    for($i=1; $i<=$pages; $i++)
    {
    	echo "<a class=\"a-menu\" href=\"?page=$i\">$i</a> ";
    }
    ?>
    </center>
    <?php
	//MENYIMPAN POSTINGAN
	if(isset($_POST['save_post']))
	{
		$t = mysqli_real_escape_string($masdb, $_POST['title']);
		$permalink = masStringToStrip(strtolower($t));
		$tt = gantiSpasi($t);
		$d = mysqli_real_escape_string($masdb, $_POST['description']);
		$d_en = mysqli_real_escape_string($masdb, $_POST['description_en']);
		$cat = mysqli_real_escape_string($masdb, $_POST['cat']);
		$urlthumb = mysqli_real_escape_string($masdb, $_POST['urlthumb']);
		$keyword = mysqli_real_escape_string($masdb, $_POST['keyword']);
		$time = date("l d F Y h:i:s A");
		//SQL MENAMBAH POST
		$sql_add_post = "INSERT INTO post (title, description, description_en, keyword, date, category, urlthumb, permalink) VALUES ('$t', '$d', '$d_en', '$keyword', '$time', '$cat', '$urlthumb', '$permalink')";
		//EKSEKUSI
		if($mas_class->mas_query($masdb, $sql_add_post))
		{
			echo "Success Add a post!";
		}
		else
		{
			echo "Failed to adding a post: " . mysqli_error($masdb);
		}
		//
	}
	
	//
	//EKSEKUSI HAPUS POST
    if(isset($_GET['del_post']))
    {
        $id = $_GET['del_post'];
        $sql_del = "DELETE FROM post WHERE id='$id'";
        if($mas_class->mas_query($masdb, $sql_del))
        {
            echo "Post deleted succesfully!";
        }
        else
        {
            echo "Post could not be delete: ".mysqli_error($masdb);
        }
    }
    //EKSEKUSI DELETE CATEGORY
    if(isset($_GET['del_cat']))
    {
        $id = $_GET['del_cat'];
        $sql_del = "DELETE FROM category WHERE id=$id";
        if($mas_class->mas_query($masdb, $sql_del))
        {
            echo "Post deleted succesfully!";
            header('location:index.php');
        }
        else
        {
            echo "Post could not be delete: ".mysqli_error($masdb);
        }
    }
    //EDIT POST
    if(isset($_GET['edit_post']))
    {
        //GET DATA FROM GET ID
        $id = $_GET['edit_post'];
        //SQL GET CAT
        $sql_view_cat = "SELECT * FROM category ORDER BY id";
        //SQL GET DES TITLE
        $sql_view = "SELECT * FROM post WHERE id='$id'";
        $r = $mas_class->mas_query($masdb, $sql_view_cat);
        $view = $mas_class->mas_query($masdb, $sql_view);
        $result_view = mysqli_fetch_assoc($view);
        $t = $result_view['title'];
        $d = $result_view['description'];
        $d_en = $result_view['description_en'];
        $thumb = $result_view['urlthumb'];
        $keyword = $result_view['keyword'];
        //TAMPILKAN
        echo "
		<div class=\"d\">
			<h1 class=\"h-title\">Edit Post</h1>
      	  <form action=\"index.php\" method=\"POST\">
        		<input type=\"text\" name=\"title\" value=\"$t\">
        		<br/>
        		<h4 class=\"h-title\">Indonesian Content</h4>
	   		 <textarea id=\"ckeditor\" class=\"ckeditor\" name=\"description\">$d</textarea>
				<br/>
				<h4 class=\"h-title\">English Content</h4>
				<textarea id=\"ckeditor\" class=\"ckeditor\" name=\"description_en\">$d_en</textarea>
				<br/>
				<h4 class=\"h-title\">Keywords, separate with coma (,)</h4>
				<input type=\"text\" name=\"keyword\" value=\"$keyword\">
				<h4 class=\"h-title\">Url Thumbnails</h4>
				<input type=\"url\" name=\"urlthumb\" value=\"$thumb\">
				<h4 class=\"h-title\">Category</h4>
				<select name=\"cat\">";
       		 while($rslt = mysqli_fetch_assoc($r))
      		  {
        			$c = $rslt['category'];
  	    		  echo "<option name=\"cat\" value=\"".$c."\">".$c."</option>";
      		  }
      		  echo "
				</select>
				<input type=\"submit\" name=\"edit\" value=\"EDIT\">
	  		  <input type=\"hidden\" name=\"id\" value=\"$id\">
       	 </form>
		</div>";
    }
    //EKSEKUSI EDIT
    if(isset($_POST['edit']))
    {
	    //MENGAMBIL DATA DARI METODE POST
        $id = $_POST['id'];
        $title = $_POST['title'];
        $permalink = masStringToStrip(strtolower($title));
        $description = $_POST['description'];
        $description_en = $_POST['description_en'];
        $time = date("l, d F Y, h:i:s A");
        $cat = $_POST['cat'];
        $thumb = $_POST['urlthumb'];
        //SQL UPDATE DATA
        $sql_edit_post = "UPDATE post SET title='$title', description='$description', description_en='$description_en', date='$time', category='$cat', urlthumb='$thumb', permalink='$permalink' WHERE id='$id'";
        if(isset($_POST['edit']))
        {
            if($mas_class->mas_query($masdb, $sql_edit_post))
            {
                echo "Post $title successfully edited !";
            }
            else
            {
                echo "$title failed to edited ! :".mysqli_error($masdb);
            }
        }
    }
    
    //SETTING
    if(isset($_POST['setting_submit']))
    {
    	$meta = mysqli_real_escape_string($masdb, $_POST['meta_set']);
    	$maintenance = $_POST['mt_set'];
    	$ads_top = mysqli_real_escape_string($masdb, $_POST['ads_top']);
    	$ads_bottom = mysqli_real_escape_string($masdb, $_POST['ads_bottom']);
    	$admin_username = mysqli_real_escape_string($masdb, $_POST['username_set']);
    	$admin_password = md5(mysqli_real_escape_string($masdb, $_POST['password_set']));
    	$admin_id = mysqli_real_escape_string($masdb, $_POST['admin_id']);
    
    	$sql_update_setting = "UPDATE site SET meta='$meta', maintenance='$maintenance', ads_top='$ads_top', ads_bottom='$ads_bottom' WHERE id=1";
    	$sql_update_admin = "UPDATE admin SET username='$admin_username', password='$admin_password' WHERE id='$admin_id'";
    	if($mas_class->mas_query($masdb, $sql_update_setting) && $mas_class->mas_query($masdb, $sql_update_admin))
    	{
    		echo "Site setting has been saved!";
    	}
    	else
    	{
    		echo "Can't save setting!".mysqli_error($masdb);
    	}
    }
    //SHORTLINK
    if(isset($_POST['save_shortlink']))
    {
    	$destination_link = mysqli_real_escape_string($masdb, $_POST['shortlink']);
    	if(empty($destination_link))
    	{
    		$destination_link = $ssl.$domain."/";
    	}
    	$sql_save_link = "INSERT INTO shortlink(destination_link) VALUES('$destination_link')";
    	if(!$mas_class->mas_query($masdb, $sql_save_link))
   	 {
    		echo "Failed to add a shortlink!";
 	   }
		else
		{
			echo "Success add a shortlink!";
		}
    }
}

//KELUAR
if(isset($_GET['logout']))
{
	$logout = $_GET['logout'];
	if($logout == "1")
	{
		logout();
	}
}
include("footer_.php");
//FUNGSI FUNGSI
function login()
{
	include("interface/form_login.html");
}
function logout()
{
	session_unset("username");
	session_unset("password");
	session_destroy();
    header('location: index.php');
}
function find()
{
	include("interface/form_search_admin.html");
}
function add()
{
	include("interface/form_add_post.php");
}
function cat()
{
	include("interface/form_add_category.html");
}
function setting()
{
	include("interface/form_setting.php");
}
function shortlink()
{
	include("interface/form_shortlink.php");
}
function formmails()
{
	include("interface/form_mails.php");
}
function comments()
{
	include("interface/comment_list.php");
}
?>