<?php

/*
Plugin Name: PDF24 Post to PDF
Plugin URI: http://pdf24.org
Description: A plugin that convert a post to PDF and send the PDF to an email
Author: Stefan Ziegler
Version: 1.5
Author URI: http://www.pdf24.org
*/


/******  SETTINGS  **************************************/

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
/******  END SETTINGS  **************************************/


function pdf24Plugin_checkUseLang()
{
	global $pdf24PluginUseLang, $pdf24PluginLang;
	
	if($pdf24PluginUseLang == "detect")
	{
		if(defined('WPLANG') && strlen(WPLANG) >= 2)
		{
			$l = strtolower(substr(WPLANG, 0, 2));
			$GLOBALS["pdf24PluginUseLang"] = $l;
		}					
	}
}

function pdf24Plugin_getLangVal($key)
{
	global $pdf24PluginUseLang, $pdf24PluginLang;
	$l = isset($pdf24PluginLang[$pdf24PluginUseLang]) ? $pdf24PluginUseLang : "en";
	return $pdf24PluginLang[$l][$key];
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

function pdf24Plugin_getUrl()
{
	global $pdf24PluginUseLang, $pdf24PluginUrlRanges;
	
	$l = in_array($pdf24PluginUseLang, $pdf24PluginUrlRanges) ? $pdf24PluginUseLang : "www";
	return "http://".$l.".pdf24.org";
}

/*
$pdf24PluginUrlCache = array();

function pdf24Plugin_getUrl()
{
	global $pdf24PluginUseLang, $pdf24PluginUrlRanges, $pdf24PluginUrlCache;
		
	if(count($pdf24PluginUrlCache) == 0)
	{
		$c = 0;
		$l = in_array($pdf24PluginUseLang, $pdf24PluginUrlRanges) ? $pdf24PluginUseLang : "other";		
		foreach($pdf24PluginUrlRanges as $key => $val)
		{
			if($val == $l)
			{
				$c = $key;
				break;
			}
		}	
		$start = $c * 10;
		$end = $start + 9;
		$arr = array();
		for($i=$start; $i<=$end; $i++)
		{
			$arr[$i - $start] = $i;
		}
		shuffle($arr);
		$pdf24PluginUrlCache = $arr;
	}		
	$val = array_pop($pdf24PluginUrlCache);	
	return "http://pdf-".$val.".pdf24.org";
}
*/

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



/******  SPECIAL SETTINGS (DO NOT EDIT) *****************************/

//Seite von pdf24.org zur PDF-Erstellung
$pdf24PluginScriptUrl = "http://doc2pdf.pdf24.org/doc2pdf/wordpress.php";

//Languages
/*
$pdf24PluginLangCodes = array
(
	"aa" => "Afar",
	"ab" => "Abkhazian",
	"af" => "Afrikaans",
	"am" => "Amharic",
	"ar" => "Arabic",
	"as" => "Assamese",
	"ay" => "Aymara",
	"az" => "Azerbaijani",
	"ba" => "Bashkir",
	"be" => "Byelorussian",
	"bg" => "Bulgarian",
	"bh" => "Bihari",
	"bi" => "Bislama",
	"bn" => "Bengali",
	"bo" => "Tibetan",
	"br" => "Breton",
	"ca" => "Catalan",
	"co" => "Corsican",
	"cs" => "Czech",
	"cy" => "Welsh",
	"da" => "Danish",
	"de" => "German",
	"dz" => "Bhutani",
	"el" => "Greek",
	"en" => "English",
	"eo" => "Esperanto",
	"es" => "Spanish",
	"et" => "Estonian",
	"eu" => "Basque",
	"fa" => "Persian",
	"fi" => "Finnish",
	"fj" => "Fiji",
	"fo" => "Faroese",
	"fr" => "French",
	"fy" => "Frisian",
	"ga" => "Irish",
	"gd" => "Scots Gaelic",
	"gl" => "Galician",
	"gn" => "Guarani",
	"gu" => "Gujarati",
	"ha" => "Hausa",
	"he" => "Hebrew",
	"hi" => "Hindi",
	"hr" => "Croatian",
	"hu" => "Hungarian",
	"hy" => "Armenian",
	"ia" => "Interlingua",
	"id" => "Indonesian",
	"ie" => "Interlingue",
	"ik" => "Inupiak",
	"is" => "Icelandic",
	"it" => "Italian",
	"iu" => "Inuktitut",
	"ja" => "Japanese",
	"jw" => "Javanese",
	"ka" => "Georgian",
	"kk" => "Kazakh",
	"kl" => "Greenlandic",
	"km" => "Cambodian",
	"kn" => "Kannada",
	"ko" => "Korean",
	"ks" => "Kashmiri",
	"ku" => "Kurdish",
	"ky" => "Kirghiz",
	"la" => "Latin",
	"ln" => "Lingala",
	"lo" => "Laothian",
	"lt" => "Lithuanian",
	"lv" => "Latvian, Lettish",
	"mg" => "Malagasy",
	"mi" => "Maori",
	"mk" => "Macedonian",
	"ml" => "Malayalam",
	"mn" => "Mongolian",
	"mo" => "Moldavian",
	"mr" => "Marathi",
	"ms" => "Malay",
	"mt" => "Maltese",
	"my" => "Burmese",
	"na" => "Nauru",
	"ne" => "Nepali",
	"nl" => "Dutch",
	"no" => "Norwegian",
	"oc" => "Occitan",
	"om" => "(Afan) Oromo",
	"or" => "Oriya",
	"pa" => "Punjabi",
	"pl" => "Polish",
	"ps" => "Pashto, Pushto",
	"pt" => "Portuguese",
	"qu" => "Quechua",
	"rm" => "Rhaeto-Romance",
	"rn" => "Kirundi",
	"ro" => "Romanian",
	"ru" => "Russian",
	"rw" => "Kinyarwanda",
	"sa" => "Sanskrit",
	"sd" => "Sindhi",
	"sg" => "Sangho",
	"sh" => "Serbo-Croatian",
	"si" => "Sinhalese",
	"sk" => "Slovak",
	"sl" => "Slovenian",
	"sm" => "Samoan",
	"sn" => "Shona",
	"so" => "Somali",
	"sq" => "Albanian",
	"sr" => "Serbian",
	"ss" => "Siswati",
	"st" => "Sesotho",
	"su" => "Sundanese",
	"sv" => "Swedish",
	"sw" => "Swahili",
	"ta" => "Tamil",
	"te" => "Telugu",
	"tg" => "Tajik",
	"th" => "Thai",
	"ti" => "Tigrinya",
	"tk" => "Turkmen",
	"tl" => "Tagalog",
	"tn" => "Setswana",
	"to" => "Tonga",
	"tr" => "Turkish",
	"ts" => "Tsonga",
	"tt" => "Tatar",
	"tw" => "Twi",
	"ug" => "Uighur",
	"uk" => "Ukrainian",
	"ur" => "Urdu",
	"uz" => "Uzbek",
	"vi" => "Vietnamese",
	"vo" => "Volapuk",
	"wo" => "Wolof",
	"xh" => "Xhosa",
	"yi" => "Yiddish",
	"yo" => "Yoruba",
	"za" => "Zhuang",
	"zh" => "Chinese",
	"zu" => "Zulu"
);
*/

//URL Bereiche, jeder Eintrag bekommt einen Bereich von 10 Urls
$pdf24PluginUrlRanges=array("other","de","en","aa","ab","af","am","ar","as","ay","az","ba","be","bg","bh","bi","bn","bo","br","ca","co","cs","cy","da","dz","el","eo","es","et","eu","fa","fi","fj","fo","fr","fy","ga","gd","gl","gn","gu","ha","he","hi","hr","hu","hy","ia","id","ie","ik","is","it","iu","ja","jw","ka","kk","kl","km","kn","ko","ks","ku","ky","la","ln","lo","lt","lv","mg","mi","mk","ml","mn","mo","mr","ms","mt","my","na","ne","nl","no","oc","om","or","pa","pl","ps","pt","qu","rm","rn","ro","ru","rw","sa","sd","sg","sh","si","sk","sl","sm","sn","so","sq","sr","ss","st","su","sv","sw","ta","te","tg","th","ti","tk","tn","to","tr","ts","tt","tw","ug","uk","ur","uz","vi","vo","wo","xh","yi","yo","za","zh","zu");




?>