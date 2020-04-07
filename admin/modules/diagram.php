<style>
.years a, .types a {border: 1px solid #122a62; padding: 3px 5px;}
.years a.active, .types a.active{background: #B1B9CC; font-weight: bold; }
</style>
<?
$year = intval($_REQUEST['year']);
$type=$_REQUEST['type'];

$str.='
<div class="types">';
foreach(Diagram::$types as $key=>$val)
{
	$str.='
	<a href="?section='.$_REQUEST['section'].'&type='.$key.'&year='.$year.'" '.($type == $key?' class="active" ':'').'>'.$val.'</a>';
}
$str.='
</div>';



if($type)
{
	$str.='
	<div class="years" style="margin: 20px 0;">';
	for($y = date('Y'); $y >= Diagram::YEAR_OF_START; $y--)
	{
		$str.='
		<a '.($y==$year?' class="active" ':'').' href="?section='.$_REQUEST['section'].'&type='.$type.'&year='.$y.'">'.$y.'</a>';
	}
	$str.='
	</div>';
	
	if($year)
	{
		
		
		
		if($_REQUEST['go_btn'])
		{
			foreach($_REQUEST['data'] as $key=>$val)
			{
				$arr[$key] = floatval($val);
			}
			//vd($arr);
			$data = json_encode($arr);
			//vd($data);
			
			$sql="SELECT * FROM diagram WHERE type='".mysql_real_escape_string($type)."' AND year=".$year."";
			$qr=mysql_query($sql);
			echo mysql_error();
			if(mysql_num_rows($qr))
			{
				$sql = "UPDATE diagram SET data='".mysql_real_escape_string($data)."' WHERE year='".$year."' AND type='".mysql_real_escape_string($type)."'  ";
			}
			else
			{
				$sql="INSERT into diagram (type, year, data) values('".mysql_real_escape_string($type)."', '".$year."', '".mysql_real_escape_string($data)."')";
			}
			
			
//			$sql="REPLACE into diagram (type, year, data) values('".mysql_real_escape_string($type)."', '".$year."', '".mysql_real_escape_string($data)."')";
			//vd($sql);
			mysql_query($sql);
			echo mysql_error();
			
			$info = '<div class="">СОХРАНЕНО!</div>
			<script>notice("Сохранено!")</script>';
		}
		
		
		$str.='
		<h2>'.$year.' год</h2>
		<span style="font-size: 10px;">Чтобы описать дробую часть - используйте точки. 
		<br>Пример: <b>6.12</b>, <b>3.04</b></span><p>';
		
		$data = Diagram::getData($type, $year);
		$str.='
		<form method="post" action="?section='.$_REQUEST['section'].'&type='.$type.'&year='.$year.'">
			<table>';
		foreach(Funx::$months['ru'] as $key=>$val)
		{
			$str.='
				<tr>
					<td align="right">'.$val[0].'</td>
					<td><input type="text" name="data['.$key.']" value="'.($data[$key]?$data[$key]:'').'" style="width: 40px;"></td>
				</tr>';
			
		}
		$str.='
			</table>
		
			<input type="submit" name="go_btn" value="Сохранить" class="button" style="margin: 25px 0 0 0;">
		</form>';
		
		$str.='<p>'.$info;
	}
}

echo $str;







?>