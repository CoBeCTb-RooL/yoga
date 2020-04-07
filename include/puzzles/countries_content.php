<?php
$country = intval($_PARAMS[1]);

$country = new Page($country);
//vd($country);

if($country->attrs)
{
	if($country->attrs['pic'])
		$bg = 'background: url(/upload/images/'.$country->attrs['pic'].')';
	
?>

<div class="country-content">

	<h1 style="<?=$bg?>;background-size: cover;  "><span style="border: 5px solid #fff; padding: 20px 44px ; box-shadow: -1px 1px 0 #000; "><?=mb_strtoupper($country->attrs['name'], 'utf-8')?></span></h1>
	
</div>

<table border="0" width="100%">
	<tr>
		<td valign="top" style="padding: 0 20px 0 0;">

			<div id="countries-accordeon-wrapper">
			<?php
			#	аккордеон
			$pages = Page::getPages($country->attrs['id']);
			$str1='';
			$str1.='
				<table class="page-children" border="0">';
			$i=0;
			foreach($pages as $key=>$val)
			{
				if(!($i++))
					$str1.='<script> $( document ).ready(function() {switchPagesAccordeon('.$val->attrs['id'].')});</script>';
					
				$str1.='
					<tr>
						<td width="1"><img src="/images/suitcase.jpg" width="40" alt="" /></td>
						<td style="padding: 12px 0 0 0; ">	
							<a href="javascript: void(0)" class="title" onclick="switchPagesAccordeon('.$val->attrs['id'].')">'.mb_strtoupper($val->attrs['name'], 'utf-8').'</a>
							<a href="javascript: void(0)" class="anons" id="pages-anons-'.$val->attrs['id'].'" onclick="switchPagesAccordeon('.$val->attrs['id'].')">'.$val->attrs['anons'].'</a>
							
							<div class="pages-accordeon-content" id="pages-accordeon-'.$val->attrs['id'].'">'.$val->attrs['descr'].'</div>
						</td>
					</tr>';
			}
			$str1.='
				</table>';
			
			echo $str1;
			?>
			</div>

		</td>
		
		<td valign="top" width="320">
			<div class="country-pics">
		<?php

		//vd($country->attrs['pics']);
		foreach($country->attrs['pics'] as $key=>$pic)
		{?>
			<a href="/upload/images/<?=$pic['src']?>" onclick="return hs.expand(this)" class="highslide ">
				<img src="/resize.php?file=/upload/images/<?=$pic['src']?>&width=280"  />
			</a>
		<?php 
		}
		?>
			</div>
		</td>
	</tr>
</table>

<?php 
}?>