<div id="bottom-form-wrapper">
	<form class="bottom-form" id="bottom-form" method="post"   target="iframe1" action="/include/forms/forms.php" onsubmit="return Forms.bottomForm.check();" >
		<input type="hidden" name="action" value="bottomFormSubmit">
		<h3>Оставить заявку</h3>
		<input type="text" name="name" id="bottom-form-name" placeholder="имя" />
		<input type="text" name="tel" id="bottom-form-tel" placeholder="телефон" />
		<!--<input type="text" name="zayavka" placeholder="заявка" />-->
		<div class="info" id="bottom-form-info"></div>
		<div class="loading" id="bottom-form-loading" style="display: none;">Подождите...</div>
		<input type="submit" name="go_btn" value="Оставить заявку" class="yellow-btn">
		
		
	</form>
	
	<div id="bottom-form-success"  style="display: none;">
		<h1>Заявка отправлена!</h1>
		<span>Ожидайте! Наши специалисты обязательно свяжутся с Вами!</span>
	</div>
</div>