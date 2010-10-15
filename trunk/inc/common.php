<?php

function pdf24Plugin_getFiles($folder, $suffix=false, $flags='') {
	global $pdf24Plugin;
	
	$removeSuffix = strstr($flags,'r');
	$ignoreSuffixCase = strstr($flags,'i');
	
	$files = array();
	if ($handle = opendir($pdf24Plugin['dir'] . '/' . $folder)) {
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

function pdf24Plugin_getFile($subFolder, $name) {
	global $pdf24Plugin;
	return $pdf24Plugin['dir'] . '/' . $subFolder . '/' . $name;
}

function pdf24Plugin_getAvailableLang() {
	return pdf24Plugin_getFiles('lang', '.php', 'ir');
}

function pdf24Plugin_setLang() {
	global $pdf24Plugin;
	
	$lang = get_option('pdf24Plugin_language');
	$langFile = $lang ? $pdf24Plugin['dir'] . '/lang/' . $lang . '.php' : '';
	if($lang && file_exists($langFile)) {
		$pdf24Plugin['useLang'] = $lang;
		include_once($langFile);
	} else if(defined('WPLANG') && strlen(WPLANG) >= 2) {
		$lang = strtolower(substr(WPLANG, 0, 2));
		$langFile = $pdf24Plugin['dir'] . '/lang/' . $lang . '.php';
		if(file_exists($langFile)) {
			$pdf24Plugin['useLang'] = $lang;
			include_once($langFile);
		}
	} else {
		$pdf24Plugin['useLang'] = $pdf24Plugin['defaultLang'];
		$langFile = $pdf24Plugin['dir'] . '/lang/' . $pdf24Plugin['defaultLang'] . '.php';
		include_once($langFile);
	}	
	if(pdf24Plugin_isCustomizedLang()) {
		$pdf24Plugin['customLang'] = pdf24Plugin_getCustomizedLang();
	}
}

function pdf24Plugin_getCustomizedLang() {
	return get_option('pdf24Plugin_customLang');
}	

function pdf24Plugin_isCustomizedLang() {
	$opt = get_option('pdf24Plugin_customLang');
	return $opt && $opt != '';
}

function pdf24Plugin_getLangVal($key) {
	global $pdf24Plugin;
	
	if($pdf24Plugin['customLang'] && isset($pdf24Plugin['customLang'][$key])) {
		return $pdf24Plugin['customLang'][$key];
	}
	return isset($pdf24Plugin['lang'][$key]) ? $pdf24Plugin['lang'][$key] : '%error%';
}

function pdf24Plugin_getLangElements() {
	global $pdf24Plugin;
	if($pdf24Plugin['customLang']) {
		return array_merge($pdf24Plugin['lang'], $pdf24Plugin['customLang']);
	}
	return $pdf24Plugin['lang'];
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
	global $pdf24Plugin;

	$keyPrefix = isset($params['keyPrefix']) ? $params['keyPrefix'] : '';
	$keySuffix = isset($params['keySuffix']) ? $params['keySuffix'] : '';
	$filter = isset($params['filter']) ? $params['filter'] : $pdf24Plugin['defaultFilter'];
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
		if(pdf24Plugin_isDebug()) {
			$out .= "\n";
		}
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
	global $pdf24Plugin;
	
	$arr = array (			
		'blogCharset' => get_bloginfo('charset'),
		'blogPosts' => $postsCount,
		'blogUrl' => get_bloginfo('siteurl'),
		'blogName' => get_bloginfo('name'),			
		'blogValueEncoding' => $pdf24Plugin['defaultFilter']
	);			
	if(pdf24Plugin_isEmailOptionsInUse()) {
		$arr['blogEmailText'] = pdf24Plugin_getEmailText();
		$arr['blogEmailType'] = pdf24Plugin_getEmailType();
		$arr['blogEmailSubject'] = pdf24Plugin_getEmailSubject();
		$arr['blogEmailFrom'] = pdf24Plugin_getEmailFrom();
	}
	if(pdf24Plugin_isDocOptionsInUse()) {
		$arr['blogDocHeader'] = pdf24Plugin_getDocHeader();
		$arr['blogDocSize'] = $pdf24Plugin['docSizes'][pdf24Plugin_getDocSize()];
		$arr['blogDocOrientation'] = pdf24Plugin_getDocOrientation();
		$arr['blogDocStyle'] = pdf24Plugin_getDocStyle();
	}		
	return pdf24Plugin_getFormHiddenFields0($arr, array('skipFilterFor' => 'blogValueEncoding'));
}

function pdf24Plugin_getUrl() {
	global $pdf24Plugin;	
	return 'http://'. $pdf24Plugin['useLang'] .'.pdf24.org';
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

function pdf24Plugin_replaceLang($str, $count, $searchReplace = null) {
	if($searchReplace != null && !is_array($searchReplace)) {
		$searchReplace = null;
	}
	
	$langs = pdf24Plugin_getLangElements();
	$search = array();
	$replace = array();
	foreach($langs as $key => $val) {
		$k = '{lang_'. $key .'}';
		if($searchReplace && isset($searchReplace[$k])) {
			$val = $searchReplace[$k];
		}
		$search[] = $k;
		$replace[] = $val;
	}
	
	if($searchReplace && isset($searchReplace['{lang_sendAsPDF}'])) {
		$search[] = '{lang_sendAsPDF}';
		$replace[] = $searchReplace['{lang_sendAsPDF}'];
	} else {
		$search[] = '{lang_sendAsPDF}';
		$replace[] = $count == 1 ? pdf24Plugin_getLangVal('sendArticleAsPDF') : pdf24Plugin_getLangVal('sendArticlesAsPDF');
	}
	
	if($searchReplace && isset($searchReplace['{lang_downloadAsPDF}'])) {
		$search[] = '{lang_downloadAsPDF}';
		$replace[] = $searchReplace['{lang_downloadAsPDF}'];
	} else {
		$search[] = '{lang_downloadAsPDF}';
		$replace[] = $count == 1 ? pdf24Plugin_getLangVal('downloadArticleAsPDF') : pdf24Plugin_getLangVal('downloadArticlesAsPDF');
	}
	
	return str_replace($search, $replace, $str);
}

function pdf24Plugin_nextFormId() {
	global $pdf24Plugin;
	if(!isset($pdf24Plugin)) {
		$pdf24Plugin['nextFormId'] = 0;
	}
	return $pdf24Plugin['nextFormId']++;
}

function pdf24Plugin_parseTplContent($postsArr, $tpl, $styleId, $searchReplace = null) {
	if(count($postsArr) == 0) {
		return '';
	}		
	global $pdf24Plugin;
	
	$blUrl = pdf24Plugin_getUrl();
	$blText = pdf24Plugin_getBLText();	
	$hiddenFilds = pdf24Plugin_getBlogHiddenFields(count($postsArr));
	foreach($postsArr as $key => $val) {
		$hiddenFilds .= pdf24Plugin_getFormHiddenFields($val, '', '_' . $key);
	}
	$tpl = file_get_contents(pdf24Plugin_getFile('tpl', $tpl));
	$search = array('{styleId}', '{formId}', '{actionUrl}', '{hiddenFields}', '{blText}', '{blUrl}', 
		'{pluginUrl}', '{targetName}', '{openTargetCode}');
	$replace = array($styleId, pdf24Plugin_nextFormId(), $pdf24Plugin['serviceUrl'], $hiddenFilds, 
		$blText, $blUrl, $pdf24Plugin['url'], $pdf24Plugin['targetName'], $pdf24Plugin['jsOpenTargetWin']);
	$content = str_replace($search, $replace, $tpl);
	$content = pdf24Plugin_replaceLang($content, count($postsArr), $searchReplace);
	if($searchReplace != null && is_array($searchReplace)) {
		$content = str_replace(array_keys($searchReplace), array_values($searchReplace), $content);
	}
	if(!pdf24Plugin_isDebug()) {
		$content = str_replace(array("\r\n","\n"), ' ', $content);
	}
	return $content;
}

function pdf24Plugin_styleToTpl($style) {
	$parts = explode('_', $style);
	return $parts[1] . '.html';
}

function pdf24Plugin_getTplContent($postsArr, $wpOptionKey, $styleFolder, $styleId, $searchReplace = null) {
	$style = get_option($wpOptionKey);
	if(!$style || trim($style) == '' || !pdf24Plugin_hasStyleFile($styleFolder, $style)) {
		$style = pdf24Plugin_getDefaultStyle($styleFolder);
	}
	return pdf24Plugin_parseTplContent($postsArr, pdf24Plugin_styleToTpl($style), $styleId, $searchReplace);
}

function pdf24Plugin_getContentForm($postsArr) {
	return pdf24Plugin_getTplContent($postsArr, 'pdf24Plugin_cpStyle', 'styles/cp', 'cp');
}

function pdf24Plugin_getTopBottomForm($postsArr) {
	return pdf24Plugin_getTplContent($postsArr, 'pdf24Plugin_tbpStyle', 'styles/tbp', 'tbp');
}

function pdf24Plugin_getSidebarForm($postsArr) {
	return pdf24Plugin_getTplContent($postsArr, 'pdf24Plugin_sbpStyle', 'styles/sbp', 'sbp');
}

function pdf24Plugin_getLinkForm($postsArr, $txt = null) {
	$searchReplace = null;
	if($txt != null && trim($txt) != '') {
		$searchReplace = array(
			'{lang_downloadAsPDF}' => $txt,
			'{lang_sendAsPDF}' => $txt
		);
	}
	return pdf24Plugin_getTplContent($postsArr, 'pdf24Plugin_lpStyle', 'styles/lp', 'lp', $searchReplace);
}

function pdf24Plugin_getAllPosts() {
	global $pdf24Plugin;

	//reset
	rewind_posts();	
	$pdf24PostsArr = array();

	//pdf24 filter deaktivieren
	remove_filter("the_content", "pdf24Plugin_content", $pdf24Plugin['contentFilterPriority']);

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
		add_filter('the_content', 'pdf24Plugin_content', $pdf24Plugin['contentFilterPriority']);
	}
	return $pdf24PostsArr;
}

function pdf24Plugin_getThePost() {
	global $pdf24Plugin;
	
	//pdf24 filter deaktivieren
	remove_filter("the_content", "pdf24Plugin_content", $pdf24Plugin['contentFilterPriority']);

	$params = array(	
		"postTitle" => get_the_title(),
		"postLink" => get_permalink(),
		"postAuthor" => get_the_author(),
		"postDateTime" => get_the_time("Y-m-d H:m:s"),
		"postContent" => get_the_content()
	);
	
	if(pdf24Plugin_isCpInUse()) {
		add_filter('the_content', 'pdf24Plugin_content', $pdf24Plugin['contentFilterPriority']);
	}
	
	return $params;
}

function pdf24Plugin_getPosts() {
	if(in_the_loop()) {
		return array(pdf24Plugin_getThePost());
	}
	return pdf24Plugin_getAllPosts();
}

function pdf24Plugin_content($content) {
	$params = array(	
		"postTitle" => get_the_title(),
		"postLink" => get_permalink(),
		"postAuthor" => get_the_author(),
		"postDateTime" => get_the_time("Y-m-d H:m:s"),
		"postContent" => $content
	);
	$out = pdf24Plugin_getContentForm(array($params));
	if(get_option('pdf24Plugin_cpDisplayMode') == 'top') {
		return $out . $content;
	} else {
		return $content . $out;
	}
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
	global $pdf24Plugin;	
	$size = get_option('pdf24Plugin_docSize');
	return $size === false ? $pdf24Plugin['docSizes']['default'] : $size;
}

function pdf24Plugin_getDocOrientation() {
	global $pdf24Plugin;	
	$orientation = get_option('pdf24Plugin_docOrientation');
	return $orientation === false ? $pdf24Plugin['docOrientations']['default'] : $orientation;
}

function pdf24Plugin_getDocStyle() {
	$style = get_option('pdf24Plugin_docStyle');
	return $style === false ? '' : $style;
}

function pdf24Plugin_hasStyleFile($styleFolder, $styleFile) {
	global $pdf24Plugin;
	if(!stristr($styleFile, '.css')) {
		$styleFile .= '.css';
	}
	return file_exists($pdf24Plugin['dir'] . '/' . $styleFolder . '/' . $styleFile);
}

function pdf24Plugin_getDefaultStyle($styleFolder) {
	$files = pdf24Plugin_getFiles($styleFolder, '.css', $flags='ir');
	foreach($files as $file) {
		if(strpos($file,'default') == 0) {
			$style = $file;
			break;
		}
	}
	if(!$style || $style == '' && count($files) > 0) {
		$style = $files[0];
	}
	return $style;
}

function pdf24Plugin_appendStyle($styleOption, $styleFolder, &$stylesArr) {
	$style = get_option($styleOption);
	if(!$style || trim($style) == '') {
		$style = pdf24Plugin_getDefaultStyle($styleFolder);
	}
	$customize = get_option($styleOption . '_customize');
	$customStyle = get_option($styleOption . '_' . $style);
	if($customize === 'true' && $customStyle && trim($customStyle) != '') {
		$stylesArr[] = array('text', $customStyle);
	} else {
		if(pdf24Plugin_hasStyleFile($styleFolder, $style)) {
			$stylesArr[] = array('file', $styleFolder . '/' . $style . '.css');
		}
	}
}

function pdf24Plugin_getStyleParams($wpSetting, $folder) {
	global $pdf24Plugin;
	
	$style = get_option($wpSetting);
	$style = $style === false || $style == '' ? 'default' : $style;

	$parms = array();
	$parms['options'] = '';
	$parms['js'] = 'var ' . $wpSetting . '_custom = new Array();';
	$parms['js'] .= 'var ' . $wpSetting . '_default = new Array();';
	$files = pdf24Plugin_getFiles($folder, '.css', 'ir');
	
	foreach($files as $f) {	
		$p = explode('_', $f);
		$name = $p[0] . ($p[1][0] == 'e' ? ' (Email PDF)' : ' (Download PDF)');
		$parms['options'] .= '<option value="'. $f .'" ' . ($f == $style || $p[0] == $style ? 'selected="true"' : '') . '>'. $name .'</option>';
		
		$default = file_get_contents($pdf24Plugin['dir'] . '/' . $folder . '/' . $f . '.css');
		$custom = get_option($wpSetting . '_' . $f);
		if(!$custom || $custom == '') {
			$custom = $default;
		}
		
		if($f == $style || $p[0] == $style) {
			$parms['custom'] = htmlspecialchars($custom);
		}
		
		$parms['js'] .= $wpSetting . "_custom.push('" . htmlspecialchars(str_replace(array("\r\n","\n"),array('\r\n','\n'),$custom)) . "'); ";
		$parms['js'] .= $wpSetting . "_default.push('" . htmlspecialchars(str_replace(array("\r\n","\n"),array('\r\n','\n'),$default)) . "'); ";
		$parms['customized'] = get_option($wpSetting . '_customize') === 'true';
	}
	return $parms;
}

function pdf24Plugin_isCpInUse() {
	$opt = get_option('pdf24Plugin_cpInUse');
	return $opt === false || $opt == 'true';
}

function pdf24Plugin_isSbpInUse() {
	$opt = get_option('pdf24Plugin_sbpInUse');
	return $opt === false || $opt == 'true';
}

function pdf24Plugin_isTbpInUse() {
	$opt = get_option('pdf24Plugin_tbpInUse');
	return $opt == 'true';
}

function pdf24Plugin_isLpInUse() {
	$opt = get_option('pdf24Plugin_lpInUse');
	return $opt == 'true';
}

function pdf24Plugin_isEmailOptionsInUse() {
	$opt = get_option('pdf24Plugin_emailOptionsInUse');
	return $opt == 'true';
}

function pdf24Plugin_isDebug() {
	$opt = get_option('pdf24Plugin_debug');
	return $opt == 'true';
}


function pdf24Plugin_isDocOptionsInUse() {
	$opt = get_option('pdf24Plugin_docOptionsInUse');
	return $opt == 'true';
}

function pdf24Plugin_isAvailable() {
	$opt = get_option('pdf24Plugin_availability');
	return $opt === false || $opt == 'public' || ($opt == 'private' && is_user_logged_in());
}

function pdf24Plugin_sidebar() {
	if(pdf24Plugin_isSbpInUse() && pdf24Plugin_isAvailable()) {
		echo pdf24Plugin_getSidebarForm(pdf24Plugin_getAllPosts());
	}	
}

function pdf24Plugin_topBottom() {		
	if(pdf24Plugin_isTbpInUse() && pdf24Plugin_isAvailable()) {
		echo pdf24Plugin_getTopBottomForm(pdf24Plugin_getAllPosts());
	}
}

function pdf24Plugin_link($txt = null) {		
	if(pdf24Plugin_isLpInUse() && pdf24Plugin_isAvailable()) {
		echo pdf24Plugin_getLinkForm(pdf24Plugin_getPosts(), $txt);
	}
}
	
function pdf24Plugin_sidebarBox() {
	pdf24Plugin_sidebar();
}

function pdf24Plugin_topBottomBox() {		
	pdf24Plugin_topBottom();
}

function pdf24Plugin_widget($args) {
	if(pdf24Plugin_isSbpInUse() && pdf24Plugin_isAvailable()) {
		extract($args);
		echo $before_widget . $before_title . pdf24Plugin_getWidgetTitle() . $after_title;
		echo pdf24Plugin_getSidebarForm(pdf24Plugin_getAllPosts());
	}
}

function pdf24Plugin_getWidgetTitle() {
	$text = get_option('pdf24Plugin_widgetTitle');
	return $text === false ? '' : trim($text);
}



?>