<?php
class User 
{
	var $attrs;
	
	function User($id)
	{
		global $_CONFIG; 
		
		if($id = intval($id))
		{
			$sql="SELECT * FROM users WHERE id=".$id;
			$qr=mysql_query($sql);
			echo mysql_error();
			if($u=mysql_fetch_array($qr, MYSQL_ASSOC))
				$this->attrs = $u;
			
		}
	}
	
	
	
	function authenticate($email, $pass)
	{
		$sql="SELECT * FROM users WHERE email='".mysql_real_escape_string($email)."' AND passwd = '".mysql_real_escape_string($pass)."'";
		$qr=mysql_query($sql);
		echo mysql_error();
		$usr=mysql_fetch_array($qr, MYSQL_ASSOC);
		if($usr)
		{
			$_SESSION['user'] = array(
									'id'=>$usr['id'],
									'surname'=>$usr['surname'],
									'name'=>$usr['name'],
									'fathername'=>$usr['fathername'],
									'email'=>$usr['email'], 
							);
			return true; 
		}
	}
	
	
	
	static $creditPlecho = array('1:500', '2:700', '3:400', '7:800', );
	
	static $accountType = array(1=>'ECN Pro - Личный счёт', 'Кредитный счёт', 'Текущий счёт', 'Дебетный счёт', );
	
	
	
	
	
	
	function dateOfBirthInput($birthdate)
	{
		global $_CONST;
		
		if($birthdate)
		{
			$day = intval(substr($birthdate, 8, 2));
			$month = intval(substr($birthdate, 5, 2));
			$year = intval(substr($birthdate, 0, 4));
		}
		
		#	день
		$str.='
		<select name="day" id="day">
			<!--<option value="">--</option>-->';
		for($i=1; $i<=31; $i++)
			$str.='
			<option value="'.$i.'" '.($i == $day ? ' selected="selected" ' : '').'>'.$i.'</option>';
		$str.='
		</select>';
		
		#	месяц
		$str.='
		<select name="month" id="month">
			<!--<option value="">--</option>-->';
		foreach(Funx::$months[$_SESSION['lang']] as $key=>$val)
		{
			$str.='
			<option value="'.$key.'" '.($key == $month ? ' selected="selected" ' : '').'>'.$val[1].'</option>';
		}
		$str.='
		</select>';
	
		#	год
		$str.='
		<select name="year" id="year">
			<!--<option value="">--</option>-->';
		$y=date('Y')-17;
		//vd($y);
		for($i=$y; $i>=($y-80); $i--)
			$str.='
			<option value="'.$i.'" '.($i == $year ? ' selected="selected" ' : '').'>'.$i.'</option>';
		$str.='
		</select>';	
		
		
		return $str;
	}
	
	
	
	
	
	function sexInput($value)
	{
		global $_CONST;
		
		$str.='
		<select name="sex" id="sex">
			<option value="">'.$_CONST['ВЫБЕРИТЕ (для селектов)'].'</option>';
		foreach(Funx::$sex[$_SESSION['lang']] as $key=>$val)
		{
			$str.='
			<option value="'.$key.'" '.($key == $value ? ' selected="selected" ' : '').'>'.$val.'</option>';
		}
		$str.='	
		</select>';
		
		return $str;
	}
	
	
	
	
	#	кредитное плечо
	function creditPlechoInput($value)
	{
		global $_CONST;
		
		$str.='
		<select name="credit_plecho" id="credit_plecho">
			<option value="">'.$_CONST['ВЫБЕРИТЕ (для селектов)'].'</option>';
		foreach(User::$creditPlecho as $key=>$val)
		{
			$str.='
			<option value="'.$val.'"  '.($value == $val ? ' selected="selected" ' : '').'>'.$val.'</option>';
		}
		$str.='	
		</select>';
		
		return $str;
	}
	
	
	
	
	
	
	
	#	вид счёта
	function accountTypeInput($value)
	{
		global $_CONST;
		
		$str.='
		<select name="account_type" id="account_type">
			<option value="">'.$_CONST['ВЫБЕРИТЕ (для селектов)'].'</option>';
		foreach(User::$accountType as $key=>$val)
		{
			$str.='
			<option value="'.$key.'" '.($value == $key ? ' selected="selected" ' : '').'>'.$val.'</option>';
		}
		$str.='	
		</select>';
		
		return $str;
	}
	
	
	
	
	
	
	
	
	function emailExists($eml, $id)
	{
		$sql="SELECT id FROM `users` WHERE email = '".trim(mysql_real_escape_string($eml))."' ".(intval($id) ? " AND id != ".intval($id)."" : "")."";
		//vd($sql);
		$qr=mysql_query($sql);
		//$next=mysql_fetch_array($qr, MYSQL_ASSOC);
		//vd($next);
		echo mysql_error();
		return mysql_num_rows($qr) > 0;
	}
	
	
	
	
	
} 













?>