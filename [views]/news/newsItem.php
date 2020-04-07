<?php
$item = $MODEL['item'];
$crumbs = $MODEL['crumbs'];
?>
<div class="crumbs">
	<span class="item"><?=join('</span><span class="item">', $crumbs)?></span>
</div>

<div class="news-el" >
	<h1 style="font-size: 29px;"><?=$item->attrs['name']?></h1>
	<span class="date"><?=Funx::mkDate($item->attrs['dt'])?></span>
	<span class="text">
		<img width="250" src="/upload/images/<?=$item->attrs['pic']?>">
		<p><?=$item->attrs['text']?></p>
		<div class="clear"></div>
	</span>
</div>