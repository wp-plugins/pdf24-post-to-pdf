<?php

function pdf24Plugin_widgetControl() {
	if (isset($_POST['pdf24PluginSubmit'])) {
		update_option('pdf24Plugin_sbpStyle', $_POST['pdf24Plugin-sbpStyle']);
		update_option('pdf24Plugin_widgetTitle', $_POST['pdf24Plugin-widget-title']);
	}
	$styleParms = pdf24Plugin_getStyleParams('pdf24Plugin_sbpStyle', 'styles/sbp');
?>
	<p style="text-align:left; line-height: 100%;">
	<label for="pdf24Plugin-widget-title" style="line-height:25px;display:block;">
		<?php _e('Title:'); ?><br />
		<input style="width:100%" type="text" id="pdf24Plugin-widget-title" name="pdf24Plugin-widget-title" value="<?php echo pdf24Plugin_wpEscape(pdf24Plugin_getWidgetTitle(), true); ?>" />
	</label>
	<label for="pdf24Plugin-sbpStyle" style="line-height:25px;display:block;">
		<?php _e('Style:'); ?><br />
		<select id="pdf24Plugin-sbpStyle" name="pdf24Plugin-sbpStyle">
		<?php  echo $styleParms['options'] ?>
		</select>
	</label>
	<input type="hidden" name="pdf24PluginSubmit" id="pdf24PluginSubmit" value="1" />
	</p>
	
<?php
}

?>
