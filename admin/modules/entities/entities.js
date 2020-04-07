



var Entities={}
Entities.ajaxPath='modules/entities/entities.php'



Entities.essence=''
Entities.settings={}

Entities.lastLeftAction=''
Entities.lastRightAction=''

var timer;
/*
Entities.drawDiv=function(containerId)
{
	var str=''
	//+'<input type="button" value="грузануть дерево" onclick="Entities.drawTree(0)">'	
	//+'		<input type="button" value="list()" onclick="Entities.list()" ><input type="button" value="edit()" onclick="Entities.edit()" >'
	+'<div style="height: 100%; background: ">'
	
	+'	<div id="entities-tree-div" style="width: 25%; float: left; margin: 10px; background:  ; "></div>'
	
	+'	<div style="float: left; margin: 10px; background: ; ">'
		
		
	
	//+'		<div id="entities-heading-div"></div>'
	+'		<div id="entities-list-div"></div>'
	+'		<div id="entities-edit-div"></div>'
	+'		<div id="entities-loading-div" style="display: none">загрузка...</div>'
	+'		<div id="entities-info-div"></div>'
	

		+'</div>'
		+'<div style="clear: both; "><div>'
		
		+'		<div id="entities-tool-div"></div>'
		+'		<p><iframe name="slonne_edit_frame" style="display: none; width: 700px; height: 400px; background: #ececec; border: 1px dashed #000; "></iframe>'
		
	+'</div>'	
	$('#'+containerId).html(str)
}*/


Entities.required={}



Entities.highlightBlock=function(id)
{
	$('.block-highlighted').removeClass('block-highlighted');
	$('#block-name-wrapper-'+id).addClass('block-highlighted');
}









Entities.init=function()
{
	$.ajax({
		url: Entities.ajaxPath+'?action=init',
		data:Entities.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				
				notice('iNiTiaLiZeD. ', 'entities-info-div')
			}
			else
			{
				error(data.error, 'entities-info-div')
			}
			//alert(data.html)
			$('#entities-container-div').html(data.html)

		},
		error: function (data, textStatus) 
		{
			error('Ошибка сервера.. попробуйте позднее', 'entities-info-div')
		}
		
	});	
}








Entities.initLangsChoice=function()
{
	$.ajax({
		url: Entities.ajaxPath+'?action=initLangsChoice',
		data:Entities.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				
				notice('LaNGS iNiTiaLiZeD. ', 'entities-info-div')
			}
			else
			{
				error(data.error, 'entities-info-div')
			}
			//alert(data.html)
			$('#entities-langs-div').html(data.html)
			//alert(data.html)

		},
		error: function (data, textStatus) 
		{
			error('Ошибка сервера.. попробуйте позднее', 'entities-info-div')
		}
		
	});	
}







Entities.switchLang=function(l)
{

	
	$.ajax({
		url: Entities.ajaxPath+'?action=switchLang&lang='+l,
		data:Entities.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				//Entities.drawTree(0, true);
				
				/*if(Entities.pidToList > 0)
				{
					Entities.list(Entities.pidToList)
				}*/
				//alert(Entities.lastRightAction)
				//alert(Entities.lastLeftAction)
				//alert(Entities.lastLeftAction)
				eval(Entities.lastLeftAction)
				eval(Entities.lastRightAction)
				
				notice('LaNG CHaNGeD. ', 'entities-info-div')
			}
			else
			{
				error(data.error, 'entities-info-div')
			}
			//alert(data.html)
			$('#entities-tool-div').html(data.html)

		},
		error: function (data, textStatus) 
		{
			error('Ошибка сервера.. попробуйте позднее', 'entities-info-div')
		}
		
	});	
}










Entities.pidToList=0



Entities.list=function(pid)
{
	//alert(pid)
	if(typeof pid == 'undefined')
		pid=Entities.pidToList
	
	Entities.pidToList=pid
		
	Entities.highlightBlock(pid)
	
	$('#entities-list-div').slideDown()
	$('#entities-edit-div').slideUp()
	
	loading(1, 'entities-loading-div', 'fast')
	
	//$('#entities-list-info-div').html('');
	
	$('#entities-list-div').css('opacity', '.3')
	
	Entities.lastRightAction = "Entities.list("+Entities.pidToList+")"
	
	
	$.ajax({
		url: Entities.ajaxPath+'?action=list'+(typeof pid != 'undefined' ? '&pid='+pid : ''),
		data:Entities.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				
				
			//	Entities.required=data.required;
				
				/*for(var i in Entities.required)
				{
					alert(Entities.required[i])
				}*/
				//alert(data.required)
				notice('LiST iNiTiaLiZeD. ', 'entities-info-div')
			}
			else
			{
				//$('#entities-tool-div').html(data.html)
				//List.showError(data.error)
				error(data.error, 'entities-info-div')
			}
			$('#entities-list-div').html(data.html)
			$('#entities-heading-div').html(data.heading)
			loading(0, 'entities-loading-div', 'fast')
			$('#entities-list-div').css('opacity', '1')
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'entities-info-div')
		}
		
	});	
}






Entities.loadedBranches=[]


Entities.drawTree=function(pid, forced, page)
{
	//$('#entities-tree-div').slideDown()
	//$('#entities-edit-div').slideUp()
	Entities.lastLeftAction = "Entities.drawTree(0, true)"
		
	Entities.settings['tree_page'] = page
	
	var mustLoad = true;

	// 	ставлю так пока что ! типа если форсед - то всё нах перегружать! 
	//	ато при сохранении тейпа ошибка возникает - не прогружается когда на + жмешь
	
	if(forced === true)
	{
		Entities.loadedBranches=[]
	}
	
	if(forced !== true)
	{
		if($('#block-subs-wrapper-'+pid).css('display') == 'block')
		{
			$('#block-subs-wrapper-'+pid).slideUp('fast')
			$('#tree-expand-btn-'+pid).html('<i class="fa fa-plus-square-o"></i>')
			return
		}
			
		
		
		//	поймём нужно ли грузить дерево
		
		for(var i in Entities.loadedBranches)
			if(Entities.loadedBranches[i] == pid)
				mustLoad = false
	}	
	
	
	
	if(!mustLoad)
	{
		$('#block-subs-wrapper-'+pid).slideDown('fast')
		$('#tree-expand-btn-'+pid).html('<i class="fa fa-minus-square-o"></i>')
		return
	}	
	
	loading(1, 'entities-loading-div', 'fast')
	$('#tree-loading-'+pid).css('display', 'inline')
	
	$.ajax({
		url: Entities.ajaxPath+'?action=tree&pid='+pid,
		data:Entities.settings,
		success: function (data, textStatus) 
		{
			eval('data='+data);
			if(data.error == '')
			{
				Entities.loadedBranches.push(pid)
			
				notice('дерево блоков', 'entities-info-div')
			}
			else
			{
				//$('#entities-tool-div').html(data.html)
				error(data.error, 'entities-info-div')
			}
			
			if(pid == 0)
				$('#entities-tree-div').html(data.html)
			else
				$('#block-subs-wrapper-'+pid).html(data.html).slideDown('fast')
			
			
			$('#tree-expand-btn-'+pid).html('<i class="fa fa-minus-square-o"></i>')
				
			//$('#entities-heading-div').html(data.heading)
			loading(0, 'entities-loading-div', 'fast')
			$('#tree-loading-'+pid).css('display', 'none')
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'entities-info-div')
		}
		
	});	
}









//	если передан ПИД - понятно, что речь о блоках (в частности - о добавлении нового)
Entities.edit=function(id, pid, type)
{
	if(id == '')
		Entities.highlightBlock(pid);
	
	if(type != 'elements' && type != 'blocks')
	{
		error('TYPE_EMPTY_OR_INCORRECT_ERROR', 'entities-info-div')
		return
	}
	
	loading(1, 'entities-loading-div', 'fast')
	$('#entities-edit-div').css('opacity', '.3')
	

	Entities.lastRightAction = "Entities.edit('"+id+"', '"+pid+"', '"+type+"')"
	
	Entities.settings['id'] = id
	Entities.settings['type'] = type
	
	$.ajax({
		url: Entities.ajaxPath+'?action=edit&type='+type+''+(typeof id != 'undefined'?'&id='+id:'')+(typeof pid != 'undefined'?'&pid='+pid:''),
		data:Entities.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				
				
				Entities.required=data.required;
				//alert(data.required)
				notice('eDiTiNG STaRTeD.. ', 'entities-info-div')
				
				$('#entities-edit-div').html(data.html)
				
				$('#entities-edit-div').slideDown()
				$('#entities-list-div').slideUp()
			}
			else
			{
				error(data.error, 'entities-info-div')
				$('#entities-tool-div').html(data.html)
				//alert(data.error)
			}
			
			loading(0, 'entities-loading-div', 'fast')
			$('#entities-edit-div').css('opacity', '1')
			
			
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'entities-info-div')
		}
		
	});	
}








Entities.checkForm=function()
{
	//document.forms.slonne_edit_form.submit(); return; 
	
	//alert(Entities.required.length)
	//alert($('#text').html())
	
	/*for(var i in Entities.required)
	{
		alert(Entities.required[i])
	}*/
	
	var val
	var problems = []
	for(var i in Entities.required)
	{
		//alert(Entities.required[i])
		//alert(Entities.required[i].code)
		if(Entities.required[i].type == 'html' || Entities.required[i].type == 'html_long')
		{
			var oEditor = FCKeditorAPI.GetInstance(Entities.required[i].code) ;
			val = oEditor.GetHTML();
		}
		else
			if(Entities.required[i].type != 'pic')
				val=$('#'+Entities.required[i].code+'').val()
			
		//alert('#'+Entities.required[i]+'')
		
		if(val == '')
			problems.push(Entities.required[i].code)
	}
	if(problems.length > 0)
	{
		var msg=''
		for(var i in problems)
			msg+='<br>- '+problems[i]
			
		error('Не все обязательные поля заполнены!'+msg, 'Essence-info-div')
		highlight(problems)
	}
	else 
		document.forms.slonne_edit_form.submit()
}






Entities.submenuOpened=0


Entities.showSubMenu = function(id)
{
	
	if(id != Entities.submenuOpened)
	{
		$('.tree-submenu').slideUp()
		$('#block-submenu-'+id).slideDown()
		Entities.submenuOpened=id
		
		
	}
	else
	{
		Entities.submenuOpened=0
		$('.tree-submenu').slideUp()
	}
	
}







Entities.delete = function(id, type)
{
	/*alert(id)
	alert(type)
*/
	if(!confirm('Уверены?'))
		return
	
	loading(1, 'entities-loading-div', 'fast')
	
	//$('#entities-list-info-div').html('');
	
	//$('#entities-list-div').css('opacity', '.3')
	
	
	
	$.ajax({
		url: Entities.ajaxPath+'?action=delete&id='+id+'&type='+type,
		data:Entities.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				$('#entities-list-div').slideUp()
				
				/*	нужно скрыть уничтоженный блок	*/
				if(type == 'blocks' || data.joint_fields == 1)
				{
					$('#block-container-'+id).fadeOut()
				}
					
				
			//	Entities.required=data.required;
				
				/*for(var i in Entities.required)
				{
					alert(Entities.required[i])
				}*/
				//alert(data.required)
				notice('Удалено.', 'entities-info-div')
			}
			else
			{
				//$('#entities-tool-div').html(data.html)
				//List.showError(data.error)
				error(data.error, 'entities-info-div')
			}
			$('#entities-tool-div').html(data.html)
			
			loading(0, 'entities-loading-div', 'fast')
			//$('#entities-list-div').css('opacity', '1')
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'entities-info-div')
		}
		
	});	
}







Entities.treeSearch = function(pid)
{
	var word=$('#tree-search-input-'+pid).val()
	
	if(word == '')
		highlight('tree-search-input-'+pid)
	else
	{
		Entities.settings['search_word'] = word
		Entities.drawTree(pid, true)
	}
	
}









Entities.deletePic = function(src, code)
{
	/*alert(id)
	alert(type)
	
*/
	//alert(src)
	//alert(code)
	
	if(!confirm('Уверены?'))
		return
	
	
	$.ajax({
		url: Entities.ajaxPath+'?action=deletePic&src='+src+'&code='+code,
		data:Entities.settings,
		success: function (data, textStatus) 
		{
			//alert(data);return;
			eval('data='+data);
			if(data.error == '')
			{
				//alert(data.html)
			
					
				
			//	Entities.required=data.required;
				
				/*for(var i in Entities.required)
				{
					alert(Entities.required[i])
				}*/
				//alert(data.required)
				notice('Удалено.', 'entities-info-div')
			}
			else
			{
				//$('#entities-tool-div').html(data.html)
				//List.showError(data.error)
				error(data.error, 'entities-info-div')
			}
			$('#entities-tool-div').html(data.html)
			//alert(data.html)
		//	alert('#pic-'+code+'-div')
			$('#pic-'+code+'-div').fadeOut()
			
			loading(0, 'entities-loading-div', 'fast')
			//$('#entities-list-div').css('opacity', '1')
			
		},
		error: function (data, textStatus) 
		{
			//List.showError('Ошибка сервера.. попробуйте позднее')
			//List.loading(0)
			error('Ошибка сервера.. попробуйте позднее', 'entities-info-div')
		}
		
	});	
}










Entities.checkImgToDelete = function(id)
{
	if($('#delete-media-cb-'+id).attr('checked') == 'checked')
		$('#multipic-wrap-'+id).addClass('checked')
	else
		$('#multipic-wrap-'+id).removeClass('checked')
}



