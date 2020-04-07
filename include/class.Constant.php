<?php



class Constant
{
	var $attrs;
	
	static $types = array('text'=>'Короткое', 'textarea'=>'Текстовое поле', 'html'=>'редактор');
	
	
	function Constant($id)
	{
		$sql="SELECT * FROM slonne__constants WHERE id=".intval($id)." ";
		$qr=mysql_query($sql);
		echo mysql_error();
		$next=mysql_fetch_array($qr, MYSQL_ASSOC);
		$attrs=$next;

		$this->attrs=$attrs;
	}
	
	
	
	
	
	
	
	

	
	
} 















?>