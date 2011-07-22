<div id="contacts">
	<?php 
		foreach($contacts as $contact) {
		?>
            <p>
                <strong><?php print __("Name"); ?>:</strong> <?php print $contact["Name"]; ?>
            </p>
            
            <p>
                <strong><?php print __("Email"); ?>:</strong> <?php print $contact["Email"]; ?>
            </p>
            
            <p>
                <strong><?php print __("Phone"); ?>:</strong> <?php print $contact["Phone"]; ?>
            </p>    
            
            --------------    
        <?php	
		}
	?>

</div>