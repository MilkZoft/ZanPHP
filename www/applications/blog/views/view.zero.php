<?php if(!defined("_access")) die("Error: You don't have permission to access here..."); ?>

<div class="post">
	<div class="post-title">
		<?php print a($hello, _webBase); ?>
	</div>
	
	<div class="post-left">
		<?php print $date; ?>
	</div>
	
	<div class="post-right">
		<?php print $comments; ?>
	</div>
	
	<div class="clear"></div>
	
	<div class="post-content">
		<?php print $post; ?>
	</div>
</div>
