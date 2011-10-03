<div id="users">
	<form action="<?php _webBase . _sh . _webLang . _sh . "users" . _sh . "login"; ?>" method="post">
	
		<fieldset>
			<legend>Login</legend>
			
			<p>
				<?php print isset($alert["alert"]) ? $alert["alert"] : NULL; ?>
			</p>
			
			<p>
				<strong>Nombre de usuario:</strong><br />
				<input name="username" type="text" />
			</p>
			
			<p>
				<strong>Contrase&ntilde;a:</strong><br />
				<input name="pwd" type="password" />
			</p>
			
			<p>
				<input name="login" value="Conectar" type="submit" />
			</p>
		</fieldset>
	</form>
</div>