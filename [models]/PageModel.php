<?php
class Page extends Entity 
{
	var $attrs;
	const ESSENCE = 'pages';
	var $essence = self::ESSENCE;
	
	function __construct($id, $lang)
	{
		$lang = $lang? $lang: $_SESSION['lang'];
		$tmp = new Entity(self::ESSENCE, $id, Entity::TYPE_ELEMENTS, $lang);
		
		$this->attrs = $tmp->attrs;
	}
	
	
	
	
	/*function getPages($pid, $lang, $limit)
	{
		return Entity::getEntities('pages', 'elements', $lang, $pid, $limit);
	}
	*/
	
	
	
	
	/*function getChildren($pid, $limit, $additionalClauses, $order = "ORDER BY idx")
	{
		$type = Entity::TYPE_ELEMENTS;
		$activeOnly = true;
		
		return Entity::getEntities(self::ESSENCE, $type, $_SESSION['lang'], $pid, $limit, $additionalClauses, $order, $activeOnly );
	}*/
	
	function getChildren($pid, $limit, $additionalClauses, $order = "ORDER BY idx")
	{
		$type = Entity::TYPE_ELEMENTS;
		$activeOnly = true;
		$entities = Entity::getEntities(self::ESSENCE, $type, $_SESSION['lang'], $pid, $limit, $additionalClauses, $order, $activeOnly ); 
		foreach($entities as $key=>$val)
		{
			$ret[] = new self($val->attrs);
		}
		
		return $ret;
	}
	
	
	
	#	переопределение метода с учётом поля link сущности pages
	function url($module, $lang)
	{
		$lang = $lang ? $lang: $_SESSION['lang'];
		$link = $this->attrs['link'] ? str_replace('%LANG%', $lang, $this->attrs['link']) : Entity::url($module, $lang);
		return $link;
	}
	
	
	
	
	
	/*function prepareForMenu($p)
	{
		$link = $p->attrs['link'] ? str_replace('%LANG%', $_SESSION['lang'], $p->attrs['link']) : $p->url();	
		return array('link'=>$link, 'title'=>$p->attrs['name']);
	}*/
	
	
	
	
	
	
	function getTree($id)
	{
		
		$p = new Page($id);
		if(!$p->attrs)
			return;
		
		$arr[$id] = $p;
		$pid = $p->attrs['pid'];
		while($pid/* && $a < 7*/)
		{
			//$a++;
			
			
			$page = new Page($pid);
			$arr[$page->attrs['id']] = $page;
			$pid=$page->attrs['pid'];
		}
		
		return array_reverse($arr, true);
	}
	
	
	
	
	
	
	
} 
?>