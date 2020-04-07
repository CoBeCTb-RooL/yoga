<?php 
$section = $_PARAMS[0];
$subsection = $_PARAMS[1];

#	крошки
echo '
<div class="crumbs">';
$crumbs = array();
$crumbs[] = '<a href="/'.$_SESSION['lang'].'/">WALTON</a>';

if($subsection != 'decline')
	$crumbs[] = $_CONST['ПЕРЕДАТЬ СЧЁТ В ДОВЕРИТЕЛЬНОЕ УПРАВЛЕНИЕ'];
else 
	$crumbs[] = $_CONST['ОТКАЗ ОТ ДОВЕРИТЕЛЬНОГО УПРАВЛЕНИЯ'];
	
echo join('<span class="crumbs-delimiter">\\</span>', $crumbs);
echo '
</div>';


echo '
<div id="form-wrapper"></div>';

switch($section)
{
	case 'trust_management':	#	ДОВЕРИТЕЛЬНЫЕ УПРАВЛЕНИЯ
		$arg = '';
		if($subsection == 'decline')
			$arg = 'decline';
		echo '<script>Forms.TrustManagementForm.drawDiv("form-wrapper")</script>';
		echo '<script>Forms.TrustManagementForm.init("'.$arg.'")</script>';
	break;
	
	
	
	case 'index_top_20':	#	INDEX TOP 20
		$arg = '';
		if($subsection == 'replenish')
			$arg = 'replenish';
		if($subsection == 'withdraw')
			$arg = 'withdraw';
		echo '<script>Forms.IndexTop20Form.drawDiv("form-wrapper")</script>';
		echo '<script>Forms.IndexTop20Form.init("'.$arg.'")</script>';
	break;
	
	
	
	default: 
		echo 'uNHaNDLeD_FoRM_eRRoR';
	break;
}


?>









