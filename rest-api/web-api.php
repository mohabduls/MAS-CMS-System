<?php
//header status
header("Content-type: text/json");
//Include
include("../database/mascms-db.php");
include("../database/mascms-config.php");
include("../fungsi/fungsi.php");
if(!empty($_GET['api']) && !empty($_GET['cat']))
{
	$data_md5 = md5($sitename);
	$get = $_GET['api'];
	$tipe = gantiUnderScore($_GET['cat']);
	if($tipe == "all")
	{
		$post = "SELECT title, description, description_en, urlthumb, date, category FROM post ORDER BY id DESC";
	}
	else
	{
		$post = "SELECT title, description, description_en, urlthumb, date, category FROM post WHERE category='$tipe' ORDER BY id DESC";
	}
	//eksekusi
	$dbh = new PDO("mysql:host=$db_host; dbname=$db_name",$db_user, $db_pass);
	$db = $dbh->prepare($post);
	$db->execute();
	$data = $db->fetchAll(PDO::FETCH_ASSOC);
	if($get == $data_md5)
	{
		$d = json_encode($data);
		echo $d;
	}
	else
	{
		header("location: ../home.asp");
	}
}
else
{
	header("location: ../home.asp");
}
?>