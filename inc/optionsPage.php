<?php

function pdf24Plugin_getLangOptions() {
	global $pdf24Plugin;	
	$l = get_option('pdf24Plugin_language');
	if($l === false) {
		$l = isset($pdf24Plugin['useLang']) ? $pdf24Plugin['useLang'] : $pdf24Plugin['defaultLang'];
	}
	$out = '';
	foreach($pdf24Plugin['langCodes'] as $key => $val) {
		$out .= '<option value="'. $key .'"'. ($l == $key ? ' selected' : '') .'>'. $val .'</option>';
	}
	return $out;
}

function pdf24Plugin_createCustomizedLangInputs() {
	global $pdf24Plugin;
	$out = '';
	$langElements = pdf24Plugin_getLangElements();
	foreach($langElements as $key => $val) {
		$out .= '<input type="text" name="lang-'. $key .'" value="'. addslashes($val) .'" style="width:300px" /> ('. $pdf24Plugin['lang'][$key] .')<br />';
	}
	return $out;
}

function pdf24Plugin_createDocSizeOptions() {
	global $pdf24Plugin;	
	$currentSize = pdf24Plugin_getDocSize();
	$out = '';
	foreach($pdf24Plugin['docSizes'] as $key => $val) {
		if($key != 'default') {
			$out .= '<option value="'. $key .'"'. ($key == $currentSize ? ' selected' : '') .'>'. $key .'</option>';
		}
	}
	return $out;
}

function pdf24Plugin_createDocOrientationOptions() {
	global $pdf24Plugin;	
	$currentOrientation = pdf24Plugin_getDocOrientation();
	$out = '';
	foreach($pdf24Plugin['docOrientations'] as $key => $val) {
		if($key != 'default') {
			$out .= '<option value="'. $key .'"'. ($key == $currentOrientation ? ' selected' : '') .'>'. $val .'</option>';
		}
	}
	return $out;
}

include_once($pdf24Plugin['dir'] . '/inc/langCodes.php');

if (isset($_POST['update'])) {
	delete_option('pdf24Plugin_cpDisabledOnPages');
	delete_option('pdf24Plugin_sbpDisabledOnPages');
	delete_option('pdf24Plugin_tbpDisabledOnPages');
	delete_option('pdf24Plugin_lpDisabledOnPages');

	update_option('pdf24Plugin_debug', isset($_POST['debug']) ? 'true' : 'false');
	update_option('pdf24Plugin_language', $_POST['language']);
	update_option('pdf24Plugin_availability', $_POST['availability']);
	update_option('pdf24Plugin_contentCompression', isset($_POST['contentCompression']) ? 'true' : 'false');
	
	update_option('pdf24Plugin_cpInUse', isset($_POST['cpInUse']) ? 'true' : 'false');
	update_option('pdf24Plugin_cpDisplayMode', $_POST['cpDisplayMode']);
	update_option('pdf24Plugin_cpStyle', $_POST['cpStyle']);
	update_option('pdf24Plugin_cpStyle_customize', isset($_POST['cpCustomize']) ? 'true' : 'false');
	update_option('pdf24Plugin_cpStyle_' . $_POST['cpStyle'], stripslashes($_POST['cpCustomStyle']));
	pdf24Plugin_setDisabledOn('cp', 'page', isset($_POST['cpDisableOnPages']));
	pdf24Plugin_setDisabledOn('cp', 'category', isset($_POST['cpDisableInCategories']));
	pdf24Plugin_setDisabledOn('cp', 'search', isset($_POST['cpDisableInSearches']));
	pdf24Plugin_setDisabledOn('cp', 'home', isset($_POST['cpDisableOnHome']));
	pdf24Plugin_setDisabledOn('cp', 'single', isset($_POST['cpDisableOnSingles']));
		
	update_option('pdf24Plugin_sbpInUse', isset($_POST['sbpInUse']) ? 'true' : 'false');
	update_option('pdf24Plugin_sbpStyle', $_POST['sbpStyle']);
	update_option('pdf24Plugin_sbpStyle_customize', isset($_POST['sbpCustomize']) ? 'true' : 'false');
	update_option('pdf24Plugin_sbpStyle_' . $_POST['sbpStyle'], stripslashes($_POST['sbpCustomStyle']));
	pdf24Plugin_setDisabledOn('sbp', 'page', isset($_POST['sbpDisableOnPages']));
	pdf24Plugin_setDisabledOn('sbp', 'category', isset($_POST['sbpDisableInCategories']));
	pdf24Plugin_setDisabledOn('sbp', 'search', isset($_POST['sbpDisableInSearches']));
	pdf24Plugin_setDisabledOn('sbp', 'home', isset($_POST['sbpDisableOnHome']));
	pdf24Plugin_setDisabledOn('sbp', 'single', isset($_POST['sbpDisableOnSingles']));
	
	update_option('pdf24Plugin_tbpInUse', isset($_POST['tbpInUse']) ? 'true' : 'false');
	update_option('pdf24Plugin_tbpStyle', $_POST['tbpStyle']);
	update_option('pdf24Plugin_tbpStyle_customize', isset($_POST['tbpCustomize']) ? 'true' : 'false');
	update_option('pdf24Plugin_tbpStyle_' . $_POST['tbpStyle'], stripslashes($_POST['tbpCustomStyle']));
	pdf24Plugin_setDisabledOn('tbp', 'page', isset($_POST['tbpDisableOnPages']));
	pdf24Plugin_setDisabledOn('tbp', 'category', isset($_POST['tbpDisableInCategories']));
	pdf24Plugin_setDisabledOn('tbp', 'search', isset($_POST['tbpDisableInSearches']));
	pdf24Plugin_setDisabledOn('tbp', 'home', isset($_POST['tbpDisableOnHome']));
	pdf24Plugin_setDisabledOn('tbp', 'single', isset($_POST['tbpDisableOnSingles']));
	
	update_option('pdf24Plugin_lpInUse', isset($_POST['lpInUse']) ? 'true' : 'false');
	update_option('pdf24Plugin_lpStyle', $_POST['lpStyle']);
	update_option('pdf24Plugin_lpStyle_customize', isset($_POST['lpCustomize']) ? 'true' : 'false');
	update_option('pdf24Plugin_lpStyle_' . $_POST['lpStyle'], stripslashes($_POST['lpCustomStyle']));
	pdf24Plugin_setDisabledOn('lp', 'page', isset($_POST['lpDisableOnPages']));
	pdf24Plugin_setDisabledOn('lp', 'category', isset($_POST['lpDisableInCategories']));
	pdf24Plugin_setDisabledOn('lp', 'search', isset($_POST['lpDisableInSearches']));
	pdf24Plugin_setDisabledOn('lp', 'home', isset($_POST['lpDisableOnHome']));
	pdf24Plugin_setDisabledOn('lp', 'single', isset($_POST['lpDisableOnSingles']));
	
	update_option('pdf24Plugin_emailOptionsInUse', isset($_POST['emailOptionsInUse']) ? 'true' : 'false');
	update_option('pdf24Plugin_emailType', $_POST['emailType']);
	update_option('pdf24Plugin_emailSubject', stripslashes($_POST['emailSubject']));
	update_option('pdf24Plugin_emailFrom', stripslashes($_POST['emailFrom']));
	update_option('pdf24Plugin_emailText', stripslashes($_POST['emailText']));
	
	update_option('pdf24Plugin_docOptionsInUse', isset($_POST['docOptionsInUse']) ? 'true' : 'false');
	update_option('pdf24Plugin_docHeader', stripslashes($_POST['docHeader']));
	update_option('pdf24Plugin_docSize', $_POST['docSize']);
	update_option('pdf24Plugin_docOrientation', $_POST['docOrientation']);
	update_option('pdf24Plugin_docStyle', stripslashes($_POST['docStyle']));
	update_option('pdf24Plugin_docFilename_default', stripslashes($_POST['docDefaultFilename']));
	update_option('pdf24Plugin_docFilename_category', stripslashes($_POST['docCategoryFilename']));
	update_option('pdf24Plugin_docFilename_search', stripslashes($_POST['docSearchFilename']));
	update_option('pdf24Plugin_docFilename_page', stripslashes($_POST['docPageFilename']));
	update_option('pdf24Plugin_docFilename_home', stripslashes($_POST['docHomeFilename']));
	update_option('pdf24Plugin_docFilename_single', stripslashes($_POST['docSingleFilename']));
		
	update_option('pdf24Plugin_docTplInUse', isset($_POST['docTplInUse']) ? 'true' : 'false');
	update_option('pdf24Plugin_docTpl', stripslashes($_POST['docTpl']));
	
	update_option('pdf24Plugin_docEntryTplInUse', isset($_POST['docEntryTplInUse']) ? 'true' : 'false');
	update_option('pdf24Plugin_docEntryTpl', stripslashes($_POST['docEntryTpl']));

	if(isset($_POST['customLangInUse'])) {
		$customLang = array();
		foreach($_POST as $key => $val) {
			if(substr($key, 0, 5) == 'lang-') {
				$customLang[substr($key, 5)] = stripslashes($val);
			}
		}
		update_option('pdf24Plugin_customLang', $customLang);
	} else {
		update_option('pdf24Plugin_customLang', '');
	}
	
	//Update global language
	pdf24Plugin_setLang();
	global $pdf24Plugin;
	$warnings = array();
		
	//check language
	if(!isset($_POST['customLangInUse'])) {
		$availableLangs = pdf24Plugin_getAvailableLang();
		if(!in_array($_POST['language'], $availableLangs)) {
			$usedLang = $pdf24Plugin['langCodes'][$pdf24Plugin['defaultLang']];
			$warnings[] = 'There is no language file installed for this defined language. Currently the default language <b>'. $usedLang .'</b> will be used. Please use the <a href="#customLang">Customize Language</a> settings to customize the language.';
		}
	}
	
	?>
	<div class="updated">
		<p>Changes saved.</p>
		<?php if(count($warnings) > 0) echo '<b>Warning</b><ul><li>'. implode('</li><li>', $warnings) . '</li></ul>'; ?>
	</div>
	<?php
}
?>


<div class="wrap">
<script language="javascript">
document.getElementsByClassName = function(cl) {
	var retnode = [];
	var myclass = new RegExp('\\b'+cl+'\\b');
	var elem = this.getElementsByTagName('*');
	for (var i = 0; i < elem.length; i++) {
		var classes = elem[i].className;
		if (myclass.test(classes)) retnode.push(elem[i]);
	}
	return retnode;
};

var pdf24_formError = false;
function pdf24_check(elem, v) {
	if(v) {
		elem.style.border = '2px solid red';
		pdf24_formError = true;
	} else {
		elem.style.border = '';
	}
}
function pdf24_checkForm(form) {
	pdf24_formError = false;
	if(form.customLangInUse.checked) {
		for(var i=0; i<form.length; i++) {
			if(form.elements[i].name && form.elements[i].name.match(/^lang-/)) {
				pdf24_check(form.elements[i], form.elements[i].value.length < 3);
			}
		}
	}
	return !pdf24_formError;
}
function pdf24_showHideSel(id,select) {
	if(select.options[select.selectedIndex].value == '%custom%') {
		document.getElementById(id).className = '';
	} else {
		document.getElementById(id).className = 'noDis';
	}
}
function pdf24_showHideCheck(id,check) {
	if(check.checked) {
		document.getElementById(id).className = '';
	} else {
		document.getElementById(id).className = 'noDis';
	}
}



function hideInfoBox() {
	document.getElementsByTagName
	document.getElementById('InfoBox').style.visibility = "hidden";
}
function showInfoBox(e,which) {
	var offsetx = 20;
	var offsety = 0;
	var PositionX = 0;
	var PositionY = 0;
	if (!e) var e = window.event;
	if (e.pageX || e.pageY) {
		PositionX = e.pageX;
		PositionY = e.pageY;
	}
	else if (e.clientX || e.clientY) {
		PositionX = e.clientX + document.body.scrollLeft;
		PositionY = e.clientY + document.body.scrollTop;
	}
	document.getElementById("BoxInhalte").innerHTML = Inhalte;
	document.getElementById('InfoBox').style.left = (PositionX+offsetx)+"px";
	document.getElementById('InfoBox').style.top = (PositionY+offsety)+"px";
	document.getElementById('InfoBox').style.visibility = "visible";
}
// --> 
</script>
<style type="text/css">
	h3 { margin-bottom:0px; padding-bottom:0px }
	td { vertical-align:top; padding:0; margin:0; }
	table { padding:0; margin:0; }
	.descr  { margin-bottom:10px; font-style:italic;}
	.tr1 { vertical-align:top; width:280px; padding:6px;}
	.tr2 { vertical-align:top; padding:6px;}
	.noDis {display:none;}
	.cusSty {width:700px; height:150px;}
	.cusDocTpl {width:700px; height:150px;}
	.cusDocEntryTpl {width:700px; height:150px;}
	.infoBox {display:none; position:absolute; z-index:1; background-color:#FDFEFF; border:3px solid #0090E0;}
</style>

<div id="dddd" class="infoBox" style="width:400px; height:300px">

</div>

<h2>PDF24 Plugin Options</h2>
<form name="pdf24Form" method="post" onsubmit="return pdf24_checkForm(this)">
	<div class="submit">
		<input type="submit" name="update" value="Update PDF24 Plugin Options" style="font-weight:bold;" />
	</div>
	<div>
		<h3>General</h3>
		<div class="descr">General settings of the plugin.</div>
		<table>
		<tr>
			<td class="tr1">Language:</td>
			<td class="tr2"><select name="language"><?php echo pdf24Plugin_getLangOptions(); ?></select></td>
		</tr>
		<tr>
			<td class="tr1">Availability:</td>
			<td class="tr2"><select name="availability">
				<option value="public">For all visitors</option>
				<option value="private">Only for logged in users</option>
			</select></td>
		</tr>
		<tr>
			<td class="tr1">Debug outputs:</td>
			<td class="tr2"><input type="checkbox" name="debug" <?php echo pdf24Plugin_isDebug() ? 'checked' : ''; ?> /></td>
		</tr>
		<tr>
			<td class="tr1">Compress Contents</td>
			<td class="tr2"><input type="checkbox" name="contentCompression" <?php echo pdf24Plugin_isContentCompression() ? 'checked' : ''; ?> />
			Faster Load if enabled
			</td>
		</tr>
		</table>
	</div>
	<div>
		<h3>Document Options</h3>
		<div class="descr">Options for created PDF documents.</div>
		<table>
		<tr>
			<td class="tr1">Customize options</td>
			<td class="tr2"><input type="checkbox" name="docOptionsInUse" <?php echo pdf24Plugin_isDocOptionsInUse() ? 'checked' : ''; ?> onclick="pdf24_showHideCheck('docOptions', this);" /></td>
		</tr>
		</table>
		<table id="docOptions" class="<?php echo pdf24Plugin_isDocOptionsInUse() ? '' : 'noDis' ?>">
		<tr>
			<td class="tr1">Title Text:</td>
			<td class="tr2"><input name="docHeader" style="width:600px" value="<?php echo htmlspecialchars(pdf24Plugin_getDocHeader()); ?>" /></td>
		</tr>
		<tr>
			<td class="tr1">Page format:</td>
			<td class="tr2"><select name="docSize"><?php echo pdf24Plugin_createDocSizeOptions(); ?></select> <select name="docOrientation"><?php echo pdf24Plugin_createDocOrientationOptions(); ?></select></td>
		</tr>
		<tr>
			<td class="tr1">CSS Style:<br /><small>Use CSS to format the tags and classes <b>body, h1, h2, p, div, a, .bodyPart, .meta, .text</b> and others</small></td>
			<td class="tr2"><textarea name="docStyle" style="width:600px; height:120px"><?php echo htmlspecialchars(pdf24Plugin_getDocStyle()); ?></textarea></td>
		</tr>
		<tr>
			<td class="tr1" height="60">Default filename:</td>
			<td class="tr2"><input name="docDefaultFilename" style="width:600px" value="<?php echo htmlspecialchars(pdf24Plugin_getDocFilename('default')); ?>" />
			<br /><small>Allowed placeholders: <b>{blogName}, {date:FORMAT}</b></small></td>
		</tr>
		<tr>
			<td class="tr1" height="60">Filename for home page:</td>
			<td class="tr2"><input name="docHomeFilename" style="width:600px" value="<?php echo htmlspecialchars(pdf24Plugin_getDocFilename('home')); ?>" />
			<br /><small>Allowed placeholders: <b>{blogName}, {date:FORMAT}</b></small></td>
		</tr>
		<tr>
			<td class="tr1" height="60">Filename for single post pages:</td>
			<td class="tr2"><input name="docSingleFilename" style="width:600px" value="<?php echo htmlspecialchars(pdf24Plugin_getDocFilename('single')); ?>" />
			<br /><small>Allowed placeholders: <b>{blogName}, {date:FORMAT}, {singleId}, {singleAuthor}, {singleDate}, {singleTitle}, {singleName}</b></small></td>
		</tr>
		<tr>
			<td class="tr1" height="60">Filename for wordpress pages:</td>
			<td class="tr2"><input name="docPageFilename" style="width:600px" value="<?php echo htmlspecialchars(pdf24Plugin_getDocFilename('page')); ?>" />
			<br /><small>Allowed placeholders: <b>{blogName}, {date:FORMAT}, {pageId}, {pageAuthor}, {pageDate}, {pageTitle}, {pageName}</b></small></td>
		</tr>
		<tr>
			<td class="tr1" height="60">Filename for category pages:</td>
			<td class="tr2"><input name="docCategoryFilename" style="width:600px" value="<?php echo htmlspecialchars(pdf24Plugin_getDocFilename('category')); ?>" />
			<br /><small>Allowed placeholders: <b>{blogName}, {date:FORMAT}, {catSlug}, {catName}, {catNiceName}</b></small></td>
		</tr>
		<tr>
			<td class="tr1" height="60">Filename for search pages:</td>
			<td class="tr2"><input name="docSearchFilename" style="width:600px" value="<?php echo htmlspecialchars(pdf24Plugin_getDocFilename('search')); ?>" />
			<br /><small>Allowed placeholders: <b>{blogName}, {date:FORMAT}, {searchQuery},</b></small></td>
		</tr>
		</table>
	</div>
	<div>
		<h3>Email Options</h3>	
		<div class="descr">The created PDF is sent to the entered email address. You can enter your own email texts.</div>
		<table>
		<tr>
			<td class="tr1">Customize options</td>
			<td class="tr2"><input type="checkbox" name="emailOptionsInUse" <?php echo pdf24Plugin_isEmailOptionsInUse() ? 'checked' : ''; ?>  onclick="pdf24_showHideCheck('emailOptions', this);" /></td>
		</tr>
		</table>
		<table id="emailOptions" class="<?php echo pdf24Plugin_isEmailOptionsInUse() ? '' : 'noDis' ?>">
		<tr>
			<td class="tr1">Type:</td>
			<td class="tr2">
				<select name="emailType">
					<option value="text/plain"<?php echo (pdf24Plugin_getEmailType() == 'text/plain' ? ' selected' : ''); ?>>Text</option>
					<option value="text/html"<?php echo (pdf24Plugin_getEmailType() == 'text/html' ? ' selected' : ''); ?>>HTML</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="tr1">Subject:</td>
			<td class="tr2"><input type="text" name="emailSubject" value="<?php echo addslashes(pdf24Plugin_getEmailSubject()); ?>" style="width:400px" /></td>
		</tr>
		<tr>
			<td class="tr1">From:</td>
			<td class="tr2"><input type="text" name="emailFrom" value="<?php echo pdf24Plugin_getEmailFrom(); ?>" style="width:400px" /></td>
		</tr>
		<tr>
			<td class="tr1">Body:</td>
			<td class="tr2"><textarea name="emailText" style="width:600px; height:150px"><?php echo htmlspecialchars(pdf24Plugin_getEmailText()); ?></textarea></td>
		</tr>
		</table>
	</div>
	<div>
		<?php  $styleParms = pdf24Plugin_getStyleParams('pdf24Plugin_cpStyle', 'styles/cp'); ?>
		<script language="javascript"><?php  echo $styleParms['js']; ?></script>
		<h3>Article Plugin</h3>
		<div class="descr">This plugin displays a small box underneath or above each article to convert the article into a PDF file.</div>
		<table>
		<tr>
			<td class="tr1">Use this plugin</td>
			<td class="tr2"><input type="checkbox" name="cpInUse" <?php echo pdf24Plugin_isCpInUse() ? 'checked' : ''; ?> /></td>
		</tr>
		<tr>
			<td class="tr1">Permissions</td>
			<td class="tr2">
				<table cellspacing="0" cellpadding=0><tr>
					<td width="200">
						<input type="checkbox" name="cpDisableOnPages" <?php echo pdf24Plugin_isDisabledOn('cp','page') ? 'checked' : ''; ?> /> Disable on pages<br />
						<input type="checkbox" name="cpDisableInCategories" <?php echo pdf24Plugin_isDisabledOn('cp','category') ? 'checked' : ''; ?> /> Disable in categories<br />
						<input type="checkbox" name="cpDisableInSearches" <?php echo pdf24Plugin_isDisabledOn('cp','search') ? 'checked' : ''; ?> /> Disable in searches
					</td>
					<td valign="top">
						<input type="checkbox" name="cpDisableOnHome" <?php echo pdf24Plugin_isDisabledOn('cp','home') ? 'checked' : ''; ?> /> Disable on home page<br />
						<input type="checkbox" name="cpDisableOnSingles" <?php echo pdf24Plugin_isDisabledOn('cp','single') ? 'checked' : ''; ?> /> Disable on single post pages<br />
					</td>
				</tr></table>
			</td>
		</tr>
		<tr>
			<td class="tr1">Display mode</td>
			<td class="tr2"><select name="cpDisplayMode">
				<option value="bottom" <?php echo get_option('pdf24Plugin_cpDisplayMode') == 'bottom' ? 'selected' : ''; ?>>Below the article</option>
				<option value="top" <?php echo get_option('pdf24Plugin_cpDisplayMode') == 'top' ? 'selected' : ''; ?>>Above the article</option>
			</select></td>
		</tr>
		<tr>
			<td class="tr1">Style</td>
			<td class="tr2"><select name="cpStyle" onchange="document.forms.pdf24Form.cpCustomStyle.value = pdf24Plugin_cpStyle_custom[this.selectedIndex];">
					<?php  echo $styleParms['options'] ?>
				</select>
				&nbsp;&nbsp; <input type="checkbox" name="cpCustomize" onclick="pdf24_showHideCheck('cpCustomStyle', this);" <?php echo $styleParms['customized'] ? 'checked' : ''; ?> />
				Customize this style
			</td>
		</tr>
		<tr id="cpCustomStyle" class="<?php echo ($styleParms['customized'] ? '' : 'noDis') ?>">
			<td class="tr1">
				Custom style:<br />
				<a href="javascript:void(document.forms.pdf24Form.cpCustomStyle.value = pdf24Plugin_cpStyle_default[document.forms.pdf24Form.cpStyle.selectedIndex]);">Load styles default</a>
			</td>
			<td class="tr2"><textarea name="cpCustomStyle" class="cusSty"><?php echo $styleParms['custom']; ?></textarea></td>
		</tr>
		</table>				
	</div>
	<div>
		<?php  $styleParms = pdf24Plugin_getStyleParams('pdf24Plugin_sbpStyle', 'styles/sbp'); ?>
		<script language="javascript"><?php  echo $styleParms['js']; ?></script>
		<h3>Sidebar Plugin & Sidebar Widget Plugin</h3>
		<div class="descr">This plugin adds a widget to your Wordpress blog. Look at the widget section in your wordpress admin area to put the widget into the sidebar.<br />
		You can also add the code <b><nobr>&lt;?php pdf24Plugin_sidebar(); ?&gt;</nobr></b> into a template file where the sidebar box shall be shown.</div>			
		<table>
		<tr>
			<td class="tr1">Use this plugin</td>
			<td class="tr2"><input type="checkbox" name="sbpInUse" <?php echo pdf24Plugin_isSbpInUse() ? 'checked' : ''; ?> /></td>
		</tr>
		<tr>
			<td class="tr1">Permissions</td>
			<td class="tr2">
				<table cellspacing="0" cellpadding=0><tr>
					<td width="200">
						<input type="checkbox" name="sbpDisableOnPages" <?php echo pdf24Plugin_isDisabledOn('sbp','page') ? 'checked' : ''; ?> /> Disable on pages<br />
						<input type="checkbox" name="sbpDisableInCategories" <?php echo pdf24Plugin_isDisabledOn('sbp','category') ? 'checked' : ''; ?> /> Disable in categories<br />
						<input type="checkbox" name="sbpDisableInSearches" <?php echo pdf24Plugin_isDisabledOn('sbp','search') ? 'checked' : ''; ?> /> Disable in searches
					</td>
					<td valign="top">
						<input type="checkbox" name="sbpDisableOnHome" <?php echo pdf24Plugin_isDisabledOn('sbp','home') ? 'checked' : ''; ?> /> Disable on home page<br />
						<input type="checkbox" name="sbpDisableOnSingles" <?php echo pdf24Plugin_isDisabledOn('sbp','single') ? 'checked' : ''; ?> /> Disable on single post pages<br />
					</td>
				</tr></table>
			</td>
		</tr>
		<tr>
			<td class="tr1">Style</td>
			<td class="tr2"><select name="sbpStyle" onchange="document.forms.pdf24Form.sbpCustomStyle.value = pdf24Plugin_sbpStyle_custom[this.selectedIndex];">
					<?php  echo $styleParms['options'] ?>
				</select>
				&nbsp;&nbsp; <input type="checkbox" name="sbpCustomize" onclick="pdf24_showHideCheck('sbpCustomStyle', this);" <?php echo $styleParms['customized'] ? 'checked' : ''; ?> />
				Customize this style
			</td>
		</tr>				
		<tr id="sbpCustomStyle" class="<?php echo ($styleParms['customized'] ? '' : 'noDis') ?>">
			<td class="tr1">
				Custom style:<br />
				<a href="javascript:void(document.forms.pdf24Form.sbpCustomStyle.value = pdf24Plugin_sbpStyle_default[document.forms.pdf24Form.sbpStyle.selectedIndex]);">Load styles default</a>
			</td>
			<td class="tr2"><textarea name="sbpCustomStyle" class="cusSty"><?php echo $styleParms['custom']; ?></textarea></td>
		</tr>
		</table>
	</div>
	<div>
		<?php  $styleParms = pdf24Plugin_getStyleParams('pdf24Plugin_tbpStyle', 'styles/tbp'); ?>
		<script language="javascript"><?php  echo $styleParms['js']; ?></script>
		<h3>Top Bottom Plugin</h3>
		<div class="descr">This plugin displays a small box everywhere in your blog where you place some peace of code in a template of your theme.<br />
		Copy and paste the code <b><nobr>&lt;?php pdf24Plugin_topBottom(); ?&gt;</nobr></b> into the header or footer template (e.g. header.php, footer.php) where the box shall be shown.</div>
		<table>
		<tr>
			<td class="tr1">Use this plugin</td>
			<td class="tr2"><input type="checkbox" name="tbpInUse" <?php echo pdf24Plugin_isTbpInUse() ? 'checked' : ''; ?> /></td>
		</tr>
		<tr>
			<td class="tr1">Disable on pages</td>
			<td class="tr2">
				<table cellspacing="0" cellpadding=0><tr>
					<td width="200">
						<input type="checkbox" name="tbpDisableOnPages" <?php echo pdf24Plugin_isDisabledOn('tbp','page') ? 'checked' : ''; ?> /> Disable on pages<br />
						<input type="checkbox" name="tbpDisableInCategories" <?php echo pdf24Plugin_isDisabledOn('tbp','category') ? 'checked' : ''; ?> /> Disable in categories<br />
						<input type="checkbox" name="tbpDisableInSearches" <?php echo pdf24Plugin_isDisabledOn('tbp','search') ? 'checked' : ''; ?> /> Disable in searches
					</td>
					<td valign="top">
						<input type="checkbox" name="tbpDisableOnHome" <?php echo pdf24Plugin_isDisabledOn('tbp','home') ? 'checked' : ''; ?> /> Disable on home page<br />
						<input type="checkbox" name="tbpDisableOnSingles" <?php echo pdf24Plugin_isDisabledOn('tbp','single') ? 'checked' : ''; ?> /> Disable on single post pages<br />
					</td>
				</tr></table>
			</td>
		</tr>
		<tr>
			<td class="tr1">Style</td>
			<td class="tr2"><select name="tbpStyle" onchange="document.forms.pdf24Form.tbpCustomStyle.value = pdf24Plugin_tbpStyle_custom[this.selectedIndex];">
					<?php  echo $styleParms['options'] ?>
				</select>
				&nbsp;&nbsp; <input type="checkbox" name="tbpCustomize" onclick="pdf24_showHideCheck('tbpCustomStyle', this);" <?php echo $styleParms['customized'] ? 'checked' : ''; ?> />
				Customize this style
			</td>
		</tr>							
		<tr id="tbpCustomStyle" class="<?php echo ($styleParms['customized'] ? '' : 'noDis') ?>">
			<td class="tr1">
				Custom style:<br />
				<a href="javascript:void(document.forms.pdf24Form.tbpCustomStyle.value = pdf24Plugin_tbpStyle_default[document.forms.pdf24Form.tbpStyle.selectedIndex]);">Load styles default</a>
			</td>
			<td class="tr2"><textarea name="tbpCustomStyle" class="cusSty"><?php echo $styleParms['custom']; ?></textarea></td>
		</tr>
		</table>
	</div>
	<div>
		<?php  $styleParms = pdf24Plugin_getStyleParams('pdf24Plugin_lpStyle', 'styles/lp'); ?>
		<script language="javascript"><?php  echo $styleParms['js']; ?></script>
		<h3>Link Plugin</h3>
		<div class="descr">This plugin displays a link everywhere in your blog where you place some peace of code in a template of your theme.<br />
		Copy and paste the code <b><nobr>&lt;?php pdf24Plugin_link(); ?&gt;</nobr></b> or <b><nobr>&lt;?php pdf24Plugin_link('MY_LINK_TEXT'); ?&gt;</nobr></b>
		into a template of your theme where a Download as PDF link shall be shown.<br />If the link is placed outside the loop, the code produces a link which converts all
		articles on the current page to a PDF file. If the link is placed inside the loop, the code produces a link which converts only the current article.</div>
		<table>
		<tr>
			<td class="tr1">Use this plugin</td>
			<td class="tr2"><input type="checkbox" name="lpInUse" <?php echo pdf24Plugin_isLpInUse() ? 'checked' : ''; ?> /></td>
		</tr>
		<tr>
			<td class="tr1">Disable on pages</td>
			<td class="tr2">
				<table cellspacing="0" cellpadding=0><tr>
					<td width="200">
						<input type="checkbox" name="lpDisableOnPages" <?php echo pdf24Plugin_isDisabledOn('lp','page') ? 'checked' : ''; ?> /> Disable on pages<br />
						<input type="checkbox" name="lpDisableInCategories" <?php echo pdf24Plugin_isDisabledOn('lp','category') ? 'checked' : ''; ?> /> Disable in categories<br />
						<input type="checkbox" name="lpDisableInSearches" <?php echo pdf24Plugin_isDisabledOn('lp','search') ? 'checked' : ''; ?> /> Disable in searches
					</td>
					<td valign="top">
						<input type="checkbox" name="lpDisableOnHome" <?php echo pdf24Plugin_isDisabledOn('lp','home') ? 'checked' : ''; ?> /> Disable on home page<br />
						<input type="checkbox" name="lpDisableOnSingles" <?php echo pdf24Plugin_isDisabledOn('lp','single') ? 'checked' : ''; ?> /> Disable on single post pages<br />
					</td>
				</tr></table>
			</td>
		</tr>
		<tr>
			<td class="tr1">Style</td>
			<td class="tr2"><select name="lpStyle" onchange="document.forms.pdf24Form.lpCustomStyle.value = pdf24Plugin_lpStyle_custom[this.selectedIndex];">
					<?php  echo $styleParms['options'] ?>
				</select>
				&nbsp;&nbsp; <input type="checkbox" name="lpCustomize" onclick="pdf24_showHideCheck('lpCustomStyle', this);" <?php echo $styleParms['customized'] ? 'checked' : ''; ?> />
				Customize this style
			</td>
		</tr>
		<tr id="lpCustomStyle" class="<?php echo ($styleParms['customized'] ? '' : 'noDis') ?>">
			<td class="tr1">
				Custom style:<br />
				<a href="javascript:void(document.forms.pdf24Form.lpCustomStyle.value = pdf24Plugin_lpStyle_default[document.forms.pdf24Form.lpStyle.selectedIndex]);">Load styles default</a>
			</td>
			<td class="tr2"><textarea name="lpCustomStyle" class="cusSty"><?php echo $styleParms['custom']; ?></textarea></td>
		</tr>
		</table>
	</div>
	<div>
		<a name="customLang"></a>
		<h3>Custom language</h3>
		<div class="descr">Here you can enter your own language elements for PDF creation boxes displayed on your pages.
		Behind each box is displayed the original english text.</div>
		<table>
		<tr>
			<td class="tr1">Cutomize language</td>
			<td class="tr2"><input type="checkbox" name="customLangInUse" <?php echo pdf24Plugin_isCustomizedLang() ? 'checked' : ''; ?> onclick="pdf24_showHideCheck('langOptions', this);" /></td>
		</tr>
		</table>
		<table id="langOptions" class="<?php echo pdf24Plugin_isCustomizedLang() ? '' : 'noDis' ?>">
		<tr>
			<td class="tr1">Custom language elements:</td>
			<td class="tr2"><?php echo pdf24Plugin_createCustomizedLangInputs(); ?></td>
		</tr>
		</table>
	</div>
	<div>
		<?php  $docTpls = pdf24Plugin_getDocTpl(); ?>
		<a name="customDocTpl"></a>
		<h3>Custom document template</h3>
		<div class="descr">This options gives you the possibility to customize the document template which encloses all article entries. There are some
		placeholders which are replaced with the corresponding content.</div>
		<table>
		<tr>
			<td class="tr1">Cutomize document template</td>
			<td class="tr2"><input type="checkbox" name="docTplInUse" <?php echo pdf24Plugin_isCustomizedDocTpl() ? 'checked' : ''; ?> onclick="pdf24_showHideCheck('docTplOptions', this);" /></td>
		</tr>
		</table>
		<table id="docTplOptions" class="<?php echo pdf24Plugin_isCustomizedDocTpl() ? '' : 'noDis' ?>">
		<tr>
			<td class="tr1">Custom document template:<br />
				<a href="javascript:void(document.forms.pdf24Form.docTpl.value = '<?php echo pdf24Plugin_makeJsString($docTpls['default']); ?>');">Load default</a>
			</td>
			<td class="tr2"><textarea name="docTpl" class="cusDocTpl"><?php echo htmlspecialchars($docTpls['tpl']); ?></textarea></td>
		</tr>
		</table>
	</div>
	<div>
		<?php  $docEntryTpls = pdf24Plugin_getDocEntryTpl(); ?>
		<a name="customDocEntryTpl"></a>
		<h3>Custom document entry template</h3>
		<div class="descr">This options gives you the possibility to customize the document article entry template which is a part of the document. There are some
		placeholders which are replaced with the corresponding content.</div>
		<table>
		<tr>
			<td class="tr1">Cutomize document entry template</td>
			<td class="tr2"><input type="checkbox" name="docEntryTplInUse" <?php echo pdf24Plugin_isCustomizedDocEntryTpl() ? 'checked' : ''; ?> onclick="pdf24_showHideCheck('docEntryTplOptions', this);" /></td>
		</tr>
		</table>
		<table id="docEntryTplOptions" class="<?php echo pdf24Plugin_isCustomizedDocEntryTpl() ? '' : 'noDis' ?>">
		<tr>
			<td class="tr1">Custom document entry template:<br />
				<a href="javascript:void(document.forms.pdf24Form.docEntryTpl.value = '<?php echo pdf24Plugin_makeJsString($docEntryTpls['default']); ?>');">Load default</a>
			</td>
			<td class="tr2"><textarea name="docEntryTpl" class="cusDocEntryTpl"><?php echo htmlspecialchars($docEntryTpls['tpl']); ?></textarea></td>
		</tr>
		</table>
	</div>
	<div class="submit">
		<input type="submit" name="update" value="Update PDF24 Plugin Options" style="font-weight:bold;" />
	</div>
</form>   
</div>