<?php
require_once('../../config.php');
require_once(ROOT.'/header.php');



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
		
		
		
		
	case "floatForm":
			$f = new FloatFormStatic();
			$f->submit();		
		break;	
		
	
		
		
		


		
		
}




class Form
{
	function getEmails()
	{
		return array(
						'tsop.tya@gmail.com',
						'cobectb_rool@list.ru',
					);
	}

	function errorStatic($formId, $errors)
	{
		/*$backtrace = debug_backtrace(); 
		vd($backtrace);*/
		die('
			<script>
				window.top.showError("'.$errors[0]['msg'].'", "'.$formId.' .info");
				window.top.loading(0, "'.$formId.' .loading", "fast");
				errors = '.json_encode($errors).';
				for(var i in errors)
					window.top.$(\'#'.$formId.' input[name="\'+errors[i].name+\'"]\').addClass(\'error\');
			</script>');
	}
	
	
	function successStatic($formId)
	{
		echo '
		<script>
			window.top.$("#'.$formId.'-success").slideDown();
			window.top.$("#'.$formId.'").slideUp();
			
			window.top.loading(0, \''.$formId.' .loading\', \'fast\');
		</script>';
	}
	
	
	
	function sendEmails($emails, $subject, $msg)
	{
		foreach($emails as $key=>$eml)
		{
			vd($eml);
			Funx::sendMail($eml, 'robot@'.$_SERVER['HTTP_HOST'], $subject, $msg);
		}
	}
	
	
}





/*************************************************/
/*************************************************/
/*************************************************/
/*************************************************/
class BottomFormStatic extends Form  {
	
	const FORM_ID = 'bottom-form'; 
	
	static $emails = array();
	
	function getEmails()
	{
		if(count(self::$emails))
			return self::$emails;
		return parent::getEmails();
	}


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
				$errors[] = $val;
			}
		}
		
		#	ЗДЕСЬ МОГУТ БЫТЬ ДОП. ПРОВЕРКИ
		
		if(count($errors))
		{
			self::errorStatic(self::FORM_ID, $errors);			
		}
		else 
		{
			$this->send();
			self::successStatic(self::FORM_ID);
		}
	}
	
	
	
	
	function send()
	{
		global $_CONST, $_CONFIG;
		
		$subject = 'Заявка с сайта '.$_SERVER['HTTP_HOST'];
		
		#	сообщение
		$msg.='
		<h3>Заявка с сайта '.$_SERVER['HTTP_HOST'].'</h3>
		<div>Имя: <b>'.$_REQUEST['name'].'</b></div>
		<div>Телефон: <b>'.$_REQUEST['tel'].'</b></div>
		';

		$emails = self::getEmails();
		parent::sendEmails($emails, $subject, $msg);
	}
	

	
}






/*************************************************/
/*************************************************/
/*************************************************/
/*************************************************/
class FloatFormStatic extends Form {
	
	const FORM_ID = 'float-form'; 
	
	static $emails = array('qwe@mail.ru',);
	
	function getEmails()
	{
		if(count(self::$emails))
			return self::$emails;
		return parent::getEmails();
	}


	function submit()
	{
		global $_CONST;
		
		$fields = array( 
						array('name'=>'name', 'msg'=>'Пожалуйста, введите Ваше имя.'),
						array('name'=>'email', 'msg'=>'Пожалуйста, введите Ваш e-mail.'),  
						array('name'=>'phone', 'msg'=>'Пожалуйста, введите Ваш телефон.'),
					);
		#	здесь будут скапливаться проблемы (массив)
		$errors = array();
		foreach($fields as $key=>$val)
		{
			if(!$data[$val['name']]=mysql_real_escape_string(trim($_REQUEST[$val['name']])))
			{
				$errors[] = $val;
			}
		}
		
		#	ЗДЕСЬ МОГУТ БЫТЬ ДОП. ПРОВЕРКИ
		if(filter_var(trim($_REQUEST['email']), FILTER_VALIDATE_EMAIL) === false)
			$errors[] = array('name'=>'email', 'msg'=>'Пожалуйста, укажите ваш e-mail корректно!');
		
		
		if(count($errors))
		{
			self::errorStatic(self::FORM_ID, $errors);			
		}
		
		else 
		{
			$this->send();
			self::successStatic(self::FORM_ID);
		}		
	}
	
	
	
	
	function send()
	{
		global $_CONST, $_CONFIG;
		
		$subject = 'Заявка с сайта '.$_SERVER['HTTP_HOST'];
		
		#	сообщение
		$msg.='
		<h3>Заявка с сайта '.$_SERVER['HTTP_HOST'].'</h3>
		<div>Имя: <b>'.$_REQUEST['name'].'</b></div>
		<div>E-mail: <b>'.$_REQUEST['email'].'</b></div>
		<div>Телефон: <b>'.$_REQUEST['phone'].'</b></div>
		';

		$emails = self::getEmails();
		parent::sendEmails($emails, $subject, $msg);
	}
	

	
}




























?>