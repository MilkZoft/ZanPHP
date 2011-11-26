<p>Nuevo contacto</p>

<form action="<?php print _webBase; ?>/agenda/add" method="post">
	<fieldset>
		<p>
			<?php print isset($alert) ? $alert : NULL; ?>
		</p>

		<p>
			Nombre: <br />
			<input name="name" type="text" />
		</p>

		<p>
			Email: <br />
			<input name="email" type="text" />
		</p>

		<p>
			Phone: <br />
			<input name="phone" type="text" />
		</p>

		<p>
			<input name="save" type="submit" value="Guardar" />
		</p>
	</fieldset>
</form>