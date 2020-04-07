<?php




class Slonne
{
	var $fields;
	
	
	function Slonne($sectionType)
	{
		$fields=Slonne::getFields($sectionType);
		$this->fields=$fields;
			
	}
	


	
	
	function getFields($essence, $values)
	{
	//	vd($values);
		if(!$essence)	
			return;
			
		switch($essence)
		{
			case "cars":
					$arr=array();
					$arr[]=array("field_code"=>"brand", "field_name"=>"Марка", "required"=>true, "type"=>"label", "display"=>1, );
					//$arr[]=array("field_code"=>"andrey", "field_name"=>"Кто Андрей", "required"=>true, "type"=>"select", "options"=>array("жырный", "толстый", "рыхлый", "пузатый", "сальный", ));
					$arr[]=array("field_code"=>"model", "field_name"=>"Модель", "required"=>true, "type"=>"label", "length"=>"20", "display"=>1, );
					$arr[]=array("field_code"=>"year", "field_name"=>"Год выпуска", "required"=>false, "type"=>"label", "length"=>"4", "display"=>1, );
					$arr[]=array("field_code"=>"engine_volume", "field_name"=>"Объём двигателя", "required"=>false, "type"=>"label", "length"=>"1");
					$arr[]=array("field_code"=>"descr", "field_name"=>"Описание", "required"=>true, "type"=>"smalltext", "size"=>"300x100",  );
					$arr[]=array("field_code"=>"color", "field_name"=>"Цвет", "required"=>true, "type"=>"select", "options"=>array("чёрный", "белый", "красный", "синий", "зелёный", ));
					$arr[]=array("field_code"=>"pic", "field_name"=>"Аватарка", "required"=>false, "type"=>"pic", "multiple"=>false);
					//$arr[]=array("field_code"=>"dima", "field_name"=>"Димка лёбит", "required"=>true, "type"=>"select", "options"=>array("Жанку", "Пиго", "Велик", "Доту", "Жанкину попку", "мм и всё остальное..ДаАааА))) " ));
					$arr[]=array("field_code"=>"pics", "field_name"=>"Картинки", "required"=>false, "type"=>"pic", "multiple"=>true);
					$arr[]=array("field_code"=>"active", "field_name"=>"Активен? (чекбокис)", "required"=>false, "type"=>"checkbox", "display"=>true, );
					$arr[]=array("field_code"=>"zhanka", "field_name"=>"Жанка  - хрюшка?", "required"=>false, "type"=>"checkbox", "display"=>true, );
					
				//	$arr[]=array("field_code"=>"qwe", "field_name"=>"что то ещё ", "required"=>false, "type"=>"smalltext", "size"=>"100x60");
				break; 
				

		}
		
		
		
		if(count($arr))
		{
			foreach($arr as $key=>$val)
			{
//				vd($key);
				$arr[$key]['fieldHTML']=Slonne::getInputHTML($fieldInfo=$val, $value=$values[$val['field_code']]);
			}
		}	
		return $arr;
	}
	
	
	
	
	
	
	function getInputHTML($fieldInfo, $value)
	{
		//vd($value);
		switch($fieldInfo['type'])
		{
			
			default:
				$str.='<span style=" color: #B34949; font-size: 13px; font-weight: normal; text-align: right; text-shadow: 0 1px 2px #CCCCCC;">Oops! o_O Problems here! (field: <b>'.$fieldInfo['code'].'</b>, type:<b>'.$fieldInfo['type'].'</b> : HTML_NOT_PROVIDED)';
				break;
			
				
			
			case 'smalltext':
			case 'num':
					$str.='
					<input type="text" value="'.htmlspecialchars($value).'" name="'.$fieldInfo['code'].'" id="'.$fieldInfo['code'].'" style="width: '.($fieldInfo['size']?$fieldInfo['size']:50).'px; ">';
				break;
				

				
			case 'text': 
					if($fieldInfo['size'])
					{
						$tmp=explode("x", $fieldInfo['size']);
						$w=intval($tmp[0]);
						$h=intval($tmp[1]);
						if($w)	$styleStr.=' width: '.$w.'px; ';
						if($w)	$styleStr.=' height: '.$h.'px; ';
					}
					$str.='
					<textarea name="'.$fieldInfo['code'].'" id="'.$fieldInfo['code'].'" style="'.$styleStr.'">'.str_replace("<br />", "\n", $value).'</textarea>';
				break;

				
				
				
				
			case 'html': 
			case 'html_long': 
					if($fieldInfo['size'])
					{
						$tmp=explode("x", $fieldInfo['size']);
						$w=intval($tmp[0]);
						$h=intval($tmp[1]);
						if($w)	$styleStr.=' width: '.$w.'px; ';
						if($w)	$styleStr.=' height: '.$h.'px; ';
					}
					//$str.='<textarea name="'.$fieldInfo['code'].'" id="'.$fieldInfo['code'].'" style="'.$styleStr.'">'.str_replace("<br />", "\n", $value).'</textarea>';
					
					$str.='<div><input type="hidden" id="'.$fieldInfo['code'].'" name="'.$fieldInfo['code'].'" value="'.htmlspecialchars(stripslashes($value)).'"><input type="hidden" id="FCKeditor1___Config" value=""><iframe id="FCKeditor1___Frame" src="../FCKeditor/editor/fckeditor.html?InstanceName='.$fieldInfo['code'].'&Toolbar=Default" width="100%" height="400px" frameborder="no" scrolling="no"></iframe></div>';
					
					/*$str.='
					<textarea  name="'.$fieldInfo['code'].'" id="'.$fieldInfo['code'].'"  style="display:none">'.$value.'</textarea>
					<input type="hidden" id="FCKeditor1___Config" value="">
					<iframe id="FCKeditor1___Frame" src="../FCKeditor/editor/fckeditor.html?InstanceName='.$fieldInfo['code'].'&Toolbar=" width="100%" style="min-width: 700px; " height="300px" frameborder="no" scrolling="no"></iframe>';
					*/
				break;
				
				
				
				
				
				
				
				
				
			case 'select':
				//vd($fieldInfo);
					$str.='
					<select name="'.$fieldInfo['code'].'" id="'.$fieldInfo['code'].'">
						<option value="">-выберите-</option>';
					foreach($fieldInfo['options'] as $key=>$val)
					{
						$str.='
						<option value="'.$val.'" '.($val==$value?' selected="selected" ':'').'>'.$val.'</option>';
					}
					$str.='
					</select>';
				break;
				

				
				
				
				
			case "date":
					$str.='
					<input type="text" name="'.$fieldInfo['code'].'" id="'.$fieldInfo['code'].'" value="'.$value.'"  style="width:70px"> <img id="'.$fieldInfo['code'].'-calendar-btn" src="/js/calendar/calendar.jpg" style="border:0px;">
					
					<script>
						Calendar.setup({
						    inputField     :    "'.$fieldInfo['code'].'",      // id of the input field
						    ifFormat       :    "%Y-%m-%d",       // format of the input field
						    showsTime      :    false,            // will display a time selector
						    button         :    "'.$fieldInfo['code'].'-calendar-btn",   // trigger for the calendar (button ID)
						    singleClick    :    true,           // double-click mode
						    step           :    1                // show all years in drop-down boxes (instead of every other year as default)
						});
				</script>';
				break;	
				
				
				
				
				
				
			case 'checkbox':
					$str.='
					<input type="checkbox" '.($value?' checked="checked" ':'').' name="'.$fieldInfo['code'].'" id="'.$fieldInfo['code'].'">';
				break;
				
				
				
				
			case "pic":
				//vd($value);
				
				
				#	если картинка одиночная - пофиг суём в массив и отрисовываем - чтоб всё  водном месте было	
				/*if(!$fieldInfo['multiple'])
					if($value)
						$value=array(array('title'=>'', 'src'=>$value));*/

				//vd($value);
				if(!$fieldInfo['multiple'])
				{
					//vd($value);
					if($value)
					{
						$str.='
						<div id="pic-'.$fieldInfo['code'].'-div">
							<a href="javascript:void(0)" onclick="Entities.deletePic(\''.$value.'\', \''.$fieldInfo['code'].'\')">удалить</a><br>
							<a href="/upload/images/'.$value.'" onclick="return hs.expand(this)" class="highslide ">
								<img src="/resize.php?file=/upload/images/'.$value.'&height=120" style="">
							</a>
						</div>
						<br>';
					}
				}
				else
				{
					foreach($value as $key=>$val)
					{
						//vd($val);
						//vd($key);
						
						
						$str.='
						<div class="multipic-wrap " id="multipic-wrap-'.$key.'">	
							<label onclick="Entities.checkImgToDelete('.$key.')"><input type="checkbox" id="delete-media-cb-'.$key.'" name="delete-media['.$key.']" /> удалить</label>
							<br>
							<span>'.$val['src'].'</span><br>
							<a href="/upload/images/'.$val['src'].'" onclick="return hs.expand(this)" class="highslide ">
								<img src="/resize.php?file=/upload/images/'.$val['src'].'&height=100" style="">
							</a>
							<br>
							<!--<input type="text" name="media['.$key.']" value="'.$val['title'].'">-->
							<br>
							<textarea name="media['.$key.']" >'.$val['title'].'</textarea>
						</div>';
					}
				}
					/*$str.='
					<div id="'.$fieldInfo['code'].'-files-parent-div"></div>';
					if($fieldInfo['multiple'])
					{
						$str.='
						<input type="button" id="'.$fieldInfo['code'].'-add-btn" value="+" onclick="addFile(\''.$fieldInfo['code'].'\')">';
					}
					$str.='
					<script>addFile(\''.$fieldInfo['code'].'\')</script>';*/
				$str.='
				
				<input style="border: 0px solid #000" type="file" name="'.$fieldInfo['code'].'[]" id="'.$fieldInfo['code'].'" '.($fieldInfo['multiple']?'multiple':'').' >';
				break;
		}
		
		return $str;
	}
	
	
	
	
	
	
	
	
	
	
	static $validPicTypes=array('jpg', 'jpeg', 'gif', 'png');
	
	
	
	
	
	
	
	
	
	function isPicValid($file)
	{
		#	картинка не пришла	
		if(!$file['size'])
			return 'Пожалуйста, загрузите картинку';
			
		#	массив допустимых типов	
		foreach(Slonne::$validPicTypes as $key=>$val)
			$arr[]='image/'.$val;
		#	недопустимый тип	
		if(!in_array($file['type'], $arr))
			return 'Неверный формат картинки! ('.$file['type'].'). Допустимые: <b>'.join('</b>, <b>', Slonne::$validPicTypes).'</b>';
			
		return true;
	}
	
	
	
	
	
	
	
	
	
	
	function processPic($file, $subDirName)
	{
		//vd($file);
		if( $file['name'] )
		{
			$dot=strrpos($file['name'], '.');
			$name=(substr($file['name'], 0, $dot));
			$ext=strtolower(substr($file['name'],  $dot+1));
			
			$tmp_name=$file["tmp_name"];
			
			
			
			if (is_uploaded_file($file['tmp_name'])) 
			{
				//echo "!";
				mkdir($_SERVER['DOCUMENT_ROOT'].'/upload/images/'.($subDirName?$subDirName.'/':''));
				$token=substr(time(), 7).'_'.uniqid();
				//vd($token);
				$dest_filename=$_SERVER['DOCUMENT_ROOT'].'/upload/images/'.($subDirName?$subDirName.'/':'').''.$token.'.'.$ext;
				//vd($dest_filename);
				//if(1)
				if( move_uploaded_file($tmp_name, $dest_filename))	
				{
					return ''.($subDirName?$subDirName.'/':'').$token.'.'.$ext;
				}
			}
		}
	}
	
	
	
	
	#	перекидывает модель во вью
	function view($viewName, &$model)
	{
		//vd(VIEWS_DIR.'/'.$viewName);
		//phpinfo();
		//vd($viewName);
		//vd(realpath(VIEWS_DIR.'/'.$viewName));
		if(file_exists($view = realpath(VIEWS_DIR.'/'.$viewName)))
		{
			
			foreach($GLOBALS as $key=>$val){$$key = $val;}
			${'MODEL'} = $model;
			require($view);
			$model=NULL;
		}
		else
		{
			echo 'eRRoR: VieW <b>"'.$viewName.'"</b> NoT FouND.';
		}
	}
	
	
	
	static $paramsInnerSeparator = '_';
	
	
	#	возвращает массив параметров, у кторых задан ключ (типа "year_2013")
	#	ключ - 'year', значение - '2014'
	function getParams()
	{
		global $_PARAMS;
		
		$params = array();
		foreach($_PARAMS as $key=>$val)
		{
			list($param, $value) = explode(Slonne::$paramsInnerSeparator, $val);
			if($param)
			{
				$params[$param] = $value;
			}
		}
		
		return $params;
	}
	
	
	
	
	
	
	
} 








?>