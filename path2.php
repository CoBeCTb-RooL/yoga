<?php
error_reporting(E_ERROR  | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING | E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE);
session_start();

require_once('config.php');

if ($_GET['url']) 
{
	$arr_url=explode("/",$_GET['url']);
	
	$lang = $_CONFIG['langs'][$arr_url[0]] ? $arr_url[0] : $_CONFIG['default_lang'];  
	
	$_SESSION['lang'] = $lang;
	//$_SESSION['lang'] = $_SESSION['lang'] ? $_SESSION['lang'] : $_CONFIG['default_lang'];
	
	$module=$arr_url[1];
	
	$urlSectionsCount=count($arr_url);
	for ($i = 2; $i < $urlSectionsCount; $i++) 
	{
		if ($arr_url[$i] != "" && $arr_url[$i] != " " && $arr_url[$i] != "/" && $arr_url[$i] != "\\" ) 
			$_PARAMS[$i-2] = $arr_url[$i];
	}
	unset($_GET['url']);
}


require('header.php'); 
#	наполнение глобальных переменных фронтэнда
require_once('startup.php');


ob_start();
$arr = NULL;

if(!$module) 
	$module='index';
 
if(!in_array($module, array_keys($_CONFIG['ALLOWED_MODULES'])) && file_exists('modules/'.$_CONFIG['ALLOWED_MODULES'][$module]))
	$module = 'error';
	
$_GLOBALS['module'] = $module;
	
require('modules/'.$_CONFIG['ALLOWED_MODULES'][$module].'');   

$_GLOBALS['CONTENT']=ob_get_clean();



require_once('templates/'.$_CONFIG['template'].'/template.php');
//phpinfo();

//vd(gd_info());
//vd(function_exists("imagegif"));

#	формируется яваскрипт-массив _CONSTANTS
require_once('include/constants_js.php');
?>