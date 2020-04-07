<?php
$projects = $MODEL['projects']; 

//vd($MODEL);
//vd($projects);
?>
<h1 class="header">Проекты</h1>
<div class="projects">
<?php
foreach($projects as $key=>$val)
{?>
	<div class="item">
		<img src="/resize.php?file=/upload/images/<?=$val->attrs['pic']?>&width=240" alt="" />
		<div class="info">
			<div class="title"><?=$val->attrs['name']?></div>
			<div class="descr"><?=$val->attrs['descr']?></div>
		</div>
		<div class="clear"></div>
	</div>
<?php 	
} 
?>

	<?=Funx::drawPages($MODEL['totalElements'], $MODEL['page'], $MODEL['elPP']);?>
</div>