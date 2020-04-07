<img src="/images/index-top-20-ru.jpg" alt="" />

<div align="center" id="index-top-20-open-account">
	<a href="javascript:void(0)" class="big-btn" onclick="$('#reg-float-form').modal({'modal':true, 'overlayClose':true, 'minWidth': '60%', 'minHeight':'80%' }); Forms.openAccount.drawDiv('reg-float-form');  Forms.openAccount.init(); "><?=$_CONST['ОТКРЫТЬ ТОРГОВЫЙ СЧЁТ']?></a>
</div>

<div id="index-top-20-accordeon-wrapper">
<?php
#	аккордеон
echo Funx::accordeon(69);
?>
</div>



<div style="padding: 100px;">
	<div style="float: left">
		<a class="big-btn" href="/<?=$_SESSION['lang']?>/forms/index_top_20/replenish"><?=$_CONST['ПОПОЛНИТЬ СЧЕТ В INDEX TOP 20']?></a>
	</div>
	
	<div style="float: right">
		<a class="big-btn" href="/<?=$_SESSION['lang']?>/forms/index_top_20/withdraw"><?=$_CONST['ВЫВЕСТИ СРЕДСТВА С INDEX TOP 20']?></a>
	</div>
</div>