<?php
$mainNews = $MODEL['mainNews'];
$news = $MODEL['news'];
$years = $MODEL['years'];
$chosenYear = $MODEL['chosenYear'];
$title = $MODEL['title'];
?>


<div class="news-section">
	
	<h1>Новости</h1>
	
	<!--список годов-->
	<div class="years">
		<h3>Архив новостей</h3>
		<div class="years-wrapper">
		<?php
		
		foreach($years as $key=>$val) 
		{?>
			<a class="<?=($chosenYear == $val || (!intval($chosenYear) && $val == 'Все') ? "active" : "")?>" href="/<?=$_SESSION['lang']?>/news/year_<?=$val?>"><?=$val?></a>
		<?php 	
		}
		?>
		</div>
	</div>
	<!--//список годов-->
	
	
	<div class="news-wrapper">
	
		<!--список ГЛАВНЫХ новостей-->
		<h3><?=$title?></h3>
		<?php 
		if(count($mainNews))
		{?>
		<div class="news-list">
			<?php 
			foreach($mainNews as $key=>$val)
			{
				//$link = '/'.$_SESSION['lang'].'/news/'.$val->attrs['id'].'_'.str2url($val->attrs['name']).'';
				$link = $val->url();
				?>
			<div class="item">
				
				<a href="<?=$link?>"><img src="/resize.php?file=/upload/images/<?=$val->attrs['pic']?>&width=130"></a>
				<h2><a href="<?=$link?>"><?=$val->attrs['name']?></a></h2>
				<span class="anons"><?=$val->attrs['anons']?></span>
				
				<div class="clear"></div>
				<span class="date"><?=Funx::mkDate($val->attrs['dt'])?></span>
			</div>
			<?php 
			}
			?>
		</div>
		<?php 
		}
		else
		{?>
			<!--Новостей нет.-->
		<?php 	
		} ?>
		<!--//список ГЛАВНЫХ новостей-->
		
		
		
		<div class="clear"></div>
		
		
		<!--список новостей-->
		<div class="news-list-mini">
		<?php 
		if(count($news))
		{
			foreach($news as $key=>$val)
			{
				$link = '/'.$_SESSION['lang'].'/news/'.$val->attrs['id'].'_'.str2url($val->attrs['name']).'';
				?>
			<div class="item">
				<span class="date"><?=Funx::mkDate($val->attrs['dt'], 'numeric')?> г.</span>
				<div class="info"> 
					<h2><a href="<?=$link?>"><?=$val->attrs['name']?></a></h2>
				</div>
				<div class="clear"></div>
			</div>
		<?php 
			}
		}
		else
		{?>
			Новостей нет.
		<?php 	
		} 
		?>
		
	
		<?=Funx::drawPages($MODEL['totalElements'], $MODEL['page'], $MODEL['elPP']);?>
		
		</div>
		<!--//список новостей-->
	
	</div>
	
	
	
	
	<div class="clear"></div>
</div>