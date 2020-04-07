<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<meta charset="utf-8">
	
	<title><?=$_GLOBALS['TITLE']?></title>
	
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<script type="text/javascript" src="/js/libs/jquery-1.11.0.min.js"></script>
	
	<!--LESS-->
	<link rel="stylesheet/less" type="text/css" href="/css/style.less" />
	<link rel="stylesheet/less" type="text/css" href="/css/slonne.less" />
	<script src="/js/libs/less/less-1.7.3.min.js" type="text/javascript"></script>
	
	<!--<link rel="stylesheet" href="/css/style.css">-->
	<!--<link rel="stylesheet" href="/css/slonne.css">-->
	
	<script type="text/javascript" src="/js/libs/highslide-4.1.13/highslide-full.packed.js"></script>
	<link rel="stylesheet" type="text/css" href="/js/libs/highslide-4.1.13/highslide.css" />
	
	<!--стандартные js Slonne-->
	<script type="text/javascript" src="/js/common.js"></script>
	<script src="/include/forms/forms.js" type="text/javascript"></script>
	
	<!--Модальное окно-->
	<script type='text/javascript' src='/js/plugins/jquery.simplemodal/jquery.simplemodal.js'></script>
	<link rel="stylesheet" type="text/css" href="/js/plugins/jquery.simplemodal/simplemodal.css" />
	
	<!--Карусель (для клиентов)-->
	<script type='text/javascript' src='/js/plugins/jquery.jcarousellite.min.js'></script>
	
	<!--Слайдер (для индекса)-->
	<script type='text/javascript' src='/js/plugins/jquery.superslides/jquery.superslides.min.js'></script>
	<link rel="stylesheet" type="text/css" href="/js/plugins/jquery.superslides/superslides.css" />
	
	<!--Шрифт OPEN SANS-->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:600,300&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
	
	<script type="text/javascript">
		hs.graphicsDir = '/js/libs/highslide-4.1.13/graphics/';
		hs.align = 'center';
		hs.transitions = ['expand', 'crossfade'];
		hs.wrapperClassName = 'dark borderless floating-caption';
		hs.fadeInOut = true;
		hs.dimmingOpacity = .55;
		hs.showCredits = false;
	
		// Add the controlbar
		if (hs.addSlideshow) hs.addSlideshow({
			//slideshowGroup: 'group1',
			interval: 5000,
			repeat: false,
			useControls: false,
			fixedControls: 'fit',
			overlayOptions: {
				opacity: .6,
				position: 'bottom center',
				hideOnMouseOut: true
			}
		});
	</script>


	

</head>



<body >

<div id="restricted-area">
	Это<br> частный сайт <br>
	Не просматривайте его.
</div>

<table class="t" border="0" style="height: 100%; width: 100%; ">
	<tr><td>
<div class="block" id="menu1">
	<div class="content">
	<!--МЕНЮ-->
		<ul class="primary" >
		<?
		foreach($_GLOBALS['MENU'] as $key=>$val)
		{?>
			<li>
				<a href="<?=$val['link']?>"><?=$val['title']?></a>
			</li>
		<?php 	
		}
		?>
		</ul>
	<!--//МЕНЮ-->
	</div>
</div>
	</td></tr>





<tr><td style="height: 100%; <?=($_GLOBALS['module'] == 'index' ? 'vertical-align: middle;' : '')?>">
<div class="block" id="content">
	<div class="content">
	<!--КОНТЕНТ-->
		<?=$_GLOBALS['CONTENT']?>
	<!--//КОНТЕНТ-->
	</div>
</div>
</td></tr>



<tr><td>
<div class="block" id="footer">
	<div class="content" >
	<!--Футер-->
		<div id="caution"> 
			Материалы сайта позаимствованы из книги <b>"ЙОГА ДЛЯ ЗДОРОВЬЯ" Ричарда Хитлмана</b>. 
			<br><b>Внимание!</b> Сайт предназначен только для меня, и исключительно для более удобного <b>частного</b> использования этой книги.
			<br>Остальных просьба немедленно удалиться с сайта. Или не шуметь хотя бы..
		</div>
		<div id="razrab">Разработка <a href="http://slonne.kz">CoBeCTb'soft</a> &copy;2014</div> 
	<!--//Футер-->
	</div>
</div>
</td></tr>
</table>






<iframe name="iframe1" style="width: 700px; height: 400px;  background: #fff; display:none; ;">asdasd</iframe>

</body>

</html> 




<script>
jQuery(function ($) {
	// Load dialog on page load
	//$('#basic-modal-content').modal();

	// Load dialog on click
	$('.modal-opener').click(function (e) {
		$('#float-form-wrapper').modal();
		return false;
	});

	
});
</script>
