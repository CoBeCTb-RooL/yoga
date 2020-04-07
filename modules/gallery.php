<?php 
$_GLOBALS['TITLE'] = 'Галерея';

$id=intval($_PARAMS[0]);


if(!$id)
{
	$albums = Entity::getEntities('gallery', 'elements', $_SESSION['lang'], $pid=0, $limit='');
	
	echo '
	<h1>Альбомы:</h1>
	<div class="gallery-albums-wrapper">';
	foreach($albums as $key=>$val)
	{
		$tmp=array_pop(array_reverse($val->attrs['pics'], true));
		$pic = $tmp['src'];
		
		echo '
		<div class="gallery-album">
			<a href="'.$val->url().'">
				<img src="/resize.php?file=/upload/images/'.$pic.'&width=200"  />
				'.$val->attrs['name'].'
			</a>
		</div>
		';
	}
	echo '
	</div>';
	
	$_GLOBALS['TITLE'] = 'Галерея';
}



else
{
	$album = new Entity('gallery', $id, 'elements', $_SESSION['lang']);

	echo '
	<a href="javascript:history.go(-1)">&larr; назад</a>		
		<h1>'.$album->attrs['name'].'</h1>';
	$photos=$album->attrs['pics'];
	echo'
	<div class="gallery-photos-wrapper">';
	foreach($photos as $key=>$val)
	{
		echo '
		<div class="gallery-photo">
			<a href="/upload/images/'.$val['src'].'" onclick="return hs.expand(this)" class="highslide ">
				<img src="/resize.php?file=/upload/images/'.$val['src'].'&width=200"  />
				
			</a>
			<span>'.$val['title'].'</span>
		</div>';
	}
	echo'
	</div>';
	
	$_GLOBALS['TITLE'] = $album->attrs['name'].' - Галерея';
}






?>