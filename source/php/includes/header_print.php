<!DOCTYPE html>
<html lang="en">
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
	    <meta name="description" content="Things you always wanted to know about the web but were afraid to ask. Learn the basics of the web and web browsers, and how their evolution is changing the way we work and play online." >
	    <meta name="keywords" content="browsers, web, google, cookies, cloud computing, html5 book, web apps, javascript" >
	    <meta name="author" content="Google, inc." >
	    
	    <!-- Adds support for the Chrome Frame IE plugin -->
	    <meta http-equiv="X-UA-Compatible" content="chrome=1">
		
		<title>20 Things I Learned About Browsers and the Web</title>
		
		<link type="text/css" href="<?php echo BOOK_URL_ROOT; ?>/css/reset.css" rel="stylesheet" media="screen, print" />
		<link type="text/css" href="<?php echo BOOK_URL_ROOT; ?>/css/layouts.css" rel="stylesheet" media="screen, print" />
		<link type="text/css" href="<?php echo BOOK_URL_ROOT; ?>/css/print-preview.css?v=2" rel="stylesheet" media="screen, print" />
		
    <script type="text/javascript">
      var SERVER_VARIABLES = {
	ROOT_URL: "<?php echo BOOK_URL_ROOT; ?>",
        PAGE: "<?php print_locale_page_label(); ?>",
        PAGES:  "<?php print_locale_pages_label(); ?>",
        THING:  "<?php print_locale_sharer_label_one(); ?>",
        FOREWORD:  "<?php print_locale_menu_foreword(); ?>",
        LANG: <?php echo '"' . $GLOBALS['GET_language'] . '"'; ?>,
        SITE_VERSION: <?php echo $versionNumber; ?>,
        FACEBOOK_MESSAGE: "<?php print_locale_facebook_message(); ?>",
        FACEBOOK_MESSAGE_SINGLE: "<?php echo print_locale_facebook_message_single(); ?>",
        TWITTER_MESSAGE: "<?php print_locale_twitter_message(); ?>",
        TWITTER_MESSAGE_SINGLE: "<?php print_locale_twitter_message_single(); ?>",
        BUZZ_MESSAGE: "<?php print_locale_buzz_message(); ?>",
        BUZZ_MESSAGE_SINGLE: "<?php print_locale_buzz_message_single(); ?>",
        SOLID_BOOK_COLOR: "<?php echo $SOLID_BOOK_COLOR ; ?>"
      };
    </script>

		<script type="text/javascript"> 
			document.write('<link rel="stylesheet" type="text/css" media="all" href="<?php echo BOOK_URL_ROOT; ?>/css/hideOnLoad.css" />');	
		</script> 
		
	</head>
	<body class="<?php echo body_class(); ?>">
		
	<?php include_once(BOOK_ROOT . "/php/includes/analyticstracking.php") ?>
