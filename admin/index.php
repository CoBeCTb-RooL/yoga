<?php
//require_once('header.php');

require_once('../config.php');
require_once('header.php');
//vd($_SESSION);
$_CONFIG['adminErrorModulePath']='modules/error/index.php';
?>



<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>SLoNNe CMS</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="css/style.css" rel="stylesheet" type="text/css" >
<script src="../js/libs/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="../js/plugins/jquery.stickr.js" type="text/javascript"></script>
<script src="../js/jquery-ui-1.9.2.custom.min.js" type="text/javascript"></script>
<script src="../js/common.js" type="text/javascript"></script>


<script type="text/javascript" language="javascript" src="../js/calendar/calendar.js"></script>
<script type="text/javascript" language="javascript" src="../js/calendar/calendar-setup.js"></script>
<script type="text/javascript" language="javascript" src="../js/calendar/lang/calendar-ru.js"></script>
<link rel="StyleSheet" href="/js/calendar/calendar.css" type="text/css">

<link href='http://fonts.googleapis.com/css?family=Mystery+Quest' rel='stylesheet' type='text/css'>
 <link rel=" icon" href="favicon.ico" type="image/x-icon">
<link href="../css/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="../js/libs/highslide-4.1.13/highslide-with-gallery.packed.js"></script>
<link rel="stylesheet" type="text/css" href="../js/highslide/highslide.css" />



<script>
hs.graphicsDir = '/js/libs/highslide-4.1.13/graphics/';
hs.align = 'center';
hs.transitions = [];
hs.outlineType = 'rounded-white';
hs.fadeInOut = true;
hs.headingEval = 'this.a.title';
//hs.dimmingOpacity = 0.75;

// Add the controlbar
hs.addSlideshow({
	//slideshowGroup: 'group1',
	interval: 5000,
	repeat: false,
	useControls: true,
	fixedControls: true,
	overlayOptions: {
		opacity: .75,
		position: 'bottom center',
		hideOnMouseOut: true
	}
});
</script>

	<!--для кнопочки ЛОГАЙТ-->
	<script src="modules/admins/admins.js" type="text/javascript"></script>
</head>


	
	
	<?php
	if(!$_SESSION['admin']['id'])
	{
		echo '
		<body style="background: #fff; padding: 40px 100px; ;">
			
			<div  class="special-font">
				<img style="margin: 0 10px -20px 0;" src="images/slonik.gif" width="70" alt="" > 
				<span style="font-size: 32px;">SLoNNe CMS</span>
				
				
				
			</div>
			
			<div id="login-form">Загрузка...</div>
			
			<div style="margin-left: 170px; font-size: 12px;" id="login-form-idea-label" class="special-font">проект <span style="font-size: 15px;">"Hanna Zuckerbrod"</span></div>
		
		<script>
			//Admins.drawLoginForm(\'login-form\');
			Admins.initLoginForm();
		</script>
		
		';
		
	}
	
	else 
	{
			$admin=new Admin($_SESSION['admin']['id']);
			//vd($admin);
	?>
	<body>
	<div id="global-edging" >
		
		<div style="position: absolute; top: 10px; right: 100px; ">
			<img src="images/slonik.png" width="70" alt="" onMouseOver="$('#slonne').fadeIn(); $('#tsop').fadeIn(); " onMouseOut="$('#slonne').fadeOut(); $('#tsop').fadeOut()">
		</div>
		
		<div  style="position: absolute; top: 80px; right: 80px;  width :120px ; height: 80px ; display: none " id="slonne">
			<img src="images/slonne.gif" alt="">
		</div>
		
		<div  style="position: absolute; top: 0px; right: 170px;  width :174px ; height: 27px ; display: none " id="tsop">
			<img src="images/tsop.tya.gif" alt="">
		</div>
		
		<div style="display: inline; float: right">
		
		
		
		<a href="javascript:void(0)" id="exit-btn" style="" onclick="Admins.logout()"><span class="fa fa-road"></span> Выйти</a>
		
		</div>
		
<?php



		
$section=$_REQUEST['section'];


//vd($admin);

//$modules=$admin->getMyModules();


$modules = Module::getModules();
//vd($modules);

$str.='

<div id="admin-info">
	
</div>

<div class="menu" > <span id="admin-name">'.$admin->attrs['name'].' |</span>';	
foreach($modules as $key=>$val)
{
	if(!$admin->group->attrs['privileges_arr'][$val->attrs['code']])
		continue; 
		
	$title=($val->attrs['icon']?$val->attrs['icon'].' ':'').''.$val->attrs['name'];
	
	$href='#';
	if($key)
		$href='?section='.$key;
	
	if($val->attrs['active'])
	{
		$str.= '<a href="'.$href.'" class="'.($key==$section?'active':'').'">'.$title.'</a>';
	}
	else 
		$str.= '<a href="'.$href.'" class="inactive '.($key==$section?'active':'').'">'.$title.'</a>';
	
	
	$str.='&nbsp;&nbsp;|&nbsp;&nbsp;';
}
$str.='
</div>';




echo $str;
$str='';


if($admin->attrs['active'])
{
	if($admin->group->attrs['active'])
	{
		if(!$section)	
			$section = 'index';
		
		if(in_array($section, array_keys($modules)))	#	модуль зарегистрирован
		{
			if($modules[$section]->attrs['active'])	#	активен?
			{
				if(file_exists($modules[$section]->attrs['path']) )	#	пхпшка релаьно существует ? 
				{
					
					echo '<h1>'.$modules[$section]->attrs['name'].'</h1>';
					if($modules[$section]->attrs['get_str'])
						$_REQUEST=array_merge($modules[$section]->attrs['_GET'], $_REQUEST);
					
					require_once($modules[$section]->attrs['path']);
				}
				else #	пхпшка не существует
				{
					$_REQUEST['problem'] = 'MODULE_NOT_EXIST';
					require_once($_CONFIG['adminErrorModulePath']);
				}
			}
			else	#	модуль неактивен	
			{
				$_REQUEST['problem'] = 'MODULE_INACTIVE';
				require_once($_CONFIG['adminErrorModulePath']);
			}
		}
		else	#	если неведомая херня 
		{
			$_REQUEST['problem'] = 'UNKNOWN_TYPE';
			require_once($_CONFIG['adminErrorModulePath']);
		}
	}
	else 	
		echo '<div style="margin: 30px 0 0 20px;">Ваша группа деактивирована..</div>';
}
else
	echo '<div style="margin: 30px 0 0 20px;">Ваша учётная запись деактивирована..</div>';

?>

</div>




<?php
$str='';
$module=$modules[$section];
$str.='
<span style="font-size: 10px;">';
$str.='module: <b>'.$section.'</b> ('.$module->attrs['name'].')
<br>active: '.($module->attrs['active'] ? '<span style="color: green; font-weight: bold; ">yes</span>' : '<span style="color: red; font-weight: bold; ">no</span>').'
<br>required file: <b>'.$module->attrs['path'].($module->attrs['get_str']?'?'.$module->attrs['get_str']:'').'</b>
<hr>
privileges: <b>'.join ("</b>, <b>", array_keys($_SESSION['admin']['privileges'][$section])).'</b>
 ';

$str.='
</span>';


echo $str;
?>



<?php 
//vd($str);

	}
?>





	
</body>




















