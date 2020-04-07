



var EE={}
EE.ajaxPath='modules/essence_edit/essenceEdit.php'


EE.settings={}

EE.settings['essence']=''
EE.settings['owner_type']=''
	


EE.drawDiv=function(containerId)
{
	var str=''
		//+EE.ajaxPath+'<br>'
		+'<input type="button" value="list()" onclick="EE.list()" ><input type="button" value="editEssence()" onclick="EE.editEssence()" >'
	+'<div class="eee" id="ee-list-div"></div>'
	+'<div class="ee" id="ee-edit-div"></div>'
	
	+'<div class="ee" id="ee-fields-list-div"></div>'
	+'<div class="ee" id="ee-fields-edit-div"></div>'
	
	+'<div id="ee-loading-div" style="display: none">загрузка...</div>'
	
	+'<div id="ee-info-div"></div>'
	+'<div id="ee-tool-div"></div>'
	
	+'<iframe name="ee_iframe" style="width: 700px; height: 200px; background: #ececec; border: 1px dashed #000; "></iframe>'

	$('#'+containerId).html(str)
}


EE.required={}






EE.list=function()
{
	switchTo('ee-list-div', 'ee')
	
	loading(1, 'ee-loading-div', 'fast')
	
	$('#ee-list-info-div').html('');
	$.ajax({
		url: EE.ajaxPath+'?action=list',
		data:EE.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				$('#ee-list-div').html(data.html)
				loading(0, 'ee-loading-div', 'fast')
				//alert(data.required)
				//notice('ПаНКи - ХоОЙ! ', 'ee-info-div')
			}
			else
			{
				$('#ee-tool-div').html(data.html)
				//List.showError(data.error)
				error(data.error, 'ee-info-div')
			}
			
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'ee-info-div')
		}
		
	});	
}








EE.deleteEssence=function(id)
{
	if(!confirm("Уверены? Все данные будут безвозвратно удалены!"))
		return
	
	
	loading(1, 'ee-loading-div', 'fast')
	
	//$('#ee-list-info-div').html('');
	$.ajax({
		url: EE.ajaxPath+'?action=deleteEssence&id='+id,
		data:EE.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				EE.list();
			//	notice('ПаНКи - ХоОоооОЙ! ', 'ee-info-div')
			}
			else
			{
				error(data.error, 'ee-info-div')
			}
			$('#ee-tool-div').html(data.html)
			loading(0, 'ee-loading-div', 'fast')
			
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'ee-info-div')
		}
		
	});	
}










EE.editEssence=function(id)
{
	
	
	loading(1, 'EE-loading-div', 'fast')
	
	
	
	$.ajax({
		url: EE.ajaxPath+'?action=editEssence'+(typeof id != 'undefined'?'&id='+id:''),
		data:EE.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				$('#ee-edit-div').html(data.html)
				loading(0, 'ee-loading-div', 'fast')
				EE.required=data.required;
				//alert(data.required)
				//notice('ПаНКи - ХоОЙ! ', 'ee-info-div')
			}
			else
			{
				error(data.error, 'ee-info-div')
				//alert(data.error)
			}
			
			switchTo('ee-edit-div', 'ee')
			
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'ee-info-div')
		}
		
	});	
}








EE.checkNewEssenceForm=function()
{
	//document.forms.essence_Essence_form.submit()
	//alert(Essence.required.length)
	
	var val
	var problems = []
	
	var name=$('#ee-name').val()
	var code=$('#ee-code').val()
	
	if(name=='')
		problems.push('ee-name')
	if(code=='')
		problems.push('ee-code')
	
	
	if(problems.length > 0)
	{
		error('Не все обязательные поля заполнены!', 'Essence-info-div')
		highlight(problems)
	}
	else document.forms.essence_edit_form.submit()
}






var i=1

EE.fieldsList=function(code, owner_type)
{
	//alert(EE.settings['essence'])
	
	if(typeof code == 'undefined')
		code=EE.settings['essence']
	if(typeof owner_type == 'undefined')
		owner_type=EE.settings['owner_type']
	
	
	EE.settings['essence']=code
	EE.settings['owner_type']=owner_type
	//alert(owner_type)
	//location.href='#/action::fields/owner_type::'+owner_type
	
	/*if(i!=1)
	{
		alert(123)
		return
	}
	i=0*/
	
	/*if(typeof owner_type == 'undefined')
		owner_type='elements'*/
	//if($('#ee-fields-list-div').css('display') == none )
		
	loading(1, 'ee-loading-div', 'fast')
	
	$('#ee-fields-list-div').html('').css('display', 'none')
	
	$('#ee-fields-list-info-div').html('');
	$.ajax({
		url: EE.ajaxPath+'?action=fieldsList',
		data:EE.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				$('#ee-fields-list-div').html(data.html)
				loading(0, 'ee-loading-div', 'fast')
				//alert(data.required)
				//notice('ПаНКи - ХоОЙ! ', 'ee-info-div')
			}
			else
			{
				$('#ee-tool-div').html(data.html)
				//List.showError(data.error)
				error(data.error, 'ee-info-div')
			}
			switchTo('ee-fields-list-div', 'ee')
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'ee-info-div')
		}
		
	});	
}


















EE.fieldEdit=function(essence, owner_type, id)
{
	
	//alert(type)
	loading(1, 'EE-loading-div', 'fast')
	
	
	
	$.ajax({
		url: EE.ajaxPath+'?action=fieldEdit&essence='+essence+'&owner_type='+owner_type+''+(typeof id != 'undefined'?'&id='+id:''),
		//data:EE.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				$('#ee-fields-edit-div').html(data.html)
				loading(0, 'ee-loading-div', 'fast')
				EE.required=data.required;
				//alert(data.required)
				//notice('ПаНКи - ХоОЙ! ', 'ee-info-div')
			}
			else
			{
				error(data.error, 'ee-info-div')
				//alert(data.error)
			}
			
			switchTo('ee-fields-edit-div', 'ee')
			
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'ee-info-div')
		}
		
	});	
}




EE.deleteField=function(id)
{
	if(!confirm("Уверены? Это поле будет безвозвратно уничтожено ко всем чертям!"))
		return
	
	
	loading(1, 'ee-loading-div', 'fast')
	
	//$('#ee-list-info-div').html('');
	$.ajax({
		url: EE.ajaxPath+'?action=deleteField&id='+id,
		data:EE.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				//notice('ПаНКи - ХоОоооОЙ! ', 'ee-info-div')
				notice(data.html, 'ee-info-div')
				
				
				EE.fieldsList(EE.settings['essence'], EE.settings['owner_type'])
			}
			else
			{
				error(data.error, 'ee-info-div')
			}
			$('#ee-tool-div').html(data.html)
			loading(0, 'ee-loading-div', 'fast')
			
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'ee-info-div')
		}
		
	});	
}








EE.fixLangVersion=function(l, ess, type)
{
	//alert(l);
	//return
	loading(1, 'ee-loading-div', 'fast')
	
	
	//$('#ee-list-info-div').html('');
	$.ajax({
		url: EE.ajaxPath+'?action=fixLangVersion&l='+l+'&ess='+ess+'&type='+type,
		data:EE.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				//notice('ПаНКи - ХоОоооОЙ! ', 'ee-info-div')
				notice('ok', 'ee-info-div')
				
				EE.list()
				//EE.fieldsList(EE.settings['essence'], EE.settings['owner_type'])
			}
			else
			{
				error(data.error, 'ee-info-div')
			}
			$('#ee-tool-div').html(data.html)
			loading(0, 'ee-loading-div', 'fast')
			
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'ee-info-div')
		}
		
	});	
}





EE.changeFieldType = function(type)
{
	//alert(type)
	$('.edit-fields-dop-div').slideUp()
	if(type != '')
		$('#'+type+'-div').slideDown();
}


