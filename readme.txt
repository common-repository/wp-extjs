=== WP ExtJS ===
Contributors: Michael Lynn
Author URI: http://www.mlynn.org
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=J89ARHMKVMAAN
Tags: ExtJS, Wordpress, Javascript, Sencha, wp-extjs
Requires at least: 3.0
Tested up to: 3.0.1
Stable tag: 1.0.4

== Description ==

WP-ExtJS lets you leverage the power of the ExtJS Javascript Framework right inside your Wordpress blog pages and posts.  Create ExtJS Snippets using a new custom post type and include those snippets in your posts or pages using the wp-extjs shortcodes.  This plugin is perfect for developers that love to write about their ExtJS projects using a Wordpress Blog.

* `[wp-extjs snippet_id=909 height=200 width=400]` - Include the ExtJS Snippet Post ID 909 and display in an inline frame with a height of 200 and width of 400

== Installation ==

1. Upload all the files into your wp-content/plugins directory under wp-extjs.
1. Activate the plugin at the plugin administration page
1. Create a new ExtJS Snippet and save it.  Create or edit an existing page or post and click the ExtJS Snippet Inserter at the top of post edit panel to insert the snippet shortcode into your post.  
1. Select the snippet you wish to use in your post or page and click the 'insert shortcode' button

Please see the [wp-extjs plugin home page](http://mlynn.org/wp-extjs) for details

== Frequently Asked Questions ==

= Why do I get a blank portion on my page or post where the ExtJS Script is to be displayed? =
Most likely because your script is not being interpreted properly.  This occurs most often when you have a bug in your script.  
= How / Where do I get ExtJS? 
Visit <a href=http://www.sencha.com/products/js/download.php>Sencha.com</a>

= How do I install ExtJS?
Simply download the latest archive from <a href=http://www.sencha.com/products/js/download.php>Sencha.com</a>, unzip the archive in a web accessible directory.  Take note of the path relative to your web server root... you will need this for configuring wp-extjs.

== Screenshots ==

1. Showing the WP-ExtJS Settings Form
2. ExtJS Snippets Management Panel
3. Add New Snippet Screen
4. Insert ExtJS Snippets Popup Window - Used to insert shortcodes into posts
5. Showing shortcode inserted into post

== Changelog ==
= Version 1.0.4 =
* Moved the display for additional header elements to BEFORE the script - thanks Stephan!

= Version 1.0.3 =
* Correct mismatched form tag due to donate section - was only a problem in Chrome for some reason
* Corrected optional display of donate panel
* Added Warning message if specified extjs directory does not exist
* Will only implement functionality if "Enable Plugin" option is set

= Version 1.0.2 =
* Fix default extjs path - make it more logical

= Version 1.0.0 =
* Initial Release

== Upgrade Notice ==
= Version 1.0.4 =
* Update to move to move additional header elements

= Version 1.0.3 =
* Fixes problem with Chrome browser in administration / settings screen and adds warning check for extjs library path

= Version 1.0.2 =
* Fixes default extjs path and theme css

= Version 1.0.0 =
* Fixes the lack of WP-ExtJS
