<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
require_once(ROOT.'/header.php');

vd($_REQUEST);


$action=$_REQUEST['action'];

//usleep(500000);

switch($action)
{
	default:
			echo '{"error":"AJAX(\"'.$_SERVER['PHP_SELF'].'\")::no action!'.($action?' ('.$action.')':'').'"}';
		break;
		
		
		
	case "bottomFormSubmit":
			$f = new BottomFormStatic();
			$f->submit();		
		break;	
		
	
		
		
		

	case "drawBank":
			$f = new BankForm();
			$f->draw();
		break;
		
	case "submitBankForm":
			$f = new BankForm();
			$f->submit();
		break;	

		
		
		
}










/*************************************************/
/*************************************************/
/*************************************************/
/*************************************************/
class bottomFormStatic {
	
	const FORM_ID = 'bottom-form'; 
	static $emails = array(
						'tsop.tya@gmail.com',
					);

	function submit()
	{
		global $_CONST;
		
		$fields = array( 
						array('name'=>'name', 'msg'=>'Пожалуйста, введите Ваше имя.'),  
						array('name'=>'tel', 'msg'=>'Пожалуйста, введите Ваш телефон.'),
					);
		#	здесь будут скапливаться проблемы (массив)
		$errors = array();
					
		foreach($fields as $key=>$val)
		{
			if(!$data[$val['name']]=mysql_real_escape_string(trim($_REQUEST[$val['name']])))
			{
				#	для отображения ошибок
				$val['name'] = self::FORM_ID.'-'.$val['name'];
				$errors[] = $val;
			}
		}
		
		#	здесь могут быть доп. проверки
		
		if(count($errors))
		{
			die('
				<script>
					window.top.showError("'.$errors[0]['msg'].'", "'.self::FORM_ID.'-info");
					window.top.loading(0, "'.self::FORM_ID.'-loading", "fast");
					errors = '.json_encode($errors).';
					for(var i in errors)
						$(\'#\'+errors[i].name).addClass(\'error\')
				</script>');			
		}
		
		$this->send();
		echo '
		<script>
			window.top.$("#'.self::FORM_ID.'-success").slideDown();
			window.top.$("#'.self::FORM_ID.'").slideUp();
			
			window.top.loading(0, \''.self::FORM_ID.'-loading\', \'fast\');
		</script>';
		
		
	}
	
	
	
	
	function send()
	{
		global $_CONST, $_CONFIG;
		
		$subject = 'Заявка с сайта '.$_SERVER['HTTP_HOST'];
		$title = 'Заявка с сайта '.$_SERVER['HTTP_HOST'];
		
		#	сообщение
		$msg.='
		<h1>'.$title.'</h1>
		<div>Имя: <b>'.$_REQUEST[$this->settings['field_prefix'].'name'].'</b></div>
		<div>Телефон: <b>'.$_REQUEST[$this->settings['field_prefix'].'tel'].'</b></div>
		';

		foreach(self::$emails as $key=>$eml)
		{
			Funx::sendMail($eml, 'robot@'.$_SERVER['HTTP_HOST'], $subject, $msg);
		}
	}
	

	
}








/*************************************************/
/*************************************************/
/*************************************************/
/*************************************************/
class BankForm {
	
	
	public $settings = array(
		'loadingDiv' 	=> 'bank-loading-div',
		'infoDiv' 		=> 'bank-info-div',
		'contentDiv' 	=> 'bank-content',
		'toolDiv' 		=> 'bank-tool-div',
		'iframe' 		=> 'bank_iframe',
	
		'form_name' 				=> 'bank_form',
		'action'					=> 'submitBankForm',
		'field_prefix' 				=> 'bank_',
		'onsubmit_function_name'	=> 'Forms.Bank.check',
		'success_div_id'			=> 'bank-success',
	);
	
	
	
	function draw()
	{
		global $_CONST;
		
		$error='';
		$str='';
		ob_start();
		//vd($_REQUEST['arg']);
		$subsection = $_REQUEST['subsection'];
		
		$str.='
		<div id="reg-form-header">
			<div style="text-align: center; margin: 0 0 20px 0;"><img src="/images/logo-blue.jpg"></div>

		</div>';
		
		$str.='	
			<form name="'.$this->settings['form_name'].'" id="'.$this->settings['form_name'].'" method="post" action="/include/forms/forms.php" target="'.$this->settings['iframe'].'" onsubmit="'.$this->settings['onsubmit_function_name'].'(\''.$subsection.'\'); return false; ">
				<input type="hidden" name="action" value="'.$this->settings['action'].'">
				<input type="hidden" name="subsection" value="'.$subsection.'">
				<input type="hidden" name="send" value="">
				';
		
		
			
		
		$str.='
		<table class="form-table" border="0" width="90%">
						
			<tr>
				<th></th>
				<td>';
		
		$str.='
		<select name="lico" class="full-width">';
		foreach(Funx::$lica[$_SESSION['lang']] as $key=>$val)
		{
			$str.='
			<option value="'.$key.'">'.$val.'</option>';
		}
		$str.='
		</select>';
		
		$str.='</td>
			</tr>
			
			<tr>
				<th>'.$_CONST['ИМЯ'].'<i class="req">*</i>:</th>
				<td><input type="text" name="'.$this->settings['field_prefix'].'name" id="'.$this->settings['field_prefix'].'name" class="full-width" ></td>
			</tr>
			
			<tr>
				<th>'.$_CONST['EMAIL'].'<i class="req">*</i>:</th>
				<td><input type="text" name="'.$this->settings['field_prefix'].'email" id="'.$this->settings['field_prefix'].'email" class="full-width" ></td>
			</tr>
			
			<tr>
				<th>'.$_CONST['ТЕЛЕФОН'].'<i class="req">*</i>:</th>
				<td><input type="text" name="'.$this->settings['field_prefix'].'tel" id="'.$this->settings['field_prefix'].'tel" class="full-width" ></td>
			</tr>

		</table>';
		
		
		$str.='
				<p><input type="submit" name="edit_go_btn" value="Отправить" class="btn" onclick="document.forms.'.$this->settings['form_name'].'.send.value=0;" >  <span class="cabinet-loading-div" style="display: none; ">'.$_CONST['ЗАГРУЗКА'].'</span>
				';
		
		$str.='	
			</form>
		</div>
		';
		
		#	див саксесса
		$p = new Page(255);
		$str.='
		<div id="'.$this->settings['success_div_id'].'" class="success" style="display: none; margin: 20px 0 0 0; width: 310px;">
			'.$p->attrs['descr'].'
		</div>';
		
		echo $str;
		$html=ob_get_clean();
		
		$json['html']=$html;
		$json['error']=$error;
		
		echo json_encode($json);
	}

	
	
	
	function submit()
	{
		global $_CONST;
		
		//vd($_REQUEST);
		$subsection = $_REQUEST['subsection'];
		
		$fields = array( 
						$this->settings['field_prefix'].'name', 
						$this->settings['field_prefix'].'email', 
						$this->settings['field_prefix'].'tel', 
					);
			
		$err = $_CONST['ERROR Не все обязательные поля заполнены корректно'];
		
		foreach($fields as $key=>$val)
		{
			if(!$data[$val]=mysql_real_escape_string(trim($_REQUEST[$val])))
			$problems[]=$val;
		}
		
		if(!filter_var( ($data[$this->settings['field_prefix'].'email']=mysql_real_escape_string(trim($_REQUEST[$this->settings['field_prefix'].'email']))), FILTER_VALIDATE_EMAIL))
		{
			$problems[]=$this->settings['field_prefix'].'email';
			
			if(count($problems) == 1)	#	только имейл
				$err = $_CONST['ERROR Пожалуйста, введите корректный E-mail'];
		}
		
		//vd($problems);
		if(count($problems))
		{
			die('
				<script>
					window.top.showError("'.$err.'", "'.$this->settings['infoDiv'].'");
					window.top.markError(["'.join('", "', $problems).'"])
					
					//window.top.$(\'#'.$this->settings['field_prefix'].'captcha_pic\').attr(\'src\', \'/kcaptcha/?'.time().'\');
					window.top.loading(0, \''.$this->settings['loadingDiv'].'\', \'fast\');
				</script>');
		}
		
		

		$this->send();
		echo '
		<script>
			window.top.$("#'.$this->settings['success_div_id'].'").slideDown();
			window.top.$("#'.$this->settings['form_name'].'").slideUp();
			
			//window.top.showNotice("Вы успешно зарегистрированы!", "'.$this->settings['infoDiv'].'");
			window.top.loading(0, \''.$this->settings['loadingDiv'].'\', \'fast\');
		</script>';
		
		
	}
	
	
	
	
	function send()
	{
		global $_CONST, $_CONFIG;
		
		$subsection = $_REQUEST['subsection'];
		//vd($_REQUEST);
		
		#	кому шлём
		//$emls[] = 'tsop.tya@gmail.com';
		$emls[] = 'bankaccount@waltonuniversal.com';
		
		$subject = 'Форма БАНК';
		$title = 'Форма БАНК';
		
		#	сообщение
		$msg.='
		<h1>'.$title.'</h1>
		<div><b>'.Funx::$lica[$_SESSION['lang']][$_REQUEST['lico']].'</b></div>
		<div>Имя: <b>'.$_REQUEST[$this->settings['field_prefix'].'name'].'</b></div>
		<div>E-mail: <a href="mailto:'.$_REQUEST[$this->settings['field_prefix'].'email'].'">'.$_REQUEST[$this->settings['field_prefix'].'email'].'</a></div>
		<div>Телефон: <b>'.$_REQUEST[$this->settings['field_prefix'].'tel'].'</b></div>
		';
		
		
		
		foreach($emls as $key=>$eml)
		{
			Funx::sendMail($eml, 'robot@'.$_SERVER['HTTP_HOST'], $subject, $msg);
		}
	}
	

	
}








?>