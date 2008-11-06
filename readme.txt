=== PDF24 Posts to PDF ===
Contributors: pdf24
Donate link: http://www.pdf24.org/
Tags: pdf, create pdf, convert to pdf, post to pdf, entry to pdf
Requires at least: 1.5.0
Tested up to: 2.5
Stable tag: 2.1

Let your visitors create a pdf from a post and send the created pdf to an email.

== Description ==

"Posts to pdf" allows you to convert posts (blog entries) into a pdf file. Therefore a little box will be shown below every post, where you can fill in your e-mail address. If you send the form, a pdf-file will be generated of the particular article and sent to the indicated e-mail address. Its very easy to use. Test it out!

== Installation ==

1. Add the plugin file `pdf24.php` to your wordpress plugin folder `/wp-content/plugins/`
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure the plugin in wordpress admin section Options->PDF24 Plugin

If the sidebar box should be displayed:
Insert the following code into the `sidebar.php`, where the pdf box should appear.

`<!-- Start pdf24.org sidebar plugin -->`
`<?php pdf24Plugin_sidebarBox(); ?>`
`<!-- End pdf24.org sidebar plugin -->`

If the top/bottom bar should be displayed:
Insert the following code into the theme file `index.php`, where the pdf bar should appear.

`<!-- Start pdf24.org top/bottom plugin -->`
`<?php pdf24Plugin_topBottomBox(); ?>`
`<!-- End pdf24.org top/bottom plugin -->`

== Screenshots ==

1. Screenshot1

== Service of pdf24 ==

This plugin was made by pdf24.org. The plugin uses the service of pdf24 to create the pdf files. You do not need any special addon for this plugin. Everything is done by pdf24. This has several advantages. You do not have extra traffic on your server. The plugin is for free.

== Frequently Asked Questions ==

= How can i remove the boxes underneath each article =

1. Locate to the section Options->PDF24 Plugin in wordpress admin area
1. Uncheck 'Use this plugin' in the section Content Plugin
1. Save the settings

= Where do i have to insert the code to display the sidebar box =

In my theme directory there is an file named sidebar.php. A part of the file with inserted code looks like this:

`...`
`<li>`
`<?php include (TEMPLATEPATH . '/searchform.php'); ?>`
`</li>`

`<!-- Start pdf24.org sidebar plugin -->`
`<?php pdf24Plugin_sidebarBox(); ?>`
`<!-- End pdf24.org sidebar plugin -->`
`...`

= Where do i have to insert the code to display the bottom bar box =

In my theme directory there is an file named index.php. A part of the file with inserted code looks like this:

`...`
`<?php endwhile; ?>`

`<!-- Start pdf24.org top bottom plugin -->`
`<?php pdf24Plugin_topBottomBox(); ?>`
`<!-- End pdf24.org top bottom plugin -->`

`<div class="navigation">`
`...`

= Where do i have to insert the code to display the top bar box =

In my theme directory there is an file named index.php. A part of the file with inserted code looks like this:

`...`
`<div id="content" class="narrowcolumn">`
	
`<!-- Start pdf24.org top bottom plugin -->`
`<?php pdf24Plugin_topBottomBox(); ?>`
`<!-- End pdf24.org top bottom plugin -->`

`<?php if (have_posts()) : ?>`
`...`
