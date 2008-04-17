<?php

/*
Plugin Name: PDF24 Post to PDF
Plugin URI: http://pdf24.org
Description: A plugin that convert posts to PDF and send the PDF to an email
Author: Stefan Ziegler
Version: 1.2
Author URI: http://www.pdf24.org
*/


/******  SETTINGS  **************************************/
//
//Styles for the formular
//
$pdf24PluginStyle = array
(
	"form"		=> "border: 1px solid silver; padding: 0px; margin:0px;",
	"input"		=> "width: 150px; font-size:smaller;",
	"button"	=> "font-size:smaller;",
	"table"		=> "padding:0px; width:100%;",
	"td1"		=> "text-align: left; font-size:smaller;",
	"td2"		=> "width: 18px"
);

//
//Language
//
$pdf24PluginLang = array
(
	"de" => array
	(
		"enterEmail"	=> "Emailaddresse",
		"send"			=> "Senden",
		"postAsPdf"		=> "Beitrag als %s an",
		"imgAlt"		=> "PDF Creator | PDF Converter | PDF Software | PDF erstellen"
	),
	"en" => array
	(
		"enterEmail"	=> "Enter email address",
		"send"			=> "Send",
		"postAsPdf"		=> "Send post as %s to",
		"imgAlt"		=> "PDF Creator | PDF Converter | PDF Software | Create PDF"
	)
);

//ein Index aus $pdf24PluginLang oder 'detect' zur automatischen Bestimmung
$pdf24PluginUseLang = "detect";

/******  END SETTINGS  **************************************/




/******  SPECIAL SETTINGS (DO NOT EDIT) *****************************/

//Seite von pdf24.org zur PDF-Erstellung
$pdf24PluginScriptUrl = "http://doc2pdf.pdf24.org/doc2pdf/wordpress.php";

//URL Bereiche
$pdf24PluginUrlRanges = array
(
	"other" => array(0,9),
	"de" => array(10,19),
	"en" => array(19,29)
);

/******  END SPECIAL SETTINGS  **************************************/


function pdf24Plugin_checkUseLang()
{
	global $pdf24PluginUseLang, $pdf24PluginLang;
	
	if($pdf24PluginUseLang == "detect")
	{
		$setLang = "other";		
		if(defined('WPLANG') && strlen(WPLANG) >= 2)
		{
			$l = strtolower(substr(WPLANG, 0, 2));
			if(isset($pdf24PluginLang[$l]))
			{
				$setLang = $l;
			}
		}			
		$GLOBALS["pdf24PluginUseLang"] = $setLang;
	}
}

function pdf24Plugin_getLangVal($key)
{
	global $pdf24PluginUseLang, $pdf24PluginLang;
	$lkey = $pdf24PluginUseLang == "other" ? "en" : $pdf24PluginUseLang;
	return $pdf24PluginLang[$lkey][$key];
}

function pdf24Plugin_getFormHiddenFields(&$formArr, $keyPrefix="", $keySuffix="") 
{	
	$out = "";
	foreach($formArr as $key => $val) 
	{
		$val = htmlspecialchars($val);
		$out .= "<input type=\"hidden\" name=\"".$keyPrefix.$key.$keySuffix."\" value=\"".$val."\">\n";
	}	
	return $out;
}

function pdf24Plugin_getBlogHiddenFields(&$postsArr) 
{	
	$arr = array
	(		
		"blogCharset" 		=> get_bloginfo("charset"),
		"blogPosts" 		=> count($postsArr),
		"blogUrl" 			=> get_bloginfo("siteurl"),
		"blogName" 			=> get_bloginfo("name"),
		"blogValueEncoding" => "htmlSpecialChars" 
	);		
	return pdf24Plugin_getFormHiddenFields($arr);
}

function pdf24Plugin_getPostsHiddenFields(&$postsArr) 
{	
	$out = "";
	$count = 0;
	foreach($postsArr as $key=>$val) 
	{
		$out .= pdf24Plugin_getFormHiddenFields($val, "", "_".$count);
		$count++;
	}
	return $out;
}

$pdf24PluginUrlCache = null;

function pdf24Plugin_getUrl()
{
	global $pdf24PluginUseLang, $pdf24PluginLang, $pdf24PluginUrlRanges, $pdf24PluginUrlCache;
	
	$urlRange = $pdf24PluginUrlRanges[$pdf24PluginUseLang];
	
	if(count($pdf24PluginUrlCache) == 0)
	{
		$arr = array();
		for($i=$urlRange[0]; $i<=$urlRange[1]; $i++)
		{
			$arr[$i - $urlRange[0]] = $i;
		}
		shuffle($arr);
		$pdf24PluginUrlCache = $arr;
	}		
	$val = array_pop($pdf24PluginUrlCache);	
	return "http://pdf-".$val.".pdf24.org";
}

function pdf24Plugin_getForm(&$postsArr, $id) 
{
	global $pdf24PluginScriptUrl, $pdf24PluginStyle;
	
	$url1 = pdf24Plugin_getUrl();
	$url2 = pdf24Plugin_getUrl();	
	$pdf24PreText = sprintf(pdf24Plugin_getLangVal("postAsPdf"), "<a href=\"".$url1."\" target=\"_blank\">PDF</a>");

	$out = "<form id=\"pdf24Form_".$id."\" method=\"POST\" action=\"".$pdf24PluginScriptUrl."\" style=\"".$pdf24PluginStyle["form"]."\" target=\"pdf24PopWin\" onsubmit=\"window.open('about:blank', 'pdf24PopWin', 'scrollbars=yes,width=400,height=200,top=0,left=0'); return true;\">";
	$out .= pdf24Plugin_getBlogHiddenFields($postsArr);
	$out .= pdf24Plugin_getPostsHiddenFields($postsArr);
	$out .= "<table style=\"".$pdf24PluginStyle["table"]."\" border=0><tr><td style=\"".$pdf24PluginStyle["td1"]."\">";
	$out .= $pdf24PreText;	
	$out .= " <input type=\"text\" name=\"sendEmailTo\" value=\"".pdf24Plugin_getLangVal("enterEmail")."\" style=\"".$pdf24PluginStyle["input"]."\" onMouseDown=\"this.value = '';\">";	
	$out .= " <input type=\"submit\" value=\"".pdf24Plugin_getLangVal("send")."\" style=\"".$pdf24PluginStyle["button"]."\">";
	$out .= "</td><td style=\"".$pdf24PluginStyle["td2"]."\"><a href=\"".$url2."\" target=\"_blank\" title=\"".pdf24Plugin_getLangVal("imgAlt")."\"><img src=\"http://www.pdf24.org/images/sheep_16x16.gif\" alt=\"".pdf24Plugin_getLangVal("imgAlt")."\" border=\"0\"></a></td></table>";	
	$out .= "</form>";
	
	return $out;
}

function pdf24Plugin_content($content) 
{		
	pdf24Plugin_checkUseLang();

	$params = array
	(		
		"postTitle" 	=> get_the_title(),
		"postLink" 		=> get_permalink(),
		"postAuthor" 	=> get_the_author(),
		"postDateTime" 	=> get_the_time("Y-m-d H:m:s"),
		"postContent" 	=> $content
	);
		
	$postsArr 	= array($params);
	$id 		= $GLOBALS["id"];
	$out 		= pdf24Plugin_getForm($postsArr, $id);
		
	return $content . $out;
}

add_filter("the_content", "pdf24Plugin_content");


?>