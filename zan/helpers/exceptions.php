<?php
/**
 * Access from index.php:
 */
if(!defined("_access")) {
	die("Error: You don't have permission to access here...");
}

function getException($e = NULL) {
	if(is_object($e)) {
		?>
        	<p style="text-align:center;">
            	<img src="<?php echo get("webURL"); ?>/www/lib/images/zanphp.png" />
            </p>
            
        	<div style="width: 500px; border: 1px solid #000; background-color: #e3f5f9; margin: 0 auto; font-family: Arial, Helvetica, sans-serif; font-size: 0.8em; padding: 10px;">
            	<p>
                    <strong><?php echo __(_("Error Code")); ?>:</strong> <br /> <?php echo $e->getCode(); ?>
                </p>
                <?php 
                    if(!get("production")) {
                    ?>
                        <p><strong><?php echo __(_("Error Message")); ?>:</strong> <br /> <?php echo $e->getMessage(); ?></p>
                        <p><strong><?php echo __(_("Error File")); ?>:</strong> <br /> <?php echo $e->getFile(); ?></p>
                        <p><strong><?php echo __(_("Error Line")); ?>:</strong> <br /> <?php echo $e->getLine(); ?></p>
                    <?php
                    }
                    ?>
            </div>
        <?php
		
		exit;
	} else {
        ?>
            <p style="text-align:center;">
                <img src="<?php echo get("webURL"); ?>/www/lib/images/zanphp.png" />
            </p>
            
            <div style="width: 500px; border: 1px solid #000; background-color: #e3f5f9; margin: 0 auto; font-family: Arial, Helvetica, sans-serif; font-size: 0.8em; padding: 10px;">
                <p><strong><?php echo __("Error Message"); ?>:</strong> <br /> <?php echo $e; ?></p>
            </div>
        <?php

        exit;
    }
}