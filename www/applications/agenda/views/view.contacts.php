<div id="contacts">
	<?php
		if(is_array($contacts)) {
			foreach($contacts as $contact) {
				print $contact["Name"] . "<br />" . $contact["Email"];
			}
		}
	?>
</div>
