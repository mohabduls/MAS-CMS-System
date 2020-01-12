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
	
<div class="container-fluid p-5 text-center text-dark">
	<!--ADS COOMING SOON-->
	<?php
	$ads = "SELECT * FROM site WHERE id=1";
	$adsquery = $mas_class->mas_query($masdb, $ads);
	$fetch_ads = mysqli_fetch_assoc($adsquery);
	
	//SHOW ADS 1
	echo $fetch_ads['ads_top'];
	?>
	
	<?php
	
	if(isset($_GET['go']))
	{
		$get_id = mysqli_real_escape_string($masdb, masFilterSearch($_GET['go']));
		$sql_check = "SELECT * FROM shortlink WHERE id='$get_id'";
		$query = $mas_class->mas_query($masdb, $sql_check);
		$get_link = mysqli_fetch_assoc($query);
		if(mysqli_num_rows($query) == 0)
		{
			?>
			<p class="text-danger"><?php echo $shortlink_notfound_lang; ?></p>
			<?php
		}
		else
		{
			?>
			<script>
				var timeLeft = 10;
				var downloadTimer = setInterval(function(){
					document.getElementById('timer').innerHTML = timeLeft;
					timeLeft -= 1;
					if(timeLeft < 0)
					{
						clearInterval(downloadTimer);
						document.getElementById('loading').style.display = "none";
						showButton();
					}
				}, 1000);
				function showButton()
				{
					var btn = document.getElementById('link');
					btn.style.display = "inline";
					
				}
			</script>
			<p class="text-dark"><?php echo $shortlink_explanation_lang; ?></p>
			<b><p class="text-dark" style="font-size: 10em;" id="timer">10</p></b>
			<div style="width: 80px; height: 80px;" class="spinner-grow text-success" role="status" id="loading">
				<span class="sr-only">Loading..</span>
			</div>
			<a href="<?php echo $get_link['destination_link']; ?>" target="_blank"><button id="link" style="display: none;" class="btn btn-success"><?php echo $go_lang; ?></button></a>
			<br/>
			<?php
		}
	}
	else
	{
		?>
		<h1 style="font-size: 20em;" class="text-dark">?</h1>
		<?php
	}
	?>
	<!--ADS COOMING SOON-->
	<?php
	//SHOW ADS 2
	echo $fetch_ads['ads_bottom'];
	?>
</div>

<?php
include("footer.php");
?>