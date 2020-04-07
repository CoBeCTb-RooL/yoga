<?php
//include_once('../config.php');
require_once(ROOT.'/include/class.Field.php');
require_once(ROOT.'/include/class.Slonne.php');

class Essence
{
	var $attrs, $fields;
	
	
	#	маска для названия таблиц сущности
	static $elementsTblMask = '#ESSENCECODE__elements#LANGPOSTFIX';
	static $blocksTblMask = '#ESSENCECODE__blocks#LANGPOSTFIX';
	
	
	
	function Essence($code)
	{
		//vd($code);
		if(is_array($code))
			$attrs=$code;
		else
		{
			$code=trim($code);
			
			$sql="SELECT * FROM slonne_essences WHERE code='".mysql_real_escape_string($code)."' OR id='".intval($code)."'";
			$qr=mysql_query($sql);
			echo mysql_error();
			$attrs=mysql_fetch_array($qr, MYSQL_ASSOC);
		}
		
		if($attrs)
		{
			
			$this->attrs=$attrs;
			
			if($this->attrs)
			{
				$this->fields=Essence::getFields($this->attrs['id']);
			}
			
		}
	}
	
	
	
	
	
	
	
	
	function getFields($id, $values)
	{
		if($id=intval($id))
		{
			$sql="SELECT * FROM slonne_fields WHERE pid = ".$id." ORDER BY idx"; 
			$qr=mysql_query($sql);
			echo mysql_error();
			while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
			{
				$arr[$next['owner_type']][]=new Field($next);
			}
		}
		
		return $arr;
	}
	
	
	
	
	
	static $fieldTypes=array(
							
							'smalltext'=>'Текстовое поле (varchar)',
							'text'=>'Textarea (text)',
							'html'=>'HTML редактор (text)',
							'html_long'=>'HTML редактор(longtext)',
							'num'=>'Число (int)',
							'date'=>'Дата',
							'select'=>'Выпадающий список',
							'checkbox'=>'Галочка',
							'pic'=>'Картинка',
							
						);
	
	#	дефолтовые значения для длин и размеров полей
	static $defaultFieldsPresets=array(
									'smalltext'=>array('size'=>"240", ),
									'text'=>array('width'=>"250", "height"=>"60", ),
	
									//'select'=>array('options'=>"выбор 1\nвыбор 2\nвыбор 3\n", ),
								);
								
								
								
								
								
								
	
								
								
								
	function delete()
	{
		return Essence::deleteEssence($this->attrs['id']);
	}
	
	
	
	
	
	
	function deleteEssence($id)
	{	
		global $_CONFIG;
		
		
		$et=new Essence($id);
		
		//vd($et);
		//die;
		
		if($et->attrs)
		{
			foreach($et->fields as $key=>$val)
			{
				foreach($val as $key2=>$f)
				{
					$f->delete();
				}
			}
			$sql="DELETE FROM slonne_essences WHERE id=".$et->attrs['id'];
			mysql_query($sql);
		//	vd($sql);
			echo mysql_error();
			
			foreach($_CONFIG['langs'] as $key=>$val)
			{
				$elementsTbl=Essence::getTblName($et->attrs['code'], 'elements', $key);
				$blocksTbl=Essence::getTblName($et->attrs['code'], 'blocks', $key);
				
				$sql="DROP TABLE `".$elementsTbl."` ".( !  ($et->attrs['joint_fields'] || $et->attrs['linear'])? ", `".$blocksTbl."`" : "" )."";
				
				mysql_query($sql);
				echo mysql_error();
			}
			//vd($sql);
			
			return true;
		}
		else return 'eRRoR: ESSENCE_NOT_EXIST';
		
	}
	
	
	
	
	
	
	
	
	
	
	
	function getLangVersionsExistence()
	{
		global $_CONFIG;
		
		//vd($_CONFIG['langs']);
		
		foreach($_CONFIG['langs'] as $key=>$val)
		{
			$elementsTbl=Essence::getTblName($this->attrs['code'], 'elements', $key);
			$blocksTbl=Essence::getTblName($this->attrs['code'], 'blocks', $key);
			
			//vd($key);
			//vd($this);
			$sql="SELECT * FROM `".mysql_real_escape_string($elementsTbl)."`";
			$qr=mysql_query($sql);
			
			if(!$e=mysql_error())	#	надо делать
				$arr[$key]['elements'] = true;
			else 
				$arr[$key]['elements'] = false;
			
			#	проверяем блоки(если надо)
			if(!($this->attrs['linear'] || $this->attrs['joint_fields']))
			{
				$sql="SELECT * FROM `".mysql_real_escape_string($blocksTbl)."`";
				$qr=mysql_query($sql);
				
				if(!$e=mysql_error())	#	надо делать
					$arr[$key]['blocks'] = true;
				else 
					$arr[$key]['blocks'] = false;
			}
			
		}
		
		
		return $arr;
		
	}
	
	
	
	
	
	
	
	
	
	function getTblName($essenceCode, $type, $lang)
	{
		global $_CONFIG;
		
		$mask=Essence::$elementsTblMask;
		if($type=='blocks')
			$mask=Essence::$blocksTblMask;
		
		$tmp=str_replace('#ESSENCECODE', $essenceCode, str_replace('#LANGPOSTFIX', $_CONFIG['langs'][$lang]['postfix'], $mask));

		return $tmp;
	}
	
} 
?>