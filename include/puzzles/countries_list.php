<?php
#	$countriesRazdelId инициализируется в pages
#	перед вызовом пазла
$countries = Page::getPages($countriesRazdelId); 

?>

<div class="countries-list">
	<?php
	foreach($countries as $key=>$country)
	{
		$link = '/'.$_SESSION['lang'].'/pages/'.$p->attrs['id'].'_'.str2url($p->attrs['name']).'/'.$country->attrs['id'].'_'.str2url($country->attrs['name']).'';
		$img = '/upload/images/'.$country->attrs['flag'];
		//vd(ROOT.$img);
		if(!$country->attrs['flag'])
			$img = '/images/no-flag.jpg';
	?>
	<div class="country-wrapper">
		<a href="<?=$link?>"><img src="<?=$img?>" /></a>
		<a href="<?=$link?>" <?=($country->attrs['id'] == intval($_PARAMS[1]) ? ' class="active"' : '' )?>><?=mb_strtoupper($country->attrs['name'], 'utf-8')?></a>
	</div>
	<?php 
	} 
	?>
</div>
