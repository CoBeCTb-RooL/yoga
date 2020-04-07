<?php
error_reporting(E_ERROR  | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING | E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE);
session_start();
//$_SESSION=array();
//var_dump($_SESSION);

require_once('config.php');
$_SESSION['lang'] = $_SESSION['lang'] ? $_SESSION['lang'] : $_CONFIG['default_lang'];
//error_reporting(E_ALL);

require('header.php'); 
#	наполнение глобальных переменных фронтэнда
require_once('startup.php');


$module="index";

ob_start();

include(CONTROLLERS_DIR.'/indexController.php');
$_GLOBALS['CONTENT']=ob_get_clean();


require_once('templates/'.$_CONFIG['template'].'/template.php');

?>