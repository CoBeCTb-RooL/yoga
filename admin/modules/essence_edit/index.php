<?php
if(!$_SESSION['admin']['privileges']['essences'])
	echo 'Недостаточно прав.';
else 
{
?>

<div id="ee-container-div"></div>



<script src="modules/essence_edit/essenceEdit.js" type="text/javascript"></script>

<script>

EE.drawDiv('ee-container-div')

EE.list()
</script>




<?php
}
?>