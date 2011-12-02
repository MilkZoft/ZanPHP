<?php 
	if(!defined("_access")) {
		die("Error: You don't have permission to access here..."); 
	}
	
	if(isMobile()) {
		include "mobile/content.php";
	} else {
?>
<div class="span10">
	<h2>Main Content</h2>
	<?php $this->load(isset($view) ? $view : NULL, TRUE); ?>
</div>
<?php } ?>