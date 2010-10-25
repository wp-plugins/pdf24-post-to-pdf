<?php
/*
Plugin Name: PDF24 Articles To PDF
Plugin URI: http://www.pdf24.org
Description: A plugin that converts articles to PDF. Visitors of your blog can make a copy of articles in form of a PDF. Contents in the PDF are linked with your blog.
Author: Stefan Ziegler
Version: 3.0.6
Author URI: http://www.pdf24.org
*/


/**************************************************************************************
 * PLUGIN SETTINGS
 */
if(!isset($pdf24Plugin)) {
	require_once( ABSPATH . WPINC . '/pluggable.php' );
	
	//array holding all pdf24 data
	global $pdf24Plugin;
	$pdf24Plugin = array(
		'dir' => dirname(__FILE__),
		'url' => get_option('siteurl') . '/wp-content/plugins/' . basename(dirname(__FILE__))
	);
	
	//include necessary files
	include_once($pdf24Plugin['dir'] . '/inc/config.php');
	include_once($pdf24Plugin['dir'] . '/inc/common.php');
		
	//set language elements for plugin
	pdf24Plugin_setLang();
}
	
function pdf24Plugin_adminMenu() {
	add_options_page('PDF24 Plugin Options Page', 'PDF24 Plugin', 8, 'pdf24', 'pdf24Plugin_options');
}

function pdf24Plugin_options() {
	global $pdf24Plugin;
	include_once($pdf24Plugin['dir'] . '/inc/optionsPage.php');
}

function pdf24Plugin_head() {		
	global $pdf24Plugin;
	
	$stylesArr = array();
	if(pdf24Plugin_isCpInUse()) {
		pdf24Plugin_appendStyle('pdf24Plugin_cpStyle', 'styles/cp', $stylesArr);
	}
	if(pdf24Plugin_isTbpInUse()) {
		pdf24Plugin_appendStyle('pdf24Plugin_tbpStyle', 'styles/tbp', $stylesArr);
	}
	if(pdf24Plugin_isSbpInUse() && is_active_widget('pdf24Plugin_widget')) {
		pdf24Plugin_appendStyle('pdf24Plugin_sbpStyle', 'styles/sbp', $stylesArr);
	}
	if(pdf24Plugin_isLpInUse()) {
		pdf24Plugin_appendStyle('pdf24Plugin_lpStyle', 'styles/lp', $stylesArr);
	}
	if(count($stylesArr) > 0) {
		$outText = '';
		$outFiles = '';
		foreach($stylesArr as $val) {
			if($val[0] == 'text') {
				$outText .= $val[1];
			} else {
				$outFiles .= '<link rel="stylesheet" type="text/css" href="'. ($pdf24Plugin['url'] . '/' . $val[1]) .'" />' . "\n";
			}
		}
		echo $outFiles;
		if($outText != '') {
			echo "<style type=\"text/css\">\n". $outText ."\n</style>\n";
		}
	}
}

function pdf24Plugin_registerWidget() {
	global $pdf24Plugin;
	$widgetId = 'Articles To PDF';
	if(function_exists('wp_register_sidebar_widget')) {
		wp_register_sidebar_widget( $widgetId, $widgetId, 'pdf24Plugin_widget');		
		include_once($pdf24Plugin['dir'] . '/inc/widgetControl.php');
		wp_register_widget_control($widgetId, $widgetId, 'pdf24Plugin_widgetControl');
	}
	else {
		register_sidebar_widget($widgetId, 'pdf24Plugin_widget');		
		include_once($pdf24Plugin['dir'] . '/inc/widgetControl.php');
		register_widget_control($widgetId, 'pdf24Plugin_widgetControl');
	}
}

if(pdf24Plugin_isAvailable()) {
	if(pdf24Plugin_isCpInUse()) {
		add_filter('the_content', 'pdf24Plugin_content', $pdf24Plugin['contentFilterPriority']);
	}
	if(pdf24Plugin_isCpInUse() || pdf24Plugin_isSbpInUse() || pdf24Plugin_isTbpInUse() || pdf24Plugin_isLpInUse()) {
		add_action ('wp_head', 'pdf24Plugin_head' );	
	}
}

add_action('plugins_loaded', 'pdf24Plugin_registerWidget');
add_action('admin_menu', 'pdf24Plugin_adminMenu');

?>