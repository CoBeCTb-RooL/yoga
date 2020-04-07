<?php




class Group
{
	var $attrs;
	
	
	
	
	function Group($id)
	{
		//vd($id);
		//if($id=intval($id))
		if(gettype($id) == 'array')
			$attrs=$id;
		else
		{
			$sql="SELECT * FROM slonne_admins_groups WHERE id=".intval($id)."";
			//vd($sql);
			$qr=mysql_query($sql);
			echo mysql_error();
			$next=mysql_fetch_array($qr, MYSQL_ASSOC);
			//vd($next);
			$attrs=$next;
			
			
		}
		if($attrs)
			$attrs['privileges_arr'] = Group::actionsStr2Arr($attrs['privileges']);
		
		
		$this->attrs=$attrs;
	}
	
	
	
	
	
	
	
	
	
	
	
	function getGroups($type)
	{
		if($type == 'active')
			$sqlInc=" AND active=1";
		elseif($type == 'inactive')
			$sqlInc=" AND active=0";
			
		$sql="SELECT * FROM slonne_admins_groups WHERE 1 ".$sqlInc." ORDER BY idx";
		$qr=mysql_query($sql);
		echo mysql_error();
		while($next=mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			$arr[$next['id']] = new Group($next);
		}
		
		
		return $arr;
	}

	
	function getActiveGroups()
	{
		return Module::getGroups($type='active');
	}
	
	
	function getInactiveGroups()
	{
		return Module::getGroups($type='inactive');
	}
	
	
	
	
	
	
	function getSelectHTML($priv)
	{
		$modules=Module::getModules();
		
		//vd($modules);
		
	//	$actionTypes=array('list'=>'Просмотр', 'edit'=>'Редактирование', 'save'=>'Изменение', 'delete'=>'Удаление', );
		//vd($actionTypes);
		//vd($modules);
		
		foreach($modules as $key=>$m)
		{	
			
			$str.='
			
				
				<fieldset class="privileges-fieldset '.(!$m->attrs['active']?'inactive':'').'" >
					<legend>
						<label><input type="checkbox" '.(!$m->attrs['active']?' disabled="disabled" ':'').' '.($priv[$m->attrs['code']]?' checked="checked" ':'').' onclick="Admins.switchGlobalPriv(\''.$m->attrs['code'].'\')" id="priv-global-'.$m->attrs['code'].'" name="privileges['.$m->attrs['code'].']">&nbsp;'.($m->attrs['icon']?$m->attrs['icon']:'').' '.$m->attrs['name'].'</label>
					</legend>
					';
			foreach($m->attrs['actions_arr'] as $code=>$typeName)
			{
				$str.='
					<label class="priveleges-action-type" title="'.$code.'" >
						<input class="priv-sub-'.$m->attrs['code'].'" id="priv-sub-'.$code.'"  type="checkbox" name="privileges['.$m->attrs['code'].']['.$code.']" '.(!$m->attrs['active']?' disabled="disabled" ':'').'    '.($priv[$m->attrs['code']][$code]?' checked="checked" ':'').'    onclick="Admins.switchGlobalPrivFromSub(\''.$code.'\', \''.$m->attrs['code'].'\')"> '.$typeName.' 
					</label>
					<br>';
			}
			$str.=' 
				</fieldset>
			';
		}
		return $str;
		
	}
		
	
	
	
	
	
	
	static 	$modulesDelimiter = '|',
			$middleDelimiter = ':', 
			$actionsDelimiter=',';
	
	
	
	
	
	
	
	function actionsArr2str($arr)
	{
		foreach($arr as $module=>$val)
		{
			$str=$module.Group::$middleDelimiter;
			$arr2=array();
			foreach(array_keys($val) as $action)
			{
				$arr2[]=$action;
			}
			$tmp1=join(Group::$actionsDelimiter, $arr2);
			$arr3[]=$str.$tmp1;
		}
		$str=join(Group::$modulesDelimiter, $arr3);
		return $str;
	}
	
	
	
	
	
	
	
	function actionsStr2Arr($str)
	{
		//vd($str);
		$tmp1=explode(Group::$modulesDelimiter, $str);
		foreach($tmp1 as $val)
		{
			$tmp2=explode(Group::$middleDelimiter, $val);
			//vd($tmp2);
			$arr[$tmp2[0]]=array();
			$tmp3=explode(Group::$actionsDelimiter, $tmp2[1]);
			//vd($tmp3);
			foreach($tmp3 as $val2)
			{
				$arr[$tmp2[0]][$val2]=true;
			}
		}
		//vd($tmp1);
		//vd($arr);
		return $arr;
	}
	
	
	
	
	
	
	
	
} 















?>