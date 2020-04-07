<?php
$p = $MODEL['p'];
$crumbs = $MODEL['crumbs'];
?>




<div class="crumbs">
	<span class="item"><?=join('</span><span class="item">', $crumbs)?></span>
</div>

<?php
if($p->attrs)
{?>
	<div class="page">
		<h1><?=$p->attrs['name']?></h1>
		<span class="text"><?=$p->attrs['descr']?></span>
	</div>
<?php 	
} 
else
{?>
	Раздел не найден.
<?php 	
}
?>