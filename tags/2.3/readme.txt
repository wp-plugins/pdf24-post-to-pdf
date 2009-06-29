=== PDF24 Article To PDF ===
Contributors: pdf24
Donate link: http://www.pdf24.org/
Tags: pdf, create pdf, convert to pdf, article to pdf
Requires at least: 1.5.0
Tested up to: 2.8
Stable tag: 2.3

A plugin to convert articles to PDF. Visitors can make a copy of articles in form of a PDF. Contents in created PDF files are linked with your blog.

== Description ==

This plugin enables your readers to convert one or more articles into a pdf file. Therefore a little box can be shown below every post, in the sidebar or at top or on bottom af a page. In that boxes you can fill in your e-mail address. After sending the form a pdf file will be created and sent to the entered email address.

If one box below each article were used to create the pdf the pdf will only contain that article. If the sidebar box or the top/bottom box were used to create the pdf the pdf will contain all article on the page.

The plugin can be highly configured in your blog admin area. There you can configure email properties, page format and layout, enable or disable some plugin boxes, changing styles and so on. Its very easy to use. Check it out!

== Installation ==

1. Copy the plugin folder `pdf24` to your wordpress plugin folder `/wp-content/plugins/`
1. Activate the plugin through the 'Plugins' menu in WordPress admin area
1. Configure the plugin in wordpress admin area Settings->PDF24 Plugin

The plugin is configured to display a small pdf box below each article by default. The plugin can display a PDF box below each article, in the sidebar or on top or bottom of each page. To enable or disable some of these boxes please change the plugin settings.

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

1. Screenshot1

== Changelog ==

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

== Service of pdf24 ==

This plugin was made by pdf24.org. The plugin uses the service of pdf24.org to create the pdf files. You do not need any special addon for this plugin. Everything is done by pdf24.org. This has several advantages. You do not have extra traffic on your server and you do not need a large computer system to create the pdf files. The plugin is free.

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
