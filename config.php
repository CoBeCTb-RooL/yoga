<?
define('ROOT', $_SERVER['DOCUMENT_ROOT']);


$_CONFIG['template'] = 'default';



$_CONFIG['langs']=array(
	'ru'=>array('title'=>'Русская', 'postfix'=>'', 'siteTitle'=>'Rus',  ),
//	'en'=>array('title'=>'Engish',  'postfix'=>'_en', 'siteTitle'=>'Eng', ),
//	'kz'=>array('title'=>'Қазақ',  'postfix'=>'_kz', 'siteTitle'=>'Каз', ),
//	'tur'=>array('title'=>'Türk',  'postfix'=>'_tur', 'siteTitle'=>'Tur', ),

);



#	ЯЗЫК ПО УМОЛЧАНИЮ
$_CONFIG['default_lang'] = 'ru'; 
$_CONFIG['default_admin_lang'] = 'ru'; 




define('CONTROLLERS_DIR', 	'[controllers]');
define('VIEWS_DIR', 		'[views]');
define('MODELS_DIR', 		'[models]');



$_CONFIG['ALLOWED_MODULES'] = array(
			"index" => "../".CONTROLLERS_DIR."/indexController.php", 
			"error" => "../".CONTROLLERS_DIR."/errorController.php", 
            "pages"  =>"../".CONTROLLERS_DIR."/pagesController.php",
            "news"  =>"../".CONTROLLERS_DIR."/newsController.php",
			"projects"  =>"../".CONTROLLERS_DIR."/projectsController.php",
			"asanas"  =>"../".CONTROLLERS_DIR."/asanasController.php",
			"programs"  =>"../".CONTROLLERS_DIR."/programsController.php",
			"gallery"  =>"gallery.php",
			"cabinet" => "cabinet/index.php",
            
			"services" => "../".CONTROLLERS_DIR."/servicesController.php",
			"contacts" => "../".CONTROLLERS_DIR."/contactsController.php",
            
            
            "forms"=>"forms.php",
            

            );



/*
class Config
{
	
	
	public static $allowedModules=array(
			"index" => "index.php", 
			"error" => "error.php",
            "pages"=>"pages.php",
            "news"  =>"../[controllers]/newsController.php",
			"gallery"  =>"gallery.php",
			"cabinet" => "cabinet/index.php",
            "vacancy"=>"vac.php",


            "search" => "search.php",
            "sitemap" => "map.php",

            "registration" => "registr.php",
            "zakupki" =>"zakupki.php",
            "arhiv_zakupok" =>"arhiv_zakupok.php",
            "uchastniki" =>"uchastniki.php",
            "contacts" =>"contacts.php",
            
            "forms"=>"forms.php",
            

            );
            
            
            
	
	
	
} */


?>