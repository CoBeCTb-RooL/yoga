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
	$db_login='v_93999_yoga';
	$db_pass='IqxfHY26zqN0vK6d';
	$db_host='localhost';
	$db_name='v_93999_yoga';
	
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