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
		$parms['options'] .= '<option value="'. $f .'" ' . ($f == $style || $p[0] == $style ? 'selected' : '') . '/>'. $name .'</option>';
		
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

include_once($pdf24Plugin['dir'] . '/inc/langCodes.php');

if (isset($_POST['update'])) {
	update_option('pdf24Plugin_debug', isset($_POST['debug']) ? 'true' : 'false');
	update_option('pdf24Plugin_language', $_POST['language']);
	update_option('pdf24Plugin_availability', $_POST['availability']);
	
	update_option('pdf24Plugin_cpInUse', isset($_POST['cpInUse']) ? 'true' : 'false');
	update_option('pdf24Plugin_cpDisplayMode', $_POST['cpDisplayMode']);
	update_option('pdf24Plugin_cpStyle', $_POST['cpStyle']);
	update_option('pdf24Plugin_cpStyle_customize', isset($_POST['cpCustomize']) ? 'true' : 'false');
	update_option('pdf24Plugin_cpStyle_' . $_POST['cpStyle'], stripslashes($_POST['cpCustomStyle']));
	
	update_option('pdf24Plugin_sbpInUse', isset($_POST['sbpInUse']) ? 'true' : 'false');
	update_option('pdf24Plugin_sbpStyle', $_POST['sbpStyle']);
	update_option('pdf24Plugin_sbpStyle_customize', isset($_POST['sbpCustomize']) ? 'true' : 'false');
	update_option('pdf24Plugin_sbpStyle_' . $_POST['sbpStyle'], stripslashes($_POST['sbpCustomStyle']));
	
	update_option('pdf24Plugin_tbpInUse', isset($_POST['tbpInUse']) ? 'true' : 'false');
	update_option('pdf24Plugin_tbpStyle', $_POST['tbpStyle']);
	update_option('pdf24Plugin_tbpStyle_customize', isset($_POST['tbpCustomize']) ? 'true' : 'false');
	update_option('pdf24Plugin_tbpStyle_' . $_POST['tbpStyle'], stripslashes($_POST['tbpCustomStyle']));
	
	update_option('pdf24Plugin_lpInUse', isset($_POST['lpInUse']) ? 'true' : 'false');
	update_option('pdf24Plugin_lpStyle', $_POST['lpStyle']);
	update_option('pdf24Plugin_lpStyle_customize', isset($_POST['lpCustomize']) ? 'true' : 'false');
	update_option('pdf24Plugin_lpStyle_' . $_POST['tbpStyle'], stripslashes($_POST['tbpCustomStyle']));
	
	update_option('pdf24Plugin_emailOptionsInUse', isset($_POST['emailOptionsInUse']) ? 'true' : 'false');
	update_option('pdf24Plugin_emailType', $_POST['emailType']);
	update_option('pdf24Plugin_emailSubject', stripslashes($_POST['emailSubject']));
	update_option('pdf24Plugin_emailFrom', $_POST['emailFrom']);
	update_option('pdf24Plugin_emailText', stripslashes($_POST['emailText']));
	
	update_option('pdf24Plugin_docOptionsInUse', isset($_POST['docOptionsInUse']) ? 'true' : 'false');
	update_option('pdf24Plugin_docHeader', stripslashes($_POST['docHeader']));
	update_option('pdf24Plugin_docSize', $_POST['docSize']);
	update_option('pdf24Plugin_docOrientation', $_POST['docOrientation']);
	update_option('pdf24Plugin_docStyle', $_POST['docStyle']);	
	
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
			$warnings[] = 'There is no language file installed for this defined language. Currently the default language <b>'. $usedLang .'</b> is used. Please use the <a href="#customLang">Customize Language</a> settings to customize the language.';
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
		if(form.useCustomLang.checked) {
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
</script>
<style type="text/css">
	h3 { margin-bottom:0px; padding-bottom:0px }
	.descr  { margin-bottom:10px; font-style:italic;}
	.tr1 { vertical-align:top; width:180px}
	.noDis {display:none;}
	.cusSty {width:700px; height:150px;}
</style>

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
			<td><select name="language"><?php echo pdf24Plugin_getLangOptions(); ?></select></td>
		</tr>
		<tr>
			<td class="tr1">Availability:</td>
			<td><select name="availability">
				<option value="public">For all visitors</option>
				<option value="private">Only for logged in users</option>
			</select></td>
		</tr>
		<tr>
			<td class="tr1">Debug outputs:</td>
			<td><input type="checkbox" name="debug" <?php echo pdf24Plugin_isDebug() ? 'checked' : ''; ?></td>
		</tr>
		</table>
	</div>
	<div>
		<h3>Document Options</h3>
		<div class="descr">Options of created pdf documents.</div>
		<table>
		<tr>
			<td class="tr1">Customize options</td>
			<td><input type="checkbox" name="docOptionsInUse" <?php echo pdf24Plugin_isDocOptionsInUse() ? 'checked' : ''; ?> onclick="pdf24_showHideCheck('docOptions', this);" /></td>
		</tr>
		</table>
		<table id="docOptions" class="<?php echo pdf24Plugin_isDocOptionsInUse() ? '' : 'noDis' ?>">
		<tr>
			<td class="tr1">Title Text:</td>
			<td><input name="docHeader" style="width:600px" value="<?php echo htmlspecialchars(pdf24Plugin_getDocHeader()); ?>" /></td>
		</tr>
		<tr>
			<td class="tr1">Page format:</td>
			<td><select name="docSize"><?php echo pdf24Plugin_createDocSizeOptions(); ?></select> <select name="docOrientation"><?php echo pdf24Plugin_createDocOrientationOptions(); ?></select></td>
		</tr>
		<tr>
			<td valign="top">CSS Style:<br />(<small>Use CSS to format the tags and classes body, h1, h2, p, div, a, .bodyPart, .meta, .text</small>)</td>
			<td><textarea name="docStyle" style="width:600px; height:150px"><?php echo htmlspecialchars(pdf24Plugin_getDocStyle()); ?></textarea></td>
		</tr>
		</table>
	</div>
	<div>
		<h3>Email Options</h3>	
		<div class="descr">The created PDF is sent to the entered email address. You can enter your own email texts.</div>
		<table>
		<tr>
			<td class="tr1">Customize options</td>
			<td><input type="checkbox" name="emailOptionsInUse" <?php echo pdf24Plugin_isEmailOptionsInUse() ? 'checked' : ''; ?>  onclick="pdf24_showHideCheck('emailOptions', this);" /></td>
		</tr>
		</table>
		<table id="emailOptions" class="<?php echo pdf24Plugin_isEmailOptionsInUse() ? '' : 'noDis' ?>">
		<tr>
			<td class="tr1">Type:</td>
			<td>
				<select name="emailType">
					<option value="text/plain"<?php echo (pdf24Plugin_getEmailType() == 'text/plain' ? ' selected' : ''); ?>>Text</option>
					<option value="text/html"<?php echo (pdf24Plugin_getEmailType() == 'text/html' ? ' selected' : ''); ?>>HTML</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="tr1">Subject:</td>
			<td><input type="text" name="emailSubject" value="<?php echo addslashes(pdf24Plugin_getEmailSubject()); ?>" style="width:400px" /></td>
		</tr>
		<tr>
			<td class="tr1">From:</td>
			<td><input type="text" name="emailFrom" value="<?php echo pdf24Plugin_getEmailFrom(); ?>" style="width:400px" /></td>
		</tr>
		<tr>
			<td class="tr1" valign="top">Body:</td>
			<td><textarea name="emailText" style="width:600px; height:150px"><?php echo htmlspecialchars(pdf24Plugin_getEmailText()); ?></textarea></td>
		</tr>
		</table>
	</div>
	<div>
		<?php  $styleParms = pdf24Plugin_getStyleParams('pdf24Plugin_cpStyle', 'styles/cp'); ?>
		<script language="javascript"><?php  echo $styleParms['js']; ?></script>
		<h3>Article Plugin</h3>
		<div class="descr">This plugin displays a small box underneath each article to convert the above article to pdf.</div>
		<table>
		<tr>
			<td class="tr1">Use this plugin</td>
			<td><input type="checkbox" name="cpInUse" <?php echo pdf24Plugin_isCpInUse() ? 'checked' : ''; ?> /></td>
		</tr>
		<tr>
			<td class="tr1">Display mode</td>
			<td><select name="cpDisplayMode">
				<option value="bottom" <?php echo get_option('pdf24Plugin_cpDisplayMode') == 'bottom' ? 'selected' : ''; ?>>Below the article</option>
				<option value="top" <?php echo get_option('pdf24Plugin_cpDisplayMode') == 'top' ? 'selected' : ''; ?>>Above the article</option>
			</select></td>
		</tr>
		<tr>
			<td class="tr1">Style</td>
			<td><select name="cpStyle" onchange="document.forms.pdf24Form.cpCustomStyle.value = pdf24Plugin_cpStyle_custom[this.selectedIndex];">
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
			<td><textarea name="cpCustomStyle" class="cusSty"><?php echo $styleParms['custom']; ?></textarea></td>
		</tr>
		</table>				
	</div>
	<div>
		<?php  $styleParms = pdf24Plugin_getStyleParams('pdf24Plugin_sbpStyle', 'styles/sbp'); ?>
		<script language="javascript"><?php  echo $styleParms['js']; ?></script>
		<h3>Sidebar Plugin</h3>	
		<div class="descr">This plugin displays a small box everywhere in your blog where you place some peace of code in a template of your theme.<br />
		Copy and paste the code <b><nobr>&lt;?php pdf24Plugin_sidebar(); ?&gt;</nobr></b> into the sidebar template file (e.g. sidebar.php) where the box shall be shown.</div>			
		<table>
		<tr>
			<td class="tr1">Use this plugin</td>
			<td><input type="checkbox" name="sbpInUse" <?php echo pdf24Plugin_isSbpInUse() ? 'checked' : ''; ?> /></td>
		</tr>
		<tr>
			<td class="tr1">Style</td>
			<td><select name="sbpStyle" onchange="document.forms.pdf24Form.sbpCustomStyle.value = pdf24Plugin_sbpStyle_custom[this.selectedIndex];">
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
			<td><textarea name="sbpCustomStyle" class="cusSty"><?php echo $styleParms['custom']; ?></textarea></td>
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
			<td><input type="checkbox" name="tbpInUse" <?php echo pdf24Plugin_isTbpInUse() ? 'checked' : ''; ?> /></td>
		</tr>
		<tr>
			<td class="tr1">Style</td>
			<td><select name="tbpStyle" onchange="document.forms.pdf24Form.tbpCustomStyle.value = pdf24Plugin_tbpStyle_custom[this.selectedIndex];">
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
			<td><textarea name="tbpCustomStyle" class="cusSty"><?php echo $styleParms['custom']; ?></textarea></td>
		</tr>
		</table>
	</div>
	<div>
		<?php  $styleParms = pdf24Plugin_getStyleParams('pdf24Plugin_lpStyle', 'styles/lp'); ?>
		<script language="javascript"><?php  echo $styleParms['js']; ?></script>
		<h3>Link Plugin</h3>
		<div class="descr">This plugin displays a link everywhere in your blog where you place some peace of code in a template of your theme.<br />
		Copy and paste the code <b><nobr>&lt;?php pdf24Plugin_link(); ?&gt;</nobr></b> or <b><nobr>&lt;?php pdf24Plugin_link('MY_LINK_TEXT'); ?&gt;</nobr></b>
		into a template of your theme where a download as PDF link shall be shown. If the link is places outside the loop, the code produce a link which converts all
		articles on the current page to PDF. If the link is places inside the loop, the code produce a link which converts only the current article to PDF.</div>
		<table>
		<tr>
			<td class="tr1">Use this plugin</td>
			<td><input type="checkbox" name="lpInUse" <?php echo pdf24Plugin_isLpInUse() ? 'checked' : ''; ?> /></td>
		</tr>
		<tr>
			<td class="tr1">Style</td>
			<td><select name="lpStyle" onchange="document.forms.pdf24Form.lpCustomStyle.value = pdf24Plugin_lpStyle_custom[this.selectedIndex];">
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
			<td><textarea name="lpCustomStyle" class="cusSty"><?php echo $styleParms['custom']; ?></textarea></td>
		</tr>
		</table>
	</div>
	<div>
		<a name="customLang"></a>
		<h3>Custom language</h3>
		<div class="descr">Here you can enter your own language elements for pdf creation boxes displayed on your pages.
		Behind each box is displayed the original english text.</div>
		<table>
		<tr>
			<td class="tr1">Cutomize language</td>
			<td><input type="checkbox" name="customLangInUse" <?php echo pdf24Plugin_isCustomizedLang() ? 'checked' : ''; ?> onclick="pdf24_showHideCheck('langOptions', this);" /></td>
		</tr>
		</table>
		<table id="langOptions" class="<?php echo pdf24Plugin_isCustomizedLang() ? '' : 'noDis' ?>">
		<tr>
			<td class="tr1">Custom language elements:</td>
			<td><?php echo pdf24Plugin_createCustomizedLangInputs(); ?></td>
		</tr>
		</table>
	</div>
	<div class="submit">
		<input type="submit" name="update" value="Update PDF24 Plugin Options" style="font-weight:bold;" />
	</div>
</form>   
</div>