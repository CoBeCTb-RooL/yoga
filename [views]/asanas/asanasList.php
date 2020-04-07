<?php
$asanas = $MODEL['asanas'];
$program = $MODEL['program'];
//vd($asanas);
?>



<?php
if($program->attrs)
{?>
	<a href="javascript:history.go(-1)">&larr; назад</a><p>
<?php 
} 
?>

<div class="asanas">
	<h1><?=($program->attrs ? $program->attrs['name'] : 'Асаны')?></h1>
	<div class="wrapper">
<?php 
$i=0;
foreach($asanas as $key=>$val)
{?>
		<div class="item">
		<?php 
		if($program->attrs)
		{
			$link = '/'.$_SESSION['lang'].'/'.$_GLOBALS['module'].'/'.$program->urlPiece().'/'.$val->urlPiece().'';
			?>
			<h2><?=++$i?>. <a href="<?=$link?>"> <?=$val->attrs['name']?> <span class="num">(<?=$val->attrs['num']?>)</span></a> </h2>
		<?php 
		}
		else
		{
			$link = $val->url($_GLOBALS['module']);?>
			<h2><a href="<?=$link?>"><span class="num"><?=$val->attrs['num']?>.</span> <?=$val->attrs['name']?></a></h2>
		<?php 
		}?>
		</div>
<?php 
}
?>
	
		<?=Funx::drawPages($MODEL['totalElements'], $MODEL['page'], $MODEL['elPP']);?>
	</div>
	
	
	<div class="clear"></div>
</div>