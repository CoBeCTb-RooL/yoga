<?php
$model = array();

$activeId = intval($_PARAMS[0]);
$subActiveId = intval($_PARAMS[1]);
$model['activeId'] = $activeId;
$model['subActiveId'] = $subActiveId;


#	крошки
$crumbs = array();
//$crumbs[] = '<a href="/'.$_SESSION['lang'].'/">ГЛАВНАЯ</a>';
//$crumbs[] = '<a href="/'.$_SESSION['lang'].'/'.$module.'/">Услуги</a>';
$crumbs[] = 'Услуги';

$subService = new Page($subActiveId);
$service = new Page($activeId);

if($subService->attrs)
{
	//$crumbs[] = '<a href="/'.$_SESSION['lang'].'/'.$module.'/'.$service->attrs['id'].'_'.str2url($service->attrs['name']).'">'.$service->attrs['name'].'</a>';
	$crumbs[] = $service->attrs['name'];
	//$crumbs[] = $subService->attrs['name'];
}
elseif($service->attrs)
{
	//$crumbs[] = $service->attrs['name'];
}

$model['crumbs'] = $crumbs;

if($subActiveId)
	$service = new Page($subActiveId);
elseif($activeId)
	$service = new Page($activeId);
else
	$service = new Page(260);
	
$info['name'] = $service->attrs['name'];
$info['descr'] = $service->attrs['descr'];
$model['info'] = $info;

$services = Page::getChildren(260);
foreach($services as $key=>$val)
{
	$tmp = array();
	$tmp['id'] = $val->attrs['id'];
	$tmp['name'] = $val->attrs['name'];
	$tmp['href'] = '/'.$_SESSION['lang'].'/'.$module.'/'.$tmp['id'].'_'.str2url($tmp['name']);
	
	$subs = Page::getChildren($tmp['id']);
	foreach($subs as $key2=>$val2)
	{
		//vd($val2);
		$tmp2 = array();
		$tmp2['id'] = $val2->attrs['id'];
		$tmp2['name'] = $val2->attrs['name'];
		$tmp2['href'] = '/'.$_SESSION['lang'].'/'.$module.'/'.$tmp['id'].'_'.str2url($tmp['name']).'/'.$tmp2['id'].'_'.str2url($tmp2['name']);
		
		$tmp['subs'][] = $tmp2;
	}
	
	$arr[]=$tmp;
}

$services = $arr;
$model['services'] = $arr;

//vd($services);





Slonne::view('services/servicesView.php', $model);




?>


