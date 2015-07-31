=== PDF24 Article To PDF ===
Contributors: pdf24, StefanZiegler
Donate link: http://www.pdf24.org/
Tags: pdf, create pdf, convert to pdf, article to pdf, pdf plugin, pdf widget
Requires at least: 1.5.0
Tested up to: 4.2.3
Stable tag: 3.9.1

A plugin to create PDF files of articles in your blog.


== Description ==

This plugin enables your blog readers to create PDF files of one or more articles in your blog. To realize that a little box is shown below or above
every article, in a sidebar, on the top or bottom of a page or wherever you want in your wordpress blog by inserting a peace of code in a template.

The plugin provides two modes to create PDF files. The first mode is the email mode. In that mode each box has a field in which a visitor
can enter an email address to which the created PDF will be sent. The second mode is the direct download mode. No email address is needed
in that mode. Each PDF box or each PDF link creates the PDF directly and the user can download the created PDF file.

A PDF box or a PDF link below or above each article creates a PDF file with the corresponding article only. A PDF widget box in the sidebar
or above or below all articles creates a PDF file with all articles on the current page.

The PDF boxes, PDF links and the format of the PDF file can be highly customized by CSS and templates. Furthermore you can configure a lot of
other parts of the plugin by editing the plugin settings which are provided in your Wordpress admin area.

Your server or your webspace, where your blog is hosted, does not need any special modules to run this plugin. Just install it and it will work.

Custom fields are supported. Look at the installation and FAQ section to get more information about that.

* Create PDF files of one or more articles
* Download the PDF file or send it to an email address
* Supports PDF articles bars, sidebars, top/bottom bars and PDF links
* The plugin and the PDF file is highly customizable
* No special server requirements needed
* Supports custom fields
* Easy installation without or with minimal template changes



== Installation ==

= Main Installation =
1. Unpack the plugin zip archive in your wordpress plugin folder `/wp-content/plugins/` or use your Wordpress plugin manager to download the plugin.
1. Activate the plugin through the 'Plugins' menu in WordPress admin area
1. Configure the plugin in wordpress admin area Settings->PDF24 Plugin

By default the PDF plugin is configured to display a small PDF box below each article. You can change that in the plugin settings in your Wordpress admin area.
The PDF plugin can display a box above or below each article, in the sidebar, on top or bottom of each page or you can place
a PDF link everywhere in your blog. To enable or disable some of these boxes simply change the plugin settings.


= PDF Sidebar Widget =
If you want to use the PDF sidebar widget you have to enable the sidebar plugin. Then open the widget section in Wordpress admin area and put the widget
into the sidebar.


= PDF Top/Bottom Bar =
If you want to use the PDF top/bottom box, the link plugin or if you want to show a sidebar box without using the widget then you have to insert
some peace of code into a template file where the box or the link shall appear.

Insert the following code into the theme file `header.php`, `footer.php` or an other one, where the top/bottom PDF bar shall appear.
`<?php pdf24Plugin_topBottom(); ?>`


= PDF Link =
Insert the following code into any theme file, where you want to display a PDF link which creates a PDF file with one or more articles in you blog.
`<?php pdf24Plugin_link(); ?>`


= PDF Sidebar Box =
Insert the following code into any theme file, where you want to display a PDF sidebar box by which users can create a PDF file with the articles
on the currently displayed page.
`<?php pdf24Plugin_sidebar(); ?>`


= Custom Fields Support =
If you use custom fields and if you want it to be part of the PDF file then you have to add some codes to your template files to mark the content
so that the PDF24 plugin knows what the content is. This can be done like the following:

	<?php pdf24Plugin_begin(); ?>
	<?php the_content(); ?>
	... Your custom fields code ...
	<?php pdf24Plugin_end(); ?>
	<?php pdf24Plugin_post(); ?>  OR  <?php pdf24Plugin_form(ID); ?>
	
The above call to *pdf24Plugin_post()* can be replaced with *pdf24Plugin_link()* or *pdf24Plugin_topBottom()* or *pdf24Plugin_sidebar()*. Each of these
methods shows a different box depending on what you want.

You can also replace the call to *pdf24Plugin_post()* with a call to *pdf24Plugin_form(ID)*, which then outputs nothing visible to the user. The call to
*pdf24Plugin_form()* just creates a hidden form which can than be sent by an other peace of code. The create form is hidden and you need a link or a button
to submit the form and that functionality is provided with the *pdf24Plugin_formSubmit(ID,TPL)* method. The call to *pdf24Plugin_formSubmit(ID,TPL)* can be
placed elsewhere in your template and prints a link which submits the form create by a call to *pdf24Plugin_form(ID)*. This is a very flexible feature. The
first argument, the ID, is a needed argument. If you create a hidden form by a call to *pdf24Plugin_form(ID)* then the form is identified by the ID. A call
to *pdf24Plugin_formSubmit(ID,TPL)* with the same ID will submit the form identified by that ID. The ID argument can simply be the wordpress post identifier
which you get by a call to get_the_ID(). The second parameter, the TPL, is a optinal argument. This
argument controls the output (normal link, image link or whatever). The TPL argument is the name of a template of the plugin which is loaded and printed.

Don't forget to look at the FAQ section to get more information about custom fields support.

If you have any problems with the installation or the custom fields support feel free to contact us.



== Screenshots ==

1. Test page with two Send as PDF boxes enabled
2. Plugin settings in Wordpress admin area
3. Test page with two Download as PDF links enabled
4. Links and boxes can be inserted in multiple places
5. You can choose between multiple styles our you can customize each style
6. You can place PDF links everywhere in your Wordpress Blog
7. The Plugin supports Wordpress Widgets



== Changelog ==

= 3.8.0 =
* Added support for SSL pages

= 3.7.0 =
* Checked against the newest Wordpress version
* Improved styles and templates to improve the look and feel in new wordpress blogs
* Improved some images to look better on different backgrounds
* Smaller status window which shows the conversion process

= 3.6.1 =
* Fixed a template bug

= 3.6.0 =
* Image link for link plugin supported
* Option to call user event function when creating a PDF file
* Is't now possible to place a link everywhere inside a template and not only only after the call to pdf24Plugin_end()
* WPML support added. The plugin now uses the Wordpress Localization Technology for text of the plugin
* Fixed a tpl tag issue
* Fixed a script bug in plugin setting page

= 3.5.2 =
* Minor fix

= 3.5.1 =
* Fixed a deprecated issue regarding the PDF widget
* Fixed the $after_widget issue

= 3.5.0 =
* Added support for custom fields by surrounding the content with special plugin methods so that the PDF24 plugin knows what the content of an article is.
* Fixed some deprecated issues
* Added more information to the plugin options page
* Added more information to this readme file

= 3.4.3 =
* Fixed another minor bug when displaying Wordpress pages

= 3.4.2 =
* Fixed a minor bug regarding the determination of the blog url

= 3.4.1 =
* Fixed a minor bug which was introduced in the prior version

= 3.4.0 =
* Overworked and improved some core parts of the plugin to realize the new features which follows below.
* Added the feature that you can specify the filename of the resulting PDF file. Names can be added for several blog areas. These are the home page, category pages, blog pages, single post pages and search pages. Placeholders are available for each of the different names. The new settings are available in plugin settings in admin area.
* Added display permission for each of the plugin types (content bar, top bottom bar, sidebar, links). You can specify where the plugin can display a PDF bar or link. You can disable the bars, boxes or links on home page, category pages, blog pages, single post pages and search pages. The new settings are available in plugin settings in admin area.

= 3.3.3 =
* minor fix

= 3.3.2 =
* Added compression feature
* Added compression option to settings of the plugin
* Fixed some minor bugs

= 3.3.1 =
* Added a fix to work together with the plugin wp-Typography

= 3.3.0 =
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

= 2.1.0 =
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

In my theme directory there is a file named index.php. A part of that file looks like this:
`<div id="content" class="narrowcolumn">`

After that code insert this:
`<?php pdf24Plugin_topBottom(); ?>`

= Where do i have to insert the code to display a PDF link? =

You can place the PDF link code into any theme file. Just open that file where the PDF link should appear and
insert the code `<?php pdf24Plugin_link(); ?>`

= Does the plugin support the Wordpress widget system? =

Yes. Open the plugin settings and enable the Wordpress widget plugin. Then open the widget manager and you will see the PDF24 plugin.
Add the PDF24 widget to the sidebar or any other widget place.

= How to add Wordpress Custom Fields to the PDF file =

Normally, the PDF24 plugin only uses the contents of the articles as content for the PDF file. Custom fields are extra information, that is
normally not part of the content itself but can be used in templates to present extra information. To tell the PDF24 plugin what content shall be part
of the PDF file, you have mark up the content area within Wordpress theme files with special PDF24 plugin methods.

To mark up the content, do this:

	<?php pdf24Plugin_begin(); ?>
	<?php the_content(); ?>
	... Your custom fields code here...
	<?php pdf24Plugin_end(); ?>
	
The above code tells PDF24 that the content is the articles content itself and the rest of the content between the two methods. You can place or
output any other custom fields or information between the *pdf24Plugin_begin* and *pdf24Plugin_end* method. All that content between the two method
is added to the PDF file.

All PDF24 plugin PDF bars, links and boxes automatically uses this information to create the PDF file. Special care must be taken when you use the
article bars which are automatically added to the content of each article. If you use the above markup code then the article bars will be removed
from the content and you have to insert some extra code where the article bar shall appear.

This extra code is:

	<?php pdf24Plugin_post(); ?>
	
A sample code section of my test *loop.php* template file looks like this:

	<?php pdf24Plugin_begin(); ?>
	<?php the_content(); ?>
	<?php the_meta(); ?>
	<?php pdf24Plugin_end(); ?>
	<?php pdf24Plugin_post(); ?>

This new code tells the PDF24 plugin what content is to add to the PDF file. This is the outputs of the codes which are placed inside
the *pdf24Plugin_begin* and *pdf24Plugin_end* methods. The next line, the call to the method *pdf24Plugin_post*, outputs the article PDF bar which
the user have to use to generate the PDF file. If you don't call the *pdf24Plugin_post* method then the article bar will not be shown. You can omit this
call when you use the PDF top/bottom bar, the PDF sidebar, the PDF widget or a PDF link.

The above call to *pdf24Plugin_post()* can be replaced with *pdf24Plugin_link()* or *pdf24Plugin_topBottom()* or *pdf24Plugin_sidebar()*. Each of these
methods shows a different box depending on what you want. Replacing *pdf24Plugin_post()* with *pdf24Plugin_link()* will output a *Download as PDF* link
instead of the bigger article bar. The above code could also look like the following one:

	<?php pdf24Plugin_begin(); ?>
	<?php the_content(); ?>
	... Your custom fields code here...
	<?php pdf24Plugin_end(); ?>
	<?php pdf24Plugin_link(); ?> OR <?php pdf24Plugin_form(get_the_ID()); ?>
	
You can place the *pdf24Plugin_link()* call wherever you want, except before the *pdf24Plugin_end()* call.
The call to pdf24Plugin_form(get_the_ID()) must also be placed after the *pdf24Plugin_end()* call. This call only generates a hidden form which
needs a link to be sent. This link can be created with the function *pdf24Plugin_formSubmit(get_the_ID())*. The call to this function can be placed
everywhere in a template (after a call to pdf24Plugin_end() or before the call or in an other template) because the created link references the created
hidden form created by *pdf24Plugin_form(get_the_ID());*.

This feature makes PDF24 a very powerful PDF generator. You can generate the PDF file of nearly every content in you blog.
