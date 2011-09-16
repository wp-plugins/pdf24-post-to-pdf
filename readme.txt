=== PDF24 Article To PDF ===
Contributors: pdf24, StefanZiegler
Donate link: http://www.pdf24.org/
Tags: pdf, create pdf, convert to pdf, article to pdf, pdf plugin, pdf widget
Requires at least: 1.5.0
Tested up to: 3.2.1
Stable tag: 3.3

A plugin to convert articles to PDF. Visitors can create a PDF of articles in blog. Contents in created PDF files are linked with your blog.

== Description ==

This plugin enables your readers to convert one or more articles to PDF files. Therefore a little box is shown below every article, 
in the sidebar, on the top or bottom of each page or wherever in your wordpress blog where you place some peace of code.

The plugin provides two modes to create PDF files. The first mode is the email mode. In that mode each box has a field in which a visitor
has to enter an email address to which the created PDF will is sent. The second mode is the direct download mode. No email address is needed
in that mode. Each PDF box or each PDF link creates the PDF directly and the user has to download the created PDF.

A PDF box/link below or above each article creates a PDF only of the corresponding article. A PDF widget box in the sidebar or above or below all
articles creates a PDF from all articles on the page.

The PDF boxes, PDF links and the format of the PDF file can be customized by CSS and templates. Furthermore you can configure some other parts
of the plugin. Plugin settings are provided in Wordpress admin area in settings section.

Your server or your webspace where your blog is hosted does not need any special modules to run this plugin. Just install it and it will work.

== Installation ==

1. Unpack the plugin zip archive in your wordpress plugin folder `/wp-content/plugins/` or use your Wordpress plugin manager to download the plugin.
1. Activate the plugin through the 'Plugins' menu in WordPress admin area
1. Configure the plugin in wordpress admin area Settings->PDF24 Plugin

By default the PDF plugin is configured to display a small PDF box below each article. You can change that in settings.
The PDF plugin can display boxes above or below each article, in the sidebar, on top or bottom of each page or you can place
a PDF link everywhere in your blog. To enable or disable some of these boxes simply change the plugin settings.

If you want to use the PDF sidebar widget you have to enable the sidebar plugin. Then open the widget section and put the widget
into the sidebar.

If you want to use the PDF top/bottom box or the link plugin you have to insert some peace of code into a template file
where the box or the link shall appear.

Insert the following code into the theme file `header.php` or `footer.php`, where the top/bottom PDF bar shall be shown.
`<?php pdf24Plugin_topBottom(); ?>`

Insert the following code into any theme file, where you want to display a PDF link.
`<?php pdf24Plugin_link(); ?>`

== Screenshots ==

1. Test page with two Send as PDF boxes enabled
2. Plugin settings in Wordpress admin area
3. Test page with two Download as PDF links enabled
4. Links and boxes can be inserted in multiple places
5. You can choose between multiple styles our you can customize each style
6. You can place PDF links everywhere in your Wordpress Blog

== Changelog ==

= 3.3 =
* Added a new feature. You can disable the PDF boxes and PDF links on Wordpress pages. The options can be configured in plugin settings. You can do that individually for each of the built in sub plugins.
* Fixed a bug in link sub plugin. Styles could not be customized.

= 3.2.0 =
* Added 2 new options in plugin settings. The PDF document template and the article entry template can be customized. It's simple HTML code which is to modify. This provides you the possibility to control the design of the created PDF file. You can add headers, footers and other elements.

= 3.1.1 =
* Added dutch language file

= 3.1.0 =
* Fixed a bug which has crept in in last version. The Download as PDF link was never shown in the article if the more tag was used. Now the link is shown in the article itself but not in article overview if the is cutted because of the more tag.

= 3.0.9 =
* Fixed: Works now together with the Wordpress more tag. The content plugin will not show it's bar it the more tag is used.
* Fixed: Do not display the content bars of the content plugin if contents are build up for rss feed requests.
* Fixed: Fixed a small language selection bug. There could be one case where the language selector could not find a language which results into a script warning.

= 3.0.8 =
* Overworked a core part

= 3.0.7 =
* Added romanian language
* Approved for wordpress 3.1

= 3.0.6 =
* Corrected and changed some styles.
* Corrected some languages files.

= 3.0.5 =
* The Widget Plugin is activated by default so that you can use the widget without activating it in plugin settings.

= 3.0.4 =
* Changed the sidebar plugin to support Wordpress widgets. The sidebar plugin can now be used as a widget.

= 3.0.3 =
* Changed the function call get_the_date() to get_the_time() to work wit older Wordpress versions

= 3.0.2 =
* Fixed bug in language customization. Language can be customized again.

= 3.0.1 =
* Added a PDF link feature to display PDF links everywhere in your blog.

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
* Plugin order changed so that PDF24 plugin is called at last. This change fixes some problems working together with some other plugins.
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

= 3.0.6 =
You should update, because some style files had missing closing tags.

= 3.0.5 =
If you want to use the Article to PDF feature as a widget in Wordpress then update to the new version.

= 3.0.4 =
If you want to use the Article to PDF feature as a widget in Wordpress then update to the new version.

= 3.0.3 =
Do not update if you already use a working version of this plugin. This plugin contains only a change to work with older
Wordpress versions.

= 3.0.2 =
If you need language customization you need this update. Language customization could not be enabled in version
3.0.1 and 3.0.0. Version 3.0.2 fixes this bug.

= 3.0.1 =
You can upgrade if you need the new PDF link feature. The PDF link feature provides the ability to
display PDF links everywhere in your blog. Thats what a lot of people want.

= 3.0.0 =
We have redesigned a lot of parts of the plugin. If you update your currently installed plugin
please configure the plugin in settings of wordpress admin area.


== Frequently Asked Questions ==

= How can i remove the boxes underneath each article? =

1. Locate to the section Settings->PDF24 Plugin in wordpress admin area
1. Uncheck 'Use this plugin' in the section Article Plugin
1. Save the settings

= Where do i have to insert the code to display the bottom bar box? =

In my theme directory there is an file named index.php. A part of that file looks like this:
`<?php endwhile; ?>`

After that code insert this small peace of code:
`<?php pdf24Plugin_topBottom(); ?>`

= Where do i have to insert the code to display the top bar box =

In my theme directory there is an file named index.php. A part of that file looks like this:
`<div id="content" class="narrowcolumn">`

After that code insert this:
`<?php pdf24Plugin_topBottom(); ?>`

= Where do i have to insert the code to display a PDF link? =

You can place the PDF link code into any theme file. Just open the one where the PDF link should appear and
insert the code `<?php pdf24Plugin_link(); ?>`

= Does the plugin support the Wordpress widget system? =

Yes. Open the plugin settings and enable the Wordpress widget plugin. Then open the widget manager and you will see the PDF24 plugin.
Add the PDF24 widget to the sidebar or nay other widget places.