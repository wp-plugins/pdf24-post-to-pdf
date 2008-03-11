<?php

/*
Plugin Name: PDF24 Posts to PDF
Plugin URI: http://pdf24.org
Description: A plugin that convert posts to PDF and send the PDF to an email
Author: Stefan Ziegler
Version: 1.1
Author URI: http://www.pdf24.org
*/


/**********  SETTINGS ******************************/

$pdf24StyleForm 	= "text-align:center; border: 1px solid silver; padding: 5px;";
$pdf24StyleInput 	= "";
$pdf24StyleButton 	= "";

$pdf24Lang = array();

//german Language
$pdf24Lang["de"]["enterEmail"]		= "Emailaddresse";
$pdf24Lang["de"]["send"]			= "Senden";
$pdf24Lang["de"]["postsAsPdf"]		= "BeitrÃ¤ge als PDF an";
$pdf24Lang["de"]["linkTitle"]		= "Kostenlos PDF erstellen und kostenloser PDF Creator und PDF Converter";

//default Language
$pdf24Lang["def"]["enterEmail"]		= "Enter email address";
$pdf24Lang["def"]["send"]			= "Send";
$pdf24Lang["def"]["postsAsPdf"]		= "Send posts as PDF to";
$pdf24Lang["def"]["linkTitle"]		= "create PDF and free PDF Creator and free PDF converter";

//ein Index aus $pdf24PluginLang oder 'detectFromBrowser' zur automatischen Bestimmung
$pdf24UseLang 						= "detectFromBrowser";


//Script auf pdf24, welches die Anfrage bearbeitet
$pdf24ScriptUrl 	= "http://doc2pdf.pdf24.org/doc2pdf/wordpress.php";

/********** END SETTINGS ******************************/

//Sprache setzen, wenn detectFromBrowser
if($pdf24UseLang == "detectFromBrowser")
{
	$setLang = "def";
	
	if(isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
	{
		$l = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2);
		if(isset($pdf24Lang[$l]))
		{
			$setLang = $l;
		}
	}
	$pdf24UseLang 	= $setLang;
	$pdf24Langu 	= $pdf24Lang[$pdf24UseLang];
}

$pdf24Langu = $pdf24Lang[$pdf24UseLang];

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


echo "<form method=\"POST\" action=\"".$pdf24ScriptUrl."\" style=\"".$pdf24StyleForm."\" target=\"pdf24PopWin\" onsubmit=\"window.open('about:blank', 'pdf24PopWin', 'resizable=yes,scrollbars=yes,width=400,height=200,top=0,left=0'); return true;\">\n";
echo pdf24_getFormHiddenFields($pdf24BlogArr);

$pdf24Count = 0;
foreach($pdf24PostsArr as $key=>$val) 
{
	echo pdf24_getFormHiddenFields($val, "", "_".$pdf24Count);	
	$pdf24Count++;
}

echo "<b>".$pdf24Langu["postsAsPdf"]."</b>";	
echo " <input type=\"text\" name=\"sendEmailTo\" value=\"".$pdf24Langu["enterEmail"]."\" style=\"".$pdf24StyleInput."\" onMouseDown=\"this.value = '';\">";	
echo " <input type=\"submit\" value=\"".$pdf24Langu["send"]."\" style=\"".$pdf24StyleButton."\">";
echo " <br> <a href=\"http://www.pdf24.org\" target=\"_blank\" title=\"".$pdf24Langu["linkTitle"]."\">www.pdf24.org</a>";
echo "</form>";

?>