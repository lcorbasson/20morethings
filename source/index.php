<?php

/**
 * Copyright 2011 Google Inc.
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */ 

//import com.fi.twentythings.Article;
//import com.fi.twentythings.Locale;
//import com.fi.twentythings.Page;
//import com.fi.twentythings.Version;
//import com.googlecode.objectify.Objectify;
//import com.googlecode.objectify.ObjectifyService;

//TODO:remove, for debugging
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

define('BOOK_ROOT', getcwd());
define('BOOK_URL_ROOT', dirname($_SERVER['SCRIPT_NAME']));

header("Cache-Control: no-cache, must-revalidate");
header("Content-Type: text/html;charset=UTF-8");

require_once(BOOK_ROOT . '/php/libraries/browser.php');
require_once(BOOK_ROOT . '/locale/locale.php');
require_once(BOOK_ROOT . '/locale/' . LOCALE_CONFIGURATION);


/**
 * Java imports
 */
 
//import com.fi.twentythings.Article;
//import com.fi.twentythings.Locale;
//import com.fi.twentythings.Page;
//import com.googlecode.objectify.Objectify;
//import com.googlecode.objectify.ObjectifyService;

$browser = new Browser();
global $BROWSER_NAME;
$BROWSER_NAME = $browser->getBrowser() ;
global $BROWSER_VERSION;
$BROWSER_VERSION = $browser->getVersion() ;

/**
 * Route to basic or advanced
 */
 
function is_basic() {
	global $BROWSER_NAME, $BROWSER_VERSION;
	if(	
		( $BROWSER_NAME == Browser::BROWSER_CHROME && $BROWSER_VERSION >= 5 ) ||
		( $BROWSER_NAME == Browser::BROWSER_IE && $BROWSER_VERSION >= 9 ) ||
		( $BROWSER_NAME == Browser::BROWSER_FIREFOX && $BROWSER_VERSION >= 3.6 ) ||
		( $BROWSER_NAME == Browser::BROWSER_SAFARI && $BROWSER_VERSION >= 4 ) ||
		( $BROWSER_NAME == Browser::BROWSER_OPERA && $BROWSER_VERSION >= 10 ) ||
		is_touchdevice() ||
		is_chromeframe()
		) {
//return true; // TODO FIXME for DEBUGGING
		return false;
	} else {
		return true;
	}
}

/**
 * 
 */

function is_facebook(){
	return preg_match( "/facebook/is", $_SERVER["HTTP_USER_AGENT"] );
}

/**
 * 
 */
function is_chromeframe(){
	return preg_match( '/chromeframe/is', $_SERVER['HTTP_USER_AGENT'] );
}

/**
 * 
 */
function is_touchdevice(){
	global $BROWSER_NAME;
	return preg_match( '/iPad|iPhone|iPod|Android/', $BROWSER_NAME );
}

/**
 * Determines if we are running on the live server (or locally)
 */
function is_live() {
	global $DEVELOPMENT_HOSTS_EXPRESSION;
	return preg_match( $DEVELOPMENT_HOSTS_EXPRESSION, $_SERVER['SERVER_NAME']) ? false : true;
}

/**
 * Assembles body class
 * 
 */
 
function body_class() {
    global $langcode;
	$bodyClass = '';
	
	if( is_basic() ) {
		$bodyClass .= 'basic ';
	}
	
	if( isset($_GET['view']) && ($_GET['view'] == 'home' || $_GET['view'] == 'credits' || $_GET['view'] == 'tot' || $_GET['view'] == '404' ) ) {
		$bodyClass .= $_GET['view'] . ' ';
	}
	
	if( is_touchdevice() ) {
		$bodyClass .= 'touch-device ';
	}
	
	$bodyClass .= $langcode . ' ';
	
	return $bodyClass;
}


$pages = array(); 
$activePages = array();
$parsedPages = array();

/**
 * Load the articles from the datastore
 * 
 */
 
function load_articles()
{

	global $pages, $activePages, $ofy, $localeclass, $articleclass, $pageclass, $langcode, $contents;
	global $ALL_CHAPTERS;
	
//	$locales = $ofy->query($localeclass)->filter('id',$langcode)->list();	
	$locales = array( $langcode ); // TODO: add ALL languages
	
	foreach ( $locales as $locale ) {

//			$localeArticles = $ofy->query($articleclass)->filter('locale',$langcode)->order('order')->list();	//json_decode( $locale->getProperty('articles')->getValue() );
			$localeArticles = $ALL_CHAPTERS;
			
			foreach ($localeArticles as $article) {
//				$stub = $article->getStub();
				$stub = $article['stub'];
//				$title = $article->getTitle();
				$title = $article['title'];
//				$subtitle = $article->getSubtitle();
				$subtitle = $article['subtitle'];
//				$numberofpages = $article->getNumberOfPages();
				$numberofpages = $article['numberOfPages'];
//				$active = $article->getActive();
				$active = $article['active'];
//				$hidden = $article->getHidden();
				$hidden = ( array_key_exists( 'hidden', $article ) ? $article['hidden'] : 0 );
//				$order = $article->getOrder();
				$order = $article['order'];
				
//				$articlepages = $ofy->query($pageclass)->filter('locale',$langcode)->filter('stub', $stub)->list();
				$articlepages = array();
				for ( $i = 1; $i <= $numberofpages; $i++ ) {
					array_push( $articlepages, $stub . '-' . $i . '.html' );
				}
				
				$contents = array();
//				$templates = array();
				$templates = $article['templates'];
				foreach($articlepages as $articlepage) {
//					array_push($templates, $articlepage->getTemplate());
//					array_push($contents, $articlepage->getContent());
					array_push($contents, file_get_contents( BOOK_ROOT . '/locale/' . $_GET['language'] . '/pages/' . $articlepage ));
				}					
		
				
				$pages[$stub] = array(
					'numberOfPages' => $numberofpages,
					'title' => $title,
					'subtitle' => $subtitle,
					'active' => $active,
					'hidden' => $hidden,
					'order' => $order,
					'templates' => $templates,
					'contents' => $contents,
					'stub' => $stub
				);
				
				if($active == '1') {
					$activePages[$stub] = array(
						'numberOfPages' => $numberofpages,
						'title' => $title,
						'subtitle' => $subtitle,
						'active' => $active,
						'hidden' => $hidden,
						'templates' => $templates,
						'contents' => $contents
					);
				}
			}
		//}	
	}
}

load_articles();

/**
 * 
 */
 
function parse_article_pages()
{
	global $pages, $parsedPages, $ofy, $pageclass, $articleclass, $langcode;
//	$querypages = $ofy->query($pageclass)->filter('locale',$langcode)->list();  //->filter('stub', $currentArticle);	
	
	$i=1;
	
	foreach ($querypages as $page) {  //as $key => $value	
				
		if($page->getPageNumber()=='1') {
			$i = 1;
//			$pagearticleparent = $ofy->query($articleclass)->filter('locale', $langcode)->filter('stub', $page->getStub())->list()->get(0);
		}
		
		$pagetitle = $pagearticleparent->getTitle();			
		$stub = $page->getStub();
		$title = $pagearticleparent->getTitle();
		$subtitle = $pagearticleparent->getSubtitle();
		$template = $page->getTemplate();
		
		$outputpage = "<section class=\"".$template." title-".$stub." page-".$i."\">";
		$outputpage .= "<div class=\"page\">";
		$contents = trim($page->getContent());
			
		$filename = 'locale/'.$page->getLocale().'/pages/'.$stub.'-'.$i.'.html';
		
		// Get the page template name for the page
		$pageTemplate = $templates[$i-1];
		
		$contents = trim( file_get_contents($filename) );
		
		// Replace all dynamic variables in the page contents
		$contents = preg_replace( '/{TITLE}/is', $title, $contents );
		$contents = preg_replace( '/{SUBTITLE}/is', $subtitle, $contents );
		
		$outputpage .= $contents;
		
		// Close the wrapper element for the page
		$outputpage .= "</div>";
		$outputpage .= "</section>";
		
		$parsedPages[ 'pages/'.$stub.'-'.$i.'.html' ] = $outputpage;

		$i++;	
	}
}
//parse_article_pages();

/**
 * Set up page counters
 * 
 */
$totalNumberOfPages = 1;
foreach ($pages as $key => $value) {
	$pages[$key]['globalStartPage'] = $totalNumberOfPages;
	$totalNumberOfPages += $pages[$key]['numberOfPages'];
	$pages[$key]['globalEndPage'] = $totalNumberOfPages - 1;
}


/**
 * Get page query params
 * 
 */
$currentView = isset($_GET['view']) ? $_GET['view'] : null;
$currentArticle = isset($_GET['article']) ? $_GET['article'] : null;
$currentArticlePage = isset($_GET['page']) ? $_GET['page'] : '1';	


/**
 * Return stub of next/previous article
 * 
 */
function nextPrevArticleName($order) {
	global $activePages, $currentArticle;  //
	$keys = array_keys($activePages); 
	$position = array_search($currentArticle, $keys); 
	if (isset($keys[$position + 1]) || isset($keys[$position - 1])) { 
		$nextPrevArticleName = $order == 'next' ? $keys[$position + 1] : $keys[$position - 1];
	}
	return $nextPrevArticleName;
}


/**
 * Return url of next page
 * 
 */
function nextPage() {
	global $activePages, $currentView, $currentArticle, $currentArticlePage;
  $root = BOOK_URL_ROOT . '/' . $_GET['language'] . '/';
	
	// If we're on the home page, the next button should point to the first article
	if( $currentView == 'home' ) {
		return $root . getArrayFirstIndex( $activePages );
	}
	// If we're on the credits page, the next button should be inactive
	else if( $currentView == 'credits' ) {
		return false;
	}
	// If we're on the last page, send to credits
	else if( $currentArticle == 'theend' ) {
		return $root . 'credits';
	}
	// If we're on a regular article page get the next one
	if($activePages[$currentArticle]['numberOfPages'] == $currentArticlePage) {
		if( !$activePages[nextPrevArticleName('next')]['active'] ) return $root . 'theend';
		return $root . nextPrevArticleName('next');
	} elseif($activePages[$currentArticle]['numberOfPages'] > $currentArticlePage) {
		return $root . $currentArticle . '/' . (string)($currentArticlePage+1);
	}
}


/**
 * Return url of next page
 * 
 */
function prevPage() {
	global $activePages, $currentView, $currentArticle, $currentArticlePage;
	
	$root = BOOK_URL_ROOT . '/' . $_GET['language'] . '/';
	
	// If we're on the home page, the prev button should be inactive
	if( $currentView == 'home' ) {
		return false;
	}
	// If we're on the credits page, the prev button should point to the last article
	else if( $currentView == 'credits' ) {
		return $root . getArrayLastIndex( $activePages );
	}

	$prev = nextPrevArticleName('prev');
	if( empty($prev) && $currentArticlePage == '1' ){
		return $root;
	} elseif( $currentArticlePage == '1' ) {
		return $root . nextPrevArticleName('prev') . '/' . $activePages[nextPrevArticleName('prev')]['numberOfPages'];
	} elseif($activePages[$currentArticle]['numberOfPages'] >= $currentArticlePage) {
		return $root . $currentArticle . '/' . (string)($currentArticlePage-1);
	} else {
		return $root . $currentArticle;		
	}
}

/**
 * 404 Handling
 * 
 */
if( isset($_GET['article']) && !$activePages[$_GET['article']] ) {
//TODO:reactivate	header('Location: /' . BOOK_URL_ROOT);
}
if( $currentView == '404' ) {
	header("HTTP/1.0 404 Not Found");
}

/**
 * Checks if it's a 'stub'
 * 
 */
function isStub() {
	return isset($_GET['mode']) && ($_GET['mode'] == 'stub' || $_GET['mode'] == 'all');
}

/**
 * Checks if it's a print page
 * 
 */
function isPrintPage() {
	return isset($_GET['mode']) && ($_GET['mode'] == 'print' || $_GET['mode'] == 'printAll') ;
}

/**
 * Include header file
 * 
 */
if(isPrintPage()) {
	require_once(BOOK_ROOT . '/php/includes/header_print.php');
} elseif(!isStub()) {
	require_once(BOOK_ROOT . '/php/includes/header.php');
} 

/**
 * Spit our the right type of content depending on what
 * parameters were set in the request.
 */

// Are we printing a specific page of an article?
if(isset($_GET['article']) && !isset($_GET['page']) && isPrintPage()) {
	foreach($pages as $name => $value) {
		if( $name == $_GET['article'] ) {		
			echo isPrintPage() ? '<div id="pages">' : '<article id="'.$name.'">';
			
				for($i=1; $i<=$value['numberOfPages']; $i++) {
					$templates = $value['templates'];
					
					if ($i == 1) {
						echo '<div class="page-title">';
						echo '<h2>' . $value['title'] . '</h2>';
						if(strlen($value['subtitle'])) echo '<h3>' . $value['subtitle'] . '</h3>';
							echo '</div>';
					}
					
					echo '<section class="' . $templates[$i-1] . ' title-' . $value['stub'] . ' page-' . $i . '"><div class="page">';
					$txt = $value['contents'];
				    echo html_entity_decode($txt[$i-1]);				    
				    echo '</div></section>';
				}
				
			echo isPrintPage() ? '</div>' : '</article>';
			
		}
	}
} 

// If the request is for an article, the markup returned should
// be the first page of that article
elseif(isset($_GET['article'])) {
	$thisArticle = $pages[$currentArticle];
	echo '<section class="' . $thisArticle['templates'][$currentArticlePage-1] . ' title-' . $thisArticle['stub'] . ' page-' . $currentArticlePage . '"><div class="page">';
	if ($currentArticlePage == 1) {
		echo '<div class="page-title">';
		echo '<h2>' . $thisArticle['title'] . '</h2>';
		if(strlen($value['subtitle'])) echo '<h3>' . $thisArticle['subtitle'] . '</h3>';
		echo '</div>';
	}
	echo html_entity_decode($thisArticle['contents'][$currentArticlePage-1]);
	echo '</div></section>';	
} 

// If the requested mode is set to all, we return a concatenated
// version of all articles and pages
elseif(isset($_GET['mode']) && $_GET['mode'] == 'all') {
	// Include the flexible cross origin policy for this request
	include_once( BOOK_ROOT . '/php/includes/cors.php' );
	
	foreach($pages as $name => $value) {
		if( $value['active'] ) {
			echo '<article id="'.$name.'">';
				foreach($value['contents'] as $key => $content) {
					echo '<section class="' . $value['templates'][$key] . ' title-' . $value['stub'] . ' page-' . ($key+1) . '"><div class="page">';
					if ($key == 0) {
						echo '<div class="page-title">';
						echo '<h2>' . $value['title'] . '</h2>';
						if(strlen($value['subtitle'])) echo '<h3>' . $value['subtitle'] . '</h3>';
						echo '</div>';
					}
					echo html_entity_decode($content);
					echo '</div></section>';
				}
			echo '</article>';
		}
	}
} 

// This means we're printing the whole book
elseif(isPrintPage()) {
foreach($pages as $name => $value) {
			echo isPrintPage() ? '<div id="pages">' : '<article id="'.$name.'">';
			
				for($i=1; $i<=$value['numberOfPages']; $i++) {
					$templates = $value['templates'];
					
					if ($i == 1) {
						echo '<div class="page-title">';
						echo '<h2>' . $value['title'] . '</h2>';
						if(strlen($value['subtitle'])) echo '<h3>' . $value['subtitle'] . '</h3>';
							echo '</div>';
					}
					
					echo '<section class="' . $templates[$i-1] . ' title-' . $value['stub'] . ' page-' . $i . '"><div class="page">';
					$txt = $value['contents'];
				    echo html_entity_decode($txt[$i-1]);				    
				    echo '</div></section>';
				}
				
			echo isPrintPage() ? '</div>' : '</article>';
			
	}
}

/**
 * Include footer file
 * 
 */
if(isPrintPage()) {	
	require_once(BOOK_ROOT . '/php/includes/footer_print.php');
	
} elseif(!isStub()) {

	require_once(BOOK_ROOT . '/php/includes/content.php');
	
	if(isset($_GET['article'])) echo '<div id="articleId">' . $_GET['article'] . '</div>';
	if(isset($_GET['page'])) echo '<div id="pageNumber">' . $_GET['page'] . '</div>';
	
	$prevClass = !prevPage() ? 'inactive' : '';
	$nextClass = !nextPage() ? 'inactive' : '';
	
	?>
	<div id="pagination-prev" class="<?php echo $prevClass; ?>"><a href="<?php echo prevPage(); ?>"><div class="arrow"><?php echo $LOCALE_META_TEXT['LOCALE_PREVIOUS_PAGE']; ?></div></a></div>
	<div id="pagination-next" class="<?php echo $nextClass; ?>"><a href="<?php echo nextPage(); ?>"><div class="arrow"><?php echo $LOCALE_META_TEXT['LOCALE_NEXT_PAGE']; ?></div></a></div>
	<?php
	
	require_once(BOOK_ROOT . '/php/includes/table-of-things.php');
	require_once(BOOK_ROOT . '/php/includes/footer.php');
}


/**
 * Returns the id of the first item in an 
 * associative array.
 */
function getArrayFirstIndex($arr) {
	foreach ($arr as $key => $value)
	return $key;
}


/**
 * Returns the id of the last item in an 
 * associative array.
 */
function getArrayLastIndex($arr) {
	$result = '';
	foreach ($arr as $key => $value) {
		$result = $key;
	}
	return $result;
}
	
?>
