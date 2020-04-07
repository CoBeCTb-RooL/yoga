<?php
//vd($_SESSION['lang']);
$sql="SELECT * FROM slonne__constants ";
$qr=mysql_query($sql);
echo mysql_error();
while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
{
	$_CONST[$next['name']] = $next['value'.$_CONFIG['langs'][$_SESSION['lang']]['postfix']];
} 
?>
