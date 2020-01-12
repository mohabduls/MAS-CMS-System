<?php
//SQL FOR CREATE DATABASE
$sql_create_db = "CREATE DATABASE IF NOT EXISTS $db_name";
//SQL FOR CREATING TABLE
$sql_create_table_admin = "CREATE TABLE IF NOT EXISTS admin (id int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, username text NOT NULL, password text NOT NULL)";
$sql_create_table_post = "CREATE TABLE IF NOT EXISTS post (id int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, title varchar(255) NOT NULL, description text NOT NULL, description_en text NOT NULL, keyword text NOT NULL, category varchar(50) NOT NULL, urlthumb text NOT NULL, date varchar(50) NOT NULL, hits int(10) NOT NULL, permalink text NOT NULL)";
$sql_create_table_category = "CREATE TABLE IF NOT EXISTS category (id int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, category varchar(50) NOT NULL)";
$sql_create_table_email = "CREATE TABLE IF NOT EXISTS email (id int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, email varchar(100) NOT NULL, email_md5 varchar(500) NOT NULL)";
$sql_create_table_site_info = "CREATE TABLE IF NOT EXISTS site (id int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, meta text NOT NULL, maintenance varchar(50), ads_top text NOT NULL, ads_bottom text NOT NULL)";
$sql_create_table_shortlink = "CREATE TABLE IF NOT EXISTS shortlink (id int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, destination_link text NOT NULL)";
$sql_create_table_comments = "CREATE TABLE IF NOT EXISTS user (id int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_email text NOT NULL, user_name text NOT NULL, user_comments text NOT NULL, comments_date text NOT NULL, permalink_title text NOT NULL, is_subscribe text NOT NULL)";
$sql_create_table_reply_comments = "CREATE TABLE IF NOT EXISTS admin_reply (id int(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,administrator text NOT NULL, post_permalink text NOT NULL, comments_id int NOT NULL, reply text NOT NULL, comments_date text NOT NULL)";

//SQL ADMIN
$sql_insert_admin = "INSERT INTO admin (username, password) VALUES ('$username', '$password')";
//CHECK MENGGUNAKAN SELECT ID
$sql_check_id_admin = "SELECT * FROM admin WHERE id=1";
//SQL MELIHAT POSTINGAN
$sql_view_post = "SELECT * FROM post ORDER BY id LIMIT 10";

?>