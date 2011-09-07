<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

function getException($e) {
	if(is_object($e)) {
		?>
        	<p style="text-align:center;">
            	<img src="<?php print _webURL; ?>/www/lib/images/zanphp.png" />
            </p>
            
        	<div style="width: 500px; border: 1px solid #000; background-color: #e3f5f9; margin: 0 auto; font-family: Arial, Helvetica, sans-serif; font-size: 0.8em; padding: 10px;">
            	<p><strong><?php print __(_("Error Code")); ?>:</strong> <br /> <?php print $e->getCode(); ?></p>
                <p><strong><?php print __(_("Error Message")); ?>:</strong> <br /> <?php print $e->getMessage(); ?></p>
                <p><strong><?php print __(_("Error File")); ?>:</strong> <br /> <?php print $e->getFile(); ?></p>
                <p><strong><?php print __(_("Error Line")); ?>:</strong> <br /> <?php print $e->getLine(); ?></p>
            </div>
        <?php
		
		exit;
	}
}