<?php
include("../database/mascms-config.php");
?>
<div class="d">
	<h4 class="h-title">Web Api (JSON)</h4>
	<span class="h-title"><b>For listing by category, use this :</b></span>
	<span class="h-title"><i><?php echo $ssl.$domain."/rest-api/web-api.json?api=".md5($sitename)."&cat=<b>[YOUR-CATEGORY]</b>"; ?></i></span><br/><br/>
	<span class="h-title"><b>For list all post, use this :</b></span>
	<span class="h-title"><i><?php echo $ssl.$domain."/rest-api/web-api.json?api=".md5($sitename)."&cat=<b>all</b>"; ?></i></span><br/><br/>
	<p class="h-title">
		"<b>title</b>" > Use for get title.<br/>
		"<b>description</b>" > Use for get default (ID) description.<br/>
		"<b>description_en</b>" > Use for get english description.<br/>
		"<b>urlthumb</b>" > Use for get thumbnails url.<br/>
		"<b>date</b>" > Use for get date post. (Default English Date)<br/>
		"<b>category</b>" > Use for get category type from post.
	</p>
	<h3 class="h-title">Setting</h3>
	<form action="index.php" method="POST">
	<h4 class="h-title">Meta Tag / Keyword / Google Site Verification</h4>
	<?php
	$sql_get_setting = "SELECT * FROM site";
	$execute = $mas_class->mas_query($masdb, $sql_get_setting);
	$data = mysqli_fetch_assoc($execute);
	if(mysqli_num_rows($execute) < 1)
	{
		$in = $mas_class->mas_query($masdb, "INSERT INTO site(meta, maintenance, ads_top, ads_bottom) VALUES ('<meta>YOUR META</meta>', 'NO', 'First ads', 'Second ads')");
		if(!$in)
		{
			echo "Setting has been set by default!";
		}
	}
	?>
    <textarea name="meta_set" placeholder="This input is for meta tag, keyword , etc..."><?php echo $data['meta']; ?></textarea>
    <h4 class="h-title">Advertisement Code</h4>
    <textarea style="height: 150px;" name="ads_top" placeholder="Put your first ad code!"><?php echo $data['ads_top']; ?></textarea>
    <textarea style="height: 150px;" name="ads_bottom" placeholder="Put your second ad code!"><?php echo $data['ads_bottom']; ?></textarea>
    
    <h4 class="h-title">Site Maintenance Set</h4>
    <select name="mt_set">
    	<option value="ON">Enable</option>
    	<option value="OFF">Disable</option>
    </select>
    
    <?php 
    $sql_get_admin = "SELECT * FROM admin";
    $query_get_admin = $mas_class->mas_query($masdb, $sql_get_admin);
    $fetch_admin = mysqli_fetch_assoc($query_get_admin);
    $admin_user = $fetch_admin['username'];
    $id_admin = $fetch_admin['id'];
    ?>
    
    <h4 class="h-title">Change Username (Login)</h4>
    <input type="input" name="username_set" value="<?php echo $admin_user; ?>">
    <h4 class="h-title">Change Password (Login Default <b>admin</b>)</h4>
    <input type="password" name="password_set" value="Save">
    <input type="hidden" name="admin_id" value="<?php echo $id_admin; ?>">
    
	<input type="submit" name="setting_submit" value="Save">
  </form>
  
</div>