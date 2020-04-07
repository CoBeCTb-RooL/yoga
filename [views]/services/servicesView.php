<?php
$services = $MODEL['services']; 
$activeId = $MODEL['activeId'];
$subActiveId = $MODEL['subActiveId'];
$info = $MODEL['info'];
$crumbs = $MODEL['crumbs'];
?>




<div class="services-module">

	<div class="crumbs">
		<span class="item"><?=join('</span><span class="item">', $crumbs)?></span>
	</div>

	<div class="left-menu">
	<?php
	foreach($services as $key=>$val)
	{?>
		<div id="service-lvl1-<?=$val['id']?>" class="item <?=($val['id'] == $activeId ? 'active' : '')?>" onclick="switchServicesAccordeon(<?=$val['id']?>)" >
			<a href="<?=$val['href']?>" onclick="/*return false*/"><?=$val['name']?></a>
		</div>
		<div class="subs" id="services-subs-<?=$val['id']?>">
			<?php
			foreach($val['subs'] as $key2=>$val2)
			{?>
				<div class="sub-item">
					<a class="<?=($val2['id'] == $subActiveId ? 'active' : '')?>" href="<?=$val2['href']?>"><?=$val2['name']?></a>
				</div>
			<?php 	
			} 
			?>
		</div>
	<?php 
	} 
	?>
	</div>
	<div class="right-text">
		<h1><?=$info['name']?></h1>
		<div class="text"><?=$info['descr']?></div>
	</div>
	
	<div class="clear"></div>
</div>




<script>
var serviceOpened=0
function switchServicesAccordeon(id)
{
	//alert(id)
	if(serviceOpened != id)
	{
		$('.left-menu .subs').slideUp()
		$('#services-subs-'+id).slideDown()
		
		$('.left-menu .item').removeClass('active')
		$('#service-lvl1-'+id).addClass('active')
	}
	else
	{
		$('#services-subs-'+id).slideToggle()
	}
	
	serviceOpened = id
}


$(document).ready(function(){
	serviceOpened = <?=$activeId?>;
	switchServicesAccordeon(serviceOpened)
});
</script>