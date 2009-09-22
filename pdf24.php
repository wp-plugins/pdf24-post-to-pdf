<?php
/*
Plugin Name: PDF24 Articles To PDF
Plugin URI: http://www.pdf24.org
Description: A plugin that converts articles to PDF. Visitors of your blog can make a copy of articles in form of a PDF. Contents in the PDF are linked with your blog.
Author: Stefan Ziegler
Version: 2.3.3
Author URI: http://www.pdf24.org
*/


/**************************************************************************************
 * PLUGIN SETTINGS
 */
if(!isset($pdf24Plugin))
{
	//set flag so wordpress can't load twice
	$pdf24Plugin = true;
	
	//directory of pdf24 plugin
	$pdf24PluginDir = dirname(__FILE__);
	
	//url zum plugin
	$pdf24PluginUrl = get_option('siteurl') . '/wp-content/plugins/' . basename($pdf24PluginDir);

	//default language
	$pdf24PluginDefaultLang = 'en';
	
	//Url of pdf24.org which creates PDF
	$pdf24PluginScriptUrl = 'http://doc2pdf.pdf24.org/doc2pdf/wordpress.php';
	
	//include common functions
	include_once($pdf24PluginDir . '/inc/common.php');
	
	//include document options
	include_once($pdf24PluginDir . '/inc/docOptions.php');
	
	//set language elements for plugin
	pdf24Plugin_setLang();
}
	
function pdf24Plugin_adminMenu() 
{
	add_options_page('PDF24 Plugin Options Page', 'PDF24 Plugin', 8, 'pdf24', 'pdf24Plugin_options');
}

function pdf24Plugin_options() 
{
	global $pdf24PluginDir;
	include_once($pdf24PluginDir . '/inc/optionsPage.php');
}

function pdf24Plugin_head()
{		
	global $pdf24PluginUrl;
	
	$stylesArr = array();

	if(pdf24Plugin_isCpInUse())
	{
		pdf24Plugin_appendStyle('pdf24Plugin_cpStyle', 'pdf24Plugin_cpStyles', 'styles/ab', $stylesArr);
	}
	if(pdf24Plugin_isTbpInUse())
	{
		pdf24Plugin_appendStyle('pdf24Plugin_tbpStyle', 'pdf24Plugin_tbpStyles', 'styles/tbb', $stylesArr);
	}
	if(pdf24Plugin_isSbpInUse())
	{
		pdf24Plugin_appendStyle('pdf24Plugin_sbpStyle', 'pdf24Plugin_sbpStyles', 'styles/sb', $stylesArr);
	}
	if(count($stylesArr) > 0)
	{
		$outText = '';
		$outFiles = '';
		foreach($stylesArr as $val)
		{
			if($val[0] == 'text')
			{
				$outText .= $val[1];
			}
			else
			{
				$outFiles .= '<link rel="stylesheet" type="text/css" href="'. ($pdf24PluginUrl . '/' . $val[1]) .'" />' . "\n";
			}
		}
		echo $outFiles;
		if($outText != '')
		{
			echo "<style type=\"text/css\">\n". $outText ."\n</style>\n";
		}
	}
}

if(pdf24Plugin_isCpInUse())
{
	add_filter('the_content', 'pdf24Plugin_content');
}

if(pdf24Plugin_isCpInUse() || pdf24Plugin_isSbpInUse() || pdf24Plugin_isTbpInUse())
{
	add_action ('wp_head', 'pdf24Plugin_head' );	
}

add_action('admin_menu', 'pdf24Plugin_adminMenu');

?>