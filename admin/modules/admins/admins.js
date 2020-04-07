



var Admins={}
Admins.ajaxPath='modules/admins/admins.php'


Admins.settings={}



Admins.drawDiv=function(containerId)
{
	var str=''
		//+Admins.ajaxPath+'<br>'
	//	+'<input type="button" value="list()" onclick="Admins.list()" ><input type="button" value="Admins.edit()" onclick="Admins.edit()" >'
		+'<a class="square-link" id="admins-btn" href="javascript:void(0)" onclick="Admins.adminsList()">Администраторы</a>'
		+'<a class="square-link" id="groups-btn" href="javascript:void(0)" onclick="Admins.groupsList()">Группы</a>'
	+'<div class="admins" style="display: none; " id="admins-list-div">admins-list-div</div>'
	+'<div class="admins" style="display: none; " id="admins-edit-div"></div>'
	
	+'<div class="admins" id="groups-list-div" style="display: none; ">groups-list-div</div>'
	+'<div class="admins" id="groups-edit-div" style="display: none; "></div>'
	
	+'<div id="admins-loading-div" style="display: none">загрузка...</div>'
	
	//+'<div id="admins-info-div"></div>'
	+'<div id="admins-tool-div"></div>'
	
	+'<iframe name="admins_iframe" style="display:  ; width: 700px; height: 200px; background: #ececec; border: 1px dashed #000; "></iframe>'

	$('#'+containerId).html(str)
}







Admins.adminsList=function()
{
	switchTo('admins-list-div', 'admins')
	$('.square-link').removeClass('active')
	$('#admins-btn').addClass('active')
	
	loading(1, 'admins-loading-div', 'fast')
	
	//$('#admins-list-info-div').html('');
	$.ajax({
		url: Admins.ajaxPath+'?action=adminsList',
		data:Admins.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				$('#admins-list-div').html(data.html)
				loading(0, 'admins-loading-div', 'fast')
			}
			else
			{
				$('#admins-tool-div').html(data.html)
				error(data.error, 'admins-info-div')
			}
			
			
		},
		error: function (data, textStatus) 
		{
			error('Ошибка сервера.. попробуйте позднее', 'admins-info-div')
		}
		
	});	
}










Admins.adminEdit=function(id)
{
	
	
	loading(1, 'admins-loading-div', 'fast')
	
	
	
	$.ajax({
		url: Admins.ajaxPath+'?action=adminEdit'+(typeof id != 'undefined'?'&id='+id:''),
		data:Admins.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				$('#admins-edit-div').html(data.html)
				loading(0, 'admins-loading-div', 'fast')
			
				
				//alert(data.required)
				//notice('ПаНКи - ХоОЙ! ', 'admins-info-div')
			}
			else
			{
				error(data.error, 'admins-info-div')
				//alert(data.error)
			}
			
			switchTo('admins-edit-div', 'admins')
			
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'admins-info-div')
		}
		
	});	
}










Admins.adminDelete=function(id)
{
	if(!confirm("Уверены? "))
		return
	
	
	
	loading(1, 'admins-loading-div', 'fast')
	
	//$('#admins-list-info-div').html('');
	$.ajax({
		url: Admins.ajaxPath+'?action=adminDelete&id='+id,
		data:Admins.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				Admins.list();
			//	notice('ПаНКи - ХоОоооОЙ! ', 'admins-info-div')
				notice('Модуль удалён! ', 'admins-info-div')
			}
			else
			{
				error(data.error, 'admins-info-div')
			}
			$('#admins-tool-div').html(data.html)
			loading(0, 'admins-loading-div', 'fast')
			
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'admins-info-div')
		}
		
	});	
}










Admins.groupsList=function()
{
	switchTo('groups-list-div', 'admins')
	$('.square-link').removeClass('active')
	$('#groups-btn').addClass('active')
	
	loading(1, 'admins-loading-div', 'fast')
	
//	$('#admins-list-info-div').html('');
	$.ajax({
		url: Admins.ajaxPath+'?action=groupsList',
		data:Admins.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				$('#groups-list-div').html(data.html)
				loading(0, 'admins-loading-div', 'fast')
				//alert(data.required)
				//notice('ПаНКи - ХоОЙ! ', 'admins-info-div')
			}
			else
			{
				$('#admins-tool-div').html(data.html)
				//List.showError(data.error)
				error(data.error, 'admins-info-div')
			}
			
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'admins-info-div')
		}
		
	});	
}









Admins.groupEdit=function(id)
{
	
	
	loading(1, 'admins-loading-div', 'fast')
	
	
	
	$.ajax({
		url: Admins.ajaxPath+'?action=groupEdit'+(typeof id != 'undefined'?'&id='+id:''),
		data:Admins.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				$('#groups-edit-div').html(data.html)
				loading(0, 'admins-loading-div', 'fast')
			
				switchTo('groups-edit-div', 'admins')
				//alert(data.required)
				//notice('ПаНКи - ХоОЙ! ', 'admins-info-div')
			}
			else
			{
				error(data.error, 'admins-info-div')
				//alert(data.error)
			}
			
			
			
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'admins-info-div')
		}
		
	});	
}







Admins.initLoginForm=function()
{
	//alert("initLoginForm")
	
	$.ajax({
		url: Admins.ajaxPath+'?action=initLoginForm',
		
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				$('#login-form').html(data.html)
			
			}
			else
			{
				error(data.error, 'admins-info-div')
				//alert(data.error)
			}

		},
		error: function (data, textStatus) 
		{
	
			error('Ошибка сервера.. попробуйте позднее', 'admins-info-div')
		}
		
	});	
	
	
	
	
}








Admins.authorize=function()
{
	//alert('authorize')
	
	var email=$('#auth-email').val()
	var password=$('#auth-password').val()
	var err=''
	
	if(email=='')
	{
	
		highlight('auth-email')
		var problem=1
		//return
	}	
	if(password == '')
	{
		//error('Введите пароль!', '')
		highlight('auth-password')
		var problem=1
		//return
	}	
	if(problem==1)
	{
		error('Введите данные!')
		return
	}
	
	
	
	loading(1, 'login-form-loading-div', 'fast')
	
	document.forms.login_form.submit();
	
	/*$.ajax({
		
		url: Admins.ajaxPath+'?action=authorize&email='+email+'&password='+password,
		
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
			//	$('#login-form').html(data.html)
				notice('ok')
				highlight('auth-email', true);
				highlight('auth-password', true);
				setTimeout("location.href='?'",500)
			}
			else
			{
				error(data.error, 'admins-info-div')
				//alert(data.error)
			}
			$('#login-form-tool-div').html(data.html)
			loading(0, 'login-form-loading-div', 'fast')

		},
		error: function (data, textStatus) 
		{
	
			error('Ошибка сервера.. попробуйте позднее', 'admins-info-div')
			loading(0, 'login-form-loading-div', 'fast')
		}
		
	});	*/
	
	
	
	
	
}





Admins.logout=function()
{
//	alert('Выйти')
	if(!confirm("Уверены?"))
		return; 
	
	$.ajax({
		
		url: Admins.ajaxPath+'?action=logout',
		
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
			//	$('#login-form').html(data.html)
				location.href="?"
			
			}
			else
			{
				//error(data.error, 'admins-info-div')
				//alert(data.error)
			}
			
				
		},
		error: function (data, textStatus) 
		{
	
			error('Ошибка сервера.. попробуйте позднее', 'admins-info-div')
			
		}
		
	});	
}

/*

Admins.drawLoginForm=function(id)
{
	//alert(123)
	var str=''
	+'	<div id="auth-form-container">'
	+'		<div id="login-inputs-div">'
		+'		<span>E-mail: </span><input type="text" id="auth-email">'
		+'		<br>'
		+'		<span>Пароль: </span><input type="text" id="auth-password">'
	+'		</div>'
//	+'		<div id="login-btn-div">'
		+'	<a id="login-form-go-btn" class="transitional" href="javascript:void(0)" onclick="Admins.authorize()"><i class="fa fa-sign-in"></i></a>'
//	+'		</div>'
	+'	</div>'
		
	$('#'+id).html(str)
}*/

















Admins.switchGlobalPriv = function(priv)
{
	//alert(priv)
	//alert($('#priv-global-'+priv).attr("checked"))
	
	if($('#priv-global-'+priv).attr("checked") == 'checked')
		$('.priv-sub-'+priv).attr('checked', 'checked')
	
	else
		$('.priv-sub-'+priv).removeAttr('checked')
}




Admins.switchGlobalPrivFromSub= function(action, priv)
{
	//alert(priv)
	//alert('.priv-sub-'+priv+' #priv-sub-'+action)
	//alert($('.priv-sub-'+priv+'[id="priv-sub-'+action+'"]').attr("checked"))
	
	if($('.priv-sub-'+priv+'[id="priv-sub-'+action+'"]').attr("checked") == 'checked')
	{
		$('#priv-global-'+priv).attr('checked', 'checked')
	}
	
	/*if($('#priv-global-'+priv).attr("checked") == 'checked')
		$('.priv-sub-'+priv).attr('checked', 'checked')
	
	else
		$('.priv-sub-'+priv).removeAttr('checked')*/
}











/*******	чтоб по нажатию на ентер форма сабмитилась	*********/
$( "#auth-form-container input" ).live( "keydown", function(event) { if(event.which == 13) Admins.authorize() });
