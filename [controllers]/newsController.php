<?php


$id=intval($_PARAMS[0]);



if(!$id)	#	СПИСОК
{ 
	$params = Slonne::getParams();
	$year = intval($params['year']);
	$elPP = 7;
	$mainNewsCount = 3;
	$page = intval($params['p']) ? intval($params['p'])-1 : 0;
	
	
	$limit=" LIMIT ".$page*$elPP.", ".$elPP."";
	$clause = ($year? " AND YEAR(dt) = '".$year."'" : "") ;
	
	$news = News::getChildren(0, "", ($year? " AND YEAR(dt) = '".$year."'" : "") );
	$totalElements = count($news); 
	$news = News::getChildren(0, $limit, $clause);

	//vd($news);
	$arr['news'] = $news;
	
	#	вот сейчас будем раскидывать новости по ГЛАВНЫМ и обычным
	if($page == 0 && ( !$year || $year == date('Y')) )
	{
		$arr['mainNews'] = array_slice($news, 0, $mainNewsCount);
		$arr['news'] = array_slice($news, $mainNewsCount);
	}
	//$arr['mainNews'] = array();
	
	
	
	#	работа с годами
	$years [] = 'Все'; 
	for($i = date('Y'); $i> (date('Y')-3); $i-- )
	{
		$years[]=$i;
	}
	
	
	$arr['years'] = $years;
	$arr['chosenYear'] = $year;
	$arr['elPP'] = $elPP;
	$arr['page'] = $page;
	$arr['totalElements'] = $totalElements;
	//$arr = array('news'=>array(0=>$news[0]));
	
	$arr['title'] = !$year ? 'Последние новости' : 'Новости за <b>'.$year.'</b> год:';
	
	Slonne::view('news/newsList.php', $arr);
}





else	#	НОВОСТЬ
{
	$item = new News($id);
	//vd($item);
	#	крошки
	$crumbs = array();
	$crumbs[] = '<a href="/'.$_SESSION['lang'].'/">ГЛАВНАЯ</a>';
	$crumbs[] = '<a href="/'.$_SESSION['lang'].'/news/">Новости</a>';
	if($item->attrs)
		$crumbs[] = $item->attrs['name'];
	
	$arr['crumbs'] = $crumbs;
	$arr['item'] = $item;
		
	Slonne::view('news/newsItem.php', $arr);
}

//require_once('modules/news/news.php'); 
?>