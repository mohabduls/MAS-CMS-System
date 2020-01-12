<?php

include("../database/mascms-config.php");

?>
<div class="d">
  <h1 class="h-title">ShortLink</h1>
  <form action="index.php" method="POST">
    <input type="url" name="shortlink" placeholder="http://..">
	<input type="submit" name="save_shortlink" value="Save">
  </form>
  <div style="overflow-x:auto;">
  <?php
  $sql_check_shortlink = "SELECT * FROM shortlink ORDER BY id DESC LIMIT 50";
  $query = $mas_class->mas_query($masdb, $sql_check_shortlink);
  if(mysqli_num_rows($query) == 0)
  {
  	?>
  	<span class="h-title">No link detected, Please create one or more shortlink!</span>
	  <?php
  }
  else
  {
  	echo $low;
  	?>
  	<table>
  		<tr>
  			<th>Destination</th>
  			<th>Link</th>
			  <th>Action</th>
			  <th>Code</th>
  		</tr>
  	<?php
  	while($row = mysqli_fetch_assoc($query))
  	{
  		$link_id = $row['id'];
  		$link_destination = $row['destination_link'];
  	?>
  	<tr>
  		<td><?php echo $link_destination; ?></td>
		  <td><?php echo $ssl.$domain."/go.asp?go=".$link_id; ?></td>
		  <td><a class="t-red" href="?action=delshortlink&linkid=<?php echo $link_id; ?>">Delete</a></td>
		  <td><a class="t-green" href="?action=getcode&link=<?php echo $ssl.$domain."/go.asp?go=".$link_id; ?>">Get Code</a></td>
	  </td>
  	<?php
  	}
  	?>
  	</table>
  <?php
  }
  ?>
  </div>
</div>