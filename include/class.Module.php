<?php



class Module
{
	var $attrs;
	
	
	function Module($id)
	{
		//vd($id);
		//if($id=intval($id))
		if(gettype($id) == 'array')
			$attrs=$id;
		else
		{
			$sql="SELECT * FROM slonne_modules WHERE id=".intval($id)." OR code='".mysql_real_escape_string($id)."'";
			//vd($sql);
			$qr=mysql_query($sql);
			echo mysql_error();
			$next=mysql_fetch_array($qr, MYSQL_ASSOC);
			//vd($next);
			$attrs=$next;
		}
		
		$arr=array();
		
		//vd($attrs);
		if($attrs['get_str'])
		{
			$tmp=explode("&", $attrs['get_str']);
			//vd($tmp);
			foreach($tmp as $key=>$val)
			{
				$tmp2=explode("=", $val);
				//vd($tmp2);
				$arr[$tmp2[0]]=$tmp2[1];
			}
			//vd($arr);
			//unset($attrs['get_str']);
			$attrs['_GET']=$arr;
		}
		
		
		
		$attrs['actions_arr']=Module::parseActionsStr($attrs['actions']);
		$this->attrs=$attrs;
	}
	
	
	
	
	
	
	function getModules($type)
	{
		if($type == 'active')
			$sqlInc=" AND active=1";
		elseif($type == 'inactive')
			$sqlInc=" AND active=0";
			
		$sql="SELECT * FROM slonne_modules WHERE 1 ".$sqlInc." ORDER BY idx";
		$qr=mysql_query($sql);
		echo mysql_error();
		while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			$arr[$next['code']] = new Module($next);
		}
		
		
		return $arr;
	}

	
	function getActiveModules()
	{
		return Module::getModules($type='active');
	}
	
	
	function getInactiveModules()
	{
		return Module::getModules($type='inactive');
	}
	
	
	
	
	
	function parseActionsStr($str)
	{
		$tmp=explode("\r\n", $str);
		foreach($tmp as $key=>$val)
		{
			$tmp2=explode("=", $val);
			$actType=trim($tmp2[0]);
			$actName=trim($tmp2[1]);
			
			if($actType && $actName)
				$arr[$actType]=$actName;
		}
		
		return $arr;
	}
	
	
	
	
	
	
	

	
	
} 















?>