<?php

function pdf24Plugin_wpEscape($str) {
	$version = get_bloginfo('version');
	if(strcmp($version, '2.8') >= 0) {
		return esc_html($str);
	} else {
		return wp_specialchars($str, true);
	}
}

function pdf24Plugin_getBlogUrl() {
	$version = get_bloginfo('version');
	if(strcmp($version, '2.2') >= 0) {
		return get_bloginfo('url');
	} else {
		return get_bloginfo('siteurl');
	}
}

function pdf24Plugin_shuffle(&$items,$seed) {
	if(count($items) > 0 && $seed) {
		mt_srand(crc32(($seed) ? $seed : $items[0]));
		for ($i = count($items) - 1; $i > 0; $i--){
			$j = @mt_rand(0, $i);
			$tmp = $items[$i];
			$items[$i] = $items[$j];
			$items[$j] = $tmp;
		}
	}
}

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

function pdf24Plugin_getFileContents($subFolder, $name) {
	return file_get_contents(pdf24Plugin_getFile($subFolder, $name));
}

function pdf24Plugin_getAvailableLang() {
	return pdf24Plugin_getFiles('lang', '.php', 'ir');
}

function pdf24Plugin_setLang() {
	global $pdf24Plugin;
	
	$trylangs = array();
	$lang = get_option('pdf24Plugin_language');
	if($lang) {
		$trylangs[] = $lang;
	}
	if(defined('WPLANG') && strlen(WPLANG) >= 2) {
		$trylangs[] = strtolower(substr(WPLANG, 0, 2));
	}
	$trylangs[] = $pdf24Plugin['defaultLang'];
	$trylangs[] = 'en';
	$trylangs[] = 'de';
	
	$found = false;
	foreach($trylangs as $tryLang) {
		$pdf24Plugin['useLang'] = $tryLang;
		$langFile = $pdf24Plugin['dir'] . '/lang/' . $tryLang . '.php';
		if(file_exists($langFile)) {
			$found = true;
			break;
		}
	}
	if(!$found) {
		$pdf24Plugin['lang'] = array();
	} else {
		include_once($langFile);
		if(!isset($pdf24Plugin['lang']) || !is_array($pdf24Plugin['lang'])) {
			$pdf24Plugin['lang'] = array();
		}
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

	if(isset($pdf24Plugin['customLang']) && isset($pdf24Plugin['customLang'][$key])) {
		return $pdf24Plugin['customLang'][$key];
	}
	return isset($pdf24Plugin['lang'][$key]) ? $pdf24Plugin['lang'][$key] : '%error%';
}

function pdf24Plugin_getLangElements() {
	global $pdf24Plugin;
	if(isset($pdf24Plugin['customLang'])) {
		return array_merge($pdf24Plugin['lang'], $pdf24Plugin['customLang']);
	}
	return $pdf24Plugin['lang'];
}

function pdf24Plugin_encode($val, $filters) {
	if(is_string($filters)) {
		$filters = explode(' ', trim($filters));
	}
	foreach($filters as $filter) {
		switch($filter) {
			case '':
				break;
			case 'base64':
				$val = base64_encode($val);
				break;
			case 'htmlspecialchars':
				$val = htmlspecialchars($val);
				break;
			case 'gzdeflate':
				$val = gzdeflate($val, 9);
				break;
			default:
				die("Unsupported filter: " . $filter);
				break;
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
		'blogUrl' => pdf24Plugin_getBlogUrl(),
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
		$arr['blogDocFilename'] = pdf24Plugin_parseDocFilename(pdf24Plugin_getDocFilename());
	}
	if(pdf24Plugin_isCustomizedDocTpl()) {
		$arr['blogDocTpl'] = pdf24Plugin_getCustomizedDocTpl();
	}
	if(pdf24Plugin_isCustomizedDocEntryTpl()) {
		$arr['blogDocEntryTpl'] = pdf24Plugin_getCustomizedDocEntryTpl();
	}
	return pdf24Plugin_getFormHiddenFields0($arr, array('skipFilterFor' => 'blogValueEncoding'));
}

function pdf24Plugin_getShuffleSeed() {
	if(is_home()) {
		return '';
	}
	$uri = pdf24Plugin_getBlogUrl();
	$pos = stripos($uri, $_SERVER['HTTP_HOST']);
	if($pos !== false) {
		$uri = substr($uri, $pos + strlen($_SERVER['HTTP_HOST']));
	}
	$reqUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
	$reqUri = str_replace($uri, '', $reqUri);
	return $reqUri == '/' ? '' : $reqUri;
}

function pdf24Plugin_getDefaultBLInfo() {
	global $pdf24Plugin;
	$url = 'http://'. $pdf24Plugin['useLang'] .'.pdf24.org';
	
	if(!isset($pdf24Plugin['blWords'])) {
		$text = pdf24Plugin_getLangVal('blTexts');
		$words = preg_split('/;|\|/',$text);
		if(count($words) == 0) {
			$words = array('PDF Creator');
		}
		pdf24Plugin_shuffle($words, pdf24Plugin_getShuffleSeed());
		$pdf24Plugin['blWords'] = $words;
		$pdf24Plugin['blWordIndex'] = 0;
	}
	if($pdf24Plugin['blWordIndex'] >= count($pdf24Plugin['blWords'])) {
		$pdf24Plugin['blWordIndex'] = 0;
	}
	$word = trim($pdf24Plugin['blWords'][$pdf24Plugin['blWordIndex']++]);
	$l = $pdf24Plugin['useLang'];
	if(mt_rand(0,4) != 0 && ($l == 'de' || $l == 'en')) {
		$urlWord = str_replace(' ','-',strtolower($word));
		$urlWord = preg_replace('/\-\-+/','-',$urlWord);
		$url .= '/' . urlencode($urlWord) . '.jsp';
	}
	return array($word, $url);
}

function pdf24Plugin_queryBLinks() {
	global $pdf24Plugin;
	$ref = trim(pdf24Plugin_getBlogUrl());
	if($ref == '') {
		$ref = $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
	}
	$url = $pdf24Plugin['linkQueryUrl'];
	$url .= '?lang=' . $pdf24Plugin['useLang'];
	$url .= '&ref=' . urlencode($ref);
	$lines = file($url);
	$blInfos = array();
	foreach($lines as $line) {
		$line = preg_replace('/(\s)+/', '$1', $line);
		$blInfo = explode("\t", $line);
		if(count($blInfo) == 2) {
			$blInfos[] = $blInfo;
		}
	}
	return $blInfos;
}

function pdf24Plugin_isPrivateIp($ip) {
	$ip = strtolower(trim($ip));
	return $ip == 'localhost' || strpos($ip, '127.') === 0 || strpos($ip, '10.') === 0
		|| strpos($ip, '172.16.') === 0 || strpos($ip, '192.168.') === 0;
}

function pdf24Plugin_getBLinks() {
	global $pdf24Plugin;
	if(isset($pdf24Plugin['bLinks'])) {
		return $pdf24Plugin['bLinks'];
	}
	if(!pdf24Plugin_isPrivateIp($_SERVER['SERVER_ADDR'])) {
		$lastQuery = get_option('pdf24Plugin_lastBLinksQueryTime');
		if($lastQuery === false || time() - intval($lastQuery) > 7 * 24 * 3600) {
			update_option('pdf24Plugin_lastBLinksQueryTime', time());
			$bLinks = pdf24Plugin_queryBLinks();
			update_option('pdf24Plugin_bLinks', $bLinks);
		}
	}
	$bLinks = get_option('pdf24Plugin_bLinks');
	if(!is_array($bLinks)) {
		$bLinks = array();
	}
	pdf24Plugin_shuffle($bLinks, pdf24Plugin_getShuffleSeed());
	$pdf24Plugin['bLinks'] = $bLinks;
	$pdf24Plugin['bLinkIndex'] = 0;
	return $bLinks;
}

function pdf24Plugin_getBLInfo() {
	global $pdf24Plugin;
	$bLinks = pdf24Plugin_getBLinks();
	if(count($bLinks) == 0) {
		return pdf24Plugin_getDefaultBLInfo();
	}
	if($pdf24Plugin['bLinkIndex'] >= count($bLinks)) {
		$pdf24Plugin['bLinkIndex'] = 0;
	}
	return $bLinks[$pdf24Plugin['bLinkIndex']++];
}

function pdf24Plugin_replaceLang($str, $count, $searchReplace = null) {
	if($searchReplace != null && !is_array($searchReplace)) {
		$searchReplace = null;
	}

	$langs = pdf24Plugin_getLangElements();
	$map = array();
	foreach($langs as $key => $val) {
		$k = '{lang_'. $key .'}';
		if($searchReplace && isset($searchReplace[$k])) {
			$val = $searchReplace[$k];
		} else {
			$t = __($key, 'pdf24');
			if(trim($t) != trim($key)) {
				$val = $t;
			}
		}		
		$map[$k] = $val;
	}
	
	if($searchReplace && isset($searchReplace['{lang_sendAsPDF}'])) {
		$map['{lang_sendAsPDF}'] = $searchReplace['{lang_sendAsPDF}'];
	} else {
		$map['{lang_sendAsPDF}'] = $count == 1 ? pdf24Plugin_getLangVal('sendArticleAsPDF') : pdf24Plugin_getLangVal('sendArticlesAsPDF');
	}

	if($searchReplace && isset($searchReplace['{lang_downloadAsPDF}'])) {
		$map['{lang_downloadAsPDF}'] = $searchReplace['{lang_downloadAsPDF}'];
	} else {
		$map['{lang_downloadAsPDF}'] = $count == 1 ? pdf24Plugin_getLangVal('downloadArticleAsPDF') : pdf24Plugin_getLangVal('downloadArticlesAsPDF');
	}
	
	return str_replace(array_keys($map), array_values($map), $str);
}

function pdf24Plugin_nextFormId() {
	global $pdf24Plugin;
	if(!isset($pdf24Plugin['nextFormId'])) {
		$pdf24Plugin['nextFormId'] = 0;
	}
	return $pdf24Plugin['nextFormId']++;
}

function pdf24Plugin_parseTplContent($postsArr, $tpl, $styleId, $searchReplace = null) {
	global $pdf24Plugin;
	if(count($postsArr) == 0) {
		return '';
	}
	$blInfo = pdf24Plugin_getBLInfo();
	list($blText, $blUrl) = $blInfo;
	$hiddenFilds = pdf24Plugin_getBlogHiddenFields(count($postsArr));
	foreach($postsArr as $key => $val) {
		$hiddenFilds .= pdf24Plugin_getFormHiddenFields($val, '', '_' . $key);
	}
	$tpl = file_get_contents(pdf24Plugin_getFile('tpl', $tpl));
	$data = array(
		'{styleId}' => $styleId,
		'{formId}' => pdf24Plugin_nextFormId(),
		'{actionUrl}' => $pdf24Plugin['serviceUrl'],
		'{hiddenFields}' => $hiddenFilds,
		'{blText}' => $blText,
		'{blUrl}' => $blUrl,
		'{pluginUrl}' => $pdf24Plugin['url'],
		'{targetName}' => $pdf24Plugin['targetName'],
		'{openTargetCode}' => $pdf24Plugin['jsOpenTargetWin'],
		'{callbackCode}' => $pdf24Plugin['jsCallbackCode']
	);
	$content = str_replace(array_keys($data), array_values($data), $tpl);
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

function pdf24Plugin_validateFormId($formId) {
	return preg_replace('/[^a-zA-Z0-9\-_]/','', $formId);
}

function pdf24Plugin_parseHiddenForm($postsArr, $formId) {
	global $pdf24Plugin;
	if(count($postsArr) == 0) {
		return '';
	}
	$hiddenFilds = pdf24Plugin_getBlogHiddenFields(count($postsArr));
	foreach($postsArr as $key => $val) {
		$hiddenFilds .= pdf24Plugin_getFormHiddenFields($val, '', '_' . $key);
	}
	$tpl = file_get_contents(pdf24Plugin_getFile('tpl', 'hiddenForm.html'));
	$data = array(
		'{formId}' => pdf24Plugin_validateFormId($formId),
		'{actionUrl}' => $pdf24Plugin['serviceUrl'],
		'{hiddenFields}' => $hiddenFilds,
		'{targetName}' => $pdf24Plugin['targetName'],
	);
	$content = str_replace(array_keys($data), array_values($data), $tpl);
	return $content;
}

function pdf24Plugin_parseFormSubmit($formId, $tpl = 'formSubmitText') {
	global $pdf24Plugin;
	$tpl = file_get_contents(pdf24Plugin_getFile('tpl', $tpl . '.html'));
	$blInfo = pdf24Plugin_getBLInfo();
	list($blText, $blUrl) = $blInfo;

	$data = array(
		'{formId}' => pdf24Plugin_validateFormId($formId),
		'{pluginUrl}' => $pdf24Plugin['url'],
		'{openTargetCode}' => $pdf24Plugin['jsOpenTargetWin'],
		'{callbackCode}' => $pdf24Plugin['jsCallbackCode'],
		'{blText}' => $blText,
		'{blUrl}' => $blUrl
	);
	$content = str_replace(array_keys($data), array_values($data), $tpl);
	$content = pdf24Plugin_replaceLang($content, count($postsArr));
	return $content;
}

function pdf24Plugin_getAllPosts() {
	global $pdf24Plugin, $post;

	//reset
	rewind_posts();
	$pdf24PostsArr = array();

	//pdf24 filter deaktivieren
	$pdf24Plugin['disableContentFilter'] = true;
	if (have_posts()) {
		while (have_posts()) {
			the_post();

			//filter auf content anwenden
			$content = get_the_content();
			$content = apply_filters('the_content', $content);
			
			$pdf24Params = array(
				'postId' => $post->ID,
				'postTitle' => get_the_title(),
				'postLink' => get_permalink(),
				'postAuthor' => get_the_author(),
				'postDateTime' => get_the_time('Y-m-d H:m:s'),
				'postContent' => $content
			);
			$pdf24PostsArr[] = $pdf24Params;
		}
	}
	rewind_posts();
	$pdf24Plugin['disableContentFilter'] = false;
	return $pdf24PostsArr;
}

function pdf24Plugin_getThePost() {
	global $pdf24Plugin, $post;
	$pdf24Plugin['disableContentFilter'] = true;
	$params = array(
		'postId' => $post->ID,
		'postTitle' => get_the_title(),
		'postLink' => get_permalink(),
		'postAuthor' => get_the_author(),
		'postDateTime' => get_the_time('Y-m-d H:m:s'),
		'postContent' => get_the_content()
	);
	$pdf24Plugin['disableContentFilter'] = false;
	return $params;
}

function pdf24Plugin_getPostsArr($arg = false) {
	global $pdf24Plugin, $post;

	//if already a posts array, return this
	if($arg && is_array($arg)) {
		return $arg;
	}
	
	//if we have buffered posts, use this ones
	if(isset($pdf24Plugin['bufferedPosts']) && count($pdf24Plugin['bufferedPosts']) > 0) {
		$postsArr = $pdf24Plugin['bufferedPosts'];
		if($postsArr && count($postsArr) > 0) {
			//if we are in the loop and the current post is requested, return this one
			if(in_the_loop() && ($arg === false || $arg == $post->ID)) {
				if($postsArr[count($postsArr) - 1]['postId'] == $post->ID) {
					return array($postsArr[count($postsArr) - 1]);
				}
				return array(pdf24Plugin_getThePost());
			}
			//if $arg is false then we are not in the loop. Return all posts.
			if($arg === false) {
				return $postsArr;
			}
			//find a post based on his ID
			foreach($postsArr as $arr) {
				if($arr['postId'] == $arg) {
					return array($arr);
				}
			}
			//no post found
			return array();
		}
	}
	
	//if we are in the loop and we want the current post, return the current one
	if(in_the_loop() && ($arg === false || $arg == $post->ID)) {
		return array(pdf24Plugin_getThePost());
	}
	
	//we have no buffered posts, collect all ones on the page
	$postsArr = pdf24Plugin_getAllPosts();
	
	//if $arg is false then we are not in the loop. Return all posts.
	if($arg === false) {
		return $postsArr;
	}
	
	//find a post based on his ID
	foreach($postsArr as $arr) {
		if($arr['postId'] == $arg) {
			return array($arr);
		}
	}
	
	//no post found
	return array();
}

function pdf24Plugin_getPostForm($arg = false) {
	if($arg && is_array($arg)) {
		return pdf24Plugin_getTplContent(array($arg), 'pdf24Plugin_cpStyle', 'styles/cp', 'cp');
	}
	$postsArr = pdf24Plugin_getPostsArr($arg);
	if($postsArr && count($postsArr) > 0) {
		$postArr = $postsArr[count($postsArr) - 1];
		if($postArr) {
			return pdf24Plugin_getTplContent(array($postArr), 'pdf24Plugin_cpStyle', 'styles/cp', 'cp');
		}
	}
	return '';
}

function pdf24Plugin_getTopBottomForm($arg = false) {
	$postsArr = pdf24Plugin_getPostsArr($arg);
	if($postsArr) {
		return pdf24Plugin_getTplContent($postsArr, 'pdf24Plugin_tbpStyle', 'styles/tbp', 'tbp');
	}
	return '';
}

function pdf24Plugin_getSidebarForm($arg = false) {
	$postsArr = pdf24Plugin_getPostsArr($arg);
	if($postsArr) {
		return pdf24Plugin_getTplContent($postsArr, 'pdf24Plugin_sbpStyle', 'styles/sbp', 'sbp');
	}
	return '';
}

function pdf24Plugin_getLinkForm($txt = null, $arg = false) {
	$postsArr = pdf24Plugin_getPostsArr($arg);
	if($postsArr) {
		$searchReplace = null;
		if($txt != null && trim($txt) != '') {
			$searchReplace = array(
				'{lang_downloadAsPDF}' => $txt,
				'{lang_sendAsPDF}' => $txt
			);
		}
		return pdf24Plugin_getTplContent($postsArr, 'pdf24Plugin_lpStyle', 'styles/lp', 'lp', $searchReplace);
	}
	return '';
}

function pdf24Plugin_getHiddenForm($formId, $arg = false) {
	$postsArr = pdf24Plugin_getPostsArr($arg);
	if($postsArr) {
		return pdf24Plugin_parseHiddenForm($postsArr, $formId);
	}
	return '';
}

function pdf24Plugin_content($content) {
	global $more, $pdf24Plugin, $post;
	if(isset($pdf24Plugin['disableContentFilter']) && $pdf24Plugin['disableContentFilter']) {
		return $content;
	}
	if(isset($pdf24Plugin['bufferMode']) && $pdf24Plugin['bufferMode']) {
		return $content;
	}
	if(pdf24Plugin_isDisabledOn('cp')) {
		return $content;
	}
	if(/*(isset($more) && $more == 1) ||*/ is_feed()) {
		return $content;
	}
	if(strpos($content,'more-link') !== false || strpos($content,'<!--more-->') !== false) {
		return $content;
	}
	$params = array(
		'postId' => $post->ID,
		'postTitle' => get_the_title(),
		'postLink' => get_permalink(),
		'postAuthor' => get_the_author(),
		'postDateTime' => get_the_time('Y-m-d H:m:s'),
		'postContent' => $content
	);
	$out = pdf24Plugin_getPostForm($params);
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

function pdf24Plugin_getWhere() {
	if(is_category()) {
		return 'category';
	} else if(is_page()) {
		return 'page';
	} else if(is_home()) {
		return 'home';
	} else if(is_single()) {
		return 'single';
	} else if(is_search()) {
		return 'search';
	} else {
		return 'unknown';
	}
}

function pdf24Plugin_getDocFilename($where = false) {
	if(!$where) {
		$where = pdf24Plugin_getWhere();
		if($where == 'unknown') {
			$where = 'default';
		}
	}
	$name = get_option('pdf24Plugin_docFilename_' . $where);
	return $name === false ? '' : $name;
}

function pdf24Plugin_getDate($str) {
	if(!$str || $str == '') {
		$str = 'Y-m-d';
	}
	return date($str);
}

function pdf24Plugin_parseDocFilename($name) {
	$repl = array(
		'{blogName}' => get_bloginfo('name'),
		'{date}' => '',
		'{catSlug}' => '',
		'{catName}' => '',
		'{catNiceName}' => '',
		'{pageId}' => '',
		'{pageAuthor}' => '',
		'{pageDate}' => '',
		'{pageTitle}' => '',
		'{pageName}' => '',
		'{singleId}' => '',
		'{singleAuthor}' => '',
		'{singleDate}' => '',
		'{singleTitle}' => '',
		'{singleName}' => '',
		'{searchQuery}' => ''
	);
	
	$name = preg_replace('/\{date(:(.*?))?}/e', 'pdf24Plugin_getDate("$2")', $name);
	
	if(is_category()) {
		$cat = get_query_var('cat');
		$yourcat = get_category($cat);
		$repl['{catSlug}'] = $yourcat->slug;
		$repl['{catName}'] = $yourcat->name;
		$repl['{catNiceName}'] = $yourcat->category_nicename;
	}
	if(is_page()) {
		global $post;
		if($post) {
			$repl['{pageId}'] = $post->ID;
			$repl['{pageAuthor}'] = $post->post_author;
			$repl['{pageDate}'] = $post->post_date;
			$repl['{pageTitle}'] = $post->post_title;
			$repl['{pageName}'] = $post->post_name;
		}
	}
	if(is_single()) {
		global $post;
		if($post) {
			$repl['{singleId}'] = $post->ID;
			$repl['{singleAuthor}'] = $post->post_author;
			$repl['{singleDate}'] = $post->post_date;
			$repl['{singleTitle}'] = $post->post_title;
			$repl['{singleName}'] = $post->post_name;
		}
	}
	if(is_search()) {
		if(function_exists('get_search_query')) {
			$repl['{searchQuery}'] = get_search_query();
		}
	}
	if(is_home()) {
	}
	
	$name = str_replace(array_keys($repl), array_values($repl), $name);
	return $name;
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
		$p = strpos($file,'default_');
		if($p === 0) {
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
		} else {
			$stylesArr[] = array('file', $styleFolder . '/' . pdf24Plugin_getDefaultStyle($styleFolder) . '.css');
		}
	}
}

function pdf24Plugin_makeJsString($str) {
	return htmlspecialchars(str_replace(array("\r\n","\n","\t"), array('\r\n','\n','\t'), $str));
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
		$selected = $f == $style || $p[0] == $style;
		$parms['options'] .= '<option value="'. $f .'" ' . ($selected ? 'selected="true"' : '') . '>'. $name .'</option>';

		$default = file_get_contents($pdf24Plugin['dir'] . '/' . $folder . '/' . $f . '.css');
		$custom = get_option($wpSetting . '_' . $f);
		if(!$custom || $custom == '') {
			$custom = $default;
		}

		if($f == $style || $p[0] == $style) {
			$parms['custom'] = htmlspecialchars($custom);
		}

		$parms['js'] .= $wpSetting . "_custom.push('" . pdf24Plugin_makeJsString($custom) . "'); ";
		$parms['js'] .= $wpSetting . "_default.push('" . pdf24Plugin_makeJsString($default) . "'); ";
		$parms['customized'] = get_option($wpSetting . '_customize') === 'true';
	}
	return $parms;
}

function pdf24Plugin_isContentCompression() {
	$opt = get_option('pdf24Plugin_contentCompression');
	return $opt === false || $opt == 'true';
}

function pdf24Plugin_emptyFilter($str) {
	return trim($str) == '' ? false : true;
}

function pdf24Plugin_setDisabledOn($which, $where, $disabled) {
	$opt = get_option('pdf24Plugin_'. $which .'_disabledOn');
	if($opt === false) {
		$opt = '';
	}
	$arr = explode(',', $opt);
	$arr = array_filter($arr, 'pdf24Plugin_emptyFilter');
	$arr = array_flip($arr);
	if($disabled) {
		if(!isset($arr[$where])) {
			$arr[$where] = count($arr);
		}
	} else {
		if(isset($arr[$where])) {
			unset($arr[$where]);
		}
	}
	$arr = array_flip($arr);
	$opt = implode(',', $arr);
	update_option('pdf24Plugin_'. $which .'_disabledOn', $opt);
}

function pdf24Plugin_isDisabledOn($which, $where = false) {
	global $pdf24Plugin;
	if(!$where) {
		$where = pdf24Plugin_getWhere();
	}
	if($which == 'cp' && $where == 'page') {
		$opt = get_option('pdf24Plugin_cpDisabledOnPages');
		if($opt !== false) {
			return $opt == 'true';
		}
	} else if($which == 'sbp' && $where == 'page') {
		$opt = get_option('pdf24Plugin_sbpDisabledOnPages');
		if($opt !== false) {
			return $opt == 'true';
		}
	} else if($which == 'tbp' && $where == 'page') {
		$opt = get_option('pdf24Plugin_tbpDisabledOnPages');
		if($opt !== false) {
			return $opt == 'true';
		}
	} else if($which == 'lp' && $where == 'page') {
		$opt = get_option('pdf24Plugin_lpDisabledOnPages');
		if($opt !== false) {
			return $opt == 'true';
		}
	}
	
	$opt = get_option('pdf24Plugin_'. $which .'_disabledOn');
	if($opt === false) {
		$opt = $pdf24Plugin['defaultDisabledOn'][$which];
	}
	return strpos($opt, $where) !== false;
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

function pdf24Plugin_post($arg = false) {
	if(pdf24Plugin_isCpInUse() && pdf24Plugin_isAvailable()) {
		if(!pdf24Plugin_isDisabledOn('cp')) {
			echo pdf24Plugin_getPostForm($arg);
		}
	}
}

function pdf24Plugin_sidebar($arg = false) {
	if(pdf24Plugin_isSbpInUse() && pdf24Plugin_isAvailable()) {
		if(!pdf24Plugin_isDisabledOn('sbp')) {
			echo pdf24Plugin_getSidebarForm($arg);
		}
	}
}

function pdf24Plugin_topBottom($arg = false) {
	if(pdf24Plugin_isTbpInUse() && pdf24Plugin_isAvailable()) {
		if(!pdf24Plugin_isDisabledOn('tbp')) {
			echo pdf24Plugin_getTopBottomForm($arg);
		}
	}
}

function pdf24Plugin_link($txt = null, $arg = false) {
	if(pdf24Plugin_isLpInUse() && pdf24Plugin_isAvailable()) {
		if(!pdf24Plugin_isDisabledOn('lp')) {
			echo pdf24Plugin_getLinkForm($txt, $arg);
		}
	}
}

function pdf24Plugin_form($formId) {
	if(pdf24Plugin_isAvailable()) {
		echo pdf24Plugin_getHiddenForm($formId);
	}
}

function pdf24Plugin_formSubmit($formId, $tpl = 'formSubmitText') {
	if(pdf24Plugin_isAvailable()) {
		echo pdf24Plugin_parseFormSubmit($formId, $tpl);
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
		if(!pdf24Plugin_isDisabledOn('sbp')) {
			extract($args);
			echo $before_widget;
			echo $before_title . pdf24Plugin_getWidgetTitle() . $after_title;
			echo pdf24Plugin_getSidebarForm();
			echo $after_widget;
		}
	}
}

function pdf24Plugin_getWidgetTitle() {
	$text = get_option('pdf24Plugin_widgetTitle');
	return $text === false ? '' : trim($text);
}

function pdf24Plugin_isCustomizedDocTpl() {
	$opt = get_option('pdf24Plugin_docTplInUse');
	return $opt == 'true';
}

function pdf24Plugin_getCustomizedDocTpl() {
	return get_option('pdf24Plugin_docTpl');
}

function pdf24Plugin_getDefaultDocTpl() {
	return pdf24Plugin_getFileContents('tpl', 'docTpl.html');
}

function pdf24Plugin_getDocTpl() {
	global $pdf24Plugin;
	$default = pdf24Plugin_getDefaultDocTpl();
	$custom = pdf24Plugin_isCustomizedDocTpl() ? pdf24Plugin_getCustomizedDocTpl() : false;
	return array(
		'tpl' => ($custom ? $custom : $default),
		'custom' => $custom,
		'default' => $default
	);
}

function pdf24Plugin_isCustomizedDocEntryTpl() {
	$opt = get_option('pdf24Plugin_docEntryTplInUse');
	return $opt == 'true';
}

function pdf24Plugin_getCustomizedDocEntryTpl() {
	return get_option('pdf24Plugin_docEntryTpl');
}

function pdf24Plugin_getDefaultDocEntryTpl() {
	return pdf24Plugin_getFileContents('tpl', 'docEntryTpl.html');
}

function pdf24Plugin_getDocEntryTpl() {
	global $pdf24Plugin;
	$default = pdf24Plugin_getDefaultDocEntryTpl();
	$custom = pdf24Plugin_isCustomizedDocEntryTpl() ? pdf24Plugin_getCustomizedDocEntryTpl() : false;
	return array(
		'tpl' => ($custom ? $custom : $default),
		'custom' => $custom,
		'default' => $default
	);
}

function pdf24Plugin_begin() {
	global $pdf24Plugin;
	if(!isset($pdf24Plugin['bufferMode']) || !$pdf24Plugin['bufferMode']) {
		$pdf24Plugin['bufferMode'] = true;
		if(!isset($pdf24Plugin['bufferedPosts'])) {
			$pdf24Plugin['bufferedPosts'] = array();
		}
		ob_start();
	}
}

function pdf24Plugin_end() {
	global $pdf24Plugin, $post;
	if(!isset($pdf24Plugin['bufferMode']) || !$pdf24Plugin['bufferMode']) {
		return;
	}
	$pdf24Plugin['bufferMode'] = false;
	$content = ob_get_contents();
	ob_end_clean();
	echo $content;
	
	$params = array(
		'postId' => $post->ID,
		'postTitle' => get_the_title(),
		'postLink' => get_permalink(),
		'postAuthor' => get_the_author(),
		'postDateTime' => get_the_time("Y-m-d H:m:s"),
		'postContent' => $content
	);
	$pdf24Plugin['bufferedPosts'][] = $params;
}

?>