<?php

function pdf24Plugin_getFiles($folder, $suffix=false, $flags='') {
	global $pdf24PluginDir;
	
	$removeSuffix = strstr($flags,'r');
	$ignoreSuffixCase = strstr($flags,'i');
	
	$files = array();
	if ($handle = opendir($pdf24PluginDir . '/' . $folder)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				if(!$suffix || ($suffix && strstr($file,$suffix)) || ($suffix && $ignoreSuffixCase && stristr($file,$suffix))) {
					if($suffix && $removeSuffix) {
						$end = stristr($file,$suffix);
						$file = str_replace($end, '', $file);
					}
					$files[] = $file;
				}
			}
		}
		closedir($handle);
	}
	return $files;
}

function pdf24Plugin_getAvailableLang() {
	return pdf24Plugin_getFiles('lang', '.php', 'ir');
}

function pdf24Plugin_setLang() {
	global $pdf24PluginDefaultLang, $pdf24PluginDir, $pdf24PluginLang, $pdf24PluginCustomLang, $pdf24PluginUseLang;
	
	$lang = get_option('pdf24Plugin_language');
	$langFile = $lang ? $pdf24PluginDir . '/lang/' . $lang . '.php' : '';
	if($lang && file_exists($langFile)) {
		$pdf24PluginUseLang = $lang;
		include_once($langFile);
	} else if(defined('WPLANG') && strlen(WPLANG) >= 2) {
		$lang = strtolower(substr(WPLANG, 0, 2));
		$langFile = $pdf24PluginDir . '/lang/' . $lang . '.php';
		if(file_exists($langFile)) {
			$pdf24PluginUseLang = $lang;
			include_once($langFile);
		}
	} else {
		$pdf24PluginUseLang = $pdf24PluginDefaultLang;
		$langFile = $pdf24PluginDir . '/lang/' . $pdf24PluginDefaultLang . '.php';
		include_once($langFile);
	}	
	if(pdf24Plugin_isCustomizedLang()) {
		$pdf24PluginCustomLang = pdf24Plugin_getCustomizedLang();
	}
}

function pdf24Plugin_getCustomizedLang() {
	return get_option('pdf24Plugin_customizedLang');
}	

function pdf24Plugin_isCustomizedLang() {
	$opt = get_option('pdf24Plugin_customizedLang');
	return $opt && $opt != '';
}

function pdf24Plugin_getLangVal($key) {
	global $pdf24PluginLang, $pdf24PluginCustomLang;
	
	if($pdf24PluginCustomLang && isset($pdf24PluginCustomLang[$key])) {
		return $pdf24PluginCustomLang[$key];
	}
	return isset($pdf24PluginLang[$key]) ? $pdf24PluginLang[$key] : '%error%';
}

function pdf24Plugin_encode($val, $filters) {
	$supportedFilters = array(
		'base64' => 'base64_encode',
		'htmlspecialchars' => 'htmlspecialchars'
	);
	if(is_string($filters)) {
		$filters = explode(' ', $filters);
	}
	foreach($filters as $filter) {
		if(isset($supportedFilters[$filter])) {
			$val = $supportedFilters[$filter]($val);
		} else {
			die("Unsupported filter: " . $filter);
		}
	}
	return $val;
}

function pdf24Plugin_getFormHiddenFields0($formArr, $params) {
	global $pdf24PluginDefaultFilter;

	$keyPrefix = isset($params['keyPrefix']) ? $params['keyPrefix'] : '';
	$keySuffix = isset($params['keySuffix']) ? $params['keySuffix'] : '';
	$filter = isset($params['filter']) ? $params['filter'] : $pdf24PluginDefaultFilter;
	$skipFilterFor = isset($params['skipFilterFor']) ? $params['skipFilterFor'] : array();
	
	if(is_string($skipFilterFor) && $skipFilterFor != '*') {
		$skipFilterFor = explode(' ', $skipFilterFor);
	}
		
	$out = '';
	foreach($formArr as $key => $val) {
		if(is_array($skipFilterFor) && !in_array($key, $skipFilterFor)) {
			$val = pdf24Plugin_encode($val, $filter);
		}
		$out .= '<input type="hidden" name="'. $keyPrefix.$key.$keySuffix .'" value="'. $val .'" />';
		//$out .= "\n";
	}
	return $out;
}

function pdf24Plugin_getFormHiddenFields($formArr, $keyPrefix="", $keySuffix="") {
	return pdf24Plugin_getFormHiddenFields0($formArr, array(
		'keyPrefix' => $keyPrefix,
		'keySuffix' => $keySuffix
	));
}

function pdf24Plugin_getBlogHiddenFields($postsCount) {	
	global $pdf24PluginDocSizes, $pdf24PluginDefaultFilter;
	
	$arr = array (			
		'blogCharset' => get_bloginfo('charset'),
		'blogPosts' => $postsCount,
		'blogUrl' => get_bloginfo('siteurl'),
		'blogName' => get_bloginfo('name'),			
		'blogValueEncoding' => $pdf24PluginDefaultFilter
	);			
	if(pdf24Plugin_isEmailOptionsInUse()) {
		$arr['blogEmailText'] = pdf24Plugin_getEmailText();
		$arr['blogEmailType'] = pdf24Plugin_getEmailType();
		$arr['blogEmailSubject'] = pdf24Plugin_getEmailSubject();
		$arr['blogEmailFrom'] = pdf24Plugin_getEmailFrom();
	}
	if(pdf24Plugin_isDocOptionsInUse()) {
		$arr['blogDocHeader'] = pdf24Plugin_getDocHeader();
		$arr['blogDocSize'] = $pdf24PluginDocSizes[pdf24Plugin_getDocSize()];
		$arr['blogDocOrientation'] = pdf24Plugin_getDocOrientation();
	}		
	return pdf24Plugin_getFormHiddenFields0($arr, array('skipFilterFor' => 'blogValueEncoding'));
}

function pdf24Plugin_getUrl() {
	global $pdf24PluginUseLang;
	
	return "http://".$pdf24PluginUseLang.".pdf24.org";
}

function pdf24Plugin_getBLText() {
	$text = pdf24Plugin_getLangVal('blTexts');
	$words = preg_split('/;|\|/',$text);
	if(count($words) == 0) {
		$words = array('PDF');
	}
	$key = array_rand($words);
	return trim($words[$key]);
}

function pdf24Plugin_replaceLang($str) {
	$langs = pdf24Plugin_getLangElements();
	$search = array();
	$replace = array();
	foreach($langs as $key => $val) {
		$search[] = '['. $key .']';
		$replace[] = $val;
	}
	return str_replace($search, $replace, $str);
}

function pdf24Plugin_getLongBarForm($postsArr, $styleId) {
	if(count($postsArr) == 0) {
		return '';
	}		
	global $pdf24PluginScriptUrl, $pdf24PluginUrl;

	$url = pdf24Plugin_getUrl();
	$blText = pdf24Plugin_getBLText();
	$text = pdf24Plugin_getLangVal(count($postsArr) == 1 ? 'postAsPdf' : 'postsAsPdf');

	$out = '<div class="pdf24Plugin-'.$styleId.'-box">';
	$out .= '<form method="post" action="'.$pdf24PluginScriptUrl.'" target="pdf24PopWin" onsubmit="window.open(\'about:blank\', \'pdf24PopWin\', \'scrollbars=yes,width=400,height=200,top=0,left=0\'); return true;">';
	$out .= pdf24Plugin_getBlogHiddenFields(count($postsArr));
	foreach($postsArr as $key => $val) {
		$out .= pdf24Plugin_getFormHiddenFields($val, '', '_' . $key);
	}
	$out .= '<a href="'.$url.'" target="_blank" title="'.$blText.'"><img src="'. $pdf24PluginUrl .'/img/sheep_16x16.gif" alt="'.$blText.'" border="0" /></a>';
	$out .= ' <span class="pdf24Plugin-'.$styleId.'-space">&nbsp;&nbsp;</span> ';
	$out .= '<span class="pdf24Plugin-'.$styleId.'-text">' . $text .'</span>';
	$out .= ' <input class="pdf24Plugin-'.$styleId.'-input" style="margin: 0px;" type="text" name="sendEmailTo" value="'.pdf24Plugin_getLangVal('enterEmail').'" onmousedown="this.value = \'\';" />';	
	$out .= ' <input class="pdf24Plugin-'.$styleId.'-submit" style="margin: 0px;" type="submit" value="'.pdf24Plugin_getLangVal('send').'" />';
	$out .= '</form>';
	$out .= '</div>';
	
	return $out . "\n";
}

function pdf24Plugin_getContentForm($postsArr) {
	return pdf24Plugin_getLongBarForm($postsArr, 'cp');
}

function pdf24Plugin_getTopBottomForm($postsArr) {
	return pdf24Plugin_getLongBarForm($postsArr, 'tbp');
}

function pdf24Plugin_getSidebarForm($postsArr) {
	if(count($postsArr) == 0) {
		return '';
	}	
	global $pdf24PluginScriptUrl;
	
	$url = pdf24Plugin_getUrl();
	$blText = pdf24Plugin_getBLText();
	$text = pdf24Plugin_getLangVal(count($postsArr) == 1 ? 'postAsPdf' : 'postsAsPdf');
	
	$formHiddenFields = '';
	foreach($postsArr as $key => $val) {
		$formHiddenFields .= pdf24Plugin_getFormHiddenFields($val, '', '_' . $key);
	}
	
	$out =
	'
	<div class="pdf24Plugin-sbp-box">
	<div class="pdf24Plugin-sbp-title">'. $text .'</div>
	<form method="post" target="pdf24PopWin" action="'. $pdf24PluginScriptUrl .'" onsubmit="window.open(\'about:blank\', \'pdf24PopWin\', \'resizable=yes,scrollbars=yes,width=400,height=200,top=0,left=0\'); return true;">
	'. pdf24Plugin_getBlogHiddenFields(count($postsArr)) .'
	'. $formHiddenFields .'
	<div class="pdf24Plugin-sbp-sendto"><input type="text" name="sendEmailTo" value="'. pdf24Plugin_getLangVal('enterEmail') .'" onmousedown="this.value = \'\';" /></div>
	<div class="pdf24Plugin-sbp-submit"><input type="submit" value="'. pdf24Plugin_getLangVal('send') .'" /></div>
	</form>
	<div class="pdf24Plugin-sbp-backlink"><a href="'. $url .'" target="_blank" title="'. $blText .'">'. $blText .'</a></div>
	</div>
	';
		
	return $out . "\n";
}



function pdf24Plugin_content($content) {
	$params = array(		
		"postTitle" 	=> get_the_title(),
		"postLink" 		=> get_permalink(),
		"postAuthor" 	=> get_the_author(),
		"postDateTime" 	=> get_the_time("Y-m-d H:m:s"),
		"postContent" 	=> $content
	);	
	$out = pdf24Plugin_getContentForm(array($params));		
	return $content . $out;
}

function pdf24Plugin_getEmailText() {
	$text = get_option('pdf24Plugin_emailText');
	return $text === false ? '' : $text;
}

function pdf24Plugin_getEmailSubject() {
	$subject = get_option('pdf24Plugin_emailSubject');
	return $subject === false ? '' : $subject;
}

function pdf24Plugin_getEmailFrom() {
	$from = get_option('pdf24Plugin_emailFrom');
	return $from === false ? '' : $from;
}

function pdf24Plugin_getEmailType() {
	$type = get_option('pdf24Plugin_emailType');
	return $type === false ? 'text/plain' : $type;
}

function pdf24Plugin_getDocHeader() {
	$header = get_option('pdf24Plugin_docHeader');
	return $header === false ? '' : $header;
}

function pdf24Plugin_getDocSize() {
	global $pdf24PluginDocSizes;
	
	$size = get_option('pdf24Plugin_docSize');
	return $size === false ? $pdf24PluginDocSizes['default'] : $size;
}

function pdf24Plugin_getDocOrientation() {
	global $pdf24PluginDocOrientations;
	
	$orientation = get_option('pdf24Plugin_docOrientation');
	return $orientation === false ? $pdf24PluginDocOrientations['default'] : $orientation;
}

function pdf24Plugin_getStyle($wpOption) {
	$style = get_option($wpOption);
	if($style === false || trim($style) == '') {
		return 'default';
	}
	return $style;
}

function pdf24Plugin_appendStyle($styleOption, $stylesOption, $styleFolder, &$stylesArr) {
	$style = pdf24Plugin_getStyle($styleOption);
	if($style == '%custom%') {
		$styles = get_option($stylesOption);
		$stylesArr[] = array('text', $styles);
	} else {
		$stylesArr[] = array('file', $styleFolder . '/' . $style . '.css');
	}
}

function pdf24Plugin_isCpInUse() {
	$opt = get_option('pdf24Plugin_useCp');
	return $opt === false || $opt == 'true';
}

function pdf24Plugin_isSbpInUse() {
	$opt = get_option('pdf24Plugin_useSbp');
	return /*$opt === false || */ $opt == 'true';
}

function pdf24Plugin_isTbpInUse() {
	$opt = get_option('pdf24Plugin_useTbp');
	return /* $opt === false || */ $opt == 'true';
}

function pdf24Plugin_isEmailOptionsInUse() {
	$opt = get_option('pdf24Plugin_useEmailOptions');
	return $opt && $opt == 'true';
}

function pdf24Plugin_isDocOptionsInUse() {
	$opt = get_option('pdf24Plugin_useDocOptions');
	return $opt && $opt == 'true';
}

function pdf24Plugin_getAllPosts() {
	global $pdf24PluginContentFilterPriority;

	//reset
	rewind_posts();	
	$pdf24PostsArr = array();

	//pdf24 filter deaktivieren
	remove_filter("the_content", "pdf24Plugin_content", $pdf24PluginContentFilterPriority);

	if (have_posts()) {
		while (have_posts()) {
			the_post();
			
			//filter auf content anwenden
			$content = get_the_content();
			$content = apply_filters('the_content', $content);

			$pdf24Params = array(		
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
	if(pdf24Plugin_isCpInUse()) {
		add_filter('the_content', 'pdf24Plugin_content', $pdf24PluginContentFilterPriority);
	}
	return $pdf24PostsArr;
}
	
function pdf24Plugin_sidebarBox() {
	if(!pdf24Plugin_isSbpInUse()) {
		return;
	}
	echo pdf24Plugin_getSidebarForm(pdf24Plugin_getAllPosts());
}

function pdf24Plugin_topBottomBox() {		
	if(!pdf24Plugin_isTbpInUse()) {
		return;
	}
	echo pdf24Plugin_getTopBottomForm(pdf24Plugin_getAllPosts());
}

?>