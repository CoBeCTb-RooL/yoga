<?php
$programs = $MODEL['programs'];
?>

<div id="index">

	<div class="programs-wrapper">
<?php
foreach($programs as $key=>$val)
{	
	
	$link = '/'.$_SESSION['lang'].'/programs/'.$val->urlPiece().'/'.$programs[$key]->asanas[1]->urlPiece().'';
	?>
		<div class="program prog-<?=$key?>"><h1><a href="<?=$link?>"><?=$val->attrs['name']?></a></h1></div>
<?php 
} 
?>
	</div>
	
		
	<h1 id="all"><a href="/<?=$_SESSION['lang']?>/programs/">ВСЕ ГРУППЫ УПРАЖНЕНИЙ</a></h1>
</div>