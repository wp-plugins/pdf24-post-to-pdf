<?php

/*
Plugin Name: PDF24 Post to PDF
Plugin URI: http://pdf24.org
Description: A plugin that convert posts to PDF and send the PDF to an email
Author: Stefan Ziegler
Version: 1.1
Author URI: http://www.pdf24.org
*/


/******  SETTINGS  **************************************/

//Styles für das Formular 1
$pdf24PluginStyleForm1 		= "border: 1px solid silver; padding: 2px;";
$pdf24PluginStyleInput1 	= "width: 150px";
$pdf24PluginStyleButton1 	= "";
$pdf24PluginStyleTable1 	= "padding:0px; width:100%";

$pdf24PluginLang = array();

$pdf24PluginLang["de"]["enterEmail"]		= "Emailaddresse";
$pdf24PluginLang["de"]["send"]				= "Senden";
$pdf24PluginLang["de"]["postAsPdf"]			= "Beitrag als PDF an";

$pdf24PluginLang["def"]["enterEmail"]		= "Enter email address";
$pdf24PluginLang["def"]["send"]				= "Send";
$pdf24PluginLang["def"]["postAsPdf"]		= "Send post as PDF to";

//ein Index aus $pdf24PluginLang oder 'detectFromBrowser' zur automatischen Bestimmung
$pdf24PluginUseLang 						= "detectFromBrowser";

//Seite von pdf24.org zur PDF-Erstellung
$pdf24PluginScriptUrl = "http://doc2pdf.pdf24.org/doc2pdf/wordpress.php";

/******  END SETTINGS  **************************************/

function pdf24Plugin_getLangVal($key)
{
	global $pdf24PluginUseLang, $pdf24PluginLang;
	
	return $pdf24PluginLang[$pdf24PluginUseLang][$key];
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
	$arr = array(		
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


function pdf24Plugin_getForm1(&$postsArr, $id) 
{
	global $pdf24PluginScriptUrl, $pdf24PluginStyleForm1, $pdf24PluginStyleInput1, $pdf24PluginStyleButton1;
	global $pdf24PluginStyleTable1;

	$out = "<form id=pdf24Form_".$id." method=\"POST\" action=\"".$pdf24PluginScriptUrl."\" style=\"".$pdf24PluginStyleForm1."\" target=\"pdf24PopWin\" onsubmit=\"window.open('about:blank', 'pdf24PopWin', 'scrollbars=yes,width=400,height=200,top=0,left=0'); return true;\">";
	$out .= pdf24Plugin_getBlogHiddenFields($postsArr);
	$out .= pdf24Plugin_getPostsHiddenFields($postsArr);
	$out .= "<table style=\"".$pdf24PluginStyleTable1."\" border=0><tr><td align=\"left\">";
	$out .= pdf24Plugin_getLangVal("postAsPdf");	
	$out .= " <input type=\"text\" name=\"sendEmailTo\" value=\"".pdf24Plugin_getLangVal("enterEmail")."\" style=\"".$pdf24PluginStyleInput1."\" onMouseDown=\"this.value = '';\">";	
	$out .= " <input type=\"submit\" value=\"".pdf24Plugin_getLangVal("send")."\" style=\"".$pdf24PluginStyleButton1."\">";
	$out .= "</td><td width=\"18\"><a href=\"http://www.pdf24.org\" target=\"_blank\" title=\"www.pdf24.org\"><img src=\"http://www.pdf24.org/images/sheep_16x16.gif\" alt=\"www.pdf24.org\" border=\"0\"></a></td></table>";	
	$out .= "</form>";
	
	return $out;
}

function pdf24Plugin_checkUseLang()
{
	global $pdf24PluginUseLang, $pdf24PluginLang;
	
	if($pdf24PluginUseLang == "detectFromBrowser")
	{
		$setLang = "def";
		
		if(isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
		{
			$l = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2);
			if(isset($pdf24PluginLang[$l]))
			{
				$setLang = $l;
			}
		}
			
		$GLOBALS["pdf24PluginUseLang"] = $setLang;
	}
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
	$out 		= pdf24Plugin_getForm1($postsArr, $id);
		
	return $content . $out;
}

add_filter("the_content", "pdf24Plugin_content");


?>