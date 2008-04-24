<?php

/*
Plugin Name: PDF24 Posts to PDF
Plugin URI: http://pdf24.org
Description: A plugin that convert posts to PDF and send the PDF to an email
Author: Stefan Ziegler
Version: 1.3
Author URI: http://www.pdf24.org
*/


/**********  SETTINGS ******************************/

//available themes are "blue", "simple"
//If you want to show all themes, use "all"
$pdf24Theme = "simple";

//
//Language
//
$pdf24Lang = array
(
	"de" => array
	(
		"enterEmail"	=> "Emailaddresse", 
		"send"			=> "Senden",
		"postsAsPdf"	=> "BeitrÃ¤ge als %s an",
		"linkTitle"		=> "PDF Creator | PDF Converter | PDF Software | PDF erstellen",
		"linkText"		=> "PDF Creator"
	),
	"en" => array
	(
		"enterEmail"	=> "Enter email address", 
		"send"			=> "Send",
		"postsAsPdf"	=> "Send posts as %s to",
		"linkTitle"		=> "PDF Creator | PDF Converter | PDF Software | Create PDF",
		"linkText"		=> "PDF Creator"
	)
);

//
//an index from $pdf24Lang e.g. "de" or "detect" to set from wordpress settings
//
$pdf24UseLang = "detect";

/********** END SETTINGS ******************************/






/****** (DO NOT EDIT) *****************************/

//Seite von pdf24.org zur PDF-Erstellung
$pdf24ScriptUrl = "http://doc2pdf.pdf24.org/doc2pdf/wordpress.php";

//Languages
/*
$pdf24LangCodes = array
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
$pdf24UrlRanges=array("other","de","en","aa","ab","af","am","ar","as","ay","az","ba","be","bg","bh","bi","bn","bo","br","ca","co","cs","cy","da","dz","el","eo","es","et","eu","fa","fi","fj","fo","fr","fy","ga","gd","gl","gn","gu","ha","he","hi","hr","hu","hy","ia","id","ie","ik","is","it","iu","ja","jw","ka","kk","kl","km","kn","ko","ks","ku","ky","la","ln","lo","lt","lv","mg","mi","mk","ml","mn","mo","mr","ms","mt","my","na","ne","nl","no","oc","om","or","pa","pl","ps","pt","qu","rm","rn","ro","ru","rw","sa","sd","sg","sh","si","sk","sl","sm","sn","so","sq","sr","ss","st","su","sv","sw","ta","te","tg","th","ti","tk","tn","to","tr","ts","tt","tw","ug","uk","ur","uz","vi","vo","wo","xh","yi","yo","za","zh","zu");

//Sprache setzen, wenn detectFromBrowser
if($pdf24UseLang == "detect")
{
	if(defined('WPLANG') && strlen(WPLANG) >= 2)
	{
		$pdf24UseLang = strtolower(substr(WPLANG, 0, 2));
	}					
}

$pdf24Langu = isset($pdf24Lang[$pdf24UseLang]) ? $pdf24Lang[$pdf24UseLang] : $pdf24Lang["en"];

//zurücksetzen
rewind_posts();	
$pdf24PostsArr = array();

//pdf24 filter deaktivieren
remove_filter("the_content","pdf24Plugin_content");

if (have_posts()) 
{
	while (have_posts()) 
	{
		the_post();
		
		//filter auf content anwenden
		$content = get_the_content();
		$content =  apply_filters('the_content', $content);

		$pdf24Params = array
		(		
			"postTitle" => get_the_title(),
			"postLink" => get_permalink(),
			"postAuthor" => get_the_author(),
			"postDateTime" => get_the_time("Y-m-d H:m:s"),
			"postContent" => $content
		);
		$pdf24PostsArr[] = $pdf24Params;			
	}
}
	
rewind_posts();
	
	
$pdf24BlogArr = array
(	
	"blogCharset" => get_bloginfo("charset"),
	"blogPosts" => count($pdf24PostsArr),
	"blogUrl" => get_bloginfo("siteurl"),
	"blogName" => get_bloginfo("name"),
	"blogValueEncoding" => "htmlSpecialChars"
);

function pdf24_getFormHiddenFields(&$formArr, $keyPrefix="", $keySuffix="") 
{	
	$out = "";
	foreach($formArr as $key => $val) 
	{
		$val = htmlspecialchars($val);
		$out .= "<input type=\"hidden\" name=\"".$keyPrefix.$key.$keySuffix."\" value=\"".$val."\">\n";
	}	
	return $out;
}

$formHiddenFields1 = pdf24_getFormHiddenFields($pdf24BlogArr);
$formHiddenFields2 = "";

$pdf24Count = 0;
foreach($pdf24PostsArr as $key=>$val) 
{
	$formHiddenFields2 .= pdf24_getFormHiddenFields($val, "", "_".$pdf24Count);	
	$pdf24Count++;
}

$pdf24Offset = 0;
$pdf24UseVal = in_array($pdf24UseLang, $pdf24UrlRanges) ? $pdf24UseLang : "other";		
foreach($pdf24UrlRanges as $key => $val)
{
	if($val == $pdf24UseVal)
	{
		$pdf24Offset = $key;
		break;
	}
}	
$pdf24RangeStart = $pdf24Offset * 10;
$pdf24RangeEnd = $pdf24RangeStart + 9;

$pdf24Val1 = rand($pdf24RangeStart, $pdf24RangeEnd);
do
{
	$pdf24Val2 = rand($pdf24RangeStart, $pdf24RangeEnd);
} while($pdf24RangeEnd - $pdf24RangeStart > 1 && $pdf24Val1 == $pdf24Val2);

$pdf24Url1 = "http://pdf-".$pdf24Val1.".pdf24.org";
$pdf24Url2 = "http://pdf-".$pdf24Val2.".pdf24.org";

$pdf24TextHead = sprintf($pdf24Langu["postsAsPdf"], "<a href=\"".$pdf24Url1."\" target=\"_blank\">PDF</a>");

if($pdf24Theme == "blue" || $pdf24Theme == "all")
{
?>
	<div style="background-image:url(http://www.pdf24.org/images/plugins/box_big.gif); background-repeat: no-repeat; width:190px; height:125px; position:relative; padding:4px; font-family: Verdana, Arial">
		<div style="position:absolute; left: 10px; top: 4px; text-align:left; width:110px">
			<span style="color:#fff; font-family: Verdana, Arial; font-size: 14px; font-weight:bold"><? echo $pdf24TextHead; ?></span>
		</div>
		<form method="POST" target="pdf24PopWin" action="<? echo $pdf24ScriptUrl; ?>" style="text-align:center; padding: 5px;" onsubmit="window.open('about:blank', 'pdf24PopWin', 'resizable=yes,scrollbars=yes,width=400,height=200,top=0,left=0'); return true;">
		<? echo $formHiddenFields1; ?>
		<? echo $formHiddenFields2; ?>

			<div style="position:absolute; left: 10px; top: 71px;">
				<input type="text" name="sendEmailTo" value="<? echo $pdf24Langu["enterEmail"]; ?>" style="width: 168px; height: 17px; border:1px solid silver" onMouseDown="this.value = '';">
			</div>
			<div style="position:absolute; left: 155px; top: 100px;">
				<input type="image" src="http://www.pdf24.org/images/plugins/go1.gif">
			</div>
		</form>
		<div style="position:absolute; left: 11px; top: 103px; font-size:11px;">
			<a href="<? echo $pdf24Url2; ?>" target="_blank" title="<? echo $pdf24Langu["linkTitle"]; ?>"><? echo $pdf24Langu["linkText"]; ?></a>
		</div>
	</div>
<?
}

if($pdf24Theme == "simple" || $pdf24Theme == "all")
{
?>
	<div>
		<form method="POST" action="<? echo $pdf24ScriptUrl; ?>" style="text-align:center; border: 1px solid silver; padding: 5px;" target="pdf24PopWin" onsubmit="window.open('about:blank', 'pdf24PopWin', 'resizable=yes,scrollbars=yes,width=400,height=200,top=0,left=0'); return true;">
		<? echo $formHiddenFields1; ?>
		<? echo $formHiddenFields2; ?>

		<b><? echo $pdf24TextHead; ?></b>	
		<input type="text" name="sendEmailTo" value="<? echo $pdf24Langu["enterEmail"]; ?>" onMouseDown="this.value = '';">
		<input type="submit" value="<? echo $pdf24Langu["send"]; ?>">
		<br> <a href="<? echo $pdf24Url2; ?>" target="_blank" title="<? echo $pdf24Langu["linkTitle"]; ?>"><? echo $pdf24Langu["linkText"]; ?></a>
		</form>
	</div>
<?
}

