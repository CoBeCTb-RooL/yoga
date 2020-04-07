<?php 
$yearsToShow = Diagram::getYearsToShow();

$str.='


<h2 class="diag-links">
	ДОВЕРИТЕЛЬНОЕ УПРАВЛЕНИЕ ';
foreach($yearsToShow as $key=>$val)
	$str.='<a href="#year_'.$val.'" id="diag-link-diag-'.$val.'" onclick="switchDiagram(\'diag\', '.$val.'); return false;" class="">'.$val.' ГОД</a> '; 
$str.='
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
INDEX TOP 20 ';
foreach($yearsToShow as $key=>$val)
	$str.='<a href="#year_'.$val.'" id="diag-link-index-'.$val.'" onclick="switchDiagram(\'index\', '.$val.'); return false;" class="">'.$val.' ГОД</a> ';
$str.='
</h2>';



foreach(Diagram::$types as $diagCode=>$diagName)
{

	foreach($yearsToShow as $key=>$val)
	{
		$str.='
		<div style="display: none; " id="diag-'.$diagCode.'-'.$val.'" class="diag-table">'.Diagram::draw($diagCode, $val).'</div>';
	}
}
//$str.=Diagram::draw(date('Y'));


#	чтоб при запуске открылось
$str.='
<script>
		switchDiagram("diag", '.$yearsToShow[0].');
</script>';

echo $str; 
?>