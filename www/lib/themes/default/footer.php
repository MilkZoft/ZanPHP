<?php 
	if(!defined("_access")) {
		die("Error: You don't have permission to access here..."); 
	}
	
	if(isMobile()) {
		include "mobile/footer.php";
	} else {
?>
			</div>
		</div>

		<footer>
			<p>&copy; <?php print __("All rights reserved"); ?> - ZanPHP Framework v.2.5 - 2011 - <?php print __("Powered by"); ?> <a href="http://www.milkzoft.com" title="MilkZoft">MilkZoft</a></p>
		</footer>
	  
		</div>
	</body>
</html>
<?php } ?>