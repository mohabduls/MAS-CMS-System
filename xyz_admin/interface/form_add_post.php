<div class="d">
  <h1 class="h-title">Add Post</h1>
  <form action="index.php" method="POST">
    <h4 class="h-title">Title</h4>
    <input type="text" name="title" placeholder="Title">
    <h4 class="h-title">Indonesian Content</h4>
	<textarea class="ckeditor" id="ckeditor" name="description" placeholder="Indonesian Content"></textarea>
	<br/>
	<h4 class="h-title">English Content</h4>
	<textarea class="ckeditor" id="ckeditor" name="description_en" placeholder="English Content.."></textarea>
	<br/>
	<h4 class="h-title">Keywords, separate with coma (,)</h4>
	<input type="text" name="keyword" placeholder="keyword1,keyword2...">
	<h4 class="h-title">Url Thumbnails</h4>
	<input type="url" name="urlthumb" placeholder="https://your.url.thumbnails">
	<h4 class="h-title">Select Category</h4>
	<select name="cat">
	<?php
	include("../database/mascms-config.php");
	$sql_view_cat = "SELECT * FROM category ORDER BY id";
	$r = $mas_class->mas_query($masdb, $sql_view_cat);
	if(mysqli_num_rows($r) < 1)
	{
		?>
			<p>Please add category first before making new post !</p>
		<?php
		header("location: index.php?action=add_cat");
	}
	while($row = mysqli_fetch_assoc($r))
	{
		$name_cat = $row['category'];
		?>
		<option name="cat" value="<?php echo $name_cat; ?>"><?php echo $name_cat; ?></option>
		<?php
	}
	?>
	</select>
	<input type="submit" name="save_post" value="Save">
  </form>
</div>