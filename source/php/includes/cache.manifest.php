<?php header('Content-type: text/cache-manifest'); ?>
CACHE MANIFEST
<?php 
	require_once(BOOK_ROOT . '/php/includes/url.php');
	require_once(BOOK_ROOT . '/locale/locale.php');
	$lang = $GLOBALS['GET_language'];
	//echo 'in cache manifest and lang is ' . $lang;
	require_once(BOOK_ROOT . '/locale/' . $lang . '/configuration.php');
	$versionNumber = get_version_number();
 	echo "#version " . $versionNumber;
?>


NETWORK:
*

CACHE:
<?php 
	require_once(BOOK_ROOT . '/locale/locale.php');
	foreach( $OFFLINE_ASSETS as $asset ) {
		echo BOOK_URL_ROOT . '/' . $asset . '?v='. $versionNumber . "\n";
	}
?>

