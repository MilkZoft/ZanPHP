<!DOCTYPE html>
<html>
	<head>
		<title>MilkZoft v.2.0</title>
		<link rel="stylesheet" href="css/fonts.css" type="text/css" />
		<link rel="stylesheet" href="css/style.css" type="text/css" />					
		<link rel="stylesheet" href="css/themes/default/default.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="css/themes/pascal/pascal.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="css/themes/orman/orman.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="css/nivo-slider.css" type="text/css" media="screen" />

		
		<script type="text/javascript">							
			<!--
				if(screen.width == 1024) {
					document.write('<link rel="stylesheet" href="css/clouds1024.css" type="text/css" />');
				} else if(screen.width == 1280) {
					document.write('<link rel="stylesheet" href="css/clouds1280.css" type="text/css" />');
				} else {
					document.write('<link rel="stylesheet" href="css/clouds.css" type="text/css" />');
				}
			//-->
		</script>		
		
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery.nivo.slider.pack.js"></script>
		<script type="text/javascript" src="js/clouds.js"></script>

		<script type="text/javascript">
			$(window).load(function() {
				$('#slider').nivoSlider();
			});	
		</script>
	</head>

	
	<body>
		<div id="clouds">
			<div id="cloud1"><img src="images/cloud1.png"></div>
			<div id="cloud2"><img src="images/cloud2.png"></div>
			<div id="cloud3"><img src="images/cloud3.png"></div>
			<div id="cloud4"><img src="images/cloud2.png"></div>
			<div id="cloud5"><img src="images/cloud1.png"></div>
		</div>
		
		<div id="container">

			<div id="header">
				<div id="header-logo-fly">
					<img src="images/logo-fly.png" alt="Logo" />
				</div>
				
				<div id="header-content">
					<div id="header-content-menu">
						<ul class="oswald">
							<li><a href="index.html" title="Home">Home</a></li>

							<li><a href="services.html" title="Services">Services</a></li>
							<li><a href="portfolio.html" title="Portfolio">Portfolio</a></li>						
							<li><a href="#" title="Contact us">Contact us</a></li>
						</ul>
					</div>
					
					<div id="header-content-left">
						<img src="images/design.png" alt="Web Design" />
					</div>

					
					<div id="header-content-right">
						<h1><?php print $name; ?> and <?php print $email; ?></h1>												
					</div>
					
					<div class="clear"></div>
				</div>

			</div>
			
			<div class="line"></div>		
			
			<div id="portfolio">
				<div id="portfolio-left">
					<h2>Portfolio</h2>
					
					<img src="images/portfolio.png" alt="Portfolio" style="margin-bottom: 40px;" />
				</div>
				
				<div id="portfolio-right">
					<div class="slider-wrapper theme-default">

						<div id="slider" class="nivoSlider">
							<img src="images/portfolio/milkzoft.png" alt="MilkZoft" />
							<img src="images/portfolio/canaco.png" alt="Canaco" />
							<img src="images/portfolio/rutadelcafe.png" alt="La Ruta del Cafe Comala" />
							<img src="images/portfolio/murmullos.png" alt="Los Murmullos" />
							<img src="images/portfolio/diputados.png" alt="Diputados PAN Colima" />
						</div>
					</div>
				</div>

				
				<div class="clear"></div>
			</div>						
			
			<div class="line"></div>	
						
			<div id="process">									
				<h2>Our Process</h2>
				
				<div id="process-content">
					<img src="images/process-en.png" alt="Our Process" />
				</div>								
			</div>		
			
			<div id="bottom-menu">
				<ul class="oswald">

					<li>&copy; MilkZoft 2011 - </li>
					<li><a href="#" title="About us">About us - </a></li>
					<li><a href="#" title="Pol&iacute;tica de Calidad">Blog</a> - </li>
					<li><a href="#" title="Pol&iacute;tica de Calidad">Forums</a> - </li>

					<li><a href="#" title="Projects">Projects</a></li>											
				</ul>			
			</div>
			
			<div class="clear"></div>
			
			<div class="line"></div>	
			
			<div id="footer">
				<div id="footer-left">
					<script type="text/javascript">							
						<!--
							if(screen.width == 1024) {
								document.write('<div id="technologies1024"><span class="technologies-title pacifico">Technologies</span></div>');
							} else if(screen.width == 1280) {
								document.write('<div id="technologies1280"><span class="technologies-title pacifico">Technologies</span></div>');
							} else {
								document.write('<div id="technologies"><span class="technologies-title pacifico">Technologies</span></div>');
							}
						//-->
					</script>	
					
					<script type="text/javascript">							
						<!--
							if(screen.width == 1024) {
								document.write('<div id="newsletter1024">');
							} else if(screen.width == 1280) {
								document.write('<div id="newsletter1280">');
							} else {
								document.write('<div id="newsletter">');
							}
						//-->
					</script>

					
						<span class="newsletter-title pacifico">Newsletter</span>
						
						<form action="#" method="post" class="newsletter-form">
							<fieldset>
								<legend>Newsletter</legend>
								
								<p>
									<input id="email" name="email" type="text" value="Your email here" class="input" /><br /><br />
									<input name="subscribe" value="Subscribe" type="submit" class="submit" />
								</p>

							</fieldset>
						</form>
					</div>				
				</div>
				
				<div id="footer-right">
					<script type="text/javascript">							
						<!--
							if(screen.width == 1024) {
								document.write('<div id="contact-us1024">');
							} else if(screen.width == 1280) {
								document.write('<div id="contact-us1280">');
							} else {
								document.write('<div id="contact-us">');
							}
						//-->
					</script>
					
						<span class="contact-us-title pacifico">Contact us</span>
						
						<p class="contact-us-content">

							<strong>Call us at:</strong> +52 (312) 31-2-84-20 (M&eacute;xico) <br />
							<strong>Email:</strong> <a href="mailto:contact@milkzoft.com" title="Email us">contact@milkzoft.com</a> / <a href="mailto:carlos@milkzoft.com" title="Email us">carlos@milkzoft.com</a><br />
							<a href="skype:milkzoft?chat" title="Skype us"><img src="images/skype.png" alt="Skype" class="no-border" /></a>
							<a href="http://twitter.com/milkzoft" title="Our Twitter"><img src="images/twitter.png" alt="Twitter" class="no-border" /></a>

						</p>
					</div>
				</div>

			</div>
		</div>
	</body>
</html>
