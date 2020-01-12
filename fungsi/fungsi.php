<?php
//FUNGSI REPLACE SPASI
function gantiSpasi($t)
{
	$h = str_replace(" ", "_", $t);
	return $h;
}

// FUNGSI REPLACE STRIP
function gantiUnderScore($s)
{
	$h = str_replace("_", " ", $s);
	return $h;
}

function masStringToStrip($str)
{
	return str_replace(" ", "-", $str);
}

//FUNGSI MENGAMBIL FORM PENCARIAN
function cari()
{
	include ("xyz_admin/interface/form_search.html");
}

//MEMOTONG KATA DAN MEREPLACE KATA DOWNLOAD
function cutString($string, $from, $to)
{
	$cut = substr($string, $from, $to);
	return str_replace("Download", "", $cut);
}

function masDateFormat($string)
{
	$day_en = array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday","January","February","March","April","May","June","July","August","September","October","November","December");
	
	$day_id = array("Senin","Selasa","Rabu","Kamis","Jum'at","Sabtu","Minggu","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
	
	return str_replace($day_en, $day_id, $string);

}

function masIllegalArray()
{
	return array("(",")","\"","<",">","=","^","fuck","tai","memek","kontol","ngentot","ngewe","`","[","]","{","}","'","+","%","script","<script>","SELECT","UNION");
}

function masFilterSearch($keyword)
{
	$ilegal_string = array("(",")","\"","<",">","=","^","fuck","tai","memek","kontol","ngentot","ngewe","`","[","]","{","}","'","+","%","script","<script>","SELECT","UNION");
	
	return htmlentities(str_replace($ilegal_string, "", $keyword));
}

//News Mail
function newsMail($to, $subject, $msg)
{
	$header = "From:$myemail \r\n";  
    $header .= "MIME-Version: 1.0 \r\n";  
    $header .= "Content-type: text/html;charset=UTF-8 \r\n"; 
    $send = mail($to,$subject,$msg,$header);
    if($send == true)
    {
    	return true;
    }
    else
    {
    	return false;
	}
}

function masKeywordExploder($str)
{
	$replace = str_replace(" ", "", $str);
	$keyword = explode(",", $replace);
	return $keyword;
}

function masIsRealString($string)
{
	return htmlentities($string);
}

function masHardFilter($string)
{
	$ilegal_string = array("Bangsat","KONTOL","MEMEK","PEREK","BABI","babi","(",")","\"","<",">","=","^","fuck","tai","memek","kontol","ngentot","ngewe","`","[","]","{","}","'","+","%","script","<script>","SELECT","UNION","https://","http://","://","nyolong","sampah","bangsat","anjing","memex","mmk","</",">","visit","goblok"," goblog","pepek","pepex","ppk","setan","asu","jancok","cok","cuk");
	$lowercases = str_replace($ilegal_string, "", strtolower($string));
	
	if(empty($lowercases))
	{
		return "";
	}
	else
	{
		return htmlentities(str_replace($ilegal_string, "", $string));
	}
}

?>