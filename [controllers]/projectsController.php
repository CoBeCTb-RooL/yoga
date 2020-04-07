<?php
$arr = array();

$params = Slonne::getParams();
$elPP = 3;
$page = intval($params['p']) ? intval($params['p'])-1 : 0;

$arr['elPP'] = $elPP;
$arr['page'] = $page;

$limit=" LIMIT ".$page*$elPP.", ".$elPP."";

//function getPages($pid, $lang, $limit)


$projects = Page::getChildren(261);
$totalElements = count($projects);

$arr['totalElements'] = $totalElements;

$projects = Page::getChildren(261, $limit);
$arr['projects'] = $projects;

//vd($arr);

Slonne::view('projects/projects.php', $arr);




?>


