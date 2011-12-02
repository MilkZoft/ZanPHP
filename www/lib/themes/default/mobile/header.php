<!DOCTYPE html>
<html lang="<?php print _webLang; ?>">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1"> 
		<title><?php print $this->getTitle(); ?></title>
		
		<link href="<?php print _webURL . _sh . _www . "/lib/css/frameworks/bootstrap/bootstrap.min.css"; ?>" rel="stylesheet">
		<link href="<?php print $this->themePath; ?>/css/style.css" rel="stylesheet">
		<?php print $this->getCSS(); ?>
		
		
		<link rel="stylesheet" href="<?php print _webURL . _sh . _www . "lib/css/mobil/jquery.mobile-1.0.min.css"; ?>" />
		
		<script type="text/javascript" src="<?php print _webURL . _sh . _www . "lib/scripts/js/jquery-1.7.1.min.js"; ?>"></script>
		<script type="text/javascript" src="<?php print _webURL . _sh . _www . "lib/scripts/js/jquerymobile/jquery.mobile-1.0.min.js"; ?>"></script>
	</head>

	<body>
		<div data-role="page">
			<div data-role="header">
				<h1>ZanPHP</h1>
			</div>
