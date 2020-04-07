<?php
require_once('../../../config.php');
require_once(ROOT.'/admin/header.php');







$action=$_REQUEST['action'];

//usleep(500000);

switch($action)
{
	default:
			echo '{"error":"AJAX(\"'.$_SERVER['PHP_SELF'].'\")::no action!'.($action?' ('.$action.')':'').'"}';
		break;
		
		
	case "list":
			list1();		
		break;	
		
		

	case "edit":
			edit();
		break;	
		

		
	case "save": 	#	вызывается в ифрейме! 
			save();
		break;	
		
		
		
	case 'delete':
			delete();
		break;	
	

	
	case "saveChangesInList": 	#	вызывается в ифрейме! 
			saveChangesInList();
		break;
		
		
		
		
		
}















function list1()
{
	global $_CONFIG;
	$error="";
		
	$str='';
	ob_start();
	
	//vd($_REQUEST);
	
	$sql="SELECT * FROM slonne__constants ";
	$qr=mysql_query($sql);
	echo mysql_error();
	if(mysql_num_rows($qr))
	{
		$str.='
		<form name="const_list_form" method="post" action="modules/constants/constants.php?action=saveChangesInList" target="const_iframe" >
			
		<table class="t shadow" border="1" style="min-width: 700px;">
			<tr>
				<th width="1">id</th>
				<th width="1"></th>
				<th>Название</th>';
		foreach($_CONFIG['langs'] as $key=>$val)
		{
			$str.='
				<th>'.$key.'</th>';
		}
		$str.='		
				<th>Удалить?</th>
			</tr>';
		while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			
			//vd($tmp);
			
			$str.='
			<tr >
				<td >'.$next['id'].'</td>
				
				<td><button type="button" class="button blue small" title="редактировать" onclick="Constants.edit(\''.$next['id'].'\');" ><i style="margin: 0px 0px 0px 0px; " class="fa fa-wrench "></i> </button></td>
				<td style="font-size: 13px; font-weight: bold; ">'.$next['name'].'</td>';
			
			foreach($_CONFIG['langs'] as $key=>$val)
			{
				$str.='
					<td>'.$next['value'.$val['postfix']].'</td>';
			}
			
			$str.='
				<td width="1">
					<button class="button red small " onclick="Constants.delete('.$next['id'].')"><span class="fa fa-trash-o"></span> удалить</button>
				</td>
			</tr>';
		}
		$str.='
		</table>
		</form><p>';
	}
	else 
	{
		$str.='
		Констант нет.';
	}
	
	
	
	$str.='
	<p style="height: 10px;"></p>
	
	<div style="float: left">
		<button class="button " onclick="Constants.edit()">+ добавить <b>константу</b></button>
	</div>';
	if(mysql_num_rows($qr))
	{
		$str.='
		<div style="float: left; margin-left: 60px;">
			<button class="button blue" type="button" onclick="if(confirm(\'Уверены?\')){document.forms.constants_list_form.submit()}" ><i class="fa fa-floppy-o"></i> сохранить <b>изменения</b></button>
		</div>';
	}
	$str.='<div style="clear:both"></div>';
	
	
	
	echo $str;
	
	$html=ob_get_clean();
	
	
	$json['html']=$html;
	$json['error']=$error;
	
	
	echo json_encode($json);
}	

	





function edit()
{
	global $_CONFIG;
	$error="";

	$str='';
	ob_start();
	
	
	if($id=intval($_REQUEST['id']))
	{
		$c = new Constant($id=intval($_REQUEST['id']));
		if(!$c->attrs )
			$error="CONSTANT_NOT_EXIST_ERROR [".$_REQUEST['id']."]";
		else 
			$attrs=$c->attrs;
	}
//	vd($m);
	
	if(!$error)
	{
		$str.='<a href="javascript:void(0)" onclick="switchTo(\'const-list-div\', \'const\')">&larr; назад</a>';
		
		$str.='
		<div style="width: 600px; margin: 20px; 0" class="shadow radius">
			';
		if($c->attrs)
			$str.='
			<h3>Редактирование константы "'.$c->attrs['name'].'"</h3>';
		else 
			$str.='
			<h3>Новая константа</h3>';
		
		$str.='
		<form name="const_edit_form" method="post" action="modules/constants/constants.php?action=save" target="const_iframe" >
			'.($id?'<input type="hidden" name="id" value="'.$id.'">':'').'
			<niptu type="hidden" name="action" value="save">
		<table border="0" class="edit-table">
			<tr>
				<th>Название<span class="req">*</span>: </th>
				<td><input type="text" name="name" id="const-name" value="'.mysql_real_escape_string(htmlspecialchars($c->attrs['name'])).'" style="width: 500px; "></td>
			</tr>
			';
		
		foreach($_CONFIG['langs'] as $key=>$val)
		{
			$str.='
			<tr>
				<th>'.$key.': </th>
				<td>
					<textarea name="val['.$key.']"   style="width: 500px; ">'.$c->attrs['value'.$val['postfix']].'</textarea>
				</td>
			</tr>';
		}
		
		$str.='	
			<tr>
				<td></td>
				<td style="padding-top: 20px;">	
					<!--<a href="javascript:void(0)" style="font-size: 15px; font-weight: bold; " id="edit-save-btn" onclick="EE.checkNewEssenceForm(); ">сохранить</a>-->
					<button class="button " type="button"  onclick="document.forms.const_edit_form.submit()"><b>сохранить</b></button>
				</td>
			</tr>
		</table>
		</form>
		</div>
		
		
		';
	}

	echo $str;
	
	$html=ob_get_clean();
	
	
	$json['html']=$html;
	$json['error']=$error;

	
	
	echo json_encode($json);
}












function save()
{
	global $_CONFIG;
	//vd($_REQUEST);
	//die;
	
	
	if($id = intval($_REQUEST['id']))
	{
		$c=new Constant($id);
		if(!$c->attrs )
		{
			die('
			<script>
				window.top.error(\'CONSTANT_NOT_EXIST_ERROR ['.$_REQUEST['id'].']\', \'fields-info-div\');
				window.top.Constants.list()
				window.top.loading(0, \'const-loading-div\', \'fast\');
			</script>');
		}
	}
	
	$name=trim($_REQUEST['name']);
	
	
	
	
	if(!$name)
	{
		$troubles[]='const-name';
		$e='Введите название!';
	}
	
	
	
	
	if(!$c->attrs)
	{
		#	проверим на повтор имени
		$sql="SELECT * FROM slonne__constants WHERE name='".mysql_real_escape_string($name)."'";
		$qr=mysql_query($sql);
		echo mysql_error();
		if(mysql_num_rows($qr))	
		{
			$troubles[]='mod-name';
			$e='Константа с таким названием уже существует!';
		}	
		
		
		
		
	}
	
	
	if(count($troubles))
	{
		die('
			<script>
				window.top.error(\''.$e.'\', \'const-info-div\');
				window.top.highlight(["'.join('", "', $troubles).'"]);
				window.top.loading(0, \'const-loading-div\', \'fast\');
			</script>');
	}
	else	#	если всё ок
	{
		echo "VSE OK BUDEM SAVE";
		//die;
		if($c->attrs)
			$sql="UPDATE `slonne__constants` SET ";
		else 	
			$sql="INSERT INTO `slonne__constants` SET  ";
		
		$sql.="
		name='".mysql_real_escape_string($name)."'
		";
		foreach($_CONFIG['langs'] as $key=>$val)
		{
			$sql.="
		, value".$val['postfix']."='".mysql_real_escape_string(trim($_REQUEST['val'][$key]))."'";
		}
		
		
		if($c->attrs)
			$sql.=" WHERE id=".$c->attrs['id'];
			
	
		mysql_query($sql);
		vd($sql);
		if($e=mysql_error())
		{
			
			die('
			<script>
				window.top.error(\'ОШИБКА! '.mysql_real_escape_string($e).'\', \'const-info-div\');
				window.top.loading(0, \'const-loading-div\', \'fast\');
			</script>');
		}
	//	vd($sql);
		
	}
	$str.='
	<script>
		window.top.notice(\'Константа ['.$name.'] '.($c->attrs?'изменена':'добавлена').'!\', \'const-info-div\');
		window.top.Constants.list();
	</script>
	';
	
	
	echo $str;	
}








	
	
	
function delete()
{
	$error="";
		
	$str='';
	ob_start();
	
	//vd($_REQUEST);
	
	
	if($id=intval($_REQUEST['id']))
	{
		$sql="DELETE FROM slonne__constants WHERE id=".$id;
		mysql_query($sql);
		echo mysql_error();
	}
	else 	
		$error="NO_ID_PASSED_ERROR [".$_REQUEST['id']."]";

	echo $str;
	
	$html=ob_get_clean();
	
	
	$json['html']=$html;
	$json['error']=$error;

	
	
	echo json_encode($json);
	
}































function saveChangesInList()
{
	die;
	vd($_REQUEST);
		
	foreach($_REQUEST['idx'] as $key=>$val)
	{
		if(!is_numeric($val))
			$problems[]='idx-'.$key;
	}
	
	if(count($problems))
	{
		die('
			<script>
				window.top.error(\'Введите числовые значения!\', \'fields-info-div\');
				window.top.highlight(["'.join('", "', $problems).'"]);
				window.top.loading(0, \'mod-loading-div\', \'fast\');
			</script>');
	}
	else
	{
		#	меняем idx
		foreach($_REQUEST['idx'] as $key=>$val)
		{
			$sql="UPDATE slonne_modules SET idx='".intval($val)."' WHERE id='".intval($key)."'";
			mysql_query($sql);
			echo mysql_error();
		}
		
		
		#	меняемактивность
		foreach($_REQUEST['active'] as $key=>$val)	
			$act[]=intval($key);
		$sql="UPDATE slonne_modules SET active=0";
		mysql_query($sql);
		echo mysql_error();
		
		$sql="UPDATE slonne_modules SET active=1 WHERE id IN (".join(", ", $act).")";
		mysql_query($sql);
		echo mysql_error();
		
		
		echo '
		<script>
			window.top.notice(\'Изменения сохранены\', \'fields-info-div\');
			window.top.Modules.list();
			
			
			</script>
		';
	}	

		
	
	
	
	
	echo $str;	
}
?>