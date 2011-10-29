<div id="blog">
	<?php foreach($posts as $post) { ?>
	<div class="post">
		<span class="bold"><?php print $post["Title"]; ?></span><br />
		Escrito por <?php print $post["Author"]; ?> 
		<?php print howLong($post["Start_Date"]); ?>
		
		<p>
			<?php print $post["Content"]; ?>
		</p>
	</div>
	<br />
	<? } ?>
</div>