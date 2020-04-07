<?php $entity=$_REQUEST['e'];?>


<?php
$_SESSION['admin_lang'] = $_CONFIG['default_admin_lang'];
//vd($entity);
//vd($_SESSION); 
//vd($_SESSION['admin']['privileges'][$entity]);
//vd($_REQUEST);
//vd($entity);
//vd($_SESSION['admin']['privileges']);
if(!$_SESSION['admin']['privileges'][$entity])
	echo 'Недостаточно прав.';
else 
{
?>


<div id="entities-container-div">Загрузка...</div>



<script src="modules/entities/entities.js" type="text/javascript"></script>

<script>
Entities.settings['essence']='<?=$entity?>'
//Entities.drawDiv('entities-container-div')

//Entities.list()
//Entities.drawTree(0)
Entities.init();

<?php if(count($_CONFIG['langs']) > 1)
{?>
	setTimeout("Entities.initLangsChoice();", 200)
<?php 
}?>

</script>
<?php 
}?>