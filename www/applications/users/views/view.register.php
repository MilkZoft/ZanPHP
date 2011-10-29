<div id="users">
	<form action="<?php _webBase . _sh . _webLang . _sh . "users" . _sh . "register"; ?>" method="post">
	
		<fieldset>
			<legend>Registro de usuarios</legend>
			
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
				<strong>Correo electr&oacute;nico:</strong><br />
				<input name="email" type="text" />
			</p>
			
			<p>
				<input name="save" value="Registrar" type="submit" />
			</p>
		</fieldset>
	</form>
</div>