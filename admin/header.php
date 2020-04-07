<?php
require_once(ROOT.'/funx.php');
require_once(ROOT.'/mysql.php');
require_once(ROOT.'/include/class.Slonne.php');
require_once(ROOT.'/include/class.Entity.php');
require_once(ROOT.'/include/class.Essence.php');
require_once(ROOT.'/include/class.Group.php');
require_once(ROOT.'/include/class.Module.php');
require_once(ROOT.'/include/class.Admin.php');
require_once(ROOT.'/include/class.Constant.php');
require_once(ROOT.'/include/class.Funx.php');

session_start();
error_reporting(E_ERROR | E_PARSE);




$admin=new Admin($_SESSION['admin']['id']);
$admin->setPrivileges();

#	язык
$_SESSION['admin_lang'] = $_SESSION['admin_lang'] ? $_SESSION['admin_lang'] : $_CONFIG['default_admin_lang'];


?>
