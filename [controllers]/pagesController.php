<?php

$id=intval($_PARAMS[0]); 


if(!$id)	#	СПИСОК
{
	$pages = Page::getPages(1);
	$arr['pages'] = $pages;
	
	Slonne::view('pages/pagesList.php', $arr);
}




else	#	СТАТЬЯ
{
	$p = new Page($id, $_SESSION['lang']);
	$arr['p'] = $p;
	
	#	крошки
	$tree = Page::getTree($p->attrs['id']);
	
	foreach($tree as $key=>$val)
		$treeIds[] = $val->attrs['id'];

	$crumbs = array();
	$crumbs[] = '<a href="/'.$_SESSION['lang'].'/">ГЛАВНАЯ</a>';
	if(in_array(1, $treeIds))	#	если открыт раздел ГЛАВНОГО МЕНЮ, а не левый
	{
		$i = 0;
		foreach($tree as $key=>$val)
		{
			$i++;
			if($key == 1)	#	главное меню
				continue;

			if($i < count($tree))
			{
				$crumbs[] = '<a href="#'.$val->attrs['name'].'">'.$val->attrs['name'].'</a>';
			}
			else 	
				$crumbs[] = ''.$val->attrs['name'].'';
		}
	}
	else	#	открыт левый
	{
		$crumbs[] = ''.$p->attrs['name'].'';
	}
	
	$arr['crumbs'] = $crumbs;
	
	
	Slonne::view('pages/page.php', $arr);
}




//require_once('modules/pages.php');
?>


