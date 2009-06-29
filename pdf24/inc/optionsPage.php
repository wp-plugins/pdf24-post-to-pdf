<?php

function pdf24Plugin_getLangOptions()
{
	global $pdf24PluginLangCodes, $pdf24PluginDefaultLang, $pdf24PluginUseLang;
	
	$l = get_option('pdf24Plugin_language');
	if($l === false)
	{
		$l = isset($pdf24PluginUseLang) ? $pdf24PluginUseLang : $pdf24PluginDefaultLang;
	}
	$out = '';
	foreach($pdf24PluginLangCodes as $key => $val)
	{
		$out .= '<option value="'. $key .'"'. ($l == $key ? ' selected' : '') .'>'. $val .'</option>';
	}
	return $out;
}

function pdf24Plugin_createCustomizedLangInputs()
{
	global $pdf24PluginLang;

	$out = '';
	$langElements = pdf24Plugin_getLangElements();
	foreach($langElements as $key => $val)
	{
		$out .= '<input type="text" name="lang-'. $key .'" value="'. addslashes($val) .'" style="width:300px"/> ('. $pdf24PluginLang[$key] .')<br />';
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
		if($key != 'default')
		{
			$out .= '<option value="'. $key .'"'. ($key == $currentSize ? ' selected' : '') .'>'. $key .'</option>';
		}
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
		if($key != 'default')
		{
			$out .= '<option value="'. $key .'"'. ($key == $currentOrientation ? ' selected' : '') .'>'. $val .'</option>';
		}
	}
	return $out;
}

function pdf24Plugin_getCustomStyles($wpOption, $styleFolder)
{
	global $pdf24PluginDir;
	
	$styles = get_option($wpOption);
	if($styles === false || trim($styles) == '')
	{
		$styles = file_get_contents($pdf24PluginDir . '/'. $styleFolder .'/default.css');
	}
	return $styles;
}
	
function pdf24Plugin_getLangElements()
{
	global $pdf24PluginUseLang, $pdf24PluginLang, $pdf24PluginCustomLang;
	if($pdf24PluginCustomLang)
	{
		return array_merge($pdf24PluginLang, $pdf24PluginCustomLang);
	}
	return $pdf24PluginLang;
}

function pdf24Plugin_getStyleOptions($wpSetting, $folder)
{
	$style = get_option($wpSetting);
	$style = $style === false || $style == '' ? 'default' : $style;

	$out = '';
	$files = pdf24Plugin_getFiles($folder, '.css', 'ir');
	foreach($files as $f)
	{
		$out .= '<option value="'. $f .'" ' . ($f == $style ? 'selected' : '') . '/>'. $f .'</option>';
	}
	$out .= '<option value="%custom%" ' . ($style == '%custom%' ? 'selected' : '') . '>Custom Style</option>';
	return $out;
}

include_once($pdf24PluginDir . '/inc/langCodes.php');

if (isset($_POST['update'])) 
{	
	update_option('pdf24Plugin_language', $_POST['language']);
	update_option('pdf24Plugin_emailText', stripslashes($_POST['emailText']));
	update_option('pdf24Plugin_cpStyle', $_POST['cpStyle']);
	update_option('pdf24Plugin_cpStyles', $_POST['cpStyle'] == '%custom%' ? stripslashes($_POST['cpStyles']) : '');
	update_option('pdf24Plugin_useCp', isset($_POST['useCp']) ? 'true' : 'false');
	update_option('pdf24Plugin_sbpStyle', $_POST['sbpStyle']);
	update_option('pdf24Plugin_sbpStyles', $_POST['sbpStyle'] == '%custom%' ? stripslashes($_POST['sbpStyles']) : '');
	update_option('pdf24Plugin_useSbp', isset($_POST['useSbp']) ? 'true' : 'false');
	update_option('pdf24Plugin_tbpStyle', $_POST['tbpStyle']);
	update_option('pdf24Plugin_tbpStyles', $_POST['tbpStyle'] == '%custom%' ? stripslashes($_POST['tbpStyles']) : '');
	update_option('pdf24Plugin_useTbp', isset($_POST['useTbp']) ? 'true' : 'false');
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
	
	//Update global language
	pdf24Plugin_setLang();
	global $pdf24PluginLangCodes, $pdf24PluginDefaultLang;
	$warnings = array();
		
	//check language
	if(!isset($_POST['useCustomLang']))
	{
		$availableLangs = pdf24Plugin_getAvailableLang();
		if(!in_array($_POST['language'], $availableLangs)) 
		{	
			$usedLang = $pdf24PluginLangCodes[$pdf24PluginDefaultLang];
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


<div>
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
</script>
<style type="text/css">
	h3 { margin-bottom:0px; padding-bottom:0px }
	.descr  { margin-bottom:10px; font-style:italic;}
	.tr1 { vertical-align:top; width:150px}
</style>

<h2>PDF24 Plugin Options</h2>
<form method="post" onsubmit="return pdf24_checkForm(this)">
	<div>
		<h3>General</h3>
		<div class="descr">General settings of the plugin.</div>
		<table>
		<tr>
			<td class="tr1">Language:</td>
			<td><select name="language"><?php echo pdf24Plugin_getLangOptions(); ?></select></td>
		</tr>
		</table>
	</div>
	<div>
		<h3>Document Options</h3>
		<div class="descr">Options of created pdf documents.</div>
		<table>
		<tr>
			<td class="tr1"></td>
			<td><input type="checkbox" name="useDocOptions"<?php echo pdf24Plugin_isDocOptionsInUse() ? 'checked' : ''; ?>/> Override default document options</td>
		</tr>		
		<tr>
			<td class="tr1">Title Text:</td>
			<td><input name="docHeader" style="width:600px" value="<?php echo htmlspecialchars(pdf24Plugin_getDocHeader()); ?>" /></td>
		</tr>
		<tr>
			<td class="tr1">Page Size:</td>
			<td><select name="docSize"><?php echo pdf24Plugin_createDocSizeOptions(); ?></select></td>
		</tr>
		<tr>
			<td class="tr1">Orientation:</td>
			<td><select name="docOrientation"><?php echo pdf24Plugin_createDocOrientationOptions(); ?></select></td>
		</tr>
		</table>
	</div>
	<div>
		<h3>Email Options</h3>	
		<div class="descr">The created PDF is sent to the entered email address. Your you can enter your own email texts. Leave blank to use default texts.</div>
		<table>
		<tr>
			<td class="tr1"></td>
			<td><input type="checkbox" name="useEmailOptions"<?php echo pdf24Plugin_isEmailOptionsInUse() ? 'checked' : ''; ?>/> Override default email options</td>
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
			<td valign="top">Body:</td>
			<td><textarea name="emailText" style="width:600px; height:150px"><?php echo htmlspecialchars(pdf24Plugin_getEmailText()); ?></textarea></td>
		</tr>
		</table>
	</div>
	<div>
		<h3>Article Plugin</h3>		
		<div class="descr">This plugin displays a small box underneath each article to convert the above article to pdf.</div>
		<table>	
		<tr>
			<td class="tr1"></td>
			<td><input type="checkbox" name="useCp"<?php echo pdf24Plugin_isCpInUse() ? 'checked' : ''; ?>/> Use this plugin</td>
		</tr>
		<tr>
			<td class="tr1">Style</td>
			<td><select name="cpStyle">
					<?php  echo pdf24Plugin_getStyleOptions('pdf24Plugin_cpStyle', 'styles/ab'); ?>
				</select>
			</td>
		</tr>				
		<tr>
			<td class="tr1">Custom Style:</td>
			<td><textarea name="cpStyles" style="width:600px; height:150px"><?php echo htmlspecialchars(pdf24Plugin_getCustomStyles('pdf24Plugin_cpStyles','styles/ab')); ?></textarea></td>
		</tr>
		</table>				
	</div>
	<div>
		<h3>Sidebar Plugin</h3>	
		<div class="descr">This plugin displays a small box everywhere in your blog where you place some peace of code in a template of your theme.
		Place the code <nobr>&lt;?php pdf24Plugin_sidebarBox(); ?&gt;</nobr> in a template where the box should appear. E.G. in the sidebar template.</div>			
		<table>	
		<tr>
			<td class="tr1"></td>
			<td><input type="checkbox" name="useSbp"<?php echo pdf24Plugin_isSbpInUse() ? 'checked' : ''; ?>/> Use this plugin</td>
		</tr>
		<tr>
			<td class="tr1">Style</td>
			<td><select name="sbpStyle">
					<?php  echo pdf24Plugin_getStyleOptions('pdf24Plugin_sbpStyle', 'styles/sb'); ?>
				</select>
			</td>
		</tr>				
		<tr>
			<td class="tr1">Custom Style:</td>
			<td><textarea name="sbpStyles" style="width:600px; height:150px;"><?php echo htmlspecialchars(pdf24Plugin_getCustomStyles('pdf24Plugin_sbpStyles','styles/sb')); ?></textarea></td>
		</tr>
		</table>
	</div>
	<div>
		<h3>Top Bottom Plugin</h3>	
		<div class="descr">This plugin displays a small box everywhere in your blog where you place some peace of code in a template of your theme.
		Place the code <nobr>&lt;?php pdf24Plugin_topBottomBox(); ?&gt;</nobr> in a template where the box should appear.</div>
		<table>	
		<tr>
			<td class="tr1"></td>
			<td><input type="checkbox" name="useTbp"<?php echo pdf24Plugin_isTbpInUse() ? 'checked' : ''; ?>/> Use this Plugin</td>
		</tr>	
		<tr>
			<td class="tr1">Style</td>
			<td><select name="tbpStyle">
					<?php  echo pdf24Plugin_getStyleOptions('pdf24Plugin_tbpStyle', 'styles/tbb'); ?>
				</select>
			</td>
		</tr>							
		<tr>
			<td class="tr1">Custom Style:</td>
			<td><textarea name="tbpStyles" style="width:600px; height:150px;"><?php echo htmlspecialchars(pdf24Plugin_getCustomStyles('pdf24Plugin_tbpStyles','styles/tbb')); ?></textarea></td>
		</tr>
		</table>
	</div>
	<div>
		<a name="customLang"></a>
		<h3>Custom Language</h3>
		<div class="descr">Here you can enter your own language elements for pdf creation boxes displayed on your pages.
		Behind each box is displayed the original english text.</div>
		<table>	
		<tr>
			<td class="tr1"></td>
			<td><input type="checkbox" name="useCustomLang"<?php echo pdf24Plugin_isCustomizedLang() ? 'checked' : ''; ?>/> Use cutomized language</td>
		</tr>				
		<tr>
			<td class="tr1">Custom Language Elements:</td>
			<td><?php echo pdf24Plugin_createCustomizedLangInputs(); ?></td>
		</tr>
		</table>
	</div>
	<div class="submit">
		<input type="submit" name="update" value="Update PDF24 Plugin Options" style="font-weight:bold;" />
	</div>
</form>   
</div>