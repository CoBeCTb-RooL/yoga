<?php
class Entity
{
	var $id, $essence, $attrs;
	const TYPE_ELEMENTS = 'elements';
	const TYPE_BLOCKS = 'blocks';
	
	function Entity($essence, $id, $type, $lang)
	{
		global $_CONFIG; 
		
		$lang = $lang ? $lang : $_SESSION['admin_lang'];
		
		if(is_array($id))
		{
			$attrs=$id;
		}
		else
		{
			if($id=intval($id) )
			{
				$elementsTbl=Essence::getTblName($essence, 'elements', $lang);
				$blocksTbl=Essence::getTblName($essence, 'blocks', $lang);
				
				$tbl=mysql_real_escape_string($essence);
				$tbl = $elementsTbl;
			//	vd($type);
				if($type == 'blocks' || $type == 'block')
					$tbl = $blocksTbl;
					 
					
				$sql="SELECT * FROM `".$tbl."` WHERE id=".$id;
				//vd($sql);
				$qr=mysql_query($sql);
				echo mysql_error();
				if(mysql_num_rows($qr))
				{
					$next=mysql_fetch_array($qr, MYSQL_ASSOC);
					
					$attrs=$next;
					
				}
			}
		}
		
		if($attrs)
		{
			$attrs['type']=$type;
			$attrs['lang'] = $lang;
			$this->id=$attrs['id'];
			$this->attrs=$attrs;
			$this->essence=$essence;
			
			$this->getMyMedia();
		}
	}
	
	
	
	
	function getMyMedia()
	{
		//vd($this);
		
		#	ЧТО ЕСЛИ ПОНАДОБЯТСЯ ПОДПИСИ К ФОТКАМ? 
		#	ПОКА ЧТО ВОЗВРАЩАЕМ ПРОСТО МАССИВ
		$tmp=Entity::getMedia($this->essence, $this->attrs['type'], $this->attrs['id'], $this->attrs['lang']);
		//vd($tmp);
		
		foreach($tmp as $key=>$val)
		{
			$this->attrs[$key]=$val;
		}
		//vd($tmp);
	}
	
	
	
	
	
	function getMedia($essence, $type, $id, $lang)
	{
		global $_CONFIG;
	//	vd($essence);
	//	vd($section);
		
		$sql="
		SELECT * FROM media 
		WHERE essence='".mysql_real_escape_string($essence)."' 
		AND type='".mysql_real_escape_string($type)."' 
		AND pid=".intval($id)."";
	//	vd($sql);
		$qr=mysql_query($sql);
		echo mysql_error();
		while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			//vd($next);
			$arr[$next['field_code']][$next['id']]=array('src'=>$next['path'], 'title'=>$next['title'.$_CONFIG['langs'][$lang]['postfix']]);
		}
		
		return $arr;
	}
	
	
	
	
	
	
	function prepareObjToSaving($essence, $obj, $fields, $values)
	{
		$result='';
		
		if(!$essence->attrs)
			return 'ОШИБКА! Сущность не передана.. Essence::prepareObjToSaving FAILED';
		
		if(!count($fields))
			return 'ОШИБКА! Поля не переданы.. <br>Essence::prepareObjToSaving FAILED';
			
			
		$values=count($values)?$values:$_REQUEST;
		
		
		foreach($fields as $key=>$val)
		{
			$v=$values[$val['code']];
			
			$res=Field::checkField($val, $v, $entity=$obj);
			
			
			if($res['files'])
			{
				$files[$key]=$res['files'][$key];
			}	
			
			if($res['result'] == 'ok')
			{
				if($res['sql'])
					$sql[]=$res['sql'];
			}
			else
			{
				if(gettype($res) == 'array')
				{
					foreach($res['trouble'] as $key2=>$val2)
					{
						$troubles[$key2]=$val2;
					}
				}
				else	#	это значит что поле не описано! ну типа АНХЭНДЛД
				{
					return $res;
				}
			}
		}
		
		
		if(count($troubles))
			return $troubles;
		
		else return array('result'=>'ok', 'sql'=>$sql, 'files'=>$files);
		
		
	}
	
	
	
	
	
	
	
	function getChildBlocksCount($essence, $pid)
	{
		return Entity::getChildrenCount($essence, $pid, 'blocks');
	}
	
	
	function getChildElementsCount($essence, $pid)
	{
		return Entity::getChildrenCount($essence, $pid, 'elements');
	}
	
	function getChildrenCount($essence, $pid, $type)
	{
		global $_CONFIG;
		
		$lang = $_SESSION['admin_lang'];
		
		$elementsTbl=Essence::getTblName($essence, 'elements', $lang );
		$blocksTbl=Essence::getTblName($essence, 'blocks', $lang );
		
		if($type != 'blocks' && $type != 'elements')
			return;
		if(!$essence)	
			return;

		$tbl=$type=='blocks' ? $blocksTbl : $elementsTbl;
		
			
		
		$sql="SELECT COUNT(*) FROM `".mysql_real_escape_string($tbl)."` WHERE pid = ".intval($pid);
		$qr=mysql_query($sql);
		echo mysql_error();
		$next=mysql_fetch_array($qr);
		return $next[0];
	}
	
	
	
	
	
	
	
	function drawTreeSelect($essence, $type, $pid/*чьих детей отображать*/, $self_id, $idToBeSelected, $level=0 )
	{
		global $_CONFIG;
		//vd($idToBeSelected);
		
		$lang = $_SESSION['admin_lang'];
		
		$pid=intval($pid);
		$level=intval($level);
		
		
		//vd($essence);
		
		
		//vd($self_id);
		$et=$essence;
		if(gettype($et) == 'string')
			$et=new Essence($et);
		
	//	vd($type);
	//	vd($pid);
	//	vd($idToSelect);
	//	vd($level);
		
		//vd($et);
		$elementsTbl=Essence::getTblName($et->attrs['code'], 'elements', $lang );
		$blocksTbl=Essence::getTblName($et->attrs['code'], 'blocks', $lang );
		
		$sourceTbl=$elementsTbl;
		if(!$et->attrs['joint_fields'])
			$sourceTbl = $blocksTbl;
		
			
		//return;
		
		
		
		
		$sql="SELECT * FROM `".mysql_real_escape_string($sourceTbl)."` where id=".$pid." " ;
		$qr=mysql_query($sql);
		echo mysql_error();
		$next=mysql_fetch_array($qr, MYSQL_ASSOC);
	
		if($next['id'] == $self_id && $self_id)
			return $ret;
		
		if($next['id'] )
		{
			$ret.='
				<option '.($idToBeSelected==$next['id']?' selected="selected"  ':'').' value="'.$next['id'].'">';
				for($i=1; $i<$level; $i++)
				{
					$ret.='------';
				}
				$ret.='| '.$next['name'];
				$ret.='
				</option>';
		}
		
		
		#	детей достаём
		$sql="SELECT * FROM `".mysql_real_escape_string($sourceTbl)."` WHERE pid=".$pid." ORDER BY idx" ;
		$qr=mysql_query($sql);
		echo mysql_error();
		
		if(mysql_num_rows($qr))
		{
			while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
			{
				//drawTreeSelect($essence, $type, $pid/*чьих детей отображать*/, $idToBeSelected, $level=0 )
				$ret.=Entity::drawTreeSelect($et, $type, $next['id'], $self_id,  $idToBeSelected,  ($level+1));	
			}
		}
	
		return $ret;
	}
	
	
	
	
	
	
	
	
	function getChildBlocks()
	{
		//vd($this);
		$sql="SELECT * FROM `".mysql_real_escape_string($this->essence)."_blocks` WHERE pid=".$this->attrs['id']." ORDER BY idx";
		$qr=mysql_query($sql);
		echo mysql_error();
		while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			$arr[]=new Entity($this->essence, $next, 'blocks', $this->lang);
		}
		
		return $arr;
	}
	
	
	
	
	
	
	function getChildElements()
	{
		//vd($this);
		$sql="SELECT * FROM `".mysql_real_escape_string($this->essence)."` WHERE pid=".$this->attrs['id']." ORDER BY idx";
		$qr=mysql_query($sql);
		echo mysql_error();
		while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			$arr[]=new Entity($this->essence, $next, 'elements', $this->lang);
		}
		
		return $arr;
	}
	
	
	
	
	
	
	
	#	самоуничтожение наху
	function delete()
	{
		global $_CONFIG; 
		
		$et=new Essence($this->essence);
		
		$mediaFields=array();
		foreach($et->fields[$this->attrs['type']] as $key=>$val)
		{
			if($val->attrs['type'] == 'pic')
				$mediaFields[]=$val->attrs['code'];
		}
		
		foreach($mediaFields as $key=>$val)
		{
			//vd($key);
			$arr[]=$this->attrs[$val];
			if(is_array($this->attrs[$val]))
				foreach($this->attrs[$val] as $key2=>$val2)
					Entity::deleteMediaElement($val2['src']);
			else 		
				$this->deleteMediaElement($this->attrs[$val], $val);
		}
		
		
		foreach($_CONFIG['langs'] as $key=>$val)
		{
			$elementsTbl=Essence::getTblName($et->attrs['code'], 'elements', $key);
			$blocksTbl=Essence::getTblName($et->attrs['code'], 'blocks', $key);
			
			$tbl = $this->attrs['type'] == 'blocks' ? $blocksTbl : $elementsTbl;
			
			#	мочим саму сущность
			$sql="DELETE FROM `" . mysql_real_escape_string($tbl) . "`
			WHERE id=".$this->attrs['id'];
			mysql_query($sql);
			echo mysql_error();
		}
//		vd($et);

	//	echo '<hr>'.$this->attrs['type'].' - '.$this->attrs['id'].' - '.$this->attrs['name'].'<br>';
		
		#	нужно удалить чайлдовые блоки
		if($this->attrs['type'] == 'blocks' && !$et->attrs['joint_fields'])
		{
			$childBlocks=$this->getChildBlocks();
				
			//vd($childBlocks);
			foreach($childBlocks as $key=>$val)
				$val->delete();
		}
		
		#	удалить чайлдовые элементы
		if($et->attrs['joint_fields'] || $this->attrs['type'] == 'blocks')
		{
			$childElements=$this->getChildElements();
			foreach($childElements as $key=>$val)	
				$val->delete();
		}
		/*echo '
		<hr>';*/
			
		

	}
	
	
	
	
	
	
	/* уничтножение медиа элемента
	 *  $path - урл картинки
	 *  [$code] - поле для очищения, если медиа не множественно*/
	function deleteMediaElement($path, $code)
	{
		$file=ROOT.$path;
		$lang = $_SESSION['admin_lang'];
		
		#	можем смело длать запрос, так как пути все уникальны
		$sql="DELETE FROM media WHERE path='".mysql_real_escape_string($path)."' LIMIT 1";
		mysql_query($sql);
		echo mysql_error();
		
		unlink($file);
		
		#	если указано поле для вычищения (если медиа не множестенно)
		if($code)
		{
			$tbl=Essence::getTblName($this->essence, $this->attrs['type'], $lang);
			
			$sql="UPDATE `" . $tbl . "` SET `".mysql_real_escape_string($code)."`='' 
			WHERE id=".$this->attrs['id']." ";
			mysql_query($sql);
			echo mysql_error();
		}
		
		return true;
	}
	
	
	
	
	
	function getEntities($essence, $type, $lang, $pid, $limit, $additionalClauses, $order = " ORDER BY idx ",  $activeOnly = false )
	{
		$lang = $lang?$lang:$_SESSION['lang'];
		$tbl=Essence::getTblName($essence, $type, $lang);
		
		$pid=intval($pid);
		
		$sql="SELECT * FROM `".$tbl."` WHERE pid=".$pid." ".($activeOnly ? "AND active='1'" : "" )." ".($additionalClauses)."  ".$order." ".mysql_real_escape_string($limit)."";
		//vd($sql);
		$qr=mysql_query($sql);
		echo mysql_error();
		while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			$tmp[] = new Entity($essence, $next, $type, $lang);
		}
		
		//vd($tmp);
		
		return $tmp;
	}
	
	
	
	
	function url($module, $lang)
	{
		$lang = $lang ? $lang: $_SESSION['lang'];
		//vd($_SESSION);
		return '/'.$lang.'/'.($module ? $module : $this->essence).'/'.$this->urlPiece();
	}
	
	
	
	
	function urlPiece()
	{
		return ''.$this->attrs['id'].'_'.str2url($this->attrs['name']);
	}
	
	
	
	
	
	
	
} 
?>