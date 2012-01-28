<p>
	<strong>Editar o Eliminar encuestas</strong> <br /><br />
	<?php
		if(is_array($polls)) {
			foreach($polls as $poll) {
				print "<strong>". $poll["Title"] ."</strong>";
				print ' <a href="'. path("polls/cpanel/edit/". $poll["ID_Poll"]) .'">E</a> /';
				print ' <a href="'. path("polls/cpanel/delete/". $poll["ID_Poll"]) .'">X</a> <br />';
			}
		}
	?>
</p>