<?php
$banks = Page::getPages(50); 
//vd($banks);



?>

<div class="banks-list">
	<?php
	foreach($banks as $key=>$bank)
	{
		$link = '/'.$_SESSION['lang'].'/pages/'.$p->attrs['id'].'_'.str2url($p->attrs['name']).'/'.$bank->attrs['id'].'_'.str2url($bank->attrs['name']).'';
	?>
		<a href="<?=$link?>" <?=($bank->attrs['id'] == intval($_PARAMS[1]) ? ' class="active"' : '' )?>><?=$bank->attrs['name']?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php 
	} 
	?>
</div>




<?php
#	отрисовываем банк
if($bankId = intval($_PARAMS['1']))
{
	$bank = new Page($bankId);
	if($bank->attrs)
	{
		//vd($bank);
		?>
		
		<div class="bank">
			<span class="name"><?=$bank->attrs['name']?></span>
			<div class="content"><?=$bank->attrs['descr']?></div>
		</div>
		
		
		
		
	<?php 	
	}
	else
	{
		$str.='Раздел не найден.';
	}
} 
?>


		<?
		#	текст под банками
		$text = new Page(53);
		?>
		<table class="bank-text-below" >
			<tr>
				<td width="1" valign="top" style="padding: 6px 14px 6px 0; "><img src="/images/suitcase.jpg" alt="" /></td>
				<td valign="top" style="font-size: 13px;"><?=$text->attrs['descr']?></td>
			</tr>
		</table>
		<?php
		#	аккордеоны 
		echo Funx::accordeon(53);
		?>
		
		
		