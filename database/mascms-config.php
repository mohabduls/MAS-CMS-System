<?php
include("sql.php");
//SETTING UP DATABASE
$db_host = "localhost:3306";
$db_user = "root";
$db_pass = "";
$db_name = "korenime"; //DEFINE YOUR DATABASE NAME

//MASCMS DB Prepare
$mas_class = new MASCMS_DB();
$masdb = $mas_class->masdb($db_host, $db_user, $db_pass, $db_name);


//SITENAME
$sitename = "KoreNime";
//CMS CODE
$version_code = "MASCMS V1.01 2020";
//DOMAIN
$domain = "korenime.byethost31.com";
$ssl = "http://";
//YEARS
$tahun = date("Y");
//SITE EMAIL 
$myemail = "admin@korenime.byethost31.com";

//CREATING DATABASE
if(!$mas_class->mas_query($masdb, $sql_create_db))
{
	
}
//SELECT DB 
mysqli_select_db($masdb, $dbname);
if(!$mas_class->mas_query($masdb, $sql_create_table_admin))
{
	echo "Could not create table admin";
}

if(!$mas_class->mas_query($masdb, $sql_create_table_post))
{
	echo "Could not create table post";
}

if(!$mas_class->mas_query($masdb, $sql_create_table_category))
{
	echo "Could not create table category";
}

if(!$mas_class->mas_query($masdb, $sql_create_table_email))
{
	echo "Could not create table email";
}

if(!$mas_class->mas_query($masdb, $sql_create_table_site_info))
{
	echo "Could not create table site";
}
if(!$mas_class->mas_query($masdb, $sql_create_table_shortlink))
{
	echo "Could not create table shortlink";
}
if(!$mas_class->mas_query($masdb, $sql_create_table_comments))
{
	echo "Could not create table comments";
}
if(!$mas_class->mas_query($masdb, $sql_create_table_reply_comments))
{
	echo "Could not create table reply admin comments";
}
?>