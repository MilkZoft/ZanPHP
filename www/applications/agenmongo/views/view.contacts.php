<p>Lista de contactos:</p>

<p>
	<?php
		if(is_array($contacts)) {
			foreach($contacts as $contact) {
				print __(_("Name"))  .': '. $contact["Name"]  .'<br />';
				print __(_("Email")) .': '. $contact["Email"] .'<br />';
				print __(_("Phone")) .': '. $contact["Phone"] .'<br />';
			}
		}
	?>
</p>