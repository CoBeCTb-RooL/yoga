



var Constants={}
Constants.ajaxPath='modules/constants/constants.php'


Constants.settings={}



Constants.drawDiv=function(containerId)
{
	var str=''
		//+Constants.ajaxPath+'<br>'
	//	+'<input type="button" value="list()" onclick="Constants.list()" ><input type="button" value="Constants.edit()" onclick="Constants.edit()" >'
	+'<div class="const" id="const-list-div"></div>'
	+'<div class="const" id="const-edit-div"></div>'
	
	+'<div id="const-loading-div" style="display: none">загрузка...</div>'
	
	+'<div id="const-info-div"></div>'
	+'<iframe name="const_iframe" style="display: none; width: 700px; height: 200px; background: #ececec; border: 1px dashed #000; "></iframe>'

	$('#'+containerId).html(str)
}







Constants.list=function()
{
	switchTo('const-list-div', 'const')
	
	loading(1, 'const-loading-div', 'fast')
	
	$('#const-list-info-div').html('');
	$.ajax({
		url: Constants.ajaxPath+'?action=list',
		data:Constants.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				$('#const-list-div').html(data.html)
				loading(0, 'const-loading-div', 'fast')
				//alert(data.required)
				//notice('ПаНКи - ХоОЙ! ', 'const-info-div')
			}
			else
			{
				$('#const-tool-div').html(data.html)
				//List.showError(data.error)
				error(data.error, 'const-info-div')
			}
			
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'const-info-div')
		}
		
	});	
}










Constants.edit=function(id)
{
	
	
	loading(1, 'const-loading-div', 'fast')
	
	
	
	$.ajax({
		url: Constants.ajaxPath+'?action=edit'+(typeof id != 'undefined'?'&id='+id:''),
		data:Constants.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				$('#const-edit-div').html(data.html)
				loading(0, 'const-loading-div', 'fast')
			
				
				//alert(data.required)
				//notice('ПаНКи - ХоОЙ! ', 'const-info-div')
			}
			else
			{
				error(data.error, 'const-info-div')
				//alert(data.error)
			}
			
			switchTo('const-edit-div', 'const')
			
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'const-info-div')
		}
		
	});	
}










Constants.delete=function(id)
{
	if(!confirm("Уверены? "))
		return
	
	
	
	loading(1, 'const-loading-div', 'fast')
	
	//$('#const-list-info-div').html('');
	$.ajax({
		url: Constants.ajaxPath+'?action=delete&id='+id,
		data:Constants.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				Constants.list();
			//	notice('ПаНКи - ХоОоооОЙ! ', 'const-info-div')
				notice('Константа удалена! ', 'const-info-div')
			}
			else
			{
				error(data.error, 'const-info-div')
			}
			$('#const-tool-div').html(data.html)
			loading(0, 'const-loading-div', 'fast')
			
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'const-info-div')
		}
		
	});	
}








