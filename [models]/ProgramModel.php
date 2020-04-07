<?php
class Program extends Page 
{
	public $asanas;
	
	function getAsanas()
	{
		$asanasStr = trim(strip_tags(str_replace(array(" ", "&nbsp;"), "", $this->attrs['descr'])));
		$tmp = explode(",", $asanasStr);
		
		foreach($tmp as $key=>$val)
		{
			if($tmp = Asana::getByNum($val))
				$asanas[++$i] = $tmp; 
		}
		
		
		$this->asanas = $asanas;
		//return $asanas;
	}
	
	
	
} 
?>