<div id="poll">
	<p>
		<ul>
			<li><a href="<?php print path("polls/cpanel/add"); ?>"><?php print __(_("Add Poll")); ?></a></li>
			<li><a href=""><?php print __(_("Edit Polls")); ?></a></li>
			<li><a href=""><?php print __(_("Delete Polls")); ?></a></li>
		</ul>
	</p>

	<?php	
		if(isset($poll["answers"])) {
			if(!SESSION("ZanPoll")) {
				?>
					<form id="polls" method="post" action="<?php print _webBase . _sh . _webLang . _sh . "polls" . _sh . "vote"; ?>">			
						<p>
							<strong><?php print $poll["question"]["Title"];?></strong>
						</p>
								
						<?php 
							$i = 1; 
							
							foreach($poll["answers"] as $answer) {
								print '<input id="answer_'. $i .'" name="answer" type="radio" value="'. $answer["ID_Answer"] .'"/> '. $answer["Answer"] .'<br />';
								$i++;
							}
						?>
						
						<input name="ID_Poll" type="hidden" value="<?php print $poll["question"]["ID_Poll"]; ?>" /><br />
					  
						<label for="send-vote">
							<input id="send-vote" class="btn info" name="send" type="submit" value="<?php print __("Vote");?>" class="poll-submit" />
						</label>
					</form>
				<?php
			} else {
				if(isset($poll)) {
					$color[0] = _pollColor1;
					$color[1] = _pollColor2;
					$color[2] = _pollColor3;
					$color[3] = _pollColor4;
					$color[4] = _pollColor5;
					$color[5] = _pollColor6;
					$total    = 0;
					
					foreach($poll["answers"] as $answers) {
						$total = (int) ($total + $answers["Votes"]);
					}
					
					?>
						<p class="section">					
							<p>
								<strong><?php print $poll["question"]["Title"]; ?></strong>
							</p>
						
							<?php 
								$i = 0;
								$percentage = 0;
								
								foreach($poll["answers"] as $answers) {
									if((int) $answers["Votes"] > 0) {								
										$percentage = ($answers["Votes"] * 100) / $total;
										
										if($percentage >= 10) {
											$percentage = substr($percentage, 0, 5);
										} else {
											$percentage = substr($percentage, 0, 4);
										}
									}			

									$style = "width: ". intval($percentage) ."%; background-color: ". $color[$i] .";";
									?>
									
										<span style="margin-left:5px;"><?php print $answers["Answer"]; ?></span> <br />
										<div class="poll-graphic" style="border: 1px solid <?php print $color[$i]; ?>;">
											<span class="poll-graphic-bar bold" style="<?php print $style; ?>">&nbsp;<?php print $percentage; ?>%</span>
										</div>
									
									<?php
										$i++;
									
									$percentage = 0;
								}
								
								$show = ($total === 1) ? '1 ' . __("vote") : $total .' '. __("votes");
							?>
							
							<br />
							<strong><?php print __("Total");?>:</strong> <?php print $show; ?>
						</p>
							<?php
				}
			}
		}
	?>
</div>
