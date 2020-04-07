<?php
if(!$_SESSION['admin']['privileges']['const'])
	echo 'Недостаточно прав.';
else 
{
?>

<div id="const-container-div"></div>



<script src="modules/constants/constants.js" type="text/javascript"></script>

<script>

Constants.drawDiv('const-container-div')

Constants.list()
</script>




<?php
}
?>