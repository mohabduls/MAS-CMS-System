<?php 
/*
Copyright 09-Januari-2020
MASCMS V1.0 (MAS Content Management System)
Coded by : Mohamad Abdul Sobur
Based on PHP NATIVE
*/
header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8" ?>';
include("fungsi/fungsi.php");

//OOP CLASS
include("database/mascms-db.php");
include("database/mascms-config.php");
?>

<urlset xmlns="http://www.google.com/schemas/sitemap/0.84" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">

    <url>
        <loc><?php echo $ssl.$domain; ?>/home.asp</loc>
        <priority>1.00</priority>
    </url>

    <?php
    $sql = "SELECT * FROM post ORDER BY id";
    $result = $mas_class->mas_query($masdb,$sql);      
    while($row = mysqli_fetch_assoc($result))
    { 
    $permalink = $row['permalink'];
    ?>
    <url>
        <loc>http://<?php echo "$domain"; ?>/post.asp?title=<?php echo $permalink; ?></loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>

 	<?php
	 } 
 	?>

</urlset>