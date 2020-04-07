<?php
$programs = $MODEL['programs'];
//vd($programs);
?>


<div class="asanas">
	<h1>Группы упражнений</h1>
	<div class="wrapper">
<?php 
foreach($programs as $key=>$val)
{
	$link = $val->url("programs");
	$program = new Program($val->attrs['id']);
	$program->getAsanas();
	$link = '/'.$_SESSION['lang'].'/'.$_GLOBALS['module'].'/'.$program->urlPiece().'/'.$program->asanas[1]->urlPiece().'';
	//vd($program);
?>
		<div class="item">
			<h2><a href="<?=$link?>"><span class="num"></span> <?=$val->attrs['name']?></a></h2>
		</div>
<?php 
}
?>
	
	</div>
	
	
	<div class="clear"></div>
</div>

<div class="clear"></div>