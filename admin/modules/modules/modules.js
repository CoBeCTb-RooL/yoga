



var Modules={}
Modules.ajaxPath='modules/modules/modules.php'


Modules.settings={}



Modules.drawDiv=function(containerId)
{
	var str=''
		//+Modules.ajaxPath+'<br>'
	//	+'<input type="button" value="list()" onclick="Modules.list()" ><input type="button" value="Modules.edit()" onclick="Modules.edit()" >'
	+'<div class="mod" id="mod-list-div"></div>'
	+'<div class="mod" id="mod-edit-div"></div>'
	
	+'<div class="mod" id="mod-fields-list-div"></div>'
	+'<div class="mod" id="mod-fields-edit-div"></div>'
	
	+'<div id="mod-loading-div" style="display: none">загрузка...</div>'
	
	+'<div id="mod-info-div"></div>'
	+'<div id="mod-tool-div"></div>'
	
	+'<iframe name="mod_iframe" style="display: none; width: 700px; height: 200px; background: #ececec; border: 1px dashed #000; "></iframe>'

	$('#'+containerId).html(str)
}







Modules.list=function()
{
	switchTo('mod-list-div', 'mod')
	
	loading(1, 'mod-loading-div', 'fast')
	
	$('#mod-list-info-div').html('');
	$.ajax({
		url: Modules.ajaxPath+'?action=list',
		data:Modules.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				$('#mod-list-div').html(data.html)
				loading(0, 'mod-loading-div', 'fast')
				//alert(data.required)
				//notice('ПаНКи - ХоОЙ! ', 'mod-info-div')
			}
			else
			{
				$('#mod-tool-div').html(data.html)
				//List.showError(data.error)
				error(data.error, 'mod-info-div')
			}
			
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'mod-info-div')
		}
		
	});	
}










Modules.edit=function(id)
{
	
	
	loading(1, 'modules-loading-div', 'fast')
	
	
	
	$.ajax({
		url: Modules.ajaxPath+'?action=edit'+(typeof id != 'undefined'?'&id='+id:''),
		data:Modules.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				$('#mod-edit-div').html(data.html)
				loading(0, 'mod-loading-div', 'fast')
			
				
				//alert(data.required)
				//notice('ПаНКи - ХоОЙ! ', 'mod-info-div')
			}
			else
			{
				error(data.error, 'mod-info-div')
				//alert(data.error)
			}
			
			switchTo('mod-edit-div', 'mod')
			
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'mod-info-div')
		}
		
	});	
}










Modules.delete=function(id)
{
	if(!confirm("Уверены? "))
		return
	
	
	
	loading(1, 'mod-loading-div', 'fast')
	
	//$('#mod-list-info-div').html('');
	$.ajax({
		url: Modules.ajaxPath+'?action=delete&id='+id,
		data:Modules.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				Modules.list();
			//	notice('ПаНКи - ХоОоооОЙ! ', 'mod-info-div')
				notice('Модуль удалён! ', 'mod-info-div')
			}
			else
			{
				error(data.error, 'mod-info-div')
			}
			$('#mod-tool-div').html(data.html)
			loading(0, 'mod-loading-div', 'fast')
			
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'mod-info-div')
		}
		
	});	
}








