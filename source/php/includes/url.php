<?php
	$url = explode('/', trim(substr($_SERVER['REQUEST_URI'],strlen(BOOK_URL_ROOT)), '/'));
	global $GET_language, $GET_article, $GET_page, $GET_mode;
	if ( ( sizeof( $url ) > 0 ) && ( $url[sizeof($url)-1] == 'all' || $url[sizeof($url)-1] == 'stub' ) ) {
	        $GET_mode = $url[sizeof($url)-1];
	        array_pop($url);
	}
	if ( sizeof( $url ) > 0 ) $GET_language = $url[0];
	if ( sizeof( $url ) > 1 ) $GET_article = $url[1];
	if ( sizeof( $url ) > 2 ) $GET_page = $url[2];
	if ( sizeof( $url ) > 3 ) $GET_mode = $url[3];
	if ( isset( $_GET['language'] ) ) $GET_language = $_GET['language'];
	if ( isset( $_GET['article'] ) ) $GET_article = $_GET['article'];
	if ( isset( $_GET['page'] ) ) $GET_page = $_GET['page'];
	if ( isset( $_GET['mode'] ) ) $GET_mode = $_GET['mode'];

?>
