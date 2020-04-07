<?php
if(!$_SESSION['admin']['privileges']['modules'])
	echo 'Недостаточно прав.';
else 
{
?>

<div id="mod-container-div"></div>



<script src="modules/modules/modules.js" type="text/javascript"></script>

<script>

Modules.drawDiv('mod-container-div')

Modules.list()
</script>




<?php
}
?>