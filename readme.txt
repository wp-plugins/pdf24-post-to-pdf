=== PDF24 Articles To PDF ===
Contributors: pdf24
Donate link: http://www.pdf24.org/
Tags: pdf, create pdf, convert to pdf, article to pdf
Requires at least: 1.5.0
Tested up to: 3.0.1
Stable tag: 3.0.0

A plugin that converts articles to PDF. Visitors of your blog can make copies of articles in form of a PDF. Contents in PDF are linked back to your blog.

== Description ==

This plugin enables your readers to convert one or more articles into pdf files. Therefore a little box can be shown below every article, in the sidebar or on the top or bottom af a page. In that boxes you can fill in your e-mail address. After sending the form a pdf file will be created and sent to the entered email address.

If one of the boxes below each article was used to create the pdf, the pdf will only contain that article. If the sidebar box or the top/bottom box was used to create the pdf, the pdf will contain all articles on the page.

The plugin can be highly configured in your blog admin area. There you can configure email properties, page format and layout, enable or disable some plugin boxes, changing styles and so on. Its very easy to use. Check it out!

== Installation ==

1. Unpack the plugin zip archive in your wordpress plugin folder `/wp-content/plugins/`
1. Activate the plugin through the 'Plugins' menu in WordPress admin area
1. Configure the plugin in wordpress admin area Settings->PDF24 Plugin

The plugin is configured to display a small pdf box below each article by default. The plugin can display boxes below each article, in the sidebar or on the top or bottom of each page. To enable or disable some of these boxes please change the plugin settings.

You have to insert some peace of code into a template file if the sidebar box or the top/bottom box should be displayed:


Insert the following code into the `sidebar.php`, where the sidebar pdf box should appear.

`<!-- Start pdf24.org sidebar plugin -->`
`<?php pdf24Plugin_sidebarBox(); ?>`
`<!-- End pdf24.org sidebar plugin -->`


Insert the following code into the theme file `index.php`, where the top/bottom pdf bar should appear.

`<!-- Start pdf24.org top/bottom plugin -->`
`<?php pdf24Plugin_topBottomBox(); ?>`
`<!-- End pdf24.org top/bottom plugin -->`

== Screenshots ==

1. Test page with two Send as PDF boxes enabled
2. Plugin settings in Wordpress admin area
3. Test page with two Download as PDF links enabled
4. Links and boxes can be inserted in multiple places
5. You can choose between multiple styles our you can customize each style

== Changelog ==

= 3.0.0 =
* Added the wanted ability to download created PDF directly without sending the PDF via email
* Added CSS document section in settings to format a created PDF file
* Added a lot of new predefined styles which can be customized
* There are styles for direct PDF download and for email PDF mode
* PDF buttons can be displayed for every visitor or only for logged in user
* Produces better formatted PDF files
* Added outlines to a created PDF document based on articles title
* Visualizes the PDF creation process until the PDF is created.

= 2.3.7 =
* Some changes to work better with new wordpress 3.0 version

= 2.3.5 =
* Plugin order changed so that pdf24 plugin is called at last. This change fixes some problems working together with some other plugins.
* New value encoding added in form building process to better format the output
* Style class in admin option page added to better format this page

= 2.3.4 =
* Some language files added

= 2.3.3 =
* Plugins works now with php4 and php5

= 2.3.2 =
* Outputs are now valid xhtml code
* Fixed style problem when using only the top/bottom plugin

= 2.3.1 =
* Restructured the plugin to work with automated installation in Wordpress

= 2.3 =
* Added language files for Swedish and Greek

= 2.2 =
* Redesigned plugin options page to configure the plugin.
* Added further options to configure the plugin
* Restructured the plugin to improve performance
* Tested with Wordpress 2.8
* Predefined languages: German, English, Portuguese, Italian, Spanish, French, Japanese, Russian, Chinese

= 2.1 =
* Introduced plugin options page to configure the plugin.

== Upgrade Notice ==

= 3.0.0 =
We have redesigned a lot of parts of the plugin. If you update your currently installed plugin
please configure the plugin in settings of wordpress admin area.


== Frequently Asked Questions ==

= How can i remove the boxes underneath each article =

1. Locate to the section Settings->PDF24 Plugin in wordpress admin area
1. Uncheck 'Use this plugin' in the section Article Plugin
1. Save the settings

= Where do i have to insert the code to display the sidebar box =

In my theme directory there is an file named sidebar.php. A part of that file looks like this:

`<li>`
`<?php include (TEMPLATEPATH . '/searchform.php'); ?>`
`</li>`

After that peace of code insert this:

`<li>`
`<!-- Start pdf24.org sidebar plugin -->`
`<?php pdf24Plugin_sidebarBox(); ?>`
`<!-- End pdf24.org sidebar plugin -->`
`</li>`

= Where do i have to insert the code to display the bottom bar box =

In my theme directory there is an file named index.php. A part of that file looks like this:

`<?php endwhile; ?>`

After that code insert this:

`<!-- Start pdf24.org top bottom plugin -->`
`<?php pdf24Plugin_topBottomBox(); ?>`
`<!-- End pdf24.org top bottom plugin -->`

= Where do i have to insert the code to display the top bar box =

In my theme directory there is an file named index.php. A part of that file looks like this:

`<div id="content" class="narrowcolumn">`

After that code insert this:

`<!-- Start pdf24.org top bottom plugin -->`
`<?php pdf24Plugin_topBottomBox(); ?>`
`<!-- End pdf24.org top bottom plugin -->`
