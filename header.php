<?php
session_start();


#	необходимые подключения
require_once('config.php');
require_once('funx.php');
require_once('mysql.php');

#	необходимые классы
require_once('include/class.Funx.php');
require_once('include/class.User.php');
require_once('include/class.Essence.php');
require_once('include/class.Entity.php');
require_once(MODELS_DIR.'/PageModel.php');
require_once(MODELS_DIR.'/NewsModel.php');
require_once(MODELS_DIR.'/AsanaModel.php');
require_once(MODELS_DIR.'/ProgramModel.php');
require_once('include/class.Constant.php');
require_once('include/constants.php');






 

?>