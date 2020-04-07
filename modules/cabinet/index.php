<?php
$section = $_PARAMS[0]; 
?>

<?php 
if($_SESSION['user'])
{?>
<a href="/<?=$_SESSION['lang']?>/cabinet/edit"><?=$_CONST['РЕДАКТИРОВАТЬ ДАННЫЕ']?></a> / 
<a href="javascript:void(0)" onclick="Cabinet.logout()"><?=$_CONST['ВЫЙТИ']?></a>
<hr>
<?php 	
}
?>





<?php

switch($section)
{
	case 'edit':
			echo edit();
		break;
		
	case 'auth':
			echo auth();
		break;	
		
	default:
			if($_SESSION['user'])
				echo index();
			else 
				echo auth();
		break;
}


 





function index()
{
	$str.='INDEX INDEX INDEX INDEX INDEX INDEX INDEX INDEX INDEX ';
	
	return $str;
}




function edit()
{
	global $_CONST;
	?>
	
	<!--<h3><?=$_CONST['РЕДАКТИРОВАНИЕ ЛИЧНЫХ ДАННЫХ']?></h3>-->
	<div id="cabinet-container-div">ddd</div>

	<script src="/modules/cabinet/cabinet.js" type="text/javascript"></script>
	
	<script>
		Cabinet.drawDiv("cabinet-container-div")
		Cabinet.drawCabinet()
	</script>
<?php 
}





function auth()
{
	global $_CONST;
	//vd($_SESSION);
	
	if($_SESSION['user'])
	{?>
		<?=$_SESSION['user']['name']?> <?=$_SESSION['user']['fathername']?>, вы авторизованы! 
		<br>
		<a href="javascript:void(0)" onclick="Cabinet.logout()">Выйти</a> 
	<?php 
	}
	else 
	{
	?>
		<h3><?=$_CONST['lbl АВТОРИЗАЦИЯ в кабинете']?></h3>
		<div class="cabinet">
			<div id="cabinet-auth-div">ddd</div>
		</div>
	
		
		
		<script>
			Cabinet.drawAuthDiv("cabinet-auth-div")
			Cabinet.drawAuthForm()
		</script>
	<?php 
	}?>
<?php 	
}
?>