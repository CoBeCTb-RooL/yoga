<?php
class Field
{
	var $id, $attrs;
	
	function Field($param)
	{
		//vd($param);
		if($param)
		{
			if(is_array($param))
			{
				$param['source']='param';
				
				$attrs=$param;
			}
			elseif($param=intval($param) )
			{
				$sql="SELECT * FROM `slonne_fields` WHERE id=".$param;
				$qr=mysql_query($sql);
				echo mysql_error();
				if(mysql_num_rows($qr))
				{
					$next=mysql_fetch_array($qr, MYSQL_ASSOC);
					$next['source']='base';
					
					$attrs=$next;
				}
			}
			
			#	раскладываем выпад. список
			if($attrs['type'] == 'select' && $attrs['options'])
			{
				$tmp=explode("\r\n", $attrs['options']);
				$attrs['options']=$tmp;
			}
			
			$this->attrs=$attrs;
		}
		
		
	}
	
	
	
	
	
	
	function delete()
	{
		return Field::deleteField($this->attrs['id']);
	}
	
	
	
	function deleteField($id)
	{
		global $_CONFIG;
		
		$f=new Field($id);
		
		if($f->attrs)
		{
			$sql="DELETE FROM slonne_fields WHERE id=".$f->attrs['id'];
			//vd($sql);
			mysql_query($sql);
			if($e=mysql_error()) return 'eRRoR: '.$e;
			else
			{
				$essence=new Essence($f->attrs['pid']);
				//vd($essence);
				
				
				foreach($_CONFIG['langs'] as $key=>$val)
				{
					$sql="ALTER TABLE `".Essence::getTblName($essence->attrs['code'], $f->attrs['owner_type'], $key)."` DROP `".$f->attrs['code']."`";
					//vd($sql);
					//return;
					mysql_query($sql);
					if($e=mysql_error())
						return 'eRRoR: '.$e.'<br>'.$sql;
				}
				return true;
				//vd($sql);
			}
		}
		else return 'eRRoR: FIELD_NOT_EXIST';
	}
	
	
	
	
	
	
	
	function checkField($field, $v, $entity)
	{
	//	vd($entity);
		//vd($v);
		$problem=NULL;
		
		//$v=$values[$val['code']];
		//$field=$fieldInfo;
		
		switch($field['type'])
		{
			case "smalltext":
				$v=trim($v);
				if(!$v && $field['required'])
					$problem='Введите значение!';
				 	
				$sql[]=" ".$field['code']." = '".mysql_real_escape_string($v)."' ";
			break;

			
			case "num":
				$v=trim($v);
				if(!$v && $field['required'])	
					$problem='Введите значение!';
				elseif(!is_numeric($v) && $field['required'])
					$problem='Должно состоять из цифр!';
					
				$sql[]=" ".$field['code']." = '".mysql_real_escape_string($v)."' ";
			break;
				
			
			case "text":
				$v=trim($v);
				if(!$v && $field['required'])
					$problem='Введите текст!';
					
				$sql[]=" ".$field['code']." = '".mysql_real_escape_string($v)."' ";
			break;
			
				
			case "html":
			case "html_long":
				$v=trim($v);
				if(!$v && $field['required'])
					$problem='Введите текст!';
				 	
				$sql[]=" ".$field['code']." = '".mysql_real_escape_string($v)."' ";
				#	можно ещё крепче проверку написать
			break;
				
			
			case "select":
				if($field['multiple'])
				{
					if(!count($v) && $field['required'])
						$problem='Выберите хотя бы одно значение!';
						
					$sql[]=" ".$field['code']." = '|".join('||', $v)."|'";
				}
				else
				{
					if(!$v && $field['required'])
						$problem='Выберите значение!';
					 	
					$sql[]=" ".$field['code']." = '".mysql_real_escape_string(htmlspecialchars($v))."' ";
				}
				
			break;
			
			
			
			case "checkbox":
				$sql[]=" ".$field['code']." = '".($v?1:0)."' ";
			break;
			
			
				
			case "date":
				$v=trim($v);
				if(!$v && $field['required'])
					$problem='Введите дату!';
				else
				{
					$tmp=explode('-', $v);
					if((strlen($tmp[0])!=4 || strlen($tmp[1])!=2 || strlen($tmp[2])!=2) && $field['required']  )
						$problem='Дата должна иметь формат ГГГГ-ММ-ДД';

					elseif((!is_numeric($tmp[0]) || !is_numeric($tmp[1]) || !is_numeric($tmp[2])) && $field['required'] )
						$problem='Недопустимые символы в дате!';
						
					elseif(($tmp[1] > 12 || $tmp[2] > 31) && $field['required'])
						$problem='Некорректная дата!';
				}
				
				$sql[]=" ".$field['code']." = '".mysql_real_escape_string($v)."' ";
				
			break;

			
			
			
			
				
			case "pic":
				
				if(!$field['multiple'])
				{
					
					
					$tmp=array();
					$tmp['name']=$_FILES[$field['code']]['name'][0];
					$tmp['type']=$_FILES[$field['code']]['type'][0];
					$tmp['tmp_name']=$_FILES[$field['code']]['tmp_name'][0];
					$tmp['error']=$_FILES[$field['code']]['error'][0];
					$tmp['size']=$_FILES[$field['code']]['size'][0];
					
					$_FILES[$field['code']]=$tmp;
					
				//	vd($_FILES[$val['code']]);
					
					$tmp=Slonne::isPicValid($_FILES[$field['code']]);
					if($tmp!==true )
					{
						if($field['required'] && !$entity->attrs[$field['code']] )	
							$problem=$tmp;
					}
					else
						$files[$field['code']][]=$_FILES[$field['code']];
					
				}
				else
				{
					//vd($values['_FILES']);
					$problem='';
					foreach($_FILES[$field['code']]['name'] as $k=>$pic)
					{
						if($pic)
						{
							$tmp=array();
							$tmp['name']=$pic;
							$tmp['type']=$_FILES[$field['code']]['type'][$k];
							$tmp['tmp_name']=$_FILES[$field['code']]['tmp_name'][$k];
							$tmp['error']=$_FILES[$field['code']]['error'][$k];
							$tmp['size']=$_FILES[$field['code']]['size'][$k];
							$pics[$k]=$tmp;
						}
					}
					
					if(!count($pics) && $field['required'] && !$entity->attrs[$field['code']])
						$problem='Пожалуйста, загрузите хотя бы одну картинку!';
					
					
					foreach($pics as $key=>$val)
					{
						$tmp=Slonne::isPicValid($val);
						if($tmp!==true)	
						{
							if($field['required'])
								$problem.='<b>&quot;'.$field['name'].'&quot;</b> :'.$tmp.'<br>';
						}
						else
							$files[$field['code']][]=$val;
					}
					
				}
			break;
			
				
			default:
				//echo "!!!!";
				return "ОШИБКА! UNHANDLED_CHECK_INSTRUCTIONS (type: ".$field['type'].")";
			break;
			
			
		}
				
			
		if($problem)
		{
			$res['trouble']=array($field['code']=>$problem);
		}
		else
		{
			$res['result']='ok';
			$res['sql']=$sql[0];
			$res['files']=$files;
		}
		
			
		return $res;
	}
	
	
	
	
	
	
	
	
	
	
} 





?>