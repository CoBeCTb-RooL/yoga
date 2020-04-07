<?php
//var_dump($_SERVER['REQUEST_URI']);
require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once(ROOT.'/header.php');







$action=$_REQUEST['action'];

usleep(500000);

switch($action)
{
	default:
			echo '{"error":"AJAX(\"'.$_SERVER['PHP_SELF'].'\")::no action!'.($action?' ('.$action.')':'').'"}';
		break;
		
		
	case "drawCabinet":
			drawCabinet();		
		break;	
		
	case "saveCabinetChanges":
			saveCabinetChanges();
		break;
		
		
	case "drawAuthForm":
			drawAuthForm();		
		break;
		
	case "auth":
			auth();		
		break;
	
	case "logout":
			logout();		
		break;
	
}















function drawCabinet()
{
	global $_CONST;
	$error="";
		
	$str='';
	ob_start();
	
	
	if($_SESSION['user'])	
		$u = new User($_SESSION['user']['id']);
	//vd($u);
	
	if($u->attrs)
		$attrs = $u->attrs;
	
		
	
	$str.='
	
<div class="cabinet" >	
	<div id="cabinet-reg-form">';
	
	$str.='
		<div id="reg-form-header">
			<div id="reg-form-logo"><img src="/images/logo-blue.jpg"></div>
			<div id="reg-form-header-heading">
				';
	if(!$u->attrs)
	{
		$str.='
				<h1>'.$_CONST['заголовок формы регистрации - большое слово РЕГИСТРАЦИЯ'].'</h1>
				<h2>'.$_CONST['заголовок формы регистрации - ДЛЯ ОТКРЫТИЯ ТОРГОВОГО СЧЁТА'].'</h2>';
	}
	else
	{
		$str.='
				<h1>'.$_CONST['заголовок формы регистрации - большое слово РЕДАКТИРОВАНИЕ'].'</h1>
				<h2>'.$_CONST['заголовок формы регистрации - ЛИЧНЫХ ДАННЫХ'].'</h2>';
	}
	$str.='
				<span>'.$_CONST['форма регистрации - текст сверху - ОБРАТИТЕ ВНИМАНИЕ'].'</span>
			</div>
			
			
			<div class="clear"></div>
		</div>';
	
	$str.='	
		<form name="cabinet_edit_form" id="cabinet_edit_form" method="post" action="/modules/cabinet/cabinet.php" target="cabinet_iframe" onsubmit="Cabinet.checkCabinetData('.($u->attrs ? 'true' : '').'); return false; ">
			<input type="hidden" name="action" value="saveCabinetChanges">
			'.($u->attrs ? '<input type="hidden" name="id" value="'.$u->attrs['id'].'">' : '').'
			';
	
	
	$str.='
	<table border="0" width="100%" class="wrapper-tbl">
		<tr>
			<td width="50%" valign="top">
				<table class="cabinet-inputs-tbl" border="0">
					<tr>
						<th>'.$_CONST['ФАМИЛИЯ'].'<i class="req">*</i>:</th>
						<td><input type="text" name="surname" id="surname" placeholder="'.$_CONST['ФАМИЛИЯ'].'" value="'.$u->attrs['surname'].'"></td>
					</tr>
					<tr>
						<th>'.$_CONST['ИМЯ'].'<i class="req">*</i>:</th>
						<td><input type="text" name="name" id="name" placeholder="'.$_CONST['ИМЯ'].'" value="'.$u->attrs['name'].'"></td>
					</tr>
					<tr>
						<th>'.$_CONST['ОТЧЕСТВО'].'<i class="req">*</i>:</th>
						<td><input type="text" name="fathername" id="fathername" placeholder="'.$_CONST['ОТЧЕСТВО'].'" value="'.$u->attrs['fathername'].'"></td>
					</tr>
					<tr>
						<th>'.$_CONST['ДАТА РОЖДЕНИЯ'].'<i class="req">*</i>:</th>
						<td>
							'.User::dateOfBirthInput($u->attrs['birthdate']).'
						</td>
					</tr>
					<tr>
						<th>'.$_CONST['ПОЛ'].'<i class="req">*</i>:</th>
						<td>
							'.User::sexInput($u->attrs['sex']).'
						</td>
					</tr>
					
				</table>
			</td>
			
			
			
			
			<td width="50%" valign="top">
				<table class="cabinet-inputs-tbl" border="0">
					<tr>
						<th>'.$_CONST['ГРАЖДАНСТВО'].'<i class="req">*</i>:</th>
						<td><input type="text" name="citizenship" id="citizenship" placeholder="'.$_CONST['ГРАЖДАНСТВО'].'" value="'.$u->attrs['citizenship'].'"></td>
					</tr>
					<tr>
						<th>'.$_CONST['ТЕЛЕФОН'].'<i class="req">*</i>:</th>
						<td><input type="text" name="tel" id="tel" placeholder="'.$_CONST['ТЕЛЕФОН'].'" value="'.$u->attrs['tel'].'"></td>
					</tr>
					<tr>
						<th>'.$_CONST['АЛЬТЕРНАТИВНЫЙ НОМЕР ТЕЛЕФОНА'].':</th>
						<td><input type="text" name="tel2" id="tel2" placeholder="'.$_CONST['АЛЬТЕРНАТИВНЫЙ НОМЕР ТЕЛЕФОНА'].'" value="'.$u->attrs['tel2'].'"></td>
					</tr>
					<tr>
						<th>'.$_CONST['EMAIL'].'<i class="req">*</i>:</th>
						<td><input type="text" name="email" id="email" placeholder="'.$_CONST['EMAIL'].'" value="'.$u->attrs['email'].'"></td>
					</tr>
				</table>
			</td>
			
			
		</tr>
		<tr>
			<td colspan="2" style="height: 30px;"></td>
		</tr>
		<tr>
		
			<td width="50%" valign="top">
				<table class="cabinet-inputs-tbl" border="0">
					<tr>
						<th>'.$_CONST['СТРАНА'].'<i class="req">*</i>:</th>
						<td><input type="text" name="country" id="country" placeholder="'.$_CONST['СТРАНА'].'" value="'.$u->attrs['country'].'"></td>
					</tr>
					<tr>
						<th>'.$_CONST['ГОРОД'].'<i class="req">*</i>: </th>
						<td><input type="text" name="city" id="city" placeholder="'.$_CONST['ГОРОД'].'" value="'.$u->attrs['city'].'"></td>
					</tr>
				</table>
			</td>
			
			
			<td width="50%" valign="top">
				<table class="cabinet-inputs-tbl" border="0">
					<tr>
						<th>'.$_CONST['ИНДЕКС'].'<i class="req">*</i>:</th>
						<td><input type="text" name="index" id="index" placeholder="'.$_CONST['ИНДЕКС'].'" value="'.$u->attrs['index'].'"></td>
					</tr>
					<tr>
						<th>'.$_CONST['АДРЕС'].'<i class="req">*</i>: </th>
						<td><input type="text" name="address" id="address" placeholder="'.$_CONST['АДРЕС'].'" value="'.$u->attrs['address'].'"></td>
					</tr>
				</table>
			</td>
			
		</tr>
		<tr>
			<td colspan="2"><hr></td>
		</tr>
		<tr>
		
			<td width="50%" valign="top">
				<table class="cabinet-inputs-tbl" border="0">
					<tr>
						<th>'.$_CONST['КРЕДИТНОЕ ПЛЕЧО'].'<i class="req">*</i>: </th>
						<td>'.User::creditPlechoInput($u->attrs['credit_plecho']).'</td>
					</tr>
				</table>
			</td>
			
			<td width="50%" valign="top">
				<table class="cabinet-inputs-tbl" border="0">
					<tr>
						<th>'.$_CONST['ВИД СЧЁТА'].'<i class="req">*</i>: </th>
						<td>'.User::accountTypeInput($u->attrs['account_type']).'</td>
					</tr>
				</table>
			</td>
			
		</tr>
		<tr>
			<td colspan="2"></td>
		</tr>
		<tr>
		
			<td width="50%" valign="top">
				<table class="cabinet-inputs-tbl" border="0">
					<tr>
						<th>'.$_CONST['ВИД ДЕЯТЕЛЬНОСТИ'].'<i class="req">*</i>: </th>
						<td><input type="text" name="kind_of_activity" id="kind_of_activity" placeholder="'.$_CONST['ВИД ДЕЯТЕЛЬНОСТИ'].'" value="'.$u->attrs['kind_of_activity'].'"></td>
					</tr>
					<tr>
						<th>'.$_CONST['ДОЛЖНОСТЬ'].'<i class="req">*</i>: </th>
						<td><input type="text" name="position" id="position" placeholder="'.$_CONST['ДОЛЖНОСТЬ'].'" value="'.$u->attrs['position'].'"></td>
					</tr>
					<tr>
						<th>'.$_CONST['НАЗВАНИЕ ВАШЕГО БАНКА'].'<i class="req">*</i>: </th>
						<td><input type="text" name="bank" id="bank" placeholder="'.$_CONST['НАЗВАНИЕ ВАШЕГО БАНКА'].'" value="'.$u->attrs['bank'].'"></td>
					</tr>
					<tr>
						<th>'.$_CONST['SWIFT'].'<i class="req">*</i>: </th>
						<td><input type="text" name="swift" id="swift" placeholder="'.$_CONST['SWIFT'].'" value="'.$u->attrs['swift'].'"></td>
					</tr>
				</table>
			</td>
			
			
			<td width="50%" valign="top" >
				<table border="0" class="cabinet-inputs-tbl">
					<tr>
						<th style="width: auto; ">'.$_CONST['РАЗМЕР ПЕРВОНАЧАЛЬНОГО ВЗНОСА'].'<i class="req">*</i>:</th>
						<td><input type="text" name="initial_contribution" id="initial_contribution" placeholder="'.$_CONST['РАЗМЕР ПЕРВОНАЧАЛЬНОГО ВЗНОСА'].'" value="'.$u->attrs['initial_contribution'].'"></td>
					</tr>
				</table>
				<table border="0" class="cabinet-inputs-tbl">
					<tr>
						<th style="width: auto; ">'.$_CONST['ИСТОЧНИК КАПИТАЛА'].'<i class="req">*</i>:  </th>
						<td><input type="text" name="capital_source" id="capital_source" placeholder="'.$_CONST['ИСТОЧНИК КАПИТАЛА'].'" value="'.$u->attrs['capital_source'].'"></td>
					</tr>
				</table>
				<table border="0" class="cabinet-inputs-tbl">
					<tr>
						<th style="width: auto; ">'.$_CONST['НОМЕР БАНКОВСКОГО СЧЁТА'].'<i class="req">*</i>: </th>
						<td><input type="text" name="account_no" id="account_no" placeholder="'.$_CONST['НОМЕР БАНКОВСКОГО СЧЁТА'].'" value="'.$u->attrs['account_no'].'"></td>
					</tr>
				</table>
				<table border="0" class="cabinet-inputs-tbl">
					<tr>
						<th style="width: auto; ">'.$_CONST['ГОДОВОЙ ДОХОД'].'<i class="req">*</i>: </th>
						<td><input type="text" name="annual_income" id="annual_income" placeholder="'.$_CONST['ГОДОВОЙ ДОХОД'].'" value="'.$u->attrs['annual_income'].'"></td>
					</tr>
				</table>
			</td>
			
			
		</tr>
		
	
	</table>';
	
	
	
	
	
	
	
	#	галочка условия
	$str.='
	<div style="margin: 30px 0 0 0;" id="i-approve">
		<label class="font-blue"><input type="checkbox" name="agree" id="agree">'.$_CONST['галочка Я ПОДТВЕРЖДАЮ'].'</label>
	</div>';
	
	if(!$u->attrs)
	{
		#	пароль
		$str.='
		<hr>
		<table border="0" class="wrapper-tbl">
			<tr>
				<td valign="top">
					<table border="0" class="cabinet-inputs-tbl" >
						<tr>
							<th style="width: 120px; ">'.$_CONST['ПАРОЛЬ'].'<i class="req">*</i>:</th>
							<td><input type="password" name="pass" id="pass" placeholder="'.$_CONST['ПАРОЛЬ'].'"></td>
						</tr>
						<tr>
							<th style="width: auto; ">'.$_CONST['ПОДТВЕРДИТЕ ПАРОЛЬ'].'<i class="req">*</i>:</th>
							<td><input type="password" name="pass2" id="pass2" placeholder="'.$_CONST['ПОДТВЕРДИТЕ ПАРОЛЬ'].'"></td>
						</tr>
					</table>
				</td>
				<td>
					<table border="0">
						<tr>
							<td width="1" valign="top">
								<img src="/kcaptcha/?'.session_name().'='.session_id().'" id="captcha-pic">
								<br><a href="javascript:void(0)" onclick="$(\'#captcha-pic\').attr(\'src\', \'/kcaptcha/?\'+(new Date()).getTime());" id="re-captcha">'.$_CONST['LBL captcha НЕ ВИЖУ КОД'].'</a>
							</td>
							<td valign="top" style="font-size: 12px;">
								'.$_CONST['LBL captcha ВВЕДИТЕ ТЕКСТ НА ИЗОБРАЖЕНИИ'].'<i class="req">*</i>: <br>
								<input type="text" name="captcha" id="captcha" >
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>';
		
		
		//$str.='<hr>';
		/*$str.='Введите текст на изображении: <br>';
		$str.='<img src="/kcaptcha/?'.session_name().'='.session_id().'" id="captcha-pic">';
		$str.='<br><a href="javascript:void(0)" onclick="$(\'#captcha-pic\').attr(\'src\', \'/kcaptcha/?\'+(new Date()).getTime());">не вижу код</a>';
		$str.='<br><input type="text" name="captcha" id="captcha">';*/
	}
	
	
	$str.='
			<p><input type="submit" name="edit_go_btn" value="ok">  <span class="cabinet-loading-div" style="display: none; ">'.$_CONST['ЗАГРУЗКА'].'</span>';
	
	$str.='	
		</form>
	</div>
	
	
	<div id="cabinet-reg-success" style="display: none;">';
	if(!$u->attrs)
		$str.='Отлично! Вы успешно зарегистрированы! поздравляем!!';
	else 	
		$str.='Ваши данные успешно изменены!';
	$str.='	
	</div>
</div>';
	
	
	
	echo $str;
	
	$html=ob_get_clean();
	
	
	$json['html']=$html;
	$json['error']=$error;
	
	
	echo json_encode($json);
}	







function saveCabinetChanges()
{
	global $_CONST;
	//vd($_REQUEST);
	
	if($id = intval($_REQUEST['id']))
	{
		$u = new User($id);
		if(!$u->attrs)
		{
			die('
			<script>
				window.top.showError("ОШИБКА! Пользователь не найден. ['.$_REQUEST['id'].']", "cabinet-info-div");
				
				window.top.loading(0, \'cabinet-loading-div\', \'fast\');
			</script>');
		}
			
	}
	
	
	$err = $_CONST['ERROR Не все обязательные поля заполнены корректно'];
	if(!$data['name']=mysql_real_escape_string(trim($_REQUEST['name'])))
		$problems[]='name';
		
	if(!$data['surname']=mysql_real_escape_string(trim($_REQUEST['surname'])))
		$problems[]='surname';
	
		
	if(!filter_var( ($data['email']=mysql_real_escape_string(trim($_REQUEST['email']))), FILTER_VALIDATE_EMAIL))
	{
		$problems[]='email';
		
		if(count($problems) == 1)	#	только имейл
			$err = $_CONST['ERROR Пожалуйста, введите корректный E-mail'];
	}
	else	#	проверим емайл на существование
	{
		//vd($_SESSION);
		if(User::emailExists($data['email'], $_SESSION['user']['id']))
		{
			$problems[]='email';
			if(count($problems) == 1)
				$err = $_CONST['ERROR Пользователь с таким E-mail уже существует'];
		}
	}
		
	if(!$data['tel']=mysql_real_escape_string(trim($_REQUEST['tel'])))
		$problems[]='tel';
		
		
	if(!$u->attrs)
	{
		$pass = mysql_real_escape_string(trim($_REQUEST['pass']));
		$pass2 = mysql_real_escape_string(trim($_REQUEST['pass2']));
		if(!$pass || !$pass2 || $pass != $pass2)
		{	
			$problems[] = 'pass';
			$problems[] = 'pass2';
			if(count($problems) == 2)
				$err = $_CONST['ERROR пароли не совпадают'];
		}
	
		if($_REQUEST['captcha'] != $_SESSION['captcha_keystring'])
		{
			$problems[] = 'captcha';
			if(count($problems) == 1)
			{
				$err = $_CONST['ERROR Вы ввели неверный код с картинки'];
			}
		}
	}
	//vd($problems);
	if(count($problems))
	{
		die('
			<script>
				window.top.showError("'.$err.'", "cabinet-info-div");
				window.top.markError(["'.join('", "', $problems).'"])
				
				window.top.$(\'#captcha-pic\').attr(\'src\', \'/kcaptcha/?'.time().'\');
				window.top.loading(0, \'cabinet-loading-div\', \'fast\');
			</script>');
	}
	
	$data['fathername'] = mysql_real_escape_string(trim($_REQUEST['fathername']));
	$data['birthdate'] = intval($_REQUEST['year']).'-'.intval($_REQUEST['month']).'-'.intval($_REQUEST['day']);
	$data['sex'] = intval($_REQUEST['sex']);
	$data['citizenship'] = mysql_real_escape_string(trim($_REQUEST['citizenship']));
	$data['tel'] = mysql_real_escape_string(trim($_REQUEST['tel']));
	$data['tel2'] = mysql_real_escape_string(trim($_REQUEST['tel2']));
	
	$data['country'] = mysql_real_escape_string(trim($_REQUEST['country']));
	$data['city'] = mysql_real_escape_string(trim($_REQUEST['city']));
	$data['index'] = mysql_real_escape_string(trim($_REQUEST['index']));
	$data['address'] = mysql_real_escape_string(trim($_REQUEST['address']));
	
	$data['credit_plecho'] = mysql_real_escape_string(trim($_REQUEST['credit_plecho']));
	$data['account_type'] = mysql_real_escape_string(trim($_REQUEST['account_type']));
	$data['kind_of_activity'] = mysql_real_escape_string(trim($_REQUEST['kind_of_activity']));
	$data['position'] = mysql_real_escape_string(trim($_REQUEST['position']));
	$data['bank'] = mysql_real_escape_string(trim($_REQUEST['bank']));
	$data['swift'] = mysql_real_escape_string(trim($_REQUEST['swift']));
	$data['capital_source'] = mysql_real_escape_string(trim($_REQUEST['capital_source']));
	$data['account_no'] = mysql_real_escape_string(trim($_REQUEST['account_no']));
	$data['annual_income'] = mysql_real_escape_string(trim($_REQUEST['annual_income']));
	
	$data['pass'] = mysql_real_escape_string(trim($_REQUEST['pass']));
	
	$data['ip'] = $_SERVER['REMOTE_ADDR']; 
	
	if(!$u->attrs)
		$sql="INSERT INTO `users` SET";
	else 	
		$sql="UPDATE `users` SET";
	
	$sql.="
	`surname` = '".$data['surname']."'
	, `name` = '".$data['name']."'
	, `fathername` = '".$data['fathername']."'
	, `email` = '".$data['email']."'";
	
	if(!$u->attrs)
	{
		$sql.="
		, `passwd` = '".$data['pass']."'
		, `registration_date` = NOW()
		, `registration_ip` = '".$data['ip']."'
		, `active` = '1'
		";
	}
	
	$sql.="
	
	
	, `birthdate` = '".$data['birthdate']."'
	, `tel` = '".$data['tel']."'
	, `sex` = '".$data['sex']."'
	, `citizenship` = '".$data['citizenship']."'
	, `tel2` = '".$data['tel2']."'
	, `country` = '".$data['country']."'
	, `city` = '".$data['city']."'
	, `index` = '".$data['index']."'
	, `address` = '".$data['address']."'
	, `credit_plecho` = '".$data['credit_plecho']."'
	, `kind_of_activity` = '".$data['kind_of_activity']."'
	, `position` = '".$data['position']."'
	, `bank` = '".$data['bank']."'
	, `swift` = '".$data['swift']."'
	, `capital_source` = '".$data['capital_source']."'
	, `account_no` = '".$data['account_no']."'
	, `annual_income` = '".$data['annual_income']."'
	
	";
	
	if($u->attrs)
		$sql.="WHERE id = '".intval($u->attrs['id'])."'";
	
	mysql_query($sql);
	if($e=mysql_error() )
	{
		die('
			<script>
				window.top.showError("'.$_CONST['ERROR Ошибка при сохранении'].' <br>('.mysql_real_escape_string($e).')", "cabinet-info-div");
				
				window.top.loading(0, \'cabinet-loading-div\', \'fast\');
			</script>');
	}
	else
	{
		
		echo '
		<script>
			// window.top.showNotice("Вы успешно зарегистрированы!", "cabinet-info-div");
			window.top.$("#cabinet-reg-success").slideDown();
			window.top.$("#cabinet-reg-form").slideUp();
			window.top.loading(0, \'cabinet-loading-div\', \'fast\');
		</script>';
	}
}
	




function drawAuthForm()
{
	global $_CONST;
	$error="";
		
	$str='';
	ob_start();
	
	
	//vd($_REQUEST);
	
	$str.='
	<div class="cabinet-auth">
		<form name="cabinet_auth_form" id="cabinet_auth_form" method="post" action="" >
			<table class="cabinet-auth-tbl">
				<tr>
					<th>'.$_CONST['EMAIL'].':</th>
					<td><input type="text" id="auth-email" name="auth_email"></td>
				</tr>
				<tr>
					<th>'.$_CONST['ПАРОЛЬ'].':</th>
					<td><input type="password" id="auth-pass" name="auth_pass"></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<input type="button" name="auth_go_btn" id="cabinet-auth-btn" value="'.$_CONST['ВОЙТИ'].'" onclick="Cabinet.checkAuthForm()">
						<span id="cabinet-auth-loading-div" style="display: none; ">'.$_CONST['ЗАГРУЗКА'].'</span>
						<span id="cabinet-auth-info-div" ></span>
					</td>
				</tr>
			</table>
			
		</form>
	</div>';
	
	
	
	echo $str;
	
	$html=ob_get_clean();
	
	
	$json['html']=$html;
	$json['error']=$error;
	
	
	echo json_encode($json);
}






function auth()
{
	global $_CONST;
	$error="";
		
	$str='';
	ob_start();
	
	
	//vd($_REQUEST);
	$email = trim($_REQUEST['email']);
	$pass = trim($_REQUEST['pass']);
	
	if(!$email)
	{
		$error = $_CONST['ERROR Укажите Ваш e-mail'];
		$str.='<script>$("#auth-email").addClass("error").focus()</script>';
	}
	
	if(!$pass)
	{
		$str.='<script>$("#auth-pass").addClass("error")</script>';
		if(!$error)
		{	
			$error = $_CONST['ERROR Введите пароль'];
			$str.='<script>$("#auth-pass").focus()</script>';
		}
	}
	if(!User::authenticate($email, $pass))
	{
		$error = $_CONST['ERROR Неверный email / пароль'];
	}
	
	echo $str;
	
	$html=ob_get_clean();
	
	
	$json['html']=$html;
	$json['error']=$error;
	
	
	echo json_encode($json);
}







function logout()
{
	unset($_SESSION['user']);
}







?>