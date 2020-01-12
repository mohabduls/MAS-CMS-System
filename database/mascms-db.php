<?php
/*
Please take care when you edit this file.
You may encounter an error you do not want. Please note to pay, you must know what you edit so as to minimize the error that occurred!
*/
class MASCMS_DB{
	
	function masdb($db_host, $db_user, $db_pass, $db_name)
	{
		return mysqli_connect($db_host, $db_user, $db_pass, $db_name);
	}
	
	function mas_query($conn, $sql)
	{
		return mysqli_query($conn, $sql);
	}
	
}

?>