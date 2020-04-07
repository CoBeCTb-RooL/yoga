<?php
require_once('../../../config.php');
require_once(ROOT.'/admin/header.php');





$action=$_REQUEST['action'];

//usleep(300000);

switch($action)
{
	default:
			echo '{"error":"AJAX(\"'.$_SERVER['PHP_SELF'].'\")::no action!'.($action?' ('.$action.')':'').'"}';
		break;
		
		
	case 'init':
			init();
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
	
	case "saveChangesOfList": 	#	вызывается в ифрейме! 
			saveChangesOfList();
		break;
		
	case "saveChangesOfTree": 	#	вызывается в ифрейме! 
			saveChangesOfTree();
		break;
		
		
	case "tree": 	 
			tree();
		break;
		
		
	case "delete": 	 
			delete();
		break;

		
	case "initLangsChoice": 	 
			initLangsChoice();
		break;

	
	case "switchLang": 	 
			switchLang();
		break;	

		
		
	case "deletePic":
			deletePic();
		break;
	
}















function init()
{
	global $admin, $_CONFIG;
//	echo "!";
	$error="";
			
	//$str='!!!';
	ob_start();
	

	
	
	$et=new Essence($_REQUEST['essence']);
	
	
	if($et->attrs)
	{
		//vd($et);
		if(count($_CONFIG['langs']) > 1)
			$str.='<div id="entities-langs-div">секунду...</div>';
			
		$str.='
		
		<table border="0"  width="100%" class="t" id="entities-container-table" >
			<tr>
				
		';
		if(!$et->attrs['linear'])
		{
			$str.='
			<td style="width: 100px; border: none;" valign="top">
				<div id="entities-tree-div" style=""></div>
			</td>';
		}
		$str.='
			<td valign="top" style="border: none;">
				<div style=" margin: 0 15px; ">
					<div id="entities-list-div"></div>
					<div id="entities-edit-div"></div>
					<div id="entities-loading-div" style="display: none">загрузка...</div>
					<div id="entities-info-div"></div>
				</div>
			</td>
		</tr>
		<tr>';
		
		$str.='	
			<!--<div style="clear: both; "></div>-->
			<td colspan="2">
				<div id="entities-tool-div" ></div>
				<p><iframe name="slonne_edit_frame" style="display: none; width: 700px; height: 400px; background: #ececec; border: 1px dashed #000; "></iframe>
			</td>
			</tr>
		</table>
		';
		
		if($et->attrs['linear'])
		{
			$str.='
			<script>Entities.list();</script>';
		}
		else 	
		{
			$str.='<script>Entities.drawTree(0);</script>';
		}
	}
	else	
		$error='ESSENCE_NOT_EXIST_ERROR';
	
	
	echo $str;
	
	$html=ob_get_clean();
	
	
	$json['html']=$html;
	$json['error']=$error;
	
	
	
	echo json_encode($json);
}














function initLangsChoice()
{
	global $admin, $_CONFIG;
//	echo "!";
	$error="";
			
	//$str='!!!';
	ob_start();
	
	//vd($_REQUEST);
	$l = $_SESSION['admin_lang'] ? $_SESSION['admin_lang'] : $_CONFIG['default_admin_lang'];
	
	//vd($l);
	//vd($_SESSION['admin_lang']);
	$str='
	Версия: <select  id="langs_choice" onchange="Entities.switchLang($(this).val())">';
	foreach($_CONFIG['langs'] as $key=>$val)
	{
		$str.='
		<option value="'.$key.'" '.($key == $_SESSION['admin_lang'] ? ' selected="selected" ':'').'>'.$val['title'].'</option>';
	}
	$str.='
	</select>';
	
	echo $str;
	
	$html=ob_get_clean();
	
	
	$json['html']=$html;
	$json['error']=$error;
	
	
	
	echo json_encode($json);
}









function switchLang()
{
	global $admin, $_CONFIG;
//	echo "!";
	$error="";
			
	//$str='!!!';
	ob_start();
	
	//vd($_REQUEST);
	$l=$_REQUEST['lang'];
	if(!$_CONFIG['langs'][$l])
		$l=$_CONFIG['default_admin_lang'];
	
	$_SESSION['admin_lang'] = $l;	
	
	echo $str;
	
	$html=ob_get_clean();
	
	
	$json['html']=$html;
	$json['error']=$error;
	
	
	
	echo json_encode($json);
}











function tree()
{
	global $admin, $_CONFIG;
	
	//sleep(1);
	$error="";
			
	$str='';
	ob_start();
	//vd($_REQUEST);
	//$str.='!!!!!!!!!';
	
	$lang=$_SESSION['admin_lang'];
	
	$elPP=15;
	$p=intval($_REQUEST['tree_page'])?(intval($_REQUEST['tree_page'])-1):0;
	
	$essence=$_REQUEST['essence'];
	$pid=intval($_REQUEST['pid']);
	
	//vd($_REQUEST);
	$word=trim($_REQUEST['search_word']);
	

	$et=new Essence($essence);
	
	if($et->attrs['linear'])
	{
		
		list1();
		die;
	}
	
	
	if($et->attrs)
	{
		
		$elementsTbl=Essence::getTblName($et->attrs['code'], 'elements', $lang);
		$blocksTbl=Essence::getTblName($et->attrs['code'], 'blocks', $lang);
		
		
		if($pid == 0)
		{
			$str.='
			<form name="tree_form" method="post" method="post" action="modules/entities/entities.php?action=saveChangesOfTree" target="slonne_edit_frame">
				<input type="hidden" name="essence" value="'.$et->attrs['code'].'">';
		}
		
		//vd($et);
		$tbl=mysql_real_escape_string($et->attrs['joint_fields'] ? $elementsTbl : $blocksTbl);
		
		$sql="SELECT * FROM `".mysql_real_escape_string( $tbl )."` WHERE pid=".$pid." ".($word?" AND name LIKE '%".mysql_real_escape_string($word)."%'":'')." ORDER BY idx	";
		//if($pid)
		$limit=" LIMIT ".$p*$elPP.", ".$elPP."";
		//vd($sql);
		$qr=mysql_query($sql);
		echo mysql_error();
		if($totalElements = mysql_num_rows($qr))
		{
			if($pid)
			{
				$qr=mysql_query($sql.$limit);
				echo mysql_error();
			}
			
			while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
			{
				#	узнаем есть ли дети
				if(!$et->attrs['joint_fields'])
					$childBlocksCount = Entity::getChildBlocksCount($et->attrs['code'], $next['id']);
				
				$childElementsCount = Entity::getChildElementsCount($et->attrs['code'], $next['id']);
		
				$str.='
				
				
					<div  class="block-container" id="block-container-'.$next['id'].'" style=" width: 100%; margin: 0px; /*background:rgba(0,0,0,0.8)*/  "  >
						<nobr >
							<span style="width: 10px;  display: inline-block; ">
								
								<img src="images/loading1.gif" width="12" id="tree-loading-'.$next['id'].'" style="display: none;" />
								
								<a  href="javascript: void(0)" style="'.( ( ($et->attrs['joint_fields']&&$childElementsCount)||(!$et->attrs['joint_fields']&&$childBlocksCount) ) ? 'display: visible; ' : 'display: none; ').'"  id="tree-expand-btn-'.$next['id'].'" onclick="Entities.drawTree('.$next['id'].')"><i class="fa fa-plus-square-o"></i></a>
								
							</span>							
							<span id="block-name-wrapper-'.$next['id'].'" class="block-name '.(!$next['active']?'block-inactive':'').'" oncontextmenu="javascript:Entities.showSubMenu(\''.$next['id'].'\'); return false;">
								<a   href="javascript:void(0)" onclick=" if (timer) clearTimeout(timer); timer = setTimeout(function() {Entities.settings[\'\']=\'\',  Entities.list('.$next['id'].'); }, 200);"  ondblclick=" clearTimeout(timer); Entities.highlightBlock('.$next['id'].');  Entities.edit(\''.$next['id'].'\', '.$next['pid'].', \'blocks\')"><span style="font-weight: normal; font-size: 9px; ">
									'.$next['id'].') </span> '.$next['name'].'
								</a> 
								<input type="text" style="font-size: 9px; height: 15px;" size="2" name="idx['.$next['id'].']" value="'.$next['idx'].'">
								';
				
				$str.='
							</span>';
				
				
				if(!$et->attrs['joint_fields'])
				{
					$str.='
					&nbsp;<a class="elements-count" style="'.($childElementsCount || 1? '' : 'display: none').'"   href="javascript:void(0)" onclick="Entities.list('.$next['id'].');">(<span class="fa fa-th"></span>: <span id="elements-count-'.$next['id'].'">'.$childElementsCount.')</span></a>
					';
				}
				
		/*	if($childElementsCount && !$et->attrs['joint_fields'] || 1)
				{
					$str.='
								&nbsp;<a class="elements-count"   href="javascript:void(0)" onclick="Entities.list('.$next['id'].');">(<span class="fa fa-th"></span>: <span id="elements-count-'.$next['id'].'">'.$childElementsCount.')</span></a>
								';
				}	*/
						
				$str.='			
							
						</nobr>
						
						<div class="tree-submenu" id="block-submenu-'.$next['id'].'" style="display:none;">
						
							
							
							<button style="margin-left: 10px;" onclick="Entities.edit(\''.$next['id'].'\', '.$next['pid'].', \'blocks\')"  class="button orange small "><span class="fa fa-edit"></span> ред.</button>
							';
							
				if(!$et->attrs['joint_fields'])
				{
					$str.='
							<!--<a  href="javascript:void(0)" onclick="Entities.edit(\'\', '.$next['id'].', \'blocks\')" ><i class="fa fa-plus"></i> блок</a>-->
							<button type="button"  onclick="Entities.edit(\'\', '.$next['id'].', \'blocks\')"  class="button green small "><span class="fa fa-plus"></span> блок</button>
							';
				}
				$str.='
				
							
				
							<!--<a  href="javascript:void(0)" onclick="Entities.edit(\'\', '.$next['id'].', \'elements\')" ><i class="fa fa-plus"></i> эл</a>-->
							<button type="button"    onclick="Entities.edit(\'\', '.$next['id'].', \'elements\')"  class="button green small "><span class="fa fa-plus"></span> эл</button>
							
							
							
							
							<button  type="button"  style="margin-left: 30px;" onclick="Entities.delete('.$next['id'].', \''.($et->attrs['joint_fields']?'elements':'blocks').'\')"  class="button red small " ><span class="fa fa-trash-o"></span> удалить</button>
							
							
						</div>
						
						
						<div class="block-subs-wrapper" id="block-subs-wrapper-'.$next['id'].'" style="display: none; "></div>
						
					</div>
					
				';
			}
			$str.='
			<div style="height: 5px;"></div>';
			if($pid)
			{	
				#	постраничная
				$str.='<div class="tree">'.drawPagesSmall($totalElements, $p, $elPP, $onclick=" Entities.drawTree(".$pid.", true, ###)").'</div>';
				
				
			}
		}
		else
		{
			$str.='блоков нет.';
		}
		
		#	поиск
		if($pid)
		{
			/*$str.='
			<span class="tree-search">
				<span>Искать:</span> <input type="text" id="tree-search-input-'.$pid.'" value="'.$word.'"> 
				<button type="button" id="tree-search-go-btn-'.$pid.'" onclick="Entities.treeSearch('.$pid.')" ><i class="fa fa-search"></i></button>
				<div class="clear"></div>
			</span>';*/
		}
		
		
		if($pid == 0)
		{
			$str.='
			</form>
			<br>
			
			
				<button  style="margin: 1px 0 0 0 ;"class="button blue" type="button" onclick="document.forms.tree_form.submit()" ><i class="fa fa-floppy-o"></i> сохранить <b>изменения</b></button>
			
			<p>
				<button  type="button" class="button" onclick="Entities.edit(\'\', 0, \'blocks\')">+ <b>'.($et->attrs['joint_fields']?'элемент':'блок').'</b> в корень</button>
			
			
				';
		}
	}
	else
	{
		$error="ОШИБКА! [".$essence."] <br>(ESSENCE_NOT_EXIST_ERROR) ";
		$str.='Сущность <b>'.$essence.'</b> не существует.';
	}
	
	
	
	echo $str;
	
	$html=ob_get_clean();
	
	
	$json['html']=$html;
	$json['error']=$error;
	
	
	
	echo json_encode($json);
}

















function list1()
{
	global $admin;
	
	$error="";
			
	$str='';
	ob_start();

	//vd($_REQUEST);
	$lang = $_SESSION['admin_lang'];
	
	$pid=intval($_REQUEST['pid']);
	
	
	
	$order=mysql_real_escape_string($_REQUEST['order']);
	if(!$order)
		$order="idx";
	
	$desc=intval($_REQUEST['desc']);
	
	$elPP=15;
	$p=intval($_REQUEST['p'])?(intval($_REQUEST['p'])-1):0;

	
	$essence=$_REQUEST['essence'];

	$et=new Essence($essence);
	//vd($et);
	
	
	
	
	
	if($et->attrs)
	{
		if($et->attrs['joint_fields'])
			$parent=new Entity($essence, $pid, 'elements', $lang);
		else 	
			$parent=new Entity($essence, $pid, 'blocks', $lang);
		
		//vd($parent);	
		
			
		$str.=drawBlockHeading($parent, $et);	
			
		
		#	если не указан пид - значит товары в корне, и типа пометка не нужна. Потому что линейный значит
		if($pid)
		{
			/*$str.='
			<h1 style="display: inline; font-weight: normal; ">Элементы в <b>"'.$parent->attrs['name'].'"</b></h1>
			<p>';*/
			
			$str.='<h2 style="display: inline; font-weight: normal; "><span class="fa fa-th"></span> Элементы:</h2><br>';
		}
		
		
		
		$elementsTbl=Essence::getTblName($et->attrs['code'], 'elements', $lang);
			
		
		
		if($fields=$et->fields['elements'])
		{
			$sql="SELECT * FROM `".mysql_real_escape_string($elementsTbl)."` WHERE pid='".$pid."' ORDER BY ".($order)." ".($desc?' DESC ':'')." ";
			//vd($sql);
			$limit="LIMIT ".$p*$elPP.", ".$elPP."";
			
		//	vd($sql.$limit);
			
			#	считаем сколько всего
			$qr=mysql_query($sql);
			$totalElements=mysql_num_rows($qr);
			
			#	теперь достаём по делу
			$qr=mysql_query($sql.$limit);
			if(!$e=mysql_error())
			{
				if(mysql_num_rows($qr))
				{
					#	какие поля отображать?
					foreach($fields as $key=>$val)
					{
						//vd($val);
						if($val->attrs['displayed'])
							$displayedFields[$val->attrs['code']]=$val;
					}
				//	vd($displayedFields);
					$countFrom=$p*$elPP+1;
					$countTill=($p*$elPP+$elPP) < $totalElements ? ($p*$elPP+$elPP) : $totalElements;
					
					$str.='
					<div style="text-align: right; ">Показаны: <b>'.$countFrom.'-'.$countTill.'</b> из <b>'.$totalElements.'</b></div>';
					
					$str.='
					<form name="elements_list_form" method="post" method="post" action="modules/entities/entities.php?action=saveChangesOfList" target="slonne_edit_frame">
						<input type="hidden" name="essence" value="'.$essence.'">
						<input type="hidden" name="pid" value="'.$pid.'">
						
					<table class="t shadow entities-list-tbl" width="100%">
						<tr>
							<th width="1">#</th>
							<th width="1"><a href="javascript:void(0)"  onclick="Entities.settings[\'desc\']='.(($order=="active" && $desc)||$order!="active"?'0':'1').'; Entities.settings[\'order\']=\'active\'; Entities.settings[\'p\']=1; Entities.list(); ">Акт.</a>&nbsp;<span class="sort-direction" >'.($order=="active"?($desc?'&#9660;':'&#9650;'):'').'</span></th>
							<th width="1"></th>
							<th width="1"><nobr><a href="javascript:void(0)"  onclick="Entities.settings[\'desc\']='.(($order=="id" && $desc)||$order!="id"?'0':'1').'; Entities.settings[\'order\']=\'id\'; Entities.settings[\'p\']=1; Entities.list('.$pid.'); ">id</a>&nbsp;<span class="sort-direction" >'.($order=="id"?($desc?'&#9660;':'&#9650;'):'').'</span></nobr></th>';
					
					foreach($displayedFields as $key=>$val)
					{
					//	vd($val);
						$style='';
						if($val->attrs['type'] == 'pic')
							if($val->attrs['multiple'])
								$style='width: 130px;';
							else 
								$style='width: 1px;';
								
						$str.='
							<th style="'.$style.'">
								<nobr>';
						if($val->attrs['type'] == 'pic' && $val->attrs['multiple'])
							$str.=''.$val->attrs['name'].'';
						else 	
							$str.='
							<nobr><a href="javascript:void(0)" onclick="Entities.settings[\'desc\']='.(($order==$val->attrs['code'] && $desc) || $order != $val->attrs['code']?'0':'1').'; Entities.settings[\'order\']=\''.$val->attrs['code'].'\'; Entities.settings[\'p\']=1; Entities.list('.$pid.'); "> '.$val->attrs['name'].'</a>&nbsp;<span class="sort-direction" >'.($order==$val->attrs['code']?($desc?'&#9660;':'&#9650;'):'').'</span></nobr>';
						$str.='	
								</nobr>	
							</th>';
					}	
					$str.='
							<th width="1"><a href="javascript:void(0)" onclick="Entities.settings[\'desc\']='.(($order=='idx' && $desc) || $order != 'idx'?'0':'1').'; Entities.settings[\'order\']=\'idx\'; Entities.settings[\'p\']=1; Entities.list('.$pid.'); "> Сорт.</a>&nbsp;<span class="sort-direction" >'.($order=='idx'?($desc?'&#9660;':'&#9650;'):'').'</span></th>
							<th class="del" width="1">Удалить</th>
							
						</tr>';
					//vd($displayedFields);
					
					$j=$countFrom-1;
					while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
					{
						$onclick='Entities.edit(\''.$next['id'].'\', \''.$next['pid'].'\', \'elements\')';
							
						$str.='
						<input type="hidden" name="displayed_ids['.$next['id'].']" value="">
						
						<tr class="'.($next['active'] ? 'active-tr' : 'inactive-tr').'" ondblclick="'.$onclick.'">
							<td align="center" style="font-size: 10px; font-weight: bold; ">'.(++$j).'</td>
							<td><input type="checkbox" name="active['.$next['id'].']" '.($next['active']?' checked="checked" ':'').'></td>
							<td align="center">
								<button class="button orange small" type="button" onclick="'.$onclick.'"><i  class="fa fa-edit"></i>ред.</button>
							</td>
							<td>'.$next['id'].'</td>';
						foreach($displayedFields as $key=>$val)
						{
							$i=0;
							$value=$next[$key];
							if($val->attrs['type'] == 'checkbox')
							{
								if($value == 0)
									$value="НЕТ";
								else	
									$value="ДА";
							}
							
							
							if($val->attrs['type']=='pic')
							{
									if(!$val->attrs['multiple'])
									{
									//	vd($value);
										if($value)
										{
											$value='
											<a href="/upload/images/'.$value.'" onclick="return hs.expand(this)" class="highslide ">
												<img src="/resize.php?file=/upload/images/'.$value.'&width=70" style="border: 1px solid #ccc; ">
											</a>';
										}
										else 
											$value='НЕТ';
									}
									else 
									{	
										$pics=array();
										$pics=$value;
										$pics=Entity::getMedia($et->attrs['code'], 'elements', $next['id']);
										$pics=$pics[$val->attrs['code']];
										//vd($pics);
											
										if(count($pics))
										{
											$value='Картинок: <b>'.count($pics).'</b><br>';
											foreach($pics as $key2=>$val2)
											{
												//vd($val);
												
													
												$value.='
												<a href="/upload/images/'.$val2['src'].'" onclick="return hs.expand(this)" class="highslide ">
													<img src="/resize.php?file=/upload/images/'.$val2['src'].'&height=35" style="">
												</a>';
												
												if(++$i>=3)
													break;
											}
											if(count($pics)>4)
												$value.='...';
										}
										else 	
											$value='НЕТ';
									}
								
							}
							
						//	vd($val);
							$str.='
							<td '.($val->attrs['marked'] ? 'style="font-weight: bold; "' : '').'>'.$value.'</td>';
						}
						$str.='
							<td><input type="text" size="5" style="font-size: 10px;" name="order['.$next['id'].']" value="'.($next['idx']).'"></td>
							<td class="del" align="center"><input  type="checkbox" name="del['.$next['id'].']" id="del-'.$next['id'].'"></td>
							
						</tr>';
						//vd($next);
						//vd($fields);
						
					}
					$str.='
					</table>
					</form>
					<p>
					';
					$str.=drawPages($totalElements, $p, $elPP, $onclick="Entities.settings['p']='###'; Entities.list();");
					
					
				}
				else
				{
					$str.='<div style="margin: 10px 0 10px 0;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ничего нет.</div>';
				}
				
				
				
				
				
				$str.='
				<div style="float: left; ">
					<button onclick="Entities.edit(\'\', '.$pid.', \'elements\')" class="button" type="button">+ добавить <b>элемент</b></button>
				</div>
				';
				if($totalElements)
				{
					$str.='
					<div style="float: right; ">
						<button class="button blue" type="button" onclick="if(confirm(\'Уверены?\')){document.forms.elements_list_form.submit()}" ><i class="fa fa-floppy-o"></i> сохранить <b>изменения</b></button>
					</div>
					';
				}
				$str.='
				<div style="clear: both;"></div>';
				/*foreach($fields as $key=>$val)
				{
					if($val->attrs['required'])
						$requiredFields[]=$val->attrs;
				}*/
	
			}
			else
			{
				$error="ОШИБКА! $e";
			}
		}
		else 
		{
			$error="ОШИБКА! [".$essence."] <br>(FIELDS_UNDEFINED_ERROR) ";
			$str.='Не настроены поля.';
		}
	}
	else
	{
		$error="ОШИБКА! [".$essence."] <br>(ESSENCE_NOT_EXIST_ERROR) ";
		$str.='Сущность <b>'.$essence.'</b> не существует.';
	}
	
	
	
	echo $str;
	
	$html=ob_get_clean();
	
	
	$json['html']=$html;
	$json['error']=$error;
	$json['required']=$requiredFields;
	$json['heading']='<h1>'.$et->attrs['name'].'</h1>';
	
	
	echo json_encode($json);
}















function edit()
{	
	global $admin, $_CONFIG;
	$error="";
			
			$str='';
			ob_start();
			//vd($_SESSION);
	$lang = $_SESSION['admin_lang'];	
		//	vd($admin);
			$essence=$_GET['essence'];
		
			
			
			
			
			if($_REQUEST['pid']!='')
			{
				$pid=intval($_REQUEST['pid']);
				$pidIsSet=true;
			}
			else 
				$pidIsSet=false;
			
			$et=new Essence($essence);
			//vd($et);
			if($et->attrs)
			{
				
				
				//vd($et);
				
				$type=$_REQUEST['type'];
				if($et->attrs['joint_fields'])
					$type='elements';
				
				if($type == 'blocks' ||  $type == 'elements')
				{
					//vd($type);
					$admin->checkPrivilege($essence, 'edit_'.$type, $ajax=true);
					
					if(isset($_REQUEST['id']))
					{
						if($id=intval($_REQUEST['id']))
						{
							$obj=new Entity($essence, $id, $type, $lang);
							//$str.=drawBlockHeading($obj, $et);	
						}	
						else
						{
							#	ОШИБКА! ЗАПИСЬ С СТАКИМ ИД НЕ НАЙДЕНА!!!
						}
					}
					
					$fields=Slonne::getFields($essence, /*передаём значение если редактируем*/$values=$obj->attrs);
					$fields=$et->fields[$type];
					//vd($fields);
					if($fields)
					{
						$str.='
						<button style="margin: -10px 10px 10px 0; " onclick="$(\'#entities-list-div\').slideDown(); $(\'#entities-edit-div\').slideUp();"  class="button blue small "><span class="fa fa-back"></span>&larr;  назад</button>';
						
						$str.='
						<h1 style="display: inline; font-weight: normal; ">';
						
						$targetOfEditing = $type=='blocks'?'блока':'элемента';
						
						
						
						$str.=($obj->attrs?'Редактирование' : 'Добавление' ).' '.$targetOfEditing.' ';
						
						//vd($pid);
						
						
						if($obj->attrs)
						{
							$str.='<b>"'.$obj->attrs['name'].'"</b>';
						}
						else
						{
							if($pid)
							{
								if( !($et->attrs['joint_fields'] || $et->attrs['linear']) )
									$parent=new Entity($essence, $pid, 'block', $lang);
								else 	
									$parent=new Entity($essence, $pid, 'element', $lang);
								//vd($parent);
								$str.='в <b>"'.$parent->attrs['name'].'"</b>';
								//vd($parent);
							}
							else 
								$str.=' в <b>корень</b>';
						}
						
						$str.='
						</h1>';
						
						$str.='
						<form name="slonne_edit_form" method="post" action="modules/entities/entities.php?action=save" target="slonne_edit_frame" enctype="multipart/form-data">
							<input type="hidden" name="essence" value="'.$et->attrs['code'].'">
							'.($obj->attrs['id']?'<input type="hidden" name="id" value="'.$obj->attrs['id'].'">':'').'
							<!--'.($pidIsSet ? '<input type="hidden" name="pid" value="'.$pid.'">' : '').'-->
							<input type="hidden" name="type" value="'.$type.'">
						<table border="0" class="edit-table" width="800">';
						$str.='
							<tr>
								<th style="font-size: 16px; font-weight: normal; padding: 10px 0;">Активен?</th>
								<td >
									<input type="checkbox" name="active" '.($obj->attrs['active'] || !$obj->attrs ?' checked="checked" ':'').'>
								</td>
							</tr>';
							
						foreach($fields as $key=>$val)
						{
							#	формируем массив обязательных полей
							if($val->attrs['required'])
								$requiredFields[]=$val->attrs;
								
							$str.='
							<tr >
								<th width="100">'.$val->attrs['name'].''.($val->attrs['required']?'<span class="req">*</span>':'').':</th>
								<td>'. Slonne::getInputHTML($val->attrs, $obj->attrs[$val->attrs['code']]) .'</td>
							</tr>';
						}
						
					//	vd($obj);
						
					
						if(!$et->attrs['linear'])
						{
							$str.='
							<tr>
								<th>Расположение: </th>
								<td>';
							$str.='
									<select name="pid">
										<option value="0">-КОРЕНЬ-</option>';
									$str.=Entity::drawTreeSelect($et, $type, 0, $self_id = ($type=='blocks'?$obj->attrs['id']:0),  $idToBeSelected=$pid, $level=0 );
									$str.='
									</select>';
							$str.='
								</td>
							</tr>';
						}
						
						
						
						
						$str.='
							<tr>
								<td></td>
								<td style="padding-top: 20px;">
									<!--<a href="javascript:void(0)" style="font-size: 15px; font-weight: bold; " id="edit-save-btn" onclick="Entities.checkForm(); ">сохранить</a>-->
									<button onclick="Entities.checkForm(); " class="button" type="button">Сохранить</button>
								</td>
							</tr>
						</table>
						</form>
						';
					}
					else
						$error="ОШИБКА! [".$essence."] <br>(NO_FIELDS_DEFINED_ERROR) ";
				}
				else 	
					$error="ОШИБКА! [".$essence."] <br>(BAD_TYPE_ERROR) ";
			}
			else
				$error="ОШИБКА! [".$essence."] <br>(ESSENCE_NOT_EXIST_ERROR) ";
			
			
		//	vd($requiredFields);

			echo $str;
			
			$html=ob_get_clean();
			
			
			$json['html']=$html;
			$json['error']=$error;
			$json['required']=$requiredFields;
			
			
			echo json_encode($json);
}










function save()
{
	global $admin, $_CONFIG;
	
//	$admin->checkPrivilege($_REQUEST['essence'], 'edit', $ajax=false);
/*	vd($_REQUEST);
	die;*/
	
	$essence=$_REQUEST['essence'];
	
	
	
	$lang = $_SESSION['admin_lang'];
	
	$pid=intval($_REQUEST['pid']);
	
	$et=new Essence($essence);
	//vd($et);
	if($et->attrs)
	{
		$type=$_REQUEST['type'];
		if($et->attrs['joint_fields'])
			$type='elements';
		
		if($type != 'blocks' &&  $type != 'elements')
		{
			die('
				<script>
					window.top.error("INCORRECT_TYPE_ERROR", "entities-info-div");
					window.top.loading(0, \'entities-loading-div\', \'fast\');
				</script>');
		}
		
		if(isset($_REQUEST['id']))
		{
			$obj=new Entity($et->attrs['code'], $_REQUEST['id'], $type, $lang);
			if(!$obj->attrs)
				$e='ОШИБКА! Объект не найден! ['.$_REQUEST['id'].']';
			/*else 	
				$act='edit';*/
		}
		//else $act='add';
		
		//die;
		
		foreach($et->fields[$type] as $key=>$val)
			$fields[$val->attrs['code']]=$val->attrs;
		
		
		$result = Entity::prepareObjToSaving($et, $obj, $fields, $values=NULL/*пусть возьмёт из РЕКУЭСТА*/);
		
		//vd($result);
		//die;
		
		//vd($result);
		//vd($_FILES);
		
		//vd($result);
		
		if($result['result']!='ok')
		{
			#	если пришёл массив проблемных полей (т.е. нет внутренней ошибки проверки)
			if(gettype($result) == 'array')
			{
				$troubles=$result;
				foreach($troubles as $key=>$val)
				{
					//vd($val);
					$troublesStr.='- '.$fields[$key]['name'].' ('.$val.')<br>';
					echo '
					<script>
					
						window.top.$("#'.$key.'-info-div").html("'.addslashes($val).'")
					</script>';
				}
				$err="Пожалуйста, заполните корректно все необходимые поля: <br>".$troublesStr;
				
			}
			else	#	какая то внутрення ошибка (для какого то типа нет инструкции проверки)
			{
				$err=$result;
			}
			die('
					<script>
						window.top.error("'.$err.'", "entities-info-div");
						window.top.highlight(["'.join('", "', array_keys($troubles)).'"])
						
						window.top.loading(0, \'catalog-loading-div\', \'fast\');
					</script>');
		}
		else	#	Всё ништяк, всё готово, можно сохранять!!! 
		{
			#	чтоб при апдейте обновлялась только текущая яз версия
			$langs=$_CONFIG['langs'];
			/*if($obj->attrs)
				$langs=$_CONFIG['langs'][$lang];*/
			
			foreach($langs as $key_lang=>$val_lang)
			{
				if($obj->attrs)
					if($key_lang != $lang)
						continue;
				
				$tbl=Essence::getTblName($et->attrs['code'], $type, $key_lang);
			/*	vd($key_lang);
			die;*/
				
				//vd($result);
				if(!$obj->attrs)
				{
					
					
					
					$sql="INSERT INTO `".$tbl."`  ";	
				}
				else
					$sql="UPDATE `".$tbl."`  ";
					
				$sql.="SET pid=".$pid.",  active=".($_REQUEST['active']?1:0).", ";
				
				
				if(!$obj->attrs)
				{
					#	достаём идх
					$sql_idx="SELECT MAX(idx) FROM `".$tbl."` WHERE pid='".$pid."'";
					$qr_idx=mysql_query($sql_idx);
					echo mysql_error();
					$next_idx=mysql_fetch_array($qr_idx);
					$idx=intval($next_idx[0]/10)*10 + 10;
					
					$sql.=" idx=".$idx.", ";
				}
				
				#	фомируем тело SQL
				
				$sql.=join("\n, ", $result['sql']);
				
				if($obj->attrs)
					$sql.=" \nWHERE id=".$obj->attrs['id'];
				
				vd($sql);
				
				//die;
				
				
				
				mysql_query($sql);
				if($e=mysql_error() )
				{
					//vd($e);
					//vd($_FILES);
					die('<script>window.top.error("<b>ОШИБКА!</b> ('.mysql_real_escape_string($e).')", "entities-info-div")</script>');
				}
				else
				{
					#	надо обработать файлы 
					
					//vd($result);
					$files=$result['files'];
					vd($files);
				//	die;
				//	vd($et);
					$id=$obj->attrs?$obj->attrs['id']:mysql_insert_id();
					
					foreach($files as $key=>$val)
					{
						foreach($val as $key2=>$val2)
						{
							if($tmp=Slonne::processPic($val2, $subDirName = $et->attrs['code']))
							{
								//vd($tmp);
							//	vd($fields[$key]);
								
								#	ЕСЛИ НЕ МАЛТИПЫЛ  - апдейт
								
							//	vd($id);
								if(!$fields[$key]['multiple'])
								{
									$sql="UPDATE `".$tbl."` SET `".mysql_real_escape_string($key)."`='".mysql_real_escape_string($tmp)."' WHERE id=".$id;
									mysql_query($sql);
								//	vd($sql);
									echo mysql_error();
								}
								else
								{
									$sql="
									INSERT INTO media 
									SET pid='".$id."', 
									essence='".$et->attrs['code']."', 
									type='".$type."',  
									field_code='".$key."', 
									path='".$tmp."'";
									mysql_query($sql);
								//	vd($sql);
									echo mysql_error();
								}
							}
							else	#	не сохранилась
								$problemPics[]=$tmp;
						}
					}
					
					
					if(count($problemPics))
					{
						echo '
						<script>	
							window.top.notice("Некоторые картинки не сохранены: <br>'.join("\n", $problemPics).'", "catalog-info-div")
						</script>';
					}
					
					//vd($pid);
					
					
					
				}
				
				
				#	редактирование тайтлов фоток
				//vd($_REQUEST);
				foreach($_REQUEST['media'] as $key=>$val)
				{
					$sql="UPDATE media SET title".$_CONFIG['langs'][$lang]['postfix']."='".mysql_real_escape_string(htmlspecialchars(trim($val)))."' WHERE id=".intval($key);
			//		vd($sql);
					mysql_query($sql);
					echo mysql_error();
				}
				
				
				
				
				#	удаление фоток
				$sql="SELECT * FROM media WHERE id IN (".join(", ", array_keys($_REQUEST['delete-media'])).") ";
				$qr=mysql_query($sql);
				echo mysql_error();
				while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
				{					
					Entity::deleteMediaElement($next['path']);
				}
				//die;
				//die;
			
		}
			
			
			$str.='
				window.top.$(\'#tree-expand-btn-'.$obj->attrs['id'].'\').css(\'display\', \'block\')
				<script>';
			
			#	если правили элемент, то есть list1
			if($et->attrs['linear'] ||$et->attrs['joint_fields'] || (!$et->attrs['linear']&&$type=='elements'))
				$str.='window.top.Entities.list('.$pid.');';
			
				
			
			//$str.='	'.($et->attrs['linear'] ||$et->attrs['joint_fields'] || (!$et->attrs['linear']&&$type=='elements') ?'window.top.Entities.list('.$pid.');':'').'';
			if($type == 'blocks')
			{
				$str.='
					window.top.Entities.drawTree('.$pid.', true);  
					//window.top.Entities.drawTree(0, true);  
					window.top.Entities.loadedBranches=[];';
			}
			
			
			$str.='
					window.top.$(\'#tree-expand-btn-'.$pid.'\').css(\'display\', \'block\')
					window.top.notice("Успешно сохранено!", "entities-info-div")
				</script>';
		}
		
		echo $str;
	}
	else 	
		die('<script>window.top.error("ОШИБКА! Сущность не найдена! ['.$_REQUEST['essence'].']", "entities-info-div")</script>');

}















function saveChangesOfList()
{
	global $admin, $_CONFIG;
	//vd($_REQUEST);
	//die;
	
	$lang = $_SESSION['admin_lang'];
	
	$et=new Essence($_REQUEST['essence']);
	if($et->attrs )
	{
		
		
		#	активность и сорт
		foreach($_REQUEST['displayed_ids'] as $key=>$val)
		{
			foreach($_CONFIG['langs'] as $key2=>$val2)
			{
				$elementsTbl=Essence::getTblName($et->attrs['code'], 'elements', $key2);
				
				$sql="
				UPDATE `".mysql_real_escape_string($elementsTbl)."` 
				SET active='".($_REQUEST['active'][$key]?1:0)."',
				idx='".intval($_REQUEST['order'][$key])."'
				WHERE id=".intval($key)."";
				mysql_query($sql);
				echo mysql_error();
				//vd($sql);
			}
		}
		
		if(count($_REQUEST['del']))
		{
			//vd($admin);
			if($admin->hasPrivilege($_REQUEST['essence'], 'delete'))
			{
				foreach($_REQUEST['del'] as $key=>$val)
				{
					//vd($key);
					$obj=new Entity($et->attrs['code'], $key, 'elements', $lang);
					$obj->delete();
					//Entity::delete(/*essence*/$et->attrs['code'], /*id*/$key, /*type*/'elements');
				}
				#	чтоб не было глюков с постраничностью
				$str.='<script>Entities.settings[\'p\']=1; </script>';
			}
			else 
				$str.= '<script>window.top.error("Нет прав на удаление!");</script>';
				
		}
		
		
		$str.='
		<script>
			window.top.notice("Изменения сохранены!");
			window.top.Entities.list();';
		if($et->attrs['joint_fields'])
		{
			$str.='
			window.top.Entities.drawTree('.intval($_REQUEST['pid']).', true); ';
		}
		
		$str.=
		'
			 
		</script>';
	}
	else 
		$str='<script>window.top.error("НЕ СОХРАНЕНО! Ошибка.  <br>(ERROR_ESSENCE_NOT_EXIST) ['.$_REQUEST['essence'].']")</script>';
	
	echo $str;
}













function saveChangesOfTree()
{
	global $admin, $_CONFIG;
	//vd($_REQUEST);
	//die;
	
	$et=new Essence($_REQUEST['essence']);
	if($et->attrs )
	{
		$type='elements';
		if(!$et->attrs['joint_fields'])
			$type='blocks';
			
		foreach($_REQUEST['idx'] as $key=>$val)
		{
			
			
			foreach($_CONFIG['langs'] as $key2=>$val2)
			{
				$tbl=Essence::getTblName($et->attrs['code'], $type, $key2);
				
				$sql="
				UPDATE `".mysql_real_escape_string($tbl)."` 
				SET 
				idx='".intval($_REQUEST['idx'][$key])."'
				WHERE id=".intval($key)."";
				mysql_query($sql);
				echo mysql_error();
				vd($sql);
			}
		}
		
		
		
		//vd($et);
		
		
		
		$str='
		<script>
			window.top.notice("Изменения сохранены!");
			window.top.Entities.drawTree(0, true);
		</script>';
	}
	else 
		$str='<script>window.top.error("НЕ СОХРАНЕНО! Ошибка.  <br>(ERROR_ESSENCE_NOT_EXIST) ['.$_REQUEST['essence'].']")</script>';
	
	echo $str;
}










function drawBlockHeading($p, $et)
{
	
	//vd($p);
	//vd($et);
	if($et->attrs['linear'])
		return;
		
	$str.='
	<div class="block-heading-wrapper">
		<!--<span id="block-heading-id-wrapper">id: <strong>'.$p->attrs['id'].'</strong></span>-->
		<h1>'.$p->attrs['name'].' </h1>
		<span id="block-heading-id-wrapper"> |'.$p->attrs['id'].'</span>
		<div class="block-heading-menu">							
			<button class="button orange small " onclick="Entities.edit(\''.$p->attrs['id'].'\', '.$p->attrs['pid'].', \'blocks\')"  ><span class="fa fa-edit"></span> ред.</button>';
							
	if(!$et->attrs['joint_fields'])
	{
		$str.='
			<button  onclick="Entities.edit(\'\', '.$p->attrs['id'].', \'blocks\')"  class="button green small "><span class="fa fa-plus"></span> блок</button>';
	}
	$str.='
			<button  onclick="Entities.edit(\'\', '.$p->attrs['id'].', \'elements\')"  class="button green small "><span class="fa fa-plus"></span> эл</button>
			<button style="margin-left: 30px;" onclick="Entities.delete('.$p->attrs['id'].', \''. $p->attrs['type'].'\')"  class="button red small "><span class="fa fa-trash-o"></span> удалить</button>	
		</div>
	</div>';
	
	return $str;
}















function delete()
{
	global $admin, $_CONFIG;
	
	$error="";
			
	//$str='!!!';
	ob_start();
	
	$lang = $_SESSION['admin_lang'];
	//$essence=$_REQUEST['essence'];
	
	$admin->checkPrivilege($_REQUEST['essence'], 'delete', $ajax=true);

	//vd($_REQUEST);
	$obj=new Entity($_REQUEST['essence'], $_REQUEST['id'], $_REQUEST['type'], $lang);	
	if($obj->attrs)
	{
		/*if($obj->attrs['type'] == 'blocks')
		{
			$error='TRyiNG To DeLeTe BLoCK!';
		}
		else */
		{
		//vd($obj);
			#	для доп инфы, уходящей жсоном
			$et=new Essence($obj->essence);
			
			$obj->delete();
		}
		//vd($et);
		
	}
	else
		$error='ENTITY_NOT_EXIST_ERROR ['.join(", ", $_GET).']';
	
	
	echo $str;
	
	$html=ob_get_clean();
	
	
	$json['html']=$html;
	$json['error']=$error;
	
	#	чтобы понимать какие действия сделать после удаления
	$json['joint_fields']=$et->attrs['joint_fields'];
	$json['linear']=$et->attrs['linear'];
	
	
	
	echo json_encode($json);
}














function deletePic()
{
	global $admin, $_CONFIG;
//	echo "!";
	$error="";
			
	//$str='!!!';
	ob_start();
	
	vd($_REQUEST);
	$lang = $_SESSION['admin_lang'];
	
	$e=new Entity($_REQUEST['essence'], $_REQUEST['id'], $type, $lang);
	
	//vd($e);
	vd($e->attrs[$_REQUEST['code']]);
	$e->deleteMediaElement($e->attrs[$_REQUEST['code']], $_REQUEST['code']);
	//Entity::deleteMediaElement($_REQUEST['src'], $_REQUEST['code']);
	
	
	
	echo $str;
	
	$html=ob_get_clean();
	
	
	$json['html']=$html;
	$json['error']=$error;
	
	
	
	echo json_encode($json);
}









?>