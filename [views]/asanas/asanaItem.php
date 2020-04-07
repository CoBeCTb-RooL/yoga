<?php
$item = $MODEL['item'];
$crumbs = $MODEL['crumbs'];
$program = $MODEL['program'];
$asanas = $MODEL['asanas'];
$prevAsana = $MODEL['prevAsana'];
$nextAsana = $MODEL['nextAsana'];
//vd($item);
?>
<div class="crumbs">
	<span class="item"><?=join('</span><span class="item">', $crumbs)?></span>
</div>



<?php
if($program->attrs)
{?>
	<h1 class="program-heading"><?=$program->attrs['name']?> </h1>
<?php 	
} 
?>


<?php	
#	ЕСЛИ ИДЁТ ПРОГРАММА - ВЫВОД ДРУГИХ АСАН 
if($program->attrs)
{
	$i=0;
	?>
	<div id="program-asanas-list">
		<?php
		foreach($asanas as $key=>$val) 
		{
			$link = '/'.$_SESSION['lang'].'/'.$_GLOBALS['module'].'/'.$program->urlPiece().'/'.$val->urlPiece().'';
			?>
			<a href="<?=$link?>" class="<?=($item->attrs['id'] == $val->attrs['id'] ? 'active' : '')?>"><span class="cipra"><?=++$i?>. </span><?=$val->attrs['name']?> <span class="num"> (<?=$val->attrs['num']?>)</span></a>
		<?php 	
		}
		?>
	</div>
<?php 		
}
?>


<?php
// 
?>
<div class="asana" >
	
	<h1>
		<span class="num">
			<?=$item->attrs['num']?>.
			 
		</span>
			<?=$item->attrs['name']?>
			<?php 
			if($program->attrs)
			{?>
				<span class="num2">(<?=$MODEL['currentNum']?> из <?=count($program->asanas)?>)</span>
			<?php 
			} 
			?>
	</h1>
	<?
	if($item->attrs['instruction'])
	{?>
		<div class="instruction"><?=$item->attrs['instruction']?></div>
	<?php 		
	}?>
	
	
	<div class="purpose-and-nav-wrapper">
	<?php 
		if($item->attrs['purpose'])
		{?>
		<div class="purpose"><?=$item->attrs['purpose']?></div>
		<?php 		
		}?>
	
		<div class="nav">
		<?php
		if($prevAsana)
		{
			$link = '/'.$_SESSION['lang'].'/'.$_GLOBALS['module'].'/'.$program->urlPiece().'/'.$prevAsana->urlPiece().'';
			?>
			<a class="prev" href="<?=$link ?>">&larr; <?=$prevAsana->attrs['name']?></a>	
		<?php 
		} 
		?>
		<?php
		if($nextAsana)
		{
			$link = '/'.$_SESSION['lang'].'/'.$_GLOBALS['module'].'/'.$program->urlPiece().'/'.$nextAsana->urlPiece().'';
			?>
			<a class="next" href="<?=$link ?>"><?=$nextAsana->attrs['name']?> &rarr; </a>	
		<?php 
		} 
		?>
		</div>
	
	</div>
	
	<div class="clear"></div>
	<div class="text">
		<p><?=$item->attrs['descr']?></p>
	</div>
	
	
</div>

<div class="clear"></div>