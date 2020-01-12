<?php
include("fungsi/fungsi.php");

//OOP CLASS
include("database/mascms-db.php");
include("database/mascms-config.php");

//Session Start
session_start();

//File Lang
$name_lang_id = "lang/lang-id.php";
$name_lang_en = "lang/lang-en.php";

//Language
$lang_id = "id";
$lang_en = "en";

//Default Language
if(empty($_SESSION['lang']))
{
	$_SESSION['lang'] = "id";
}

if(isset($_GET['lang']))
{
	$get_lang = $_GET['lang'];
		
	if($get_lang == $lang_id)
	{
		$_SESSION['lang'] = $lang_id;
	}
		
	if($get_lang == $lang_en)
	{
		$_SESSION['lang'] = $lang_en;
	}
}

if($_SESSION['lang'] == $lang_en)
{
	include("$name_lang_en");
}
else
{
	include("$name_lang_id");
}

//Check if site is under maintenance or not.
$sql_mt = "SELECT maintenance FROM site WHERE id=1";
$maintenance = $mas_class->mas_query($masdb, $sql_mt);
$mt_fetch = mysqli_fetch_assoc($maintenance);
$mt_result = $mt_fetch['maintenance'];
if($mt_result == "ON")
{
	header("location: maintenance.asp");
}

//COOKIE SET!
if(isset($_POST['comments_submit']))
{
	$ck_name = masHardFilter(masIsRealString(mysqli_real_escape_string($masdb, $_POST['comments_name'])));
	$ck_email = masHardFilter(masIsRealString(mysqli_real_escape_string($masdb, $_POST['comments_email'])));
	$ck_comments = masHardFilter(masIsRealString(mysqli_real_escape_string($masdb, $_POST['comments_fill'])));
	
	//COOKIE
	setcookie("name", $ck_name, time() - 3600);
	setcookie("email", $ck_email, time() - 3600);
	setcookie("comments", $ck_comments, time() - 3600);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>
		<?php
		$thispage = $_SERVER['PHP_SELF'];
		if($thispage == "/index.php")
		{
			if(isset($_GET['keyword']))
			{
				$key = $_GET['keyword'];
				if($key == masIllegalArray())
				{
					echo "Keyword not found!";
				}
				else
				{
					echo masFilterSearch($_GET['keyword']);
				}
			}
			else
			{
				echo $title_index_lang;
			}
		}
		if($thispage == "/show-cat.php")
		{
			$getcat = $_GET['category'];
			if(!empty($getcat))
			{
				echo $category_lang." ".gantiUnderScore($getcat);
			}
			else
			{
				header("location: home.asp");
			}
		}
		if($thispage == "/view-post.php")
		{
			$gettitle = $_GET['title'];
			$checktitle = "SELECT * FROM post WHERE permalink='$gettitle'";
			$exec = $mas_class->mas_query($masdb, $checktitle);
			$fetch = mysqli_fetch_assoc($exec);
			if(!empty($gettitle))
			{
				echo $fetch['title'];
			}
			else
			{
				header("location: home.asp");
			}
		}
		if($thispage == "/mascms-go.php")
		{
			echo $go_lang;
		}
		?>
  </title>
	<?php
	//META DESCRIPTION
	$sql_meta = "SELECT * from site WHERE id=1";
	
	$meta = $mas_class->mas_query($masdb, $sql_meta);
	
	$pages = $_SERVER['PHP_SELF'];
	if($pages == "/view-post.php")
	{
		$get_permalink = $_GET['title'];
		$sql_get_description = "SELECT * FROM post WHERE permalink='$get_permalink'";
		$exec = $mas_class->mas_query($masdb, $sql_get_description);
		$fetch = mysqli_fetch_assoc($exec);
		$desc = $fetch['description'];
		$total_chars = strlen($desc);
		?>
		<meta name="description" content="<?php echo substr(strip_tags($desc), 150,600); ?>"/>
		<?php
	}
	else
	{
		?>
		<meta name="description" content="<?php echo strip_tags($slogan3_lang);?>"/>
		<?php
	}
	
	while($d = mysqli_fetch_assoc($meta))
	{
		echo $d['meta'];
	}
	?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../theme/anim.css">
  <!--BOOTSTRAP-->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"/>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<!--heading-->
<div class="bg-dark" style="margin-bottom: 72px;">
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top p-3">
    	<a class="navbar-brand" href="/home.asp">
			<img width="100" height="30" class="d-inline-block align-top" src="/logo/korenime.png" alt="<?php echo $sitename; ?>"/>
		</a>
  	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
    		<span class="navbar-toggler-icon"></span>
    	</button>
  	  <div class="collapse navbar-collapse" id="navbar">
    		<div class="navbar-nav">
        		<a class="nav-item nav-link" href="/home.asp"><?php echo $home_lang; ?></span></a>
        		<?php
        		$query_cat = "SELECT category FROM category";
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
					<a class="nav-item nav-link" href="cat.asp?category=<?php echo $linkjudul; ?>"><?php echo $name_cat; ?> <span class="badge badge-light"><?php echo $hasil; ?></span></a>
					<?php
				}
				?>
                <a class="nav-item nav-link" href="#morecat"><?php echo $more_category_lang; ?></span></a>
                <div class="dropdown">
  			  	<a class="btn btn-dark dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    					<?php echo $language_lang; ?>
  				  </a>
  				  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
   					 <a class="dropdown-item" href="home.asp?lang=id">Indonesia</a>
    					<a class="dropdown-item" href="home.asp?lang=en">English</a>
 				   </div>
				</div>
                <div class="float-sm-right">
				    <form method="GET" class="form-inline my-2 my-lg-0" action="home.asp">
					    <input class="form-control mr-sm-2" type="search" placeholder="Search" name="keyword">
				    </form>
			    </div>
    		</div>
		</div>
	</nav>
</div>
<div class="animated-text container">
	<div class="line"><b><?php echo $recomendation_lang; ?></b></div>
	<?php
	$sql_recomendation = "SELECT * FROM post ORDER BY hits DESC LIMIT 5";
	$result = $mas_class->mas_query($masdb, $sql_recomendation);
	while($row = mysqli_fetch_assoc($result))
	{
	?>
	<div class="line"><a class="text-white" href="post.asp?title=<?php echo $row['permalink']; ?>"><?php echo cutString($row['title'], 0, 41); ?></a></div>
	<?php
	}
	?>
</div>

<!--End Heading-->