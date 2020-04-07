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
		
		
	case "adminsList":
			adminsList();		
		break;	
		
		

	case "adminEdit":
			adminEdit();
		break;	
		

		
	case "adminSave": 	#	вызывается в ифрейме! 
			adminSave();
		break;	
		
		
		
	case 'adminDelete':
			adminDelete();
		break;	
	

	
	case "saveChangesInUsersList": 	#	вызывается в ифрейме! 
			saveChangesInUsersList();
		break;
		
		
	
		
		
		
		
	case "groupsList":
			groupsList();		
		break;	
		
	case "groupEdit":
			groupEdit();
		break;	
		
		
	case "groupSave":
			groupSave();
		break;

	case "saveChangesInGroupsList":
			saveChangesInGroupsList();
		break;	
	
		
		
	case "initLoginForm":
			initLoginForm();
		break;	
		
	case "authorize":
			authorize();
		break;	
		
	case "logout":
			logout();
		break;		
		
		
		
	
}















function adminsList()
{
	$error="";
		
	$str='';
	ob_start();
	
	//vd($_REQUEST);
	
	$sql="SELECT * FROM slonne_admins ";
	$qr=mysql_query($sql);
	echo mysql_error();
	if(mysql_num_rows($qr))
	{
		$str.='
		<form name="admins_list_form" method="post" action="modules/admins/admins.php?action=saveChangesInUsersList" target="admins_iframe" >
			
		<table class="t shadow" border="0">
			<tr>
				<th width="1">id</th>
				<th>Акт.</th>
				<th></th>
				<th>Администратор</th>
				
				<th>Группа</th>
				
				<th>Удалить?</th>
			</tr>';
		while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			//vd($next);
			$a=new Admin($next);
		
			//vd($tmp);
			
			$str.='
			<tr class="'.($a->attrs['active'] ? 'active-tr' : 'inactive-tr').'">
				<td >'.$next['id'].'</td>
				<td><input type="checkbox" name="active['.$a->attrs['id'].']" '.($a->attrs['active']?' checked="checked" ':'').'></td>
				<td><button type="button" class="button blue small" title="редактировать" onclick="Admins.adminEdit(\''.$a->attrs['id'].'\');" ><i style="margin: 0px 0px 0px 0px; " class="fa fa-wrench "></i> </button></td>
				<td >'.$a->attrs['name'].'</td>
				
				<td>'.$a->group->attrs['name'].'</td>
				
				<td>
					<button class="button red small " onclick="Admins.adminDelete('.$a->attrs['id'].')"><span class="fa fa-trash-o"></span> удалить</button>
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
		Администраторов нет.';
	}
	
	
	
	$str.='
	<p style="height: 10px;"></p>
	
	<div style="float: left">
		<button class="button " onclick="Admins.adminEdit()">+ добавить <b>администратора</b></button>
	</div>';
	if(mysql_num_rows($qr))
	{
		$str.='
		<div style="float: left; margin-left: 60px;">
			<button class="button blue" type="button" onclick="if(confirm(\'Уверены?\')){document.forms.admins_list_form.submit()}" ><i class="fa fa-floppy-o"></i> сохранить <b>изменения</b></button>
		</div>';
	}
	$str.='<div style="clear:both"></div>';
	
	
	
	echo $str;
	
	$html=ob_get_clean();
	
	
	$json['html']=$html;
	$json['error']=$error;
	
	
	echo json_encode($json);
}	

	





function adminEdit()
{
	$error="";
		
	
		
		
		$str='';
		ob_start();
		
		
		if($id=intval($_REQUEST['id']))
		{
			$admin = new Admin($id=intval($_REQUEST['id']));
			if(!$admin->attrs )
				$error="ADMIN_NOT_EXIST_ERROR [".$_REQUEST['id']."]";
			else 
				$attrs=$admin->attrs;
		}
	//	vd($m);
		
		if(!$error)
		{
			$str.='<a href="javascript:void(0)" onclick="switchTo(\'admins-list-div\', \'admins\')">&larr; назад</a>';
			
			$str.='
			<div style="width: 300px; margin: 20px; 0" class="shadow radius">
				';
			if($admin->attrs)
				$str.='
				<h3>Редактирование администратора "'.$attrs['name'].'"</h3>';
			else 
				$str.='
				<h3>Новый администратор</h3>';
			
			$str.='
			<form name="admins_edit_form" method="post" action="modules/admins/admins.php?action=adminSave" target="admins_iframe" >
				'.($id?'<input type="hidden" name="id" value="'.$id.'">':'').'
			<table border="0" class="edit-table">
				<tr>
					<th width="100">ФИО<span class="req">*</span>: </th>
					<td><input type="text" name="name" id="admins-name" value="'.mysql_real_escape_string(htmlspecialchars($attrs['name'])).'" ></td>
				</tr>
				<tr>
					<th>E-mail<span class="req">*</span>:</th>
					<td><input type="text" name="email" id="admins-email" value="'.mysql_real_escape_string(htmlspecialchars($attrs['email'])).'"></td>
				</tr>
				<tr>
					<th>Группа<span class="req">*</span>:</th>
					<td>';
			$groups=Group::getGroups();
			//vd($groups);
			$str.='
						<select name="group" id="admins-group">
							<option value="">-выберите группу-</option>';
			foreach($groups as $key=>$group)
			{
				$str.='
							<option '.(!$group->attrs['active']?' disabled="disabled" ':'').' value="'.$group->attrs['id'].'" '.($attrs['group'] == $group->attrs['id']?' selected="selected" ':'').'>'.$group->attrs['name'].'</option>';
			}
			$str.='
						</select>';
			$str.='
					</td>
				</tr>
				';
				
			if($admin->attrs)
			{
				$str.='
				<tr>
					<th>Старый пароль<span class="req">*</span>: </th>
					<td><input type="password" name="old_password" id="admins-old-password" value=""></td>
				</tr>
				';
			}
			
			{
				$str.='
				<tr>
					<th>'.($admin->attrs?'Новый пароль':'Пароль').'<span class="req">*</span>: </th>
					<td><input type="password" name="password" id="admins-password" value=""></td>
				</tr>
				<tr>
					<th>Подтвердите пароль<span class="req">*</span>: </th>
					<td><input type="password" name="password2" id="admins-password2" ></td>
				</tr>';
			}	
			
			$str.='
				<tr>
					<td></td>
					<td style="padding-top: 20px;">	
						<!--<a href="javascript:void(0)" style="font-size: 15px; font-weight: bold; " id="edit-save-btn" onclick="EE.checkNewEssenceForm(); ">сохранить</a>-->
						<button class="button " type="button"  onclick="document.forms.admins_edit_form.submit()"><b>сохранить</b></button>
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












function adminSave()
{
	
	//vd($_REQUEST);
	//die;
	if($id = intval($_REQUEST['id']))
	{
		$admin=new Admin($id);
		if(!$admin->attrs )
		{
			die('
			<script>
				window.top.error(\'ADMIN_NOT_EXIST_ERROR ['.$_REQUEST['id'].']\', \'fields-info-div\');
				window.top.Admins.adminsList()
				window.top.loading(0, \'ee-loading-div\', \'fast\');
			</script>');
		}
	}
	
	$name=trim($_REQUEST['name']);
	$email=trim($_REQUEST['email']);
	$oldPassword=trim($_REQUEST['old_password']);
	$password=trim($_REQUEST['password']);
	$password2=trim($_REQUEST['password2']);
	$group=intval($_REQUEST['group']);
	
	//vd($group);
	
	
	
	if(!$name)
	{
		die('
			<script>
				window.top.error(\'Введите ФИО!\', \'fields-info-div\');
				window.top.highlight(\'admins-name\');
				window.top.loading(0, \'ee-loading-div\', \'fast\');
			</script>');
		
	}
	
	if(filter_var($email, FILTER_VALIDATE_EMAIL) === false)
	{
		die('
			<script>
				window.top.error(\'Введите корректный e-mail!\', \'fields-info-div\');
				window.top.highlight(\'admins-email\');
				window.top.loading(0, \'ee-loading-div\', \'fast\');
			</script>');
		
	}
	
	
	#	разрул с группами
	if(!$group)
	{
		die('
			<script>
				window.top.error(\'Выберите группу!\', \'fields-info-div\');
				window.top.highlight(\'admins-group\');
				window.top.loading(0, \'ee-loading-div\', \'fast\');
			</script>');
		
	}
	else
	{
		$g=new Group($group);
		//vd($g);
		if(!$g->attrs)
		{
			die('
			<script>
				window.top.error(\'Выбрана несуществующая группа! ['.$_REQUEST['group'].']\', \'fields-info-div\');
				window.top.highlight(\'admins-group\');
				window.top.loading(0, \'ee-loading-div\', \'fast\');
			</script>');
		}
	}
	
	
	
	
	#	пароли
	if(!$admin->attrs || $oldPassword)
	{
		$passwordShouldBeSaved=true;
		
		if($oldPassword)	#	старый пароль введён, значит будет процедура замены пароля
		{
			if($admin->attrs)
			{
				if(!$admin->checkOwnPassword($oldPassword))
				{
					die('
					<script>
						window.top.error(\'Неверный старый пароль! \', \'fields-info-div\');
						window.top.highlight(\'admins-old-password\');
						window.top.loading(0, \'ee-loading-div\', \'fast\');
					</script>');
				}
			}
		}
		
		if(!$password)
		{
			die('
			<script>
				window.top.error(\'Введите пароль! \', \'fields-info-div\');
				window.top.highlight(\'admins-password\');
				window.top.loading(0, \'ee-loading-div\', \'fast\');
			</script>');
		}
		if(!$password2)
		{
			die('
			<script>
				window.top.error(\'Подтвердите пароль! \', \'fields-info-div\');
				window.top.highlight(\'admins-password2\');
				window.top.loading(0, \'ee-loading-div\', \'fast\');
			</script>');
		}
		
		if($password != $password2)
		{
			die('
			<script>
				window.top.error(\'Пароли не совпали! \', \'fields-info-div\');
				window.top.highlight([\'admins-password\', \'admins-password2\']);
				window.top.loading(0, \'ee-loading-div\', \'fast\');
			</script>');
		}
		
	}
//	die;
	
	
	
	{
		echo "VSE OK BUDEM SAVE";
		
		
		
		
		//die;
		if($admin->attrs)
			$sql="UPDATE `slonne_admins` SET ";
		else 	
			$sql="INSERT INTO `slonne_admins` SET active=1, regtime=NOW(),  ";
		
		$sql.="
		name='".mysql_real_escape_string($name)."'
		, email='".mysql_real_escape_string($email)."'
		, `group`=".$group."
		".($passwordShouldBeSaved?", password='".mysql_real_escape_string($password)."'":"")."
		";
		
		
		
		
		
		if($admin->attrs)
			$sql.=" WHERE id=".$admin->attrs['id'];
			
	
		mysql_query($sql);
		vd($sql);
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
		window.top.notice(\'Администратор ['.$name.'] '.($admin->attrs?'изменён':'добавлен').'!\', \'fields-info-div\');
		window.top.Admins.adminsList();
	</script>
	';
	
	
	echo $str;	
}








	
	
	
function adminDelete()
{
	$error="";
		
	$str='';
	ob_start();
	
	//vd($_REQUEST);
	
	
	if($id=intval($_REQUEST['id']))
	{
		$sql="DELETE FROM slonne_admins WHERE id=".$id;
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































function saveChangesInUsersList()
{
	//vd($_REQUEST);
	
	#	меняем активность
	foreach($_REQUEST['active'] as $key=>$val)	
		$act[]=intval($key);
	$sql="UPDATE slonne_admins SET active=0";
	mysql_query($sql);
	echo mysql_error();
	
	if(count($act))
	{
		$sql="UPDATE slonne_admins SET active=1 WHERE id IN (".join(", ", $act).")";
		mysql_query($sql);
		echo mysql_error();
	}
	
	
	echo '
	<script>
		window.top.notice(\'Изменения сохранены\', \'fields-info-div\');
		window.top.Admins.adminsList();
		
		
		</script>
	';

	echo $str;	
}










function groupsList()
{
	$error="";
		
	$str='';
	ob_start();
	
	//vd($_REQUEST);
	
	$sql="SELECT * FROM slonne_admins_groups ORDER BY idx ";
	$qr=mysql_query($sql);
	echo mysql_error();
	if(mysql_num_rows($qr))
	{
		$str.='
		<form name="groups_list_form" method="post" action="modules/admins/admins.php?action=saveChangesInGroupsList" target="admins_iframe" >
			
		<table class="t shadow" border="0">
			<tr>
				
				<th>Акт.</th>
				<th width="1">id</th>
				
				<th></th>
				<th>Группа</th>
				
				<th>Сорт.</th>
				<th>Удалить?</th>
			</tr>';
		while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			
			//vd($tmp);
			
			$str.='
			<tr class="'.($next['active'] ? 'active-tr' : 'inactive-tr').'">
				<td><input type="checkbox" name="active['.$next['id'].']" '.($next['active']?' checked="checked" ':'').'></td>
				<td >'.$next['id'].'</td>
				
				<td><button type="button" class="button blue small" title="редактировать" onclick="Admins.groupEdit(\''.$next['id'].'\');" ><i style="margin: 0px 0px 0px 0px; " class="fa fa-wrench "></i> </button></td>
				<td style="font-weight: bold; ">'.$next['name'].'</td>
				
				<td><input size="2" style="width: 25px; font-size: 9px;"  type="text" id="idx-'.$next['id'].'" name="idx['.$next['id'].']" value="'.intval($next['idx']).'"></td>
				<td>
					<button class="button red small " onclick="Modules.adminDelete('.$next['id'].')"><span class="fa fa-trash-o"></span> удалить</button>
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
		Группы пока не назначены.';
	}
	
	
	
	$str.='
	<p style="height: 10px;"></p>
	
	<div style="float: left">
		<button class="button " onclick="Admins.groupEdit()">+ добавить <b>группу</b></button>
	</div>';
	if(mysql_num_rows($qr))
	{
		$str.='
		<div style="float: left; margin-left: 60px;">
			<button class="button blue" type="button" onclick="if(confirm(\'Уверены?\')){document.forms.groups_list_form.submit()}" ><i class="fa fa-floppy-o"></i> сохранить <b>изменения</b></button>
		</div>';
	}
	$str.='<div style="clear:both"></div>';
	
	
	
	echo $str;
	
	$html=ob_get_clean();
	
	
	$json['html']=$html;
	$json['error']=$error;
	
	
	echo json_encode($json);
}










function groupEdit()
{
	$error="";
		
	
		
		
		$str='';
		ob_start();
		
		//vd($_REQUEST);
		if($id=intval($_REQUEST['id']))
		{
			$g = new Group($id=intval($_REQUEST['id']));
			//vd($g->attrs['privileges']);
			if(!$g->attrs )
				$error="GROUP_NOT_EXIST_ERROR [".$_REQUEST['id']."]";
			else 
			{
				$attrs=$g->attrs;
				//$privileges=$g->attrs['privileges'];
			}
		}
	//	vd($m);
		
		if(!$error)
		{
			$str.='<a href="javascript:void(0)" onclick="switchTo(\'groups-list-div\', \'admins\')">&larr; назад</a>';
			
			$str.='
			<div style="width: 360px; margin: 20px; 0" class="shadow radius">
				';
			if($g->attrs)
				$str.='
				<h3>Редактирование группы "'.$g->attrs['name'].'"</h3>';
			else 
				$str.='
				<h3>Новая группа</h3>';
			
			$str.='
			<form name="groups_edit_form" method="post" action="modules/admins/admins.php?action=groupSave" target="admins_iframe" >
				'.($id?'<input type="hidden" name="id" value="'.$id.'">':'').'
			<table border="0" class="edit-table" width="100%">
				<tr>
					<th width="100">Название<span class="req">*</span>: </th>
					<td><input type="text" name="name" id="groups-name" value="'.mysql_real_escape_string(htmlspecialchars($attrs['name'])).'" ></td>
				</tr>
				
				<tr>
					<th>Права:<span class="req">*</span>: </th>
					<td>'.Group::getSelectHTML($g->attrs['privileges_arr']).'</td>
				</tr>
				
				
				
			
				<tr>
					<td></td>
					<td style="padding-top: 20px;">	
						<!--<a href="javascript:void(0)" style="font-size: 15px; font-weight: bold; " id="edit-save-btn" onclick="EE.checkNewEssenceForm(); ">сохранить</a>-->
						<button class="button " type="button"  onclick="document.forms.groups_edit_form.submit()"><b>сохранить</b></button>
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














function groupSave()
{
	
	//vd($_REQUEST);
	
	//die;
	
	if($id = intval($_REQUEST['id']))
	{
		$g=new Group($id);
		if(!$g->attrs )
		{
			die('
			<script>
				window.top.error(\'GROUP_NOT_EXIST_ERROR ['.$_REQUEST['id'].']\', \'fields-info-div\');
				window.top.Admins.groupsList()
				window.top.loading(0, \'ee-loading-div\', \'fast\');
			</script>');
		}
	}
	
	$name=trim($_REQUEST['name']);
	$privStr=Group::actionsArr2str($_REQUEST['privileges']);
	
	if(!$name)
	{
		die('
			<script>
				window.top.error(\'Введите название группы!\', \'fields-info-div\');
				window.top.highlight(\'groups-name\');
				window.top.loading(0, \'ee-loading-div\', \'fast\');
				
			</script>');
		
	}
	
	
	
	
	
	
	
	{
		
		#	подпорка для неактивных модулей
		vd($privStr);
		if($g->attrs)
		{
			//if(!$m->attrs[])
			//vd($g);
			if($modules=Module::getInactiveModules())
			{
				//vd($modules);
			//	vd($g);
				$tmp=array();
				foreach($modules as $key=>$m)
				{
					$code=$m->attrs['code'];
					$tmp[$code]=$g->attrs['privileges_arr'][$code];
				}	
				$tmp2=Group::actionsArr2str($tmp);
				//vd($tmp2);
				$privStr.=Group::$modulesDelimiter.$tmp2;
			}
			
			
			
		}
		
		//vd($privStr);
	//	die;
		
		echo "VSE OK BUDEM SAVE";
		//die;
		if($g->attrs)
			$sql="UPDATE `slonne_admins_groups` SET ";
		else 	
			$sql="INSERT INTO `slonne_admins_groups` SET active=1, idx=100,  ";
		
		$sql.="
		name='".mysql_real_escape_string($name)."'
		, privileges='".mysql_real_escape_string($privStr)."'
		";
		if($g->attrs)
			$sql.=" WHERE id=".$g->attrs['id'];
			
		
					
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
		window.top.notice(\'Группа ['.$name.'] '.($m->attrs?'изменена':'добавлена').'!\', \'fields-info-div\');
		window.top.Admins.groupsList();
	</script>
	';
	
	
	echo $str;	
}











function saveChangesInGroupsList()
{
	//vd($_REQUEST);
	
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
				window.top.loading(0, \'admins-loading-div\', \'fast\');
			</script>');
	}
	else
	{
		#	меняем idx
		foreach($_REQUEST['idx'] as $key=>$val)
		{
			$sql="UPDATE slonne_admins_groups SET idx='".intval($val)."' WHERE id='".intval($key)."'";
			mysql_query($sql);
			echo mysql_error();
		}
		
		
		#	меняемактивность
		foreach($_REQUEST['active'] as $key=>$val)	
			$act[]=intval($key);
		$sql="UPDATE slonne_admins_groups SET active=0";
		mysql_query($sql);
		echo mysql_error();
		
		if(count($act))
		{
			$sql="UPDATE slonne_admins_groups SET active=1 WHERE id IN (".join(", ", $act).")";
			mysql_query($sql);
			echo mysql_error();
		}
		
		echo '
		<script>
			window.top.notice(\'Изменения сохранены\', \'fields-info-div\');
			window.top.Admins.groupsList();
			
			
			</script>
		';
	}	

		
	
	
	
	
	echo $str;	
}
















function initLoginForm()
{
	$error="";
		

	$str='';
	ob_start();
	//unset($_SESSION);
//	vd($_SESSION);
	$str.='
	<div id="auth-form-container">
	<form name="login_form" id="login-form" method="post" action="/admin/modules/admins/admins.php?action=authorize" onsubmit="Admins.authorize(); return false;" target="login_form_iframe">
		
		<div id="login-inputs-div">
			<span>E-mail: </span><input type="text" name="email" id="auth-email">
			<br>
			<span>Пароль: </span><input type="password" id="auth-password" name="password">
		</div>
		
		<!--<a id="login-form-go-btn" class="transitional" href="javascript:void(0)" onclick="Admins.authorize()"><i class="fa fa-sign-in"></i></a>-->
		<button type="submit" id="login-form-go-btn" class="transitional" ><i class="fa fa-sign-in"></i></button>
		<div id="login-form-loading-div" style="display: none; ">Загрузка....</div>
		<div style="clear: both;"></div>
		
		<iframe  name="login_form_iframe" style=" display: none; border: 1px solid black; width: 600px ; height: 600px;"></div >
	</form>
	</div>';
	
	echo $str;
	
	$html=ob_get_clean();
	
	
	$json['html']=$html;
	$json['error']=$error;

	
	
	echo json_encode($json);
}








function authorize()
{

	
	//vd($_REQUEST);
	//unset($_SESSION);
	
	
	$email=trim($_REQUEST['email']);
	$pass=trim($_REQUEST['password']);
	
	//$email='';
	if(!$email)
	{
		die('
			<script>
			window.top.error("Введите e-mail");
			window.top.highlight(\'auth-email\');
			</script>
		');
	}
	if(!$pass)
	{
		die('
			<script>
			window.top.loading(0, \'login-form-loading-div\', \'fast\')	
			window.top.error("Введите пароль");
			window.top.highlight(\'auth-password\');
			</script>
		');
	}
	
	if(!$error)
	{
		if(!$res=Admin::authorize($email, $pass))
		{
			die('
			<script>
			window.top.loading(0, \'login-form-loading-div\', \'fast\')	
			window.top.error("Неверный логин / пароль! TRieS: ");
			window.top.highlight(\'auth-email\');
			window.top.highlight(\'auth-password\');
			</script>
			');
		}
		else	#	норм
		{
			echo'
			<script>
				window.top.loading(0, \'login-form-loading-div\', \'fast\')	
				window.top.notice("ok")
				window.top.highlight("auth-email", true);
				window.top.highlight("auth-password", true);
				window.top.setTimeout(\'location.href="?"\', 500)
				
			</script>';
		}
		
	}
	
	
	echo $str;
	
	
	
}







function logout()
{
	$error="";
		
//sleep(1);
	$str='';
	ob_start();
	
	unset($_SESSION['admin']);
	
	vd($_SESSION);
	
	echo $str;
	
	$html=ob_get_clean();
	
	
	$json['html']=$html;
	$json['error']=$error;

	
	
	echo json_encode($json);
}


?>