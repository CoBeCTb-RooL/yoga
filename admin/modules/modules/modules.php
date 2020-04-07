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
	$error="";
		
	$str='';
	ob_start();
	
	//vd($_REQUEST);
	
	$sql="SELECT * FROM slonne_modules ORDER BY idx";
	$qr=mysql_query($sql);
	echo mysql_error();
	if(mysql_num_rows($qr))
	{
		$str.='
		<form name="module_list_form" method="post" action="modules/modules/modules.php?action=saveChangesInList" target="mod_iframe" >
			
		<table class="t shadow" border="1">
			<tr>
				<th width="1">id</th>
				<th>Акт.</th>
				<th></th>
				<th>Модуль</th>
				<th>Код</th>
				<th>Path</th>
				<th>$_GET</th>
				<th>Права</th>
				<th>Сорт.</th>
				<th>Удалить?</th>
			</tr>';
		while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			
			//vd($tmp);
			
			$str.='
			<tr class="'.($next['active'] ? 'active-tr' : 'inactive-tr').'">
				<td >'.$next['id'].'</td>
				<td><input type="checkbox" name="active['.$next['id'].']" '.($next['active']?' checked="checked" ':'').'></td>
				<td><button type="button" class="button blue small" title="редактировать" onclick="Modules.edit(\''.$next['id'].'\');" ><i style="margin: 0px 0px 0px 0px; " class="fa fa-wrench "></i> </button></td>
				<td style="font-weight: bold; ">'.($next['icon']?$next['icon'].' ':'').''.$next['name'].'</td>
				<td>'.$next['code'].'</td>
				<td>'.$next['path'].'</td>
				<td>'.$next['get_str'].'</td>
				<td>--</td>
				<td><input size="2" style="width: 25px; font-size: 9px;"  type="text" id="idx-'.$next['id'].'" name="idx['.$next['id'].']" value="'.intval($next['idx']).'"></td>
				<td>
					<button class="button red small " onclick="Modules.delete('.$next['id'].')"><span class="fa fa-trash-o"></span> удалить</button>
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
		Модулией нет.';
	}
	
	
	
	$str.='
	<p style="height: 10px;"></p>
	
	<div style="float: left">
		<button class="button " onclick="Modules.edit()">+ добавить <b>модуль</b></button>
	</div>';
	if(mysql_num_rows($qr))
	{
		$str.='
		<div style="float: left; margin-left: 60px;">
			<button class="button blue" type="button" onclick="if(confirm(\'Уверены?\')){document.forms.module_list_form.submit()}" ><i class="fa fa-floppy-o"></i> сохранить <b>изменения</b></button>
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
	$error="";
		
	
		
		
		$str='';
		ob_start();
		
		
		if($id=intval($_REQUEST['id']))
		{
			$m = new Module($id=intval($_REQUEST['id']));
			if(!$m->attrs )
				$error="MODULE_NOT_EXIST_ERROR [".$_REQUEST['id']."]";
			else 
				$attrs=$m->attrs;
		}
	//	vd($m);
		
		if(!$error)
		{
			$str.='<a href="javascript:void(0)" onclick="switchTo(\'mod-list-div\', \'mod\')">&larr; назад</a>';
			
			$str.='
			<div style="width: 300px; margin: 20px; 0" class="shadow radius">
				';
			if($m->attrs)
				$str.='
				<h3>Редактирование модуля "'.$m->attrs['name'].'"</h3>';
			else 
				$str.='
				<h3>Новый модуль</h3>';
			
			$str.='
			<form name="module_edit_form" method="post" action="modules/modules/modules.php?action=save" target="mod_iframe" >
				'.($id?'<input type="hidden" name="id" value="'.$id.'">':'').'
				<niptu type="hidden" name="action" value="save">
			<table border="0" class="edit-table">
				<tr>
					<th>Название<span class="req">*</span>: </th>
					<td><input type="text" name="name" id="mod-name" value="'.mysql_real_escape_string(htmlspecialchars($attrs['name'])).'" ></td>
				</tr>
				<tr>
					<th>Иконка: </th>
					<td><input type="text" name="icon" id="mod-icon" value="'.mysql_real_escape_string(htmlspecialchars($attrs['icon'])).'"></td>
				</tr>
				<tr>
					<th>Код<span class="req">*</span>: </th>
					<td><input type="text" name="code" id="mod-code" value="'.mysql_real_escape_string(htmlspecialchars($attrs['code'])).'"></td>
				</tr>
				<tr>
					<th>Путь<span class="req">*</span>: </th>
					<td><input type="text" name="path" id="mod-path" value="'.mysql_real_escape_string(htmlspecialchars($attrs['path'])).'"></td>
				</tr>
				<tr>
					<th>$_GET-строка: </th>
					<td>?<input type="text" name="get_str" id="mod-get-str" value="'.mysql_real_escape_string(htmlspecialchars($attrs['get_str'])).'"></td>
				</tr>
				
				<tr>
					<th>Действия:</th>
					<td>
						<textarea name="actions" style="height: 80px">'.(($attrs['actions'])).'</textarea>
					</td>
				</tr>
			
				<tr>
					<td></td>
					<td style="padding-top: 20px;">	
						<!--<a href="javascript:void(0)" style="font-size: 15px; font-weight: bold; " id="edit-save-btn" onclick="EE.checkNewEssenceForm(); ">сохранить</a>-->
						<button class="button " type="button"  onclick="document.forms.module_edit_form.submit()"><b>сохранить</b></button>
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
	
	//vd($_REQUEST);
	
	
	if($id = intval($_REQUEST['id']))
	{
		$m=new Module($id);
		if(!$m->attrs )
		{
			die('
			<script>
				window.top.error(\'MODULE_NOT_EXIST_ERROR ['.$_REQUEST['id'].']\', \'fields-info-div\');
				window.top.Modules.list()
				window.top.loading(0, \'ee-loading-div\', \'fast\');
			</script>');
		}
	}
	
	$name=trim($_REQUEST['name']);
	$code=trim($_REQUEST['code']);
	$getStr=trim($_REQUEST['get_str']);
	$path=trim($_REQUEST['path']);
	$icon=trim($_REQUEST['icon']);
	
	$actions=trim($_REQUEST['actions']);
	/*if($actions=trim($_REQUEST['actions']))
	{
		//vd($actions);
		$tmp=explode("\r\n", $actions);
		foreach($tmp as $key=>$val)
		{
			//vd($val);
			$tmp2=explode("=", $val);
			
			$actType=trim($tmp2[0]);
			$actName=trim($tmp2[1]);
			
			if($actType && $actName)
				$arr[$actType]=$actName;
		}
		vd($arr);
		$tmp3=json_encode($arr);
		vd($tmp3);
	}*/
	//return;
	
	if(!$name)
	{
		$troubles[]='mod-name';
		$e='Введите название!';
	}
	
	if(!$code)
	{
		$troubles[]='mod-code';
		$e='Введите код!';
	}
	
	if(!$path)
	{
		$troubles[]='mod-path';
		$e='Введите путь!';
	}
	else
	{
		$tmp=preg_match('/^[a-zA-Z][a-zA-Z0-9_]+$/', $code);
		if(!$tmp)	
		{
			$troubles[]='mod-code';
			$e='Некорректный код!';
		}
	}
	
	
	if(!$m->attrs)
	{
		#	проверим на повтор имени
		$sql="SELECT * FROM slonne_modules WHERE name='".mysql_real_escape_string($name)."'";
		$qr=mysql_query($sql);
		echo mysql_error();
		if(mysql_num_rows($qr))	
		{
			$troubles[]='mod-name';
			$e='Модуль с таким названием уже существует!';
		}	
		
		
		
		#	проверим на повтор кода
		$sql="SELECT * FROM slonne_modules WHERE `code`='".mysql_real_escape_string($code)."'";
		$qr=mysql_query($sql);
		echo mysql_error();
		if(mysql_num_rows($qr))	
		{
			$troubles[]='mod-code';
			$e='Модуль с таким кодом уже существует!';
		}	
	}
	
	
	if(count($troubles))
	{
		die('
			<script>
				window.top.error(\''.$e.'\', \'fields-info-div\');
				window.top.highlight(["'.join('", "', $troubles).'"]);
				window.top.loading(0, \'ee-loading-div\', \'fast\');
			</script>');
	}
	else	#	если всё ок
	{
		echo "VSE OK BUDEM SAVE";
		//die;
		if($m->attrs)
			$sql="UPDATE `slonne_modules` SET ";
		else 	
			$sql="INSERT INTO `slonne_modules` SET active=1, idx=100, ";
		
		$sql.="
		name='".mysql_real_escape_string($name)."'
		, code='".mysql_real_escape_string($code)."'
		, icon='".mysql_real_escape_string($icon)."'
		, get_str='".mysql_real_escape_string($getStr)."'
		, path='".mysql_real_escape_string($path)."'
		, actions = '".mysql_real_escape_string($actions)."'
		";
		if($m->attrs)
			$sql.=" WHERE id=".$m->attrs['id'];
			
	
		mysql_query($sql);
		//vd($sql);
		if($e=mysql_error())
		{
			
			die('
			<script>
				window.top.error(\'ОШИБКА! '.mysql_real_escape_string($e).'\', \'fields-info-div\');
				window.top.loading(0, \'ee-loading-div\', \'fast\');
			</script>');
		}
	//	vd($sql);
		
	}
	$str.='
	<script>
		window.top.notice(\'Модуль ['.$name.'] '.($m->attrs?'изменён':'добавлен').'!\', \'fields-info-div\');
		window.top.Modules.list();
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
		$sql="DELETE FROM slonne_modules WHERE id=".$id;
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