<?
//vd(ROOT);
$str.='Welcome to <b>SLoNNE CMS</b>! Fast, easy, no excess!';


$modules = Module::getModules();
//vd($modules);
//vd($_SESSION);


$str.='
<div id="index-modules-container">';
foreach($modules as $key=>$val)
{
	/*if(!$admin->group->attrs['privileges_arr'][$val->attrs['code']])
		continue; */
	if(!$_SESSION['admin']['privileges'][$val->attrs['code']])
		continue;
	
	$str.='
		<a  href="?section='.$key.'"><span class="module-icon">'.$val->attrs['icon'].'</span><br> '.$val->attrs['name'].'</a>
	';
}
$str.='
</div>';


echo $str;
?> 