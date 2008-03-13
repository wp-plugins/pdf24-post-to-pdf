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

//available themes are "blue", "simple"
//If you want to show all themes, use "all"
$pdf24Theme = "simple";


/*
 * Thats the Language area. To provide a new Language only insert a new language block
 * like the following ones. You have to set correctly the letter code of the language.
 *
 * %s in the texts will be replaced by another text and may not be deleted. 
 */
 $pdf24Lang = array();

//german Language
$pdf24Lang["de"]["enterEmail"]		= "Emailaddresse";
$pdf24Lang["de"]["send"]			= "Senden";
$pdf24Lang["de"]["postsAsPdf"]		= "BeitrÃ¤ge als %s an";
$pdf24Lang["de"]["linkTitle"]		= "PDF Creator | PDF Converter | PDF Software | PDF erstellen";
$pdf24Lang["de"]["linkText"]		= "PDF Creator";

//default Language
$pdf24Lang["def"]["enterEmail"]		= "Enter email address";
$pdf24Lang["def"]["send"]			= "Send";
$pdf24Lang["def"]["postsAsPdf"]		= "Send posts as %s to";
$pdf24Lang["def"]["linkTitle"]		= "PDF Creator | PDF Converter | PDF Software | Create PDF";
$pdf24Lang["def"]["linkText"]		= "PDF Creator";

//an index from $pdf24Lang e.g. "de" or "def" or "detectFromBrowser" to set language from the visitors preferred language
$pdf24UseLang 						= "detectFromBrowser";

//Script on pdf24, which handles the requests and which creates and sends the pdf
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

$formHiddenFields1 = pdf24_getFormHiddenFields($pdf24BlogArr);
$formHiddenFields2 = "";

$pdf24Count = 0;
foreach($pdf24PostsArr as $key=>$val) 
{
	$formHiddenFields2 .= pdf24_getFormHiddenFields($val, "", "_".$pdf24Count);	
	$pdf24Count++;
}

$pdf24TextHead = sprintf($pdf24Langu["postsAsPdf"], "<a href=\"http://www.pdf24.org\" target=\"_blank\">PDF</a>");

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
			<a href="http://www.pdf24.org" target="_blank" title="<? echo $pdf24Langu["linkTitle"]; ?>"><? echo $pdf24Langu["linkText"]; ?></a>
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
		<br> <a href="http://www.pdf24.org" target="_blank" title="<? echo $pdf24Langu["linkTitle"]; ?>"><? echo $pdf24Langu["linkText"]; ?></a>
		</form>
	</div>
<?
}

