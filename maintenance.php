<?php
/*
Copyright 09-Januari-2020
MASCMS V1.0 (MAS Content Management System)
Coded by : Mohamad Abdul Sobur
Based on PHP NATIVE
*/

//OOP CLASS
include("database/mascms-db.php");
include("database/mascms-config.php");

$sql_get_setting = "SELECT maintenance FROM site";
$execute = $mas_class->mas_query($masdb, $sql_get_setting);
$data = mysqli_fetch_assoc($execute);

$key = $data['maintenance'];
if($key == "OFF")
{
	header("location: home.asp");
}
else
{
	include("mascms_maintenance.html");
}
?>