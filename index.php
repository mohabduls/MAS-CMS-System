<?php
/*
Copyright 09-Januari-2020
MASCMS V1.0 (MAS Content Management System)
Coded by : Mohamad Abdul Sobur
Based on PHP NATIVE
*/
include("header.php");
?>
<!--Content-->
<!--Find Content-->
<div class="container">
	<div class="row">
<?php
if(isset($_GET['keyword']))
{
	$keyword = masFilterSearch($_GET['keyword']);
	$q = mysqli_real_escape_string($masdb, $keyword);
	$key = htmlspecialchars($q);
	$search = "SELECT * FROM post WHERE title LIKE '%{$key}%' OR description LIKE '%{$q}%' OR keyword LIKE '%{$q}%'";
	$result = $mas_class->mas_query($masdb, $search);
    ?>
        <span class="d-block container text-light bg-dark p-2">
        	<svg id="i-search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="17" height="17" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3">
  			  <circle cx="14" cy="14" r="12" />
   			 <path d="M23 23 L30 30"  />
			</svg>
   		 <b>
			<?php
			if(empty($_GET['keyword']))
			{
				echo $all_keyword;
			}
			else
			{
				echo $keyword_lang." : ".$q; 
			}
			?>
			</b>
		</span>
    <?php
    if(mysqli_num_rows($result) < 1)
    {
    	?>
    	<div class="container p-2">
    		<div class="alert alert-info">
    			<p><?php echo $keyword_nothing_lang; ?> : <?php echo $q; ?></p>
   		 </div>
   	 </div>
    	<?php
    }
    while($row = mysqli_fetch_assoc($result))
    {
    	$linkjudul = gantiSpasi($row['title']);
		$id = $row['id'];
		$title = $row['title'];
		$permalink = $row['permalink'];
		$description = $row['description'];
		$cat = $row['category'];
		$date = $row['date'];
		$urlthumb = $row['urlthumb'];
		$hits = $row['hits'];
		
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
$halaman = 10;
$sql_pagination = "SELECT * from post";
$sql_count = "SELECT COUNT(*) AS total FROM post";


$page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
$mulai = ($page > 1) ? ($page * $halaman) - $halaman : 0;
$result = $mas_class->mas_query($masdb, $sql_pagination);
$total = mysqli_num_rows($result);
$pages = ceil($total/$halaman);


$query_count = $mas_class->mas_query($masdb, $sql_count);
$fetch_count = mysqli_fetch_assoc($query_count);
	
$jumlah_page = ceil($fetch_count['total']/$halaman);
$jumlah_number = 3;
	
$start_number = ($page > $jumlah_number)? $page - $jumlah_number : 1; 
$end_number = ($page < ($jumlah_page - $jumlah_number))? $page + $jumlah_number : $jumlah_page;

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
									<img class="img-fluid img-thumbnail" style="min-width: 120px; min-height: 120px; max-width: 130px; max-height: 130px; margin-left: 0px;" src="<?php echo $urlthumb; ?>" alt="<?php echo $title; ?>"/>
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
?>
		<div class="container">
		<ul class="pagination justify-content-center">
<?php
if($page == 1)
	{
		?>
		<li class="page-item disable"><a class="page-link" href="#">First</a></li>
		<?php
	}
	else
	{
		$link_prev = ($page > 1) ? $page - 1 : 1;
		
		?>
		<li class="page-item"><a class="page-link" href="?page=1">First</a></li>
		<?php
	}
    
    for($i = $start_number; $i <= $end_number; $i++)
	{
  	  ?>
    	<li class="page-item"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
    	<?php
    }
    if($page == $jumlah_page)
    {
    	?>
    	<li class="page-item disable"><a class="page-link" href="#">Last</a></li>
    	<?
    }
    else
    {
    	$link_next = ($page < $jumlah_page)? $page + 1 : $jumlah_page;
    	?>
    	<li class="page-item"><a class="page-link" href="?page=<?php echo $link_next; ?>">Next</a></li>
  	  <li class="page-item"><a class="page-link" href="?page=<?php echo $jumlah_page; ?>">Last</a></li>
    	<?php
    }
}
?>

			</ul>
		</div>
	</div>
</div>

<!--End Content-->
<?php
include("footer.php");
?>
