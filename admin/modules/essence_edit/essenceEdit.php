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
		
		
		
	case 'deleteEssence':
			deleteEssence();
		break;	
		
	
		
	case "editEssence":
			editEssence();
		break;
		
		
	
	case "save": 	#	вызывается в ифрейме! 
			saveEssence();
		break;

		
		
	case "fieldsList":
			fieldsList();
		break;
		

		
	case "fieldEdit":
			fieldEdit();
		break;


		
		
	case "saveField": 	#	вызывается в ифрейме! 
			saveField();
		break;
		
		

		
	case 'deleteField':
			deleteField();	
		break;	

		
	
	case "submitFieldsListForm": 	#	вызывается в ифрейме! 
			submitFieldsListForm();
		break;
		
		
		
		
	case 'fixLangVersion':
			fixLangVersion();
		break;		
		
		
}















function list1()
{
	global $_CONFIG;
	
	$error="";
		
	$str='';
	ob_start();
	
	//vd($_REQUEST);
	//vd($_CONFIG['langs']);
	
	
	$sql="SELECT * FROM slonne_essences ORDER BY id";
	$qr=mysql_query($sql);
	echo mysql_error();
	if(mysql_num_rows($qr))
	{
		$str.='
		<table class="t shadow" border="1">
			<tr>
				<th width="1">id</th>
				<th>Сущность</th>
				<th>Код</th>
				<th>Блоки</th>
				<th>Элементы</th>
				<th>Удалить?</th>
			</tr>';
		while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			$langVersions;
		/*	$sql="SELECT * from qweqwe";
			$qr1=mysql_query($sql);
			echo mysql_error();*/
			$et=new Essence($next);
			//vd($et);
			//vd($et->getLangVersionsExistence());
			$langsExistence=$et->getLangVersionsExistence();
			
			
			
			
			//vd($langsExistence);
			
			//vd($et);
			
			//$tmp=Essence::getFields($next['id']);
			//vd($tmp);
			$count['blocks']=count($et->fields['blocks']);
			$count['elements']=count($et->fields['elements']);
			
			//vd($tmp);
			
			$str.='
			<tr>
				<td >'.$next['id'].'</td>
				<td style="font-weight: bold; ">'.$next['name'].'</td>
				<td>'.$next['code'].'</td>';
			
			#	если 2 колонки полей
			if(!($next['joint_fields'] || $next['linear']))
			{	
				$str.='
				<td>
					<a href="javascript:void(0)" onclick="EE.fieldsList(\''.$next['code'].'\', \'blocks\')"><span class="fa fa-list"></span> Поля блоков ('.$count['blocks'].')</a>';
				
				
				#	языковые БЛОКОВ
				foreach($_CONFIG['langs'] as $key=>$val)
				{
					//vd($val);
					//vd($et);
					if($langsExistence[$key]['blocks'])
					{
						$str.='
						<br><span style="font-size: 10px; color: green" >'.$val['title'].' - ЕСТЬ!</span>';
					}
					else
					{
						$str.='
						<br><a style="font-size: 10px;" href="javascript:void(0)" onclick="EE.fixLangVersion(\''.$key.'\', \''.$et->attrs['code'].'\', \'blocks\')">'.$val['title'].' - <b>НЕТ!</b></a>';
					}
				}
				
				
				
				$str.='
				</td>
				
				<td>
					<a href="javascript:void(0)" onclick="EE.fieldsList(\''.$next['code'].'\', \'elements\')"><span class="fa fa-list"></span> Поля элементов ('.$count['elements'].')</a>';
				
				#	языковые ЭЛЕМЕНТОВ
				foreach($_CONFIG['langs'] as $key=>$val)
				{
					//vd($val);
					//vd($et);
					if($langsExistence[$key]['elements'])
					{
						$str.='
						<br><span style="font-size: 10px; color: green" >'.$val['title'].' - ЕСТЬ!</span>';
					}
					else
					{
						$str.='
						<br><a style="font-size: 10px;" href="javascript:void(0)" onclick="EE.fixLangVersion(\''.$key.'\', \''.$et->attrs['code'].'\')">'.$val['title'].' - <b>НЕТ!</b></a>';
					}
				}
				
				
				$str.='
				</td>';
			}
			
			else
			{
				$str.='
				<td colspan="2" align="center">
					<a href="javascript:void(0)" onclick="EE.fieldsList(\''.$next['code'].'\', \'elements\')"><span class="fa fa-list"></span> Поля ('.$count['elements'].')</a>';
				
				#	языковые ЭЛЕМЕНТОВ
				foreach($_CONFIG['langs'] as $key=>$val)
				{
					//vd($val);
					//vd($et);
					if($langsExistence[$key]['elements'])
					{
						$str.='
						<br><span style="font-size: 10px; color: green" >'.$val['title'].' - ЕСТЬ!</span>';
					}
					else
					{
						$str.='
						<br><a style="font-size: 10px;" href="javascript:void(0)" onclick="EE.fixLangVersion(\''.$key.'\', \''.$et->attrs['code'].'\')">'.$val['title'].' - <b>НЕТ!</b></a>';
					}
				}
				
				$str.='
				</td>';
			}
			
			$str.='	
				<td>
					<!--<a style="color: #C90000;" href="javascript:void(0)" onclick="EE.deleteEssence('.$next['id'].')"><span class="fa fa-trash-o"></span> удалить</a>-->
					<button class="button red small " onclick="EE.deleteEssence('.$next['id'].')"><span class="fa fa-trash-o"></span> удалить</button>
				</td>
			</tr>';
		}
		$str.='
		</table>';
	}
	else 
	{
		$str.='
		Сущностей нет.';
	}
	
	
	
	$str.='
	<p>
	
	
	<button class="button " onclick="EE.editEssence()">+ новая <b>сущность</b></button>';
	
	
	
	echo $str;
	
	$html=ob_get_clean();
	
	
	$json['html']=$html;
	$json['error']=$error;
	
	
	echo json_encode($json);
}	

	
	
	
	
function deleteEssence()
{
	$error="";
		
	$str='';
	ob_start();
	
	//vd($_REQUEST);
	if($id=intval($_REQUEST['id']) )
	{
		$essence=new Essence($id);
		if($essence->attrs)
		{
			//vd($essence);
			if( ($result = $essence->delete() ) === true)
			{
				$str.='Сущность <b>'.$essence->attrs['name'].'</b> удалена!';
			}
			else
			{
				$error=$result;
			}
		}
		else
			$error="eRRoR: ESSENCE_NOT_EXIST";
	}
	else 	
		$error='eRRoR: ESSENCE_UNDEFINED';
	
	$str.='';

	echo $str;
	
	$html=ob_get_clean();
	
	
	$json['html']=$html;
	$json['error']=$error;

	
	
	echo json_encode($json);
	
}







function editEssence()
{
	$error="";
		
		$str='';
		ob_start();
		$str.='<a href="javascript:void(0)" onclick="switchTo(\'ee-list-div\', \'ee\')">&larr; назад</a>';
		
		$str.='
		<div style="width: 300px; margin: 20px; 0" class="shadow radius">
			';
		if($editing)
				$str.='
				<h3>eDiTiNG eSSeNCe</h3>';
			else 
				$str.='
				<h3>Добавляем сущность</h3>';
			
			$str.='
			<form name="essence_edit_form" method="post" action="modules/essence_edit/essenceEdit.php?action=save" target="ee_iframe" enctype="multipart/form-data">
				'.($id?'<input type="hidden" name="id" value="'.$id.'">':'').'
			<table border="0" class="edit-table">
				<tr>
					<th>Название<span class="req">*</span>: </th>
					<td><input type="text" name="name" id="ee-name" ></td>
				</tr>
				<tr>
					<th>Код<span class="req">*</span>: </th>
					<td><input type="text" name="code" id="ee-code" ></td>
				</tr>
				
				<tr>
					<th>Объединённые поля: </th>
					<td><input type="checkbox" name="joint_fields" id="ee-joint-fields" ></td>
				</tr>
				
				<tr>
					<th>Линейный: </th>
					<td><input type="checkbox" name="linear" id="ee-linear" ></td>
				</tr>
				'; 
			
			$str.='
				<tr>
					<td></td>
					<td style="padding-top: 20px;">	
						<!--<a href="javascript:void(0)" style="font-size: 15px; font-weight: bold; " id="edit-save-btn" onclick="EE.checkNewEssenceForm(); ">сохранить</a>-->
						<button class="button " type="button"  onclick="EE.checkNewEssenceForm(); "><b>сохранить</b></button>
					</td>
				</tr>
			</table>
			</form>
			</div>
			
			
			';

		echo $str;
		
		$html=ob_get_clean();
		
		
		$json['html']=$html;
		$json['error']=$error;
		$json['required']=$requiredFields;
		
		
		echo json_encode($json);
}












function saveEssence()
{
	global $_CONFIG;
	
	$name=trim($_REQUEST['name']);
	$code=trim($_REQUEST['code']);
	$jointFields=$_REQUEST['joint_fields'];
	$linear=$_REQUEST['linear'];
	//vd($_REQUEST);
//	vd($name);
//	vd($table);

	
	
	
	if(!$name)
	{
		$troubles[]='ee-name';
		$e='Введите название!';
	}
	
	if(!$code)
	{
		$troubles[]='ee-code';
		$e='Введите код';
	}
	else
	{
		$tmp=preg_match('/^[a-zA-Z][a-zA-Z0-9_]+$/', $code);
		if(!$tmp)	
		{
			$troubles[]='ee-code';
			$e='Некорректный код!';
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
		
		#	сначала проверим есть ли в сущностях уже сущность с таким кодом
		$sql="SELECT * FROM slonne_essences WHERE `code`='".mysql_real_escape_string($code)."'";
	//	vd($sql);
		$qr=mysql_query($sql);
		if(mysql_num_rows($qr))
		{
			die('
			<script>
				window.top.error(\'Сущность с таким кодом уже существует!\', \'fields-info-div\');
				window.top.highlight(["ee-code"]);
				window.top.loading(0, \'ee-loading-div\', \'fast\');
			</script>');
		}
		
		#	ЗАПИСЬ В РЕЕСТР СУЩНОСТЕЙ
		$sql="
		INSERT INTO slonne_essences 
		SET 
		`name`='".mysql_real_escape_string($name)."', 
		`code`='".mysql_real_escape_string($code)."', 
		`joint_fields`='".($jointFields ? 1 : 0)."',
		`linear`='".($linear ? 1 : 0)."' 
		";
		//vd($sql);
		mysql_query($sql);
		echo mysql_error();
		if($e=mysql_error())
		{
			die('
			<script>
				window.top.error(\'ОШИБКА! '.$e.'\', \'fields-info-div\');
				window.top.highlight(["ee-code"]);
				window.top.loading(0, \'ee-loading-div\', \'fast\');
			</script>');
		}
		
		
		#	для всех языков
		foreach($_CONFIG['langs'] as $key=>$val)
		{
			
			$elementsTbl=Essence::getTblName($code, 'elements', $key);
			$blocksTbl=Essence::getTblName($code, 'blocks', $key);
			
			
			#	проверка - есть ли уже такая таблица
			$sql="SELECT * FROM `".mysql_real_escape_string($elementsTbl)."`";
			$qr=mysql_query($sql);
			if($e=mysql_error())
			{
				
				#	надо создавать!
				$sql="
					CREATE TABLE `".mysql_real_escape_string($elementsTbl)."` (
					`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
					`pid` INT( 11 ) NOT NULL ,
					`idx` INT( 11 ) NOT NULL ,
					`active` INT( 1 ) NOT NULL ,
					PRIMARY KEY ( `id` )
					);";
				//vd($sql);
				mysql_query($sql);
				if($e=mysql_error())
				{
					die('
					<script>
						window.top.error(\'ОШИБКА! '.$e.'\', \'fields-info-div\');
						window.top.highlight(["ee-code"]);
						window.top.loading(0, \'ee-loading-div\', \'fast\');
					</script>');
				}
				
				#	и для блоков
				if(!$jointFields)
				{
					$sql="
						CREATE TABLE `".mysql_real_escape_string($blocksTbl)."` (
						`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
						`pid` INT( 11 ) NOT NULL ,
						`idx` INT( 11 ) NOT NULL ,
						`active` INT( 1 ) NOT NULL ,
						PRIMARY KEY ( `id` )
						);";
					//vd($sql);
					mysql_query($sql);
					if($e=mysql_error())
					{
						die('
						<script>
							window.top.error(\'ОШИБКА! '.$e.'\', \'fields-info-div\');
							window.top.highlight(["ee-code"]);
							window.top.loading(0, \'ee-loading-div\', \'fast\');
						</script>');
					}
				}
			}
		
		}
	}
	$str.='
	<script>
		window.top.notice(\'Сущность ['.$name.'] сохранена!\', \'fields-info-div\');
		window.top.EE.list();
	</script>
	';
	
	
	echo $str;	
}








function fieldsList()
{
	global $_CONFIG;
	
	$error="";
		
		$str='';
		ob_start();
		//phpinfo();
	//	vd($_REQUEST);
		
		$str.='<a href="javascript:void(0)" onclick="switchTo(\'ee-list-div\', \'ee\')">&larr; назад</a>';
		
		$code=mysql_real_escape_string(trim($_REQUEST['essence']));
		
		//vd($_REQUEST);
		
		$type=$_REQUEST['owner_type'];
		//vd($type);
		if($type!='blocks')
			$type="elements";
		
		//	vd($type);
	//	vd($code);
		
		$essence=new Essence($code);
		//vd($essence);

		if($essence->attrs)
		{
			$str.='<div style="font-size: 16px;">Поля <b>'.($type=='blocks'?'блоков':'элементов').'</b> типа <b style="font-size: 20px;">'.$essence->attrs['name'].'</b></div>';
			
			if(count($essence->fields[$type]))
			{
				$str.='
				<form name="fields_list_form" method="post" action="modules/essence_edit/essenceEdit.php?action=submitFieldsListForm" target="ee_iframe">
			<table class="t shadow " border="1">
				<tr>
					<th width="1">#</th>
					<th></th>
					<th></th>
					<th></th>
					<th width="1">id</th>
					<th>Поле</th>
					<th>Код</th>
					<th>Тип</th>
					<th><span class="fa fa-sort-amount-asc "></span></th>
					<th>Удалить?</th>
				</tr>';
		//	vd($essence);
		
			foreach($essence->fields[$type] as $key=>$val)
			{
				//vd($val);
				$str.='
				<tr>
					<td style="font-size: 9px;">'.(++$i).'. </td>
					<td>
						<button type="button" title="редактировать" onclick="EE.fieldEdit(\''.$code.'\', \''.$type.'\', '.$val->attrs['id'].');" type="button" class="button blue small "><i style="margin: 0px 0px 0px 0px; " class="fa fa-wrench "></i> </button>
					</td>
					<td>'.($val->attrs['displayed']?'<span style="color: #000;" class="fa fa-eye" title="отображается в списке"></span>':'<span style="color: #ccc" class="fa fa-eye" title="не отображается в списке"></span>').'</td>
					<td>'.($val->attrs['required']?'<span style="color: #FA3E3E;" class="fa fa-asterisk" title="обязательное"></span>':'<span style="color: #ccc" class="fa fa-asterisk" title="необязательное"></span>').'</td>
					<td style="font-size: 9px; font-weight: bold;">'.$val->attrs['id'].'</td>
					<td style="font-weight: bold; ">'.$val->attrs['name'].'</td>
					<td><nobr>[ '.$val->attrs['code'].' ]</nobr></td>
					<td>'.Essence::$fieldTypes[$val->attrs['type']].'</td>
					<td><input class="" type="text" style="width: 25px; font-size: 9px;" name="idx['.$val->attrs['id'].']" value="'.($i*10).'" id="idx-'.$val->attrs['id'].'"></td>
					<td><button type="button" class="button red small " onclick="EE.deleteField('.$val->attrs['id'].')"><span class="fa fa-trash-o"></span> удалить</button></td>
				</tr>';
			}
			
			$str.='
			</table>
			</form>';
			
			
			
				
				
			}
			else
			{
				$str.='<p>Поля не настроены.';
			}
		}
		else
		{
			$error="eRRoR! No SuCH eSSeNCe...";
		}
		
			
		
		$str.='<p>
	
		<table border="0">
			<tr>
				<td><button class="button "  onclick="EE.fieldEdit(\''.$code.'\', \''.$type.'\');">+ новое <b>поле</b></button></td>';
		if(count($essence->fields[$type]))
		{
			$str.='
				<td style="padding-left:290px;"><button class="button " onclick="document.forms.fields_list_form.submit()">сохранить <b>изменения</b></button></td>';
		}
		$str.='
			</tr>
		</table>
		
		';
		
		
		echo $str;
		
		$html=ob_get_clean();
		
		
		$json['html']=$html;
		$json['error']=$error;
		
		
		echo json_encode($json);
}










function fieldEdit()
{
	$error="";
		
		$str='';
		ob_start();
		$type=$_REQUEST['owner_type'];
		
		
	//$a=new Essence($_REQUEST['essence']);
	//	ini_set('display_errors', '1');
		if(isset($_REQUEST['id']))
		{
			$f = new Field($_REQUEST['id']);
			//vd($f);
		}
		
		
		if($essence=trim($_REQUEST['essence']))
		{
			$e=new Essence($essence);
		//	vd($e);
			#	если ессенция существует, ну тип
			if($e->attrs)
			{
				$str.='<a href="javascript:void(0)" onclick="switchTo(\'ee-fields-list-div\', \'ee\')">&larr; назад</a>';
				
				$str.='<div style="font-size: 16px;"><b>'.($f->attrs?'Редактирование':'Добавление').'</b> поля '.($f->attrs?'<b>'.$f->attrs['name'].'</b>':'').' для <b>'.($type=='blocks'?'блоков':'элементов').'</b> типа <b style="font-size: 20px;">'.$e->attrs['name'].'</b></div>';
				
				$str.='

				<div style="width: 300px; margin: 20px; 0" class="shadow radius">';
				$str.='
					<div></div>';
		
				$str.='
					<form name="field_edit_form" method="post" action="modules/essence_edit/essenceEdit.php?action=saveField" target="ee_iframe" enctype="multipart/form-data">
						
						<input type="hidden" name="essence" value="'.$e->attrs['code'].'">
						<input type="hidden" name="ownerType" value="'.$type.'">
						<input type="hidden" name="field_id" value="'.$f->attrs['id'].'">
						
					<table border="0" class="edit-table">
						<tr>
							<th>Название<span class="req">*</span>:</th>
							<td><input type="text" name="fieldName" id="fieldName" value="'.$f->attrs['name'].'"></td>
						</tr>
						<tr>
							<th>Код поля<span class="req">*</span>:</th>
							<td><input type="text" name="fieldCode" id="fieldCode" value="'.$f->attrs['code'].'" '.($f->attrs?' disabled="disabled"':'').'></td>
						</tr>
						
						<tr id="wqersadf">
							<th>Тип<span class="req">*</span>:</th>
							<td>
								<select id="fieldType" name="fieldType" onchange="EE.changeFieldType(this.value)" '.($f->attrs?' disabled="disabled" ':'').'>
									<option value="">-выберите тип-</option>';
					foreach(Essence::$fieldTypes as $key=>$val)
					{
						$str.='		<option value="'.$key.'" '.($key == $f->attrs['type']?' selected="selected" ':'').'>'.$val.'</option>';
					}
					$str.='
								</select>
								'.($f->attrs?'<script>EE.changeFieldType(\''.$f->attrs['type'].'\')</script>':'').'
							</td>
						</tr><tr>
				<th></th>
				<td  > 
					<div id="smalltext-div" style="display: none" class="edit-fields-dop-div">
						Длинна строки: <input type="text" name="size" id="size" value="'.($f->attrs['size']?$f->attrs['size']:Essence::$defaultFieldsPresets['smalltext']['size']).'" size="2">
					</div>';
		if($f->attrs['type'] == 'text')
		{
			$tmp=explode('x', $f->attrs['size']);
			$w=intval($tmp[0]);
			$h=intval($tmp[1]);
		}
		$str.='
					<div id="text-div" style="display: none" class="edit-fields-dop-div">
						Размер: <input type="text" name="width" id="width" value="'.($w?$w:Essence::$defaultFieldsPresets['text']['width']).'" size="2"> x <input type="text" name="height" id="height" value="'.($h?$h:Essence::$defaultFieldsPresets['text']['height']).'" size="2">
					</div>
					
					<div id="html-div" style="display: none" class="edit-fields-dop-div">
					
					</div>
					
					<!--<div id="date-div" style="display: none" class="edit-fields-dop-div">
						<label>+ время? <input type="checkbox" name="date_with_time" id="date_with_time" ></label>
					</div>-->
					
					<div id="select-div" style="display: none" class="edit-fields-dop-div">
						Введите опции через ENTER:<br>
						<textarea name="options" id="options" style="height: 106px; width: 197px;">'.($f->attrs['type']=='select'?$f->attrs['options']:'').'</textarea>
						<br>
						<label>Мульти-выбор <input type="checkbox" name="select_multiple" id="select_multiple" '.($f->attrs['multiple'] && $f->attrs['type']=='select'?' checked="checked" ':'').'></label>
					</div>
					
					<div id="checkbox-div" style="display: none" class="edit-fields-dop-div">
					
					</div>
					
					<div id="pic-div" style="display: none" class="edit-fields-dop-div">
						<label>Несколько картинок <input type="checkbox" name="pic_multiple" id="pic_multiple" '.($f->attrs['multiple'] && $f->attrs['type']=='pic'?' checked="checked" ':'').'></label>
					</div>
				</td>
			</tr>
			
			<tr>
				<th>Обязательное?</th>
				<td><input type="checkbox" name="required" id="required-cb" '.($f->attrs['required']?' checked="checked" ':'').'></td>
			</tr>
			
			<tr>
				<th>Отображать в списке?</th>
				<td><input type="checkbox" name="displayed" id="displayed-cb" '.($f->attrs['displayed']?' checked="checked" ':'').'></td>
			</tr>
			
			<tr>
				<th>Отмечена?</th>
				<td><input type="checkbox" name="marked" id="marked-cb" '.($f->attrs['marked']?' checked="checked" ':'').'></td>
			</tr>
			
			<tr>
				<td colspan="2" style="padding: 5px 0 0 60px;">
					<button class="button " type="button"  onclick="document.forms.field_edit_form.submit()"><b>сохранить</b></button>
				</td>
				
			</tr>
		</table>

		
		
					
				</form>
			</div>
			
			
			';
			}
			else
				$error="eRRoR! ESSENCE_TYPE_NOT_EXIST (".$essence.")";
		}
		else
			$error="eRRoR! ESSENCE_PARAM_MISSING";
		
		
		
	
		
		echo $str;
		
		$html=ob_get_clean();
		
		
		$json['html']=$html;
		$json['error']=$error;
		
		
		echo json_encode($json);
}







function saveField()
{
	global $_CONFIG;
	//	vd($_REQUEST);
	//die;
	//vd($_REQUEST);	
	
	$name=trim($_REQUEST['fieldName']);
	$code=trim($_REQUEST['fieldCode']);
	$type=trim($_REQUEST['fieldType']);
	

	if($_REQUEST['field_id'])
		$f=new Field($_REQUEST['field_id']);

	$essence=new Essence($_REQUEST['essence']);
	if(!$essence->attrs)
		$e[]='eRRoR: NO_ESSENCE_DEFINED';
	
		
	$ownerType=$f->attrs?$f->attrs['owner_type']: trim($_REQUEST['ownerType']);
	if($ownerType)
	{
		if($ownerType!='blocks')
			$ownerType='elements';
	}
	else
		$e[]='eRRoR: NO_OWNER_TYPE_DEFINED';
	
	
	if(!$name)
	{
		$troubles[]='fieldName';
		$e[]='Введите название!';
	}
	
	#	проверка кода, если эдит
	if(!$f->attrs)
	{
		if(!$code)
		{
			$troubles[]='fieldCode';
			$e[]='Введите код!';
		}
		else
		{
			$tmp=preg_match('/^[a-zA-Z][a-zA-Z0-9_]+$/', $code);
			if(!$tmp)	
			{
				$troubles[]='fieldCode';
				$e[]='Некорректный код!';
			}
		}
	}
	
//	vd($type);
//	vd(Essence::$fieldTypes[$type]);
	if(!Essence::$fieldTypes[$type] && !$f->attrs)
	{
		$troubles[]='fieldType';
		$e[]='Не выбран тип поля!';
	}
	
	//vd($troubles);
	
	if(count($troubles))
	{
		die('
			<script>
				window.top.error(\''.join('<br>', $e	).'\', \'fields-info-div\');
				window.top.highlight(["'.join('", "', $troubles).'"]);
				window.top.loading(0, \'ee-loading-div\', \'fast\');
			</script>');
	}
	else	#	если всё ок
	{
		
		echo "VSE OK BUDEM SAVE";
		$params=$_REQUEST;
		
		$code=trim(mysql_real_escape_string($params['fieldCode']));
		$name=trim(mysql_real_escape_string($params['fieldName']));
		
		#	проверка на уже существование таких полей 
		$sql="
		SELECT * FROM slonne_fields 
		WHERE pid=".$essence->attrs['id']." 
		AND owner_type='".($f->attrs['owner_type']?$f->attrs['owner_type']:mysql_real_escape_string($ownerType))."' 
		AND (name='".$name."' ".(!$f->attrs?" OR code='".$code."' ":" AND id!=".$f->attrs['id']).") 
		";
		vd($sql);
		$qr=mysql_query($sql);
		echo mysql_error();
		if($next=mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			vd($next);
			if($name==$next['name'])
			{
				die('
				<script>
					window.top.error(\'eRRoR: NAME_EXISTS\', \'fields-info-div\');
					window.top.highlight(\'fieldName\');
					window.top.loading(0, \'ee-loading-div\', \'fast\');
				</script>');
			}
			if($code==$next['code'])
			{
				die('
				<script>
					window.top.error(\'eRRoR: CODE_EXISTS\', \'fields-info-div\');
					window.top.highlight(\'fieldCode\');
					window.top.loading(0, \'ee-loading-div\', \'fast\');
				</script>');
			}
		}
		else echo "NO_MATCH";
		
		
		
	//	vd($essence);
		//vd($params);
		if(!$f->attrs)
			$sql="INSERT INTO slonne_fields ";
		else 
			$sql="UPDATE slonne_fields ";
			
		$sql.=" 
		SET
		pid=".$essence->attrs['id']."
		, name='".$name."'";
		
		if(!$f->attrs)
		{
			$sql.="
		, code='".$code."'
		, type='".trim(mysql_real_escape_string($params['fieldType']))."'
		, owner_type='".trim(mysql_real_escape_string($params['ownerType']))."'";
		}
		
		$sql.="
		, required=".($params['required']?1:0)."
		, displayed=".($params['displayed']?1:0)."
		, marked=".($params['marked']?1:0)."
		";
		
		//$type=$f->attrs['type']?$f->attrs['type']:$type;
		if($f->attrs)
			$type=$f->attrs['type'];
		
		if($type=="smalltext")
		{
			$size=intval($params['size']);
			$sql.=", size='".($size?$size:Essence::$defaultFieldsPresets['smalltext']['size'])."'";
		}
		if($type=="text")
		{
			$w=intval($params['width']);
			$h=intval($params['height']);
			$sql.=", size='".($w?$w:Essence::$defaultFieldsPresets['text']['width'])."x".($h?$h:Essence::$defaultFieldsPresets['text']['height'])."'";
		}
		/*if($type=="date")
		{
			$sql.=", with_time='".($params['date_with_time']?1:0)."'";
		}*/

		if($type=="select")
		{
			$sql.="
			, options='".trim(mysql_real_escape_string($params['options']))."'
			, multiple='".($params['select_multiple']?1:0)."'";
		}
		if($type=="pic")
		{
			$sql.="
			, multiple='".($params['pic_multiple']?1:0)."'";
		}
		
		if($f->attrs)
		{
			$sql.=" WHERE id=".$f->attrs['id'];
		}
		else 
			$sql.=", idx=9999";
		
		
		
		//vd($sql);
		mysql_query($sql);
		if($e=mysql_error())
		{
			die('
			<script>
				window.top.error(\'ОШИБКА! '.mysql_real_escape_string($e).'\', \'fields-info-div\');
				window.top.loading(0, \'ee-loading-div\', \'fast\'); 
			</script>');
		}
		
		
		
		
		//vd($params);
		
		
		if(!$f->attrs)
		{
			foreach($_CONFIG['langs'] as $key=>$val)
			{
				$sql="ALTER TABLE `".$essence->attrs['code']. ($params['ownerType'] == 'blocks'?'__blocks':'__elements') . $val['postfix'] ."` ADD `".$code."` ";
				
				if($type=='smalltext'  || $type=='select' || $type=='pic' )
					$sql.=" VARCHAR( 255 ) NOT NULL ";
				
				if($type=='text' || $type=='html' )
					$sql.=" TEXT NOT NULL ";
				
				if($type == 'html_long')
					$sql.=" LONGTEXT NOT NULL ";
				
				if($type == 'num')
					$sql.="INT( 11 ) NOT NULL ";
				
				if($type == 'date')
					$sql.="DATETIME NOT NULL ";
				
				if($type == 'checkbox')
					$sql.="INT( 1 ) NOT NULL ";
	
			//	vd($sql);
				vd($sql);
				mysql_query($sql);
				if($e=mysql_error())
				{
					die('
					<script>
						window.top.error(\'ОШИБКА! '.mysql_real_escape_string($e).'\', \'fields-info-div\');
						window.top.loading(0, \'ee-loading-div\', \'fast\');
					</script>');
				}
			}
		}
		
		
		echo '
		<script>
			window.top.notice(\'Поле ['.$name.'] сохранено!\', \'fields-info-div\');
			window.top.EE.list();
			window.top.EE.fieldsList(\''.$essence->attrs['code'].'\', \''.$params['ownerType'].'\')
			
			</script>
		';
		

		
	}
	
	
	
	echo $str;	
}









function deleteField()
{
	$error="";
		
	$str='';
	ob_start();
	
	//vd($_REQUEST);
	if($id=intval($_REQUEST['id']))
	{
		$f=new Field($_REQUEST['id']);
		if($f->attrs)
		{
			//vd($f);
			if( ($result = $f->delete() ) === true)
			{
				$str.='Поле <b>'.$f->attrs['name'].'</b> удалено!';
			}
			else
			{
				$error=$result;
			}
		}
		else 	
			$error="eRRoR: FIELD_NOT_EXIST";
		
	}
	else 	
		$error='eRRoR: FIELD_UNDEFINED';
	
	

	echo $str;
	
	$html=ob_get_clean();
	
	
	$json['html']=$html;
	$json['error']=$error;

	
	
	echo json_encode($json);
}











function submitFieldsListForm()
{
//	vd($_REQUEST);
		
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
				window.top.loading(0, \'ee-loading-div\', \'fast\');
			</script>');
	}
	else
	{
		foreach($_REQUEST['idx'] as $key=>$val)
		{
			$sql="UPDATE slonne_fields SET idx='".intval($val)."' WHERE id='".intval($key)."'";
			mysql_query($sql);
			echo mysql_error();
		}
		echo '
		<script>
			window.top.notice(\'Изменения сохранены\', \'fields-info-div\');
		//	window.top.EE.list();
			window.top.EE.fieldsList()
			
			</script>
		';
	}	

		
	
	
	
	
	echo $str;	
}









function fixLangVersion()
{
	global $_CONFIG;
	$error="";
		
	$str='';
	ob_start();
	
	$lang = $_SESSION['admin_lang'];
	
	$type=$_REQUEST['type'] == 'blocks' ? 'blocks' : 'elements';
	
	//vd($_CONFIG['langs']);
	if($_CONFIG['langs'][$l = $_REQUEST['l']])
	{
		//vd($_REQUEST);
		$et=new Essence($_REQUEST['ess']);
	//	vd($et);
		if($et->attrs )
		{
			
			//vd($et);
			$originalTblElements=Essence::getTblName($et->attrs['code'], 'elements', $lang='' );
			$originalTblBlocks=Essence::getTblName($et->attrs['code'], 'blocks', $lang='' );
			vd($originalTblElements);
			//$newTblElements=$originalTblElements.$_CONFIG['langs'][$l]['postfix'];
			//$newTblBlocks=$originalTblBlocks.$_CONFIG['langs'][$l]['postfix'];
			
			$newTblElements=Essence::getTblName($et->attrs['code'], 'elements', $l);
			$newTblBlocks=Essence::getTblName($et->attrs['code'], 'blocks', $l);
			
			if($type == 'blocks')
			{
				$sourceTbl = $originalTblBlocks;
				$newTbl = $newTblBlocks;
			}
			else
			{
				$sourceTbl = $originalTblElements;
				$newTbl = $newTblElements;
			}
			
			$sql="SELECT * FROM `".$newTbl."`";
		//	vd($sql);
			$qr=mysql_query($sql);
			if($e=mysql_error())
			{
				vd($e);
				$sql="CREATE TABLE `".$newTbl."` LIKE `".$sourceTbl."`;";
				$qr=mysql_query($sql);
				echo mysql_error();
				
				$sql="INSERT `".$newTbl."` SELECT * FROM `".$sourceTbl."`"; 
				$qr=mysql_query($sql);
				echo mysql_error();
			}
			else
			{
				//vd($qr);
			}
			
		}
		else
			$error="UNKNOWN_ESSENCE_ERROR [".$_REQUEST['ess']."]";
		
	}
	else
		$error="SUSPICIOUS_LANG_ERROR [".$l."]";
	
	
	
	

	echo $str;
	
	$html=ob_get_clean();
	
	
	$json['html']=$html;
	$json['error']=$error;

	
	
	echo json_encode($json);
}


















?>