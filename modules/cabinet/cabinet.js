//alert(123)
var Cabinet={}
Cabinet.ajaxPath='/modules/cabinet/cabinet.php'


Cabinet.settings={}



Cabinet.drawDiv=function(containerId)
{
	//alert(containerId)
	var str=''	
	//+'<div id="cabinet-loading-div" style="display: none">загрузка...</div>'
	
	+'<div id="cabinet-content">'+_CONST['ЗАГРУЗКА']+'</div>'
	
	//+'<div id="cabinet-info-div"></div>'
	+'<div id="cabinet-tool-div"></div>'
	
	+'<iframe name="cabinet_iframe" style="display: none ; width: 700px; height: 200px; background: #ececec; border: 1px dashed #000; "></iframe>'

	$('#'+containerId).html(str)
}







Cabinet.drawCabinet=function()
{
	
	
	loading(1, 'cabinet-loading-div', 'fast')
	
	//$('#admins-list-info-div').html('');
	$.ajax({
		url: Cabinet.ajaxPath+'?action=drawCabinet',
		data:Cabinet.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				$('#cabinet-content').html(data.html)
			}
			else
			{
				$('#cabinet-tool-div').html(data.html)
				showError(data.error, 'cabinet-info-div')
			}
			loading(0, 'cabinet-loading-div', 'fast')
			
		},
		error: function (data, textStatus) 
		{
			showError('Ошибка сервера.. попробуйте позднее', 'cabinet-info-div')
		}
		
	});	
}







Cabinet.checkCabinetData=function(edit)
{
	//	стираем ерроры
	$('#cabinet_edit_form input').each(function(n,element){
		$(element).removeClass('error')
	});
	$('#cabinet-info-div').html('')
	
	//alert($('#agree').attr('checked') == '')
	if($('#agree').attr('checked') != 'checked')
	{
		alert(_CONST['ERROR галочка Я ПОДТВЕРЖДАЮ'])
		return
	}
	
	
	
	var problems = [] 
	var err = _CONST['ERROR Не все обязательные поля заполнены корректно']
	
	if($('#surname').val() == '')
		problems.push('surname')
		
	if($('#name').val() == '')
		problems.push('name')
		
	if($('#email').val() == '')
		problems.push('email')
		
	if($('#tel').val() == '')
		problems.push('tel')
		
	if(!edit)
	{
		var pass=$('#pass').val()
		var pass2=$('#pass2').val()
		if(pass == '' || pass2 == '' /*|| (pass != pass2)*/ )
		{
			problems.push('pass');
			problems.push('pass2');
			/*if(problems.length == 2)
				err = 'Введённые пароли не совпадают!'*/
		}
		
		/*if($('#captcha').val() == '')
			problems.push('captcha')*/
	}
	
	if(problems.length > 0)
	{
		var msg=''
		for(var i in problems)
		{
			//msg+='<br>- '+problems[i]
			$('#'+problems[i]).addClass('error')
		}
			
		showError(err+msg, 'cabinet-info-div')
		//highlight(problems)
	}
	else
	{
		loading(1, 'cabinet-loading-div', 'fast')
		document.forms.cabinet_edit_form.submit()
	}
	
}









Cabinet.drawAuthDiv=function(containerId)
{
	//alert(containerId)
	var str=''	
	//+'<div id="cabinet-loading-div" style="display: none">загрузка...</div>'
	
	+'<div id="cabinet-auth-content">'+_CONST['ЗАГРУЗКА']+'</div>'
	
	//+'<div id="cabinet-auth-info-div"></div>'
	+'<div id="cabinet-auth-tool-div"></div>'
	
	+'<iframe name="cabinet_auth_iframe" style="display: none; width: 700px; height: 200px; background: #ececec; border: 1px dashed #000; "></iframe>'

	$('#'+containerId).html(str)
}






Cabinet.drawAuthForm = function()
{
	
	loading(1, 'cabinet-loading-div', 'fast')
	
	//$('#admins-list-info-div').html('');
	$.ajax({
		url: Cabinet.ajaxPath+'?action=drawAuthForm',
		data:Cabinet.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				$('#cabinet-auth-content').html(data.html)
			}
			else
			{
				$('#cabinet-auth-tool-div').html(data.html)
				showError(data.error, 'cabinet-info-div')
			}
			loading(0, 'cabinet-loading-div', 'fast')
			
		},
		error: function (data, textStatus) 
		{
			showError('Ошибка сервера.. попробуйте позднее', 'cabinet-info-div')
		}
			
	});	
	
}




Cabinet.checkAuthForm = function()
{
	$('#cabinet_auth_form input').each(function(n,element){
		$(element).removeClass('error')
	});
	$('#cabinet-auth-info-div').html('')
	
	var email=$('#auth-email').val()
	var pass=$('#auth-pass').val()
	var err = ''
	
	//alert(email)
		
	if(email == '')
	{
		$('#auth-email').addClass('error')
		err = _CONST['ERROR Укажите Ваш e-mail']
		$('#auth-email').focus()
	}
	
	if(pass == '')
	{
		$('#auth-pass').addClass('error')
		if(err == '')
		{
			err = _CONST['ERROR Введите пароль']
			$('#auth-pass').focus()
		}
	}
	
	if(err != '')
	{
		showError(err, 'cabinet-auth-info-div')
	}
	else
	{
		loading(1, 'cabinet-auth-loading-div', 'fast')
		$.ajax({
			url: Cabinet.ajaxPath+'?action=auth&email='+email+'&pass='+pass,
			data:Cabinet.settings,
			success: function (data, textStatus) 
			{
				//alert(data);return;
				eval('data='+data);
				if(data.error == '')
				{
					location.reload(); 
				}
				else
				{
					showError(data.error, 'cabinet-auth-info-div')
				}
				$('#cabinet-auth-tool-div').html(data.html)
				loading(0, 'cabinet-auth-loading-div', 'fast')
				
			},
			error: function (data, textStatus) 
			{
				showError('Ошибка сервера.. попробуйте позднее', 'cabinet-info-div')
			}
				
		});	
	}
		
}








Cabinet.logout=function()
{
	$.get( Cabinet.ajaxPath+'?action=logout', function( data ) {
		location.reload(); 
	});
}




