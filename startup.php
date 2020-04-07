<?php
#	необязательные подключения всякие и инициализация переменных $_GLOBALS для шаблона


$_GLOBALS['TITLE'] = 'Йога для здорвья';
//error_reporting(E_ALL);

#	ГЛАВНОЕ МЕНЮ
$arr=array();
$p = Page::getChildren(2);
foreach($p as $key=>$val)
{
	$tmp = array();
	$tmp['title'] = $val->attrs['name'];
	$tmp['link'] = $val->url();
	
	$arr[] = $tmp;
}
$_GLOBALS['MENU']=$arr;







?>