<?php


$programId = intval($_PARAMS[0]);
$asanaId = intval($_PARAMS[1]);


if(!$programId)	#	СПИСОК ПРОГРАМ
{ 
	$programs = Page::getChildren(4);

	$arr['programs'] = $programs;
	
	Slonne::view('programs/programsList.php', $arr);
}





elseif($programId && !$asanaId)		#	СПИСОК АСАН ПРОГРАММЫ
{
	//vd($programId);
	$program = new Program($programId);
	//vd($program);
	
	if($program->attrs)
	{
		$program->getAsanas();
		$asanas = $program->asanas;
	}
	
	$arr['program'] = $program;
	$arr['asanas'] = $asanas;
	Slonne::view('asanas/asanasList.php', $arr);
}	

else	#	АСАНА ПРОГРАММЫ
{
	$program = new Program($programId);
	$program->getAsanas();
	
	$item = new Asana($asanaId);
	
	#	крошки
	$crumbs = array();
	$crumbs[] = '<a href="/'.$_SESSION['lang'].'/">ГЛАВНАЯ</a>';
	$crumbs[] = '<a href="/'.$_SESSION['lang'].'/programs/">Программы</a>';
	$crumbs[] = '<a href="'.$program->url("programs").'">'.$program->attrs['name'].'</a>';
	if($item->attrs)
		$crumbs[] = '<span>'.$item->attrs['name'].'</span>';
		
	#	ссылка НАЗАД, ссылка ВПЕРЁД
	foreach($program->asanas as $key=>$val)
		if($val->attrs['id'] == $item->attrs['id'])
			$MODEL['currentNum'] = $currentNum = $key;
	
	if($program->asanas[$currentNum-1])
		$MODEL['prevAsana'] = $program->asanas[$currentNum-1];
	if($program->asanas[$currentNum+1])
		$MODEL['nextAsana'] = $program->asanas[$currentNum+1];
			
	$MODEL['crumbs'] = $crumbs;
	$MODEL['item'] = $item;
	$MODEL['program'] = $program;
	$MODEL['asanas'] = $program->asanas;
		
	Slonne::view('asanas/asanaItem.php', $MODEL);
}

//require_once('modules/news/news.php'); 
?>