<?php

if($_SERVER['SERVER_ADDR'] == '127.0.0.1')
{
	$db_login='root';
	$db_pass='';
	$db_host='127.0.0.1';
	$db_name='yoga';
}
else
{
	$db_login='p-7456_yoga';
	$db_pass='L0g9j%0lwgHa04%9';
	$db_host='localhost';
	$db_name='p-7456_yoga';
	
}




if(mysql_connect($db_host, $db_login, $db_pass))
{
	if(mysql_select_db($db_name))
	{
		mysql_query("SET NAMES 'utf8'");
		//mysql_query($q);
		if ($err=mysql_error())
			die("$err");
	
		return;
	}
	else{
		echo "<script>alert('no such database')</script>";
		return;
	}
}
else{
	echo "<script>alert('no db connection')</script>";
	return;
}
?>