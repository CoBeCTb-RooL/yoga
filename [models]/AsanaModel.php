<?php
class Asana extends Entity 
{
	var $attrs;
	const ESSENCE = 'asanas';
	var $essence = self::ESSENCE;
	

	
	function __construct($id, $lang)
	{
		$lang = $lang? $lang: $_SESSION['lang'];
		$tmp = new Entity(self::ESSENCE, $id, Entity::TYPE_ELEMENTS, $lang);
		
		$this->attrs = $tmp->attrs;
	}
	
	
	
	
	function getChildren($pid, $limit, $additionalClauses, $order = "")
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
	
	
	
	
	function getByNum($num)
	{
		$sql = "SELECT * FROM `asanas__elements` WHERE num = '".mysql_real_escape_string($num)."'";
		$qr=mysql_query($sql);
		echo mysql_error();
		if($next=mysql_fetch_array($qr, MYSQL_ASSOC))
			return new Asana($next);
	}
	
	
} 
?>