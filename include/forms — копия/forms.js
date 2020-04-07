/*
 * Все формы на сайте
 * Все формы сабмитятся в iframe. 
 * ВАЖНО!!! 
 * префикс у ид полей - ИД ФОРМЫ , ТИРЕ и НЭЙМ!!
 * если форма #some-form, то у поля name="name" айдишник должен быть 
 * id="some-form-name"
 * */
var Forms={}
Forms.ajaxPath='/include/forms/forms.php'


Forms.settings={}




/************************************************************************************/
/************************************************************************************/
/************************************************************************************/
/************************************************************************************/
Forms.bottomForm = {}

Forms.bottomForm.FormId = 'bottom-form'	
var qweqwe = 1
Forms.bottomForm.check=function()
{
	var formId=Forms.bottomForm.FormId;
	var errors = []

	//	стираем ерроры
	$('#'+formId+' input, #'+formId+' select').each(function(n,element){
		$(element).removeClass('error')
	});
	
	$('#'+formId+'-info').html('')
	
	//	поля и их ошибки
	var fields = [
		{'name': Forms.bottomForm.FormId+"-name", 'msg':"Пожалуйста, введите Ваше имя."},
		{'name': Forms.bottomForm.FormId+"-tel", 'msg':"Пожалуйста, введите Ваш телефон."}	
	]	
	
	//	проверка
	var field;
	for(var i in fields)
	{
		field = fields[i];
		if($('#'+Forms.bottomForm.FormId+'-'+field.name).val() == '')
			errors.push(field)
	}
	
	alert(qweqwe++)
	if(errors.length > 0)
	{
		for(var i in errors)
			$('#'+Forms.bottomForm.FormId+'-'+errors[i].name).addClass('error')
			
		showError(errors[0].msg, ''+formId+'-info')
	}
	else
	{
		$('#'+formId+'-loading').css('display', 'block')
		return true;
	}
	
}







/************************************************************************************/
/************************************************************************************/
/************************************************************************************/
/************************************************************************************/
Forms.Bank = {}

Forms.Bank.loadingDiv 		= 'bank-loading-div'
Forms.Bank.infoDiv 			= 'bank-info-div'	
Forms.Bank.contentDiv 		= 'bank-content'
Forms.Bank.toolDiv 			= 'bank-tool-div'
Forms.Bank.iframe 			= 'bank_iframe'

Forms.Bank.fieldPrefix 		= 'bank_'
Forms.Bank.formName 		= 'bank_form'	
	

Forms.Bank.drawDiv = function(containerId)
{
	var str=''	
	+'<div id="'+Forms.Bank.contentDiv+'"></div>'
	+'<div id="'+Forms.Bank.loadingDiv+'">'+_CONST['ЗАГРУЗКА']+'</div>'
	+'<div id="'+Forms.Bank.infoDiv+'"></div>'
	+'<div id="'+Forms.Bank.toolDiv+'"></div>'
	+'<iframe name="'+Forms.Bank.iframe +'" style="display: none; width: 700px; height: 200px; background: #ececec; border: 1px dashed #000; "></iframe>'

	$('#'+containerId).html(str)
}


Forms.Bank.init = function(arg)
{
	var action = 'drawBank'	
			
	loading(1, Forms.Bank.loadingDiv, 'fast')
	
	$.ajax({
		url: Forms.ajaxPath+'?action='+action+'&subsection='+arg,
		data:Forms.settings,
		success: function (data, textStatus) 
		{
			eval('data='+data);
			if(data.error == '')
			{
				$('#'+Forms.Bank.contentDiv).html(data.html)
			}
			else
			{
				$('#'+Forms.Bank.toolDiv).html(data.html)
				showError(data.error, Forms.Bank.infoDiv)
			}
			loading(0, Forms.Bank.loadingDiv, 'fast')
			
		},
		error: function (data, textStatus) 
		{
			showError('Ошибка сервера.. попробуйте позднее', Forms.Bank.infoDiv)
		}
		
	});	
}



Forms.Bank.check = function(subsection)
{
	//alert(subsection)
	$('#'+Forms.Bank.formName+' input, #'+Forms.Bank.formName+' select').each(function(n,element){
		$(element).removeClass('error')
	});
	$('#'+Forms.Bank.infoDiv).html('')
	
	var err = false
	
	var fields = [ 
		              Forms.Bank.fieldPrefix+'name', 
		              Forms.Bank.fieldPrefix+'email', 
		              Forms.Bank.fieldPrefix+'tel', 
	             ]
		
	for(var i in fields)
	{
		if($('#'+fields[i]).val() == '')
		{
			$('#'+fields[i]).addClass('error')
			problems = true;
			if(err == '')
				err = _CONST['ERROR Не все обязательные поля заполнены корректно']
		}
	}
	
	if(err != '')
		showError(err, Forms.Bank.infoDiv)
	else
	{
		loading(1, Forms.Bank.loadingDiv, 'fast')
		eval('document.forms.'+Forms.Bank.formName+'.submit()')
	}
}












