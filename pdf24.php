<?php
/*
Plugin Name: PDF24 Posts to PDF
Plugin URI: http://pdf24.org
Description: A plugin that converts posts to PDF and send the PDF to an email
Author: Stefan Ziegler
Version: 2.0
Author URI: http://www.pdf24.org
*/

if(!isset($pdf24Plugin))
{
	$pdf24Plugin = true;

	//Language
	$pdf24PluginLang = array
	(
		'de' => array
		(
			'enterEmail'	=> 'Emailaddresse',
			'send'			=> 'Senden',
			'postAsPdf'		=> 'Beitrag als PDF an',
			'postsAsPdf'	=> 'BeitrÃ¤ge als PDF an',
			'imgAlt'		=> 'PDF | PDF Software | PDF erstellen',
			'linkText'		=> 'PDF erstellen'
		),
		'en' => array
		(
			'enterEmail'	=> 'Enter email address',
			'send'			=> 'Send',
			'postAsPdf'		=> 'Send post as PDF to',
			'postsAsPdf'	=> 'Send posts as PDF to',
			'imgAlt'		=> 'PDF | PDF Creator | PDF Converter',
			'linkText'		=> 'PDF Creator'
		)
	);

	//ein Index aus $pdf24PluginLang oder 'detect' zur automatischen Bestimmung
	$pdf24PluginUseLang = 'en';
	
	//wenn gesetzt, werden Language ELemente aus diesem Array verwendet
	$pdf24PluginCustomLang = null;
	
	//Default Styles für das Content Plugin
	$pdf24PluginCpDefaultStyles = 
	'
	.pdf24Plugin-cp-box { border: 1px solid silver; padding: 3px; }
	.pdf24Plugin-cp-input { width: 150px; font-size:smaller; }
	.pdf24Plugin-cp-submit { font-size:smaller; }
	.pdf24Plugin-cp-box td { font-size:smaller; }
	.pdf24Plugin-cp-box a { font-size:smaller; }
	';
	
	//Default Styles für das Sidebar Plugin
	$pdf24PluginSbpDefaultStyles = 
	'
	.pdf24Plugin-sbp-box { text-align:center; border: 1px solid silver; padding: 5px; }
	.pdf24Plugin-sbp-title { width: 150px; font-weight:bold }
	.pdf24Plugin-sbp-sendto { font-size:smaller; }
	.pdf24Plugin-sbp-submit { font-size:smaller; }
	.pdf24Plugin-sbp-backlink { font-size:smaller; }
	';
	
	//Default Styles für das Sidebar Plugin
	$pdf24PluginSbpStyle1 = 
	//$pdf24PluginSbpDefaultStyles =
	'
	.pdf24Plugin-sbp-box { background-image:url(http://www.pdf24.org/images/plugins/box_big.gif); background-repeat: no-repeat; width:190px; height:125px; position:relative; padding:4px; font-family: Verdana, Arial }
	.pdf24Plugin-sbp-title { position:absolute; left: 10px; top: 4px; text-align:left; width:110px; color:#fff; font-size: 14px; font-weight:bold }
	.pdf24Plugin-sbp-sendto { position:absolute; left: 10px; top: 71px; }
	.pdf24Plugin-sbp-sendto input { width: 168px; height: 17px; border:1px solid silver }
	.pdf24Plugin-sbp-submit { position:absolute; right: 15px; top: 100px; }
	.pdf24Plugin-sbp-submit input { background-color: #66A4DA; border:0px;  border-left: 1px solid silver; border-bottom: 1px solid silver; color:#fff; font-weight:bold; padding:1px; cursor: pointer}
	.pdf24Plugin-sbp-backlink { position:absolute; left: 11px; top: 103px; font-size:11px; }
	';

	//Seite von pdf24.org zur PDF-Erstellung
	$pdf24PluginScriptUrl = 'http://doc2pdf.pdf24.org/doc2pdf/wordpress.php';
	
	//Languages
	$pdf24PluginLangCodes = array
	(
		'aa' => 'Afar',			'ab' => 'Abkhazian',		'af' => 'Afrikaans',		'am' => 'Amharic',
		'ar' => 'Arabic',		'as' => 'Assamese',			'ay' => 'Aymara',			'az' => 'Azerbaijani',
		'ba' => 'Bashkir',		'be' => 'Byelorussian',		'bg' => 'Bulgarian',		'bh' => 'Bihari',
		'bi' => 'Bislama',		'bn' => 'Bengali',			'bo' => 'Tibetan',			'br' => 'Breton',
		'ca' => 'Catalan',		'co' => 'Corsican',			'cs' => 'Czech',			'cy' => 'Welsh',
		'da' => 'Danish',		'de' => 'German',			'dz' => 'Bhutani',			'el' => 'Greek',
		'en' => 'English',		'eo' => 'Esperanto',		'es' => 'Spanish',			'et' => 'Estonian',
		'eu' => 'Basque',		'fa' => 'Persian',			'fi' => 'Finnish',			'fj' => 'Fiji',
		'fo' => 'Faroese',		'fr' => 'French',			'fy' => 'Frisian',			'ga' => 'Irish',
		'gd' => 'Scots Gaelic',	'gl' => 'Galician',			'gn' => 'Guarani',			'gu' => 'Gujarati',
		'ha' => 'Hausa',		'he' => 'Hebrew',			'hi' => 'Hindi',			'hr' => 'Croatian',
		'hu' => 'Hungarian',	'hy' => 'Armenian',			'ia' => 'Interlingua',		'id' => 'Indonesian',
		'ie' => 'Interlingue',	'ik' => 'Inupiak',			'is' => 'Icelandic',		'it' => 'Italian',
		'iu' => 'Inuktitut',	'ja' => 'Japanese',			'jw' => 'Javanese',			'ka' => 'Georgian',
		'kk' => 'Kazakh',		'kl' => 'Greenlandic',		'km' => 'Cambodian',		'kn' => 'Kannada',
		'ko' => 'Korean',		'ks' => 'Kashmiri',			'ku' => 'Kurdish',			'ky' => 'Kirghiz',
		'la' => 'Latin',		'ln' => 'Lingala',			'lo' => 'Laothian',			'lv' => 'Latvian, Lettish',
		'lt' => 'Lithuanian',	'mg' => 'Malagasy',			'mi' => 'Maori',			'mk' => 'Macedonian',
		'ml' => 'Malayalam',	'mn' => 'Mongolian',		'mo' => 'Moldavian',		'mr' => 'Marathi',
		'ms' => 'Malay',		'mt' => 'Maltese',			'my' => 'Burmese',			'na' => 'Nauru',
		'ne' => 'Nepali',		'nl' => 'Dutch',			'no' => 'Norwegian',		'oc' => 'Occitan',
		'om' => '(Afan) Oromo',	'or' => 'Oriya',			'pa' => 'Punjabi',			'ps' => 'Pashto, Pushto',
		'pl' => 'Polish',		'pt' => 'Portuguese',		'qu' => 'Quechua',			'rm' => 'Rhaeto-Romance',
		'rn' => 'Kirundi',		'ro' => 'Romanian',			'ru' => 'Russian',			'rw' => 'Kinyarwanda',
		'sa' => 'Sanskrit',		'sd' => 'Sindhi',			'sg' => 'Sangho',			'sh' => 'Serbo-Croatian',
		'si' => 'Sinhalese',	'sk' => 'Slovak',			'sl' => 'Slovenian',		'sm' => 'Samoan',
		'sn' => 'Shona',		'so' => 'Somali',			'sq' => 'Albanian',			'sr' => 'Serbian',
		'ss' => 'Siswati',		'st' => 'Sesotho',			'su' => 'Sundanese',		'sv' => 'Swedish',
		'sw' => 'Swahili',		'ta' => 'Tamil',			'te' => 'Telugu',			'tg' => 'Tajik',
		'th' => 'Thai',			'ti' => 'Tigrinya',			'tk' => 'Turkmen',			'tl' => 'Tagalog',
		'tn' => 'Setswana',		'to' => 'Tonga',			'tr' => 'Turkish',			'ts' => 'Tsonga',
		'tt' => 'Tatar',		'tw' => 'Twi',				'ug' => 'Uighur',			'uk' => 'Ukrainian',
		'ur' => 'Urdu',			'uz' => 'Uzbek',			'vi' => 'Vietnamese',		'vo' => 'Volapuk',
		'wo' => 'Wolof',		'xh' => 'Xhosa',			'yi' => 'Yiddish',			'yo' => 'Yoruba',
		'za' => 'Zhuang',		'zh' => 'Chinese',			'zu' => 'Zulu'
	);
	
	//Dokumentgrößen, Angaben in mm
	$pdf24PluginDocSizes = array
	(
		'A0' =>	'841x1189',
		'A1' => '594x841',
		'A2' => '420x594',
		'A3' => '297x420',
		'A4' => '210x297',
		'A5' => '148x210',
		
		'B0' =>	'1000x1414',
		'B1' =>	'707x1000',
		'B2' =>	'500x707',
		'B3' =>	'353x500',
		'B4' =>	'250x353',
		'B5' =>	'176x250',
		
		'C0' =>	'917x1297',
		'C1' =>	'648x917',
		'C2' =>	'458x648',
		'C3' =>	'324x458',
		'C4' =>	'229x324',
		'C5' =>	'162x229'
	);
	
	$pdf24PluginDefaultDocSize = 'A4';
	
	//Dokumentorientierung
	$pdf24PluginDocOrientations = array
	(
		'portrait' => 'Portrait',
		'landscape' => 'Landscape'
	);
	$pdf24PluginDefaultDocOrientation = 'portrait';

	function pdf24Plugin_setLang()
	{
		global $pdf24PluginUseLang, $pdf24PluginLangCodes, $pdf24PluginLang, $pdf24PluginCustomLang;
		
		$lang = get_option('pdf24Plugin_language');
		if($lang && isset($pdf24PluginLangCodes[$lang]) && isset($pdf24PluginLang[$lang]))
		{
			$pdf24PluginUseLang = $lang;
		}
		else if(defined('WPLANG') && strlen(WPLANG) >= 2)
		{
			$lang = strtolower(substr(WPLANG, 0, 2));
			if(isset($pdf24PluginLangCodes[$lang]) && isset($pdf24PluginLang[$lang]))
			{
				$pdf24PluginUseLang = $lang;
			}
		}
		
		$pdf24PluginCustomLang = pdf24Plugin_getCustomizedLang();
	}

	function pdf24Plugin_getLangVal($key)
	{
		global $pdf24PluginUseLang, $pdf24PluginLang, $pdf24PluginCustomLang;
		if($pdf24PluginCustomLang && isset($pdf24PluginCustomLang[$key]))
		{
			return $pdf24PluginCustomLang[$key];
		}
		return isset($pdf24PluginLang[$pdf24PluginUseLang][$key]) ? $pdf24PluginLang[$pdf24PluginUseLang][$key] : '';
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

	function pdf24Plugin_getBlogHiddenFields($postsCount) 
	{	
		global $pdf24PluginDocSizes;
		$arr = array
		(			
			'blogCharset' => get_bloginfo('charset'),
			'blogPosts' => $postsCount,
			'blogUrl' => get_bloginfo('siteurl'),
			'blogName' => get_bloginfo('name'),			
			'blogValueEncoding' => 'htmlSpecialChars'
		);			
		if(pdf24Plugin_isEmailOptionsInUse())
		{
			$arr['blogEmailText'] = pdf24Plugin_getEmailText();
			$arr['blogEmailType'] = pdf24Plugin_getEmailType();
			$arr['blogEmailSubject'] = pdf24Plugin_getEmailSubject();
			$arr['blogEmailFrom'] = pdf24Plugin_getEmailFrom();
		}
		if(pdf24Plugin_isDocOptionsInUse())
		{
			$arr['blogDocHeader'] = pdf24Plugin_getDocHeader();
			$arr['blogDocSize'] = $pdf24PluginDocSizes[pdf24Plugin_getDocSize()];
			$arr['blogDocOrientation'] = pdf24Plugin_getDocOrientation();
		}		
		return pdf24Plugin_getFormHiddenFields($arr);
	}

	function pdf24Plugin_getUrl()
	{
		global $pdf24PluginUseLang, $pdf24PluginLangCodes;
		
		$l = isset($pdf24PluginLangCodes[$pdf24PluginUseLang]) ? $pdf24PluginUseLang : "www";
		return "http://".$l.".pdf24.org";
	}
		
	function pdf24Plugin_getLangElements()
	{
		global $pdf24PluginUseLang, $pdf24PluginLang, $pdf24PluginCustomLang;
		if($pdf24PluginCustomLang)
		{
			return array_merge($pdf24PluginLang[$pdf24PluginUseLang], $pdf24PluginCustomLang);
		}
		return $pdf24PluginLang[$pdf24PluginUseLang];
	}
	
	function pdf24Plugin_replaceLang($str)
	{
		$langs = pdf24Plugin_getLangElements();
		$search = array();
		$replace = array();
		foreach($langs as $key => $val)
		{
			$search[] = '['. $key .']';
			$replace[] = $val;
		}
		return str_replace($search, $replace, $str);
	}
	
	function pdf24Plugin_parseStyle($style)
	{
		return str_replace("\t",'',$style);
	}

	function pdf24Plugin_getContentForm(&$postArr, $id) 
	{
		global $pdf24PluginScriptUrl, $pdf24PluginStyle;
		
		$url = pdf24Plugin_getUrl();
		$pdf24PreText = preg_replace('/pdf/i', '<a href="'.$url.'" target="_blank">$0</a>', pdf24Plugin_getLangVal("postAsPdf"));

		$out = '<div class="pdf24Plugin-cp-box">';
		$out .= '<form id="pdf24Form_'.$id.'" method="POST" action="'.$pdf24PluginScriptUrl.'" target="pdf24PopWin" onsubmit="window.open(\'about:blank\', \'pdf24PopWin\', \'scrollbars=yes,width=400,height=200,top=0,left=0\'); return true;">';
		$out .= pdf24Plugin_getBlogHiddenFields(1);
		$out .= pdf24Plugin_getFormHiddenFields($postArr, "", "_0");
		$out .= '<table cellspacing="0" cellpadding="0" border="0" width="100%" ><tr><td align="left">';
		$out .= $pdf24PreText;	
		$out .= ' <input class="pdf24Plugin-cp-input" type="text" name="sendEmailTo" value="'.pdf24Plugin_getLangVal('enterEmail').'" onMouseDown="this.value = \'\';">';	
		$out .= ' <input class="pdf24Plugin-cp-submit" type="submit" value="'.pdf24Plugin_getLangVal('send').'">';
		$out .= '</td><td align="right"><a href="'.$url.'" target="_blank" title="'.pdf24Plugin_getLangVal('imgAlt').'"><img src="http://www.pdf24.org/images/sheep_16x16.gif" alt="'.pdf24Plugin_getLangVal('imgAlt').'" border="0"></a></td></table>';	
		$out .= '</form>';
		$out .= '</div>';
		
		return $out;
	}

	function pdf24Plugin_content($content) 
	{		
		$params = array
		(		
			"postTitle" 	=> get_the_title(),
			"postLink" 		=> get_permalink(),
			"postAuthor" 	=> get_the_author(),
			"postDateTime" 	=> get_the_time("Y-m-d H:m:s"),
			"postContent" 	=> $content
		);
		
		$id = $GLOBALS["id"];
		$out = pdf24Plugin_getContentForm($params, $id);
			
		return $content . $out;
	}
	
	function pdf24Plugin_getLangOptions()
	{
		global $pdf24PluginLangCodes, $pdf24PluginUseLang;
		
		$l = get_option('pdf24Plugin_language');
		if($l === false)
		{
			$l = $pdf24PluginUseLang;
		}
		$out = '';
		foreach($pdf24PluginLangCodes as $key => $val)
		{
			$out .= '<option value="'. $key .'"'. ($l == $key ? ' selected' : '') .'>'. $val .'</option>';
		}
		return $out;
	}
	
	function pdf24Plugin_getEmailText()
	{
		$text = get_option('pdf24Plugin_emailText');
		return $text === false ? '' : $text;
	}
	
	function pdf24Plugin_getEmailSubject()
	{
		$subject = get_option('pdf24Plugin_emailSubject');
		return $subject === false ? '' : $subject;
	}
	
	function pdf24Plugin_getEmailFrom()
	{
		$from = get_option('pdf24Plugin_emailFrom');
		return $from === false ? '' : $from;
	}
	
	function pdf24Plugin_getEmailType()
	{
		$type = get_option('pdf24Plugin_emailType');
		return $type === false ? 'text/plain' : $type;
	}
	
	function pdf24Plugin_getDocHeader()
	{
		$header = get_option('pdf24Plugin_docHeader');
		return $header === false ? '' : $header;
	}
	
	function pdf24Plugin_getDocSize()
	{
		global $pdf24PluginDefaultDocSize;
		$size = get_option('pdf24Plugin_docSize');
		return $size === false ? $pdf24PluginDefaultDocSize : $size;
	}
	
	function pdf24Plugin_getDocOrientation()
	{
		global $pdf24PluginDefaultDocOrientation;
		$orientation = get_option('pdf24Plugin_docOrientation');
		return $orientation === false ? $pdf24PluginDefaultDocOrientation : $orientation;
	}
	
	function pdf24Plugin_getCpStyles()
	{
		global $pdf24PluginCpDefaultStyles;
		
		$styles = get_option('pdf24Plugin_cpStyles');
		if($styles === false || trim($styles) == '')
		{
			$styles = pdf24Plugin_parseStyle($pdf24PluginCpDefaultStyles);
		}
		return $styles;
	}
	
	function pdf24Plugin_getSbpStyles()
	{
		global $pdf24PluginSbpDefaultStyles;
		
		$styles = get_option('pdf24Plugin_sbpStyles');
		if($styles === false || trim($styles) == '')
		{
			$styles = pdf24Plugin_parseStyle($pdf24PluginSbpDefaultStyles);
		}
		return $styles;
	}
	
	function pdf24Plugin_getCustomizedLang()
	{
		return get_option('pdf24Plugin_customizedLang');
	}	
	
	function pdf24Plugin_isCustomizedLang()
	{
		$opt = get_option('pdf24Plugin_customizedLang');
		return $opt && $opt != '';
	}
	
	function pdf24Plugin_isCpInUse()
	{
		$opt = get_option('pdf24Plugin_useCp');
		return $opt === false || $opt == 'true';
	}
	
	function pdf24Plugin_isCpCustomStylesInUse()
	{
		$styles = get_option('pdf24Plugin_cpStyles');
		return $styles !== false && $styles != '';
	}
	
	function pdf24Plugin_isSbpInUse()
	{
		$opt = get_option('pdf24Plugin_useSbp');
		return $opt === false || $opt == 'true';
	}
	
	function pdf24Plugin_isSbpCustomStylesInUse()
	{
		$styles = get_option('pdf24Plugin_sbpStyles');
		return  $styles && $styles != '';
	}
	
	function pdf24Plugin_isEmailOptionsInUse()
	{
		$opt = get_option('pdf24Plugin_useEmailOptions');
		return $opt && $opt == 'true';
	}
	
	function pdf24Plugin_isDocOptionsInUse()
	{
		$opt = get_option('pdf24Plugin_useDocOptions');
		return $opt && $opt == 'true';
	}
		
	function pdf24Plugin_sidebarBox()
	{
		global $pdf24PluginScriptUrl;
		
		if(!pdf24Plugin_isSbpInUse())
		{
			return;
		}
	
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
				$content = apply_filters('the_content', $content);

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
		if(pdf24Plugin_isCpInUse())
		{
			add_filter('the_content', 'pdf24Plugin_content');
		}
		
		$blogHiddenFields = pdf24Plugin_getBlogHiddenFields(count($pdf24PostsArr));		
		$formHiddenFields = "";
		foreach($pdf24PostsArr as $key=>$val) 
		{
			$formHiddenFields .= pdf24Plugin_getFormHiddenFields($val, "", "_".$key);
		}
		$url = pdf24Plugin_getUrl();
		$pdf24TextHead = preg_replace('/pdf/i', '<a href="'.$url.'" target="_blank">$0</a>', pdf24Plugin_getLangVal("postsAsPdf"));
		
		$out =
		'
		<div class="pdf24Plugin-sbp-box">
		<div class="pdf24Plugin-sbp-title">'. $pdf24TextHead .'</div>
		<form method="POST" target="pdf24PopWin" action="'. $pdf24PluginScriptUrl .'" onsubmit="window.open(\'about:blank\', \'pdf24PopWin\', \'resizable=yes,scrollbars=yes,width=400,height=200,top=0,left=0\'); return true;">
		'. $blogHiddenFields .'
		'. $formHiddenFields .'
		<div class="pdf24Plugin-sbp-sendto"><input type="text" name="sendEmailTo" value="'. pdf24Plugin_getLangVal('enterEmail') .'" onMouseDown="this.value = \'\';"></div>
		<div class="pdf24Plugin-sbp-submit"><input type="submit" value="'. pdf24Plugin_getLangVal('send') .'" /></div>
		</form>
		<div class="pdf24Plugin-sbp-backlink"><a href="'. $url .'" target="_blank" title="'. pdf24Plugin_getLangVal('imgAlt') .'">'. pdf24Plugin_getLangVal('linkText') .'</a></div>
		</div>
		';
		echo $out;
	}
}

if (is_plugin_page())
{
	//echo $pdf24PluginUseLang;
		
	if (isset($_POST['update'])) 
	{		
		update_option('pdf24Plugin_language', $_POST['language']);
		update_option('pdf24Plugin_emailText', stripslashes($_POST['emailText']));
		update_option('pdf24Plugin_cpStyles', isset($_POST['useCpCustomStyles']) ? stripslashes($_POST['cpStyles']) : '');
		update_option('pdf24Plugin_useCp', isset($_POST['useCp']) ? 'true' : 'false');
		update_option('pdf24Plugin_sbpStyles', isset($_POST['useSbpCustomStyles']) ? stripslashes($_POST['sbpStyles']) : '');
		update_option('pdf24Plugin_useSbp', isset($_POST['useSbp']) ? 'true' : 'false');
		update_option('pdf24Plugin_useEmailOptions', isset($_POST['useEmailOptions']) ? 'true' : 'false');
		update_option('pdf24Plugin_emailType', $_POST['emailType']);
		update_option('pdf24Plugin_emailSubject', stripslashes($_POST['emailSubject']));
		update_option('pdf24Plugin_emailFrom', $_POST['emailFrom']);
		update_option('pdf24Plugin_useDocOptions', isset($_POST['useDocOptions']) ? 'true' : 'false');
		update_option('pdf24Plugin_docHeader', stripslashes($_POST['docHeader']));
		update_option('pdf24Plugin_docSize', $_POST['docSize']);
		update_option('pdf24Plugin_docOrientation', $_POST['docOrientation']);
		
		if(isset($_POST['useCustomLang']))
		{
			$customLang = array();
			foreach($_POST as $key => $val)
			{
				if(substr($key, 0, 5) == 'lang-')
				{
					$customLang[substr($key, 5)] = stripslashes($val);
				}
			}
			update_option('pdf24Plugin_customizedLang', $customLang);
		}
		else
		{
			update_option('pdf24Plugin_customizedLang', '');
		}
		//Update global Language
		pdf24Plugin_setLang();
		
		?>
		<div class="updated"><p>Changes saved.</p></div>
		<?php
	}
	
	function pdf24Plugin_createCustomizedLangInputs()
	{
		$out = '';
		$langElements = pdf24Plugin_getLangElements();
		foreach($langElements as $key => $val)
		{
			$out .= '<input type="text" name="lang-'. $key .'" value="'. addslashes($val) .'" style="width:300px"/> ('. $val .')<br />';
		}
		return $out;
	}
	
	function pdf24Plugin_createDocSizeOptions()
	{
		global $pdf24PluginDocSizes;
		$currentSize = pdf24Plugin_getDocSize();
		$out = '';
		foreach($pdf24PluginDocSizes as $key => $val)
		{
			$out .= '<option value="'. $key .'"'. ($key == $currentSize ? ' selected' : '') .'>'. $key .'</option>';
		}
		return $out;
	}
	
	function pdf24Plugin_createDocOrientationOptions()
	{
		global $pdf24PluginDocOrientations;
		$currentOrientation = pdf24Plugin_getDocOrientation();
		$out = '';
		foreach($pdf24PluginDocOrientations as $key => $val)
		{
			$out .= '<option value="'. $key .'"'. ($key == $currentOrientation ? ' selected' : '') .'>'. $val .'</option>';
		}
		return $out;
	}
	
	?>
	<div class="wrap">
		<script language="javascript">
			var pdf24_formError = false;
			function pdf24_check(elem, v)
			{
				if(v)
				{
					elem.style.border = '2px solid red';
					pdf24_formError = true;
				}
				else
				{
					elem.style.border = '';
				}
			}
			function pdf24_checkForm(form)
			{
				pdf24_formError = false;
				if(form.useCustomLang.checked)
				{
					for(var i=0; i<form.length; i++)
					{
						if(form.elements[i].name && form.elements[i].name.match(/^lang-/))
						{
							pdf24_check(form.elements[i], form.elements[i].value.length < 3);
						}
					}
				}
				return !pdf24_formError;
			}
		</script>
		<h2>PDF24 Plugin Options</h2>
		<form method="post" onsubmit="return pdf24_checkForm(this)">		
			<fieldset class="options">
				<legend>Generel</legend>				
				<table>
				<tr>
					<td width="100">Blog Language:</td>
					<td><select name="language"><?php echo pdf24Plugin_getLangOptions(); ?></select></td>
				</tr>
				</table>
			</fieldset>
			<fieldset class="options">
				<legend>Document Options</legend>
				Options of created pdf documents.
				<table style="margin-top: 20px;">
				<tr>
					<td width="100" valign="top"></td>
					<td><input type="checkbox" name="useDocOptions"<?php echo pdf24Plugin_isDocOptionsInUse() ? 'checked' : ''; ?>/> Use this document options</td>
				</tr>		
				<tr>
					<td width="100" valign="top">Header Text:</td>
					<td><input name="docHeader" style="width:600px" value="<?php echo htmlspecialchars(pdf24Plugin_getDocHeader()); ?>" /></td>
				</tr>
				<tr>
					<td width="100" valign="top">Size:</td>
					<td><select name="docSize"><?php echo pdf24Plugin_createDocSizeOptions(); ?></select></td>
				</tr>
				<tr>
					<td width="100" valign="top">Orientation:</td>
					<td><select name="docOrientation"><?php echo pdf24Plugin_createDocOrientationOptions(); ?></select></td>
				</tr>
				</table>
			</fieldset>
			<fieldset class="options">
				<legend>Email Options</legend>	
				The created PDF is sent to the entered email address. Your you can enter your own email texts. Leave blank to use default texts.
				<table style="margin-top: 20px;">
				<tr>
					<td width="100" valign="top"></td>
					<td><input type="checkbox" name="useEmailOptions"<?php echo pdf24Plugin_isEmailOptionsInUse() ? 'checked' : ''; ?>/> Use this email options</td>
				</tr>			
				<tr>
					<td width="100">Type:</td>
					<td>
						<select name="emailType">
							<option value="text/plain"<?php echo (pdf24Plugin_getEmailType() == 'text/plain' ? ' selected' : ''); ?>>Text</option>
							<option value="text/html"<?php echo (pdf24Plugin_getEmailType() == 'text/html' ? ' selected' : ''); ?>>HTML</option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="100">Subject:</td>
					<td><input type="text" name="emailSubject" value="<?php echo addslashes(pdf24Plugin_getEmailSubject()); ?>" style="width:400px" /></td>
				</tr>
				<tr>
					<td width="100">From:</td>
					<td><input type="text" name="emailFrom" value="<?php echo pdf24Plugin_getEmailFrom(); ?>" style="width:400px" /></td>
				</tr>
				<tr>
					<td valign="top">Text:</td>
					<td><textarea name="emailText" style="width:600px; height:150px"><?php echo htmlspecialchars(pdf24Plugin_getEmailText()); ?></textarea></td>
				</tr>
				</table>
			</fieldset>
			<fieldset class="options">
				<legend>Content Plugin</legend>		
				This plugin displays a small box underneath each article to convert the above article to pdf.
				<table style="margin-top: 20px;">	
				<tr>
					<td width="100" valign="top"></td>
					<td><input type="checkbox" name="useCp"<?php echo pdf24Plugin_isCpInUse() ? 'checked' : ''; ?>/> Use this Plugin</td>
				</tr>
				<tr>
					<td width="100" valign="top"></td>
					<td><input type="checkbox" name="useCpCustomStyles"<?php echo pdf24Plugin_isCpCustomStylesInUse() ? 'checked' : ''; ?>/> Use custom styles</td>
				</tr>				
				<tr>
					<td width="100" valign="top">Custom Styles:</td>
					<td><textarea name="cpStyles" style="width:600px; height:150px"><?php echo htmlspecialchars(pdf24Plugin_getCpStyles()); ?></textarea></td>
				</tr>
				</table>				
			</fieldset>
			<fieldset class="options">
				<legend>Sidebar Plugin</legend>	
				This plugin displays a small box everywhere in your blog where you place some peace of code in a template.
				Place the code &lt;?php pdf24Plugin_sidebarBox(); ?&gt; in a template where the box should appear. E.G. in the sidebar template.				
				<table style="margin-top: 20px;">	
				<tr>
					<td width="100" valign="top"></td>
					<td><input type="checkbox" name="useSbp"<?php echo pdf24Plugin_isSbpInUse() ? 'checked' : ''; ?>/> Use this Plugin</td>
				</tr>	
				<tr>
					<td width="100" valign="top"></td>
					<td><input type="checkbox" name="useSbpCustomStyles"<?php echo pdf24Plugin_isSbpCustomStylesInUse() ? 'checked' : ''; ?>/> Use custom styles</td>
				</tr>							
				<tr>
					<td width="100" valign="top">Custom Styles:</td>
					<td><textarea name="sbpStyles" style="width:600px; height:150px;"><?php echo htmlspecialchars(pdf24Plugin_getSbpStyles()); ?></textarea></td>
				</tr>
				</table>
			</fieldset>
			<fieldset class="options">
				<legend>Custom Language</legend>
				Here you can enter your own language elements for pdf creation boxes displayed on your pages.		
				<table style="margin-top: 20px;">	
				<tr>
					<td width="100" valign="top"></td>
					<td><input type="checkbox" name="useCustomLang"<?php echo pdf24Plugin_isCustomizedLang() ? 'checked' : ''; ?>/> Use this cutomized language</td>
				</tr>				
				<tr>
					<td width="100" valign="top">Language Elements:</td>
					<td><?php echo pdf24Plugin_createCustomizedLangInputs(); ?></td>
				</tr>
				</table>
			</fieldset>
			<p><div class="submit"><input type="submit" name="update" value="Update PDF24 Plugin Options"  style="font-weight:bold;" /></div></p>
		</form>   
	</div>
	<?php
}
else
{		
	function pdf24Plugin_adminMenu() 
	{
		$pagefile = basename(__FILE__);
		add_options_page('pdf24 Plugin Options Page', 'PDF24 Plugin', 8, $pagefile);
	}
	
	function pdf24Plugin_head()
	{				
		$styles = '';
		
		if(pdf24Plugin_isCpInUse())
		{
			$stylesCp = pdf24Plugin_getCpStyles();
			if($stylesCp)
			{
				$styles .= $stylesCp;
			}
		}
		if(pdf24Plugin_isSbpInUse())
		{
			$stylesSbp = pdf24Plugin_getSbpStyles();
			if($stylesSbp)
			{
				if($styles != '')
				{
					$styles .= "\n";
				}
				$styles .= $stylesSbp;
			}
		}
		if($styles && trim($styles) != '')
		{
			echo "<style type=\"text/css\">\n". $styles ."\n</style>\n";
		}
	}

	pdf24Plugin_setLang();
	
	if(pdf24Plugin_isCpInUse())
	{
		add_filter('the_content', 'pdf24Plugin_content');
	}
	
	if(pdf24Plugin_isCpInUse() || pdf24Plugin_isSbpInUse())
	{
		add_action ('wp_head', 'pdf24Plugin_head' );	
	}
	
	add_action('admin_menu', 'pdf24Plugin_adminMenu');
}

?>