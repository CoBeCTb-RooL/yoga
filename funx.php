<?
function vd($a)
{
	echo '<pre>';
	var_dump($a);
	echo '</pre>';
}





function translit($string) {

    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
        
        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    );

    return strtr($string, $converter);

}



function str2url($str) {

    // переводим в транслит
    $str = translit($str);

    // в нижний регистр
    $str = strtolower($str);

    // заменям все ненужное нам на "-"
    $str = preg_replace('~[^-a-z0-9_\']+~u', '-', $str);

    // удаляем начальные и конечные '-'
    $str = trim($str, "-");

    return $str;
}





function drawPages($total_elements, $pg, $onepage, $onclick)
{
	$sym['beginning']='начало <<';
	$sym['prev']='&larr; предыдущие';
	
	
	$sym['end']='>> конец';
	$sym['next']='предыдущие &rarr;';
	
	
	
	//vd($total_elements);
//$total_elements = mysql_num_rows(mysql_query($search_sql));
	$print_pages = '';
	$totalPages=ceil($total_elements/$onepage);
    if($totalPages>1)
      {
        $print_pages .= '<div align="center" class="pages-div">';
        if($pg>0) 
        {
        	$print_pages .= '
        	<a  title="начало"  href="javascript: void(0)" onclick="'.getOnclick(1, $onclick).'">'.$sym['beginning'].'</a>
        	<a id="arrow" title="предыдущая" href="javascript: void(0)" onclick="'.getOnclick( ($pg), $onclick).'">'.$sym['prev'].'</a>
        	';
        }
        else
        {
        	$print_pages.='
        	<div class="pages-inactive">'.$sym['beginning'].'</div>
        	<div class="pages-inactive">'.$sym['prev'].'</div>
        	';
        }
        
        $index = $pg>=6 ? ($pg+1<ceil($total_elements/$onepage)-5 ? $pg-5 : (ceil($total_elements/$onepage)>11 ? ceil($total_elements/$onepage)-11 : 0)) : 0;
        for($i=1; $i<=(ceil($total_elements/$onepage)<11 ? ceil($total_elements/$onepage) : 11); $i++)
           {
             $index++;
             if($index>ceil($total_elements/$onepage)) break;
             if($index==$pg+1)
               {
                 $print_pages .= "<div>".$index."</div> ";
               }else{
                 $print_pages .= '<a href="javascript: void(0)" onclick="'.getOnclick($index, $onclick).'">'.$index.'</a> ';
               }

           }
        if($pg+1<ceil($total_elements/$onepage)) 
        {
        	$print_pages .= '
        	<a id="arrow" title="следующая"  href="javascript: void(0)" onclick="'.getOnclick( ($pg+2), $onclick).'">'.$sym['next'].'</a>
        	<a  title="конец"  href="javascript: void(0)" onclick="'.getOnclick($totalPages, $onclick).'">'.$sym['end'].'</a>
        	 ';
        }
        else
        {
        	$print_pages.='
        	<div class="pages-inactive">'.$sym['next'].'</div>
        	<div class="pages-inactive">'.$sym['end'].'</div>
        	';
        }
        $print_pages .= "</div><div style=\"clear:both;\">&nbsp;</div>";
      }

	return  $print_pages;
}




function getOnclick($page, $onclick)
{
	$str=str_replace("###", $page, $onclick);
	
	return $str;
}





function drawPagesSmall($total_elements, $pg, $onepage, $onclick)
{
	$sym['beginning']='<<';
	$sym['prev']='&larr;';
	
	
	$sym['end']='>>';
	$sym['next']='&rarr;';
	
	
	
	//vd($total_elements);
//$total_elements = mysql_num_rows(mysql_query($search_sql));
	$print_pages = '';
	$totalPages=ceil($total_elements/$onepage);
    if($totalPages>1)
      {
        $print_pages .= '<div align="center" class="pages-div">';
        if($pg>0) 
        {
        	$print_pages .= '
        	<a  title="начало"  href="javascript: void(0)" onclick="'.getOnclick(1, $onclick).'">'.$sym['beginning'].'</a>
        	<a id="arrow" title="предыдущая" href="javascript: void(0)" onclick="'.getOnclick( ($pg), $onclick).'">'.$sym['prev'].'</a>
        	';
        }
        else
        {
        	$print_pages.='
        	<div class="pages-inactive">'.$sym['beginning'].'</div>
        	<div class="pages-inactive">'.$sym['prev'].'</div>
        	';
        }
        
        $index = $pg>=6 ? ($pg+1<ceil($total_elements/$onepage)-5 ? $pg-5 : (ceil($total_elements/$onepage)>11 ? ceil($total_elements/$onepage)-11 : 0)) : 0;
        for($i=1; $i<=(ceil($total_elements/$onepage)<11 ? ceil($total_elements/$onepage) : 11); $i++)
           {
             $index++;
             if($index>ceil($total_elements/$onepage)) break;
             if($index==$pg+1)
               {
                 $print_pages .= "<div>".$index."</div> ";
               }else{
                 $print_pages .= '<a href="javascript: void(0)" onclick="'.getOnclick($index, $onclick).'">'.$index.'</a> ';
               }

           }
        if($pg+1<ceil($total_elements/$onepage)) 
        {
        	$print_pages .= '
        	<a id="arrow" title="следующая"  href="javascript: void(0)" onclick="'.getOnclick( ($pg+2), $onclick).'">'.$sym['next'].'</a>
        	<a  title="конец"  href="javascript: void(0)" onclick="'.getOnclick($totalPages, $onclick).'">'.$sym['end'].'</a>
        	 ';
        }
        else
        {
        	$print_pages.='
        	<div class="pages-inactive">'.$sym['next'].'</div>
        	<div class="pages-inactive">'.$sym['end'].'</div>
        	';
        }
        $print_pages .= "</div><div style=\"clear:both; font-size: 1px\">&nbsp;</div>";
      }

	return  $print_pages;
}



?>