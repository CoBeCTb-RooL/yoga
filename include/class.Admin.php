<?php

//require_once(_ROOT_.'/include/class.Group.php');


class Admin
{
	var $attrs, $group;
	
	
	function Admin($id)
	{
		//vd($id);
		//if($id=intval($id))
		if(gettype($id) == 'array')
			$attrs=$id;
		else
		{
			$sql="SELECT * FROM slonne_admins WHERE id=".intval($id)." ";
			//vd($sql);
			$qr=mysql_query($sql);
			echo mysql_error();
			$next=mysql_fetch_array($qr, MYSQL_ASSOC);
			//vd($next);
			$attrs=$next;
		}
		
		$arr=array();
		
		//vd($attrs);
		
		$this->group = new Group($attrs['group']);
		
		
		
		$this->attrs=$attrs;
	}
	
	
	
	
	
	
	
	function checkOwnPassword($pass)
	{
		return $this->attrs['password'] == $pass;
	}
	
	
	
	
	
	
	
	function authorize($email, $password)
	{
		$sql="SELECT * FROM slonne_admins WHERE email='".mysql_real_escape_string($email)."' AND password='".mysql_real_escape_string($password)."'";
		//vd($sql);
		$qr=mysql_query($sql);
		echo mysql_error();
		if($next=mysql_fetch_array($qr, MYSQL_ASSOC))
		{
			//vd($next);
			$admin=new Admin($next);
			$admin->updateLastAuthTime();
			
			//vd($admin);
			$_SESSION['admin']['id']=$admin->attrs['id'];
			$admin->setPrivileges();
			//$_SESSION['admin']['privileges']=$admin->group->attrs['privileges_arr'];
			
			return true;
		}
	}
	
	
	
	
	function setPrivileges()
	{
		$_SESSION['admin']['privileges']=$this->group->attrs['privileges_arr'];
	}
	
	
	
	function updateLastAuthTime()
	{
		$sql="UPDATE slonne_admins SET last_auth=NOW() WHERE id=".$this->attrs['id'];
		mysql_query($sql);
		echo mysql_error();
	}
	
	
	
	function getMyModules()
	{
		return Admin::getPrivilegedModules($this->group->attrs['id']);
	}
	
	
	
	
	
	
	
	
	
	function hasPrivilege($module, $action)
	{
		return $this->group->attrs['privileges_arr'][$module][$action];
	}
	
	
	
	
	
	function checkPrivilege($module, $action, $ajax)
	{
		//vd($this);
		if(!$this->hasPrivilege($module, $action))
		{
			$text="Недостаточно прав для этого действия <br>[".$module.".".$action."]";
			
			if($ajax)	
				die("{error: '".$text."'}");
			else
				die('
				<script>
					window.top.error("'.$text.'", "entities-info-div");
					window.top.loading(0, \'entities-loading-div\', \'fast\');
				</script>');
		}
		
	}

	
	
} 















?>