<?php


$id=intval($_PARAMS[0]);



if(!$id)	#	СПИСОК
{ 
	$params = Slonne::getParams();
	$elPP = 150;
	$page = intval($params['p']) ? intval($params['p'])-1 : 0;
	
	$limit=" LIMIT ".$page*$elPP.", ".$elPP."";
	
	$asanas = Asana::getChildren(0);
	$totalElements = count($asanas); 
	$asanas = Asana::getChildren(0, $limit);

	//vd(asanas);
	$arr['asanas'] = $asanas;

	$arr['elPP'] = $elPP;
	$arr['page'] = $page;
	$arr['totalElements'] = $totalElements;
	
	Slonne::view('asanas/asanasList.php', $arr);
}





else	#	НОВОСТЬ
{
	$item = new Asana($id);
	//vd($item);
	#	крошки
	$crumbs = array();
	$crumbs[] = '<a href="/'.$_SESSION['lang'].'/">ГЛАВНАЯ</a>';
	$crumbs[] = '<a href="/'.$_SESSION['lang'].'/asanas/">Асаны</a>';
	if($item->attrs)
		$crumbs[] = '<span>'.$item->attrs['name'].'</span>';
	
	$arr['crumbs'] = $crumbs;
	$arr['item'] = $item;
		
	Slonne::view('asanas/asanaItem.php', $arr);
}

//require_once('modules/news/news.php'); 
?>