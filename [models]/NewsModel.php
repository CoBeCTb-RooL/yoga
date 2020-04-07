<?php
class News extends Entity 
{
	var $attrs;
	const ESSENCE = 'news';
	var $essence = self::ESSENCE;
	

	
	function __construct($id, $lang)
	{
		$lang = $lang? $lang: $_SESSION['lang'];
		$tmp = new Entity(self::ESSENCE, $id, Entity::TYPE_ELEMENTS, $lang);
		
		$this->attrs = $tmp->attrs;
	}
	
	
	
	
	function getChildren($pid, $limit, $additionalClauses, $order = "ORDER BY dt DESC")
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
	
	
	
	
	
} 
?>