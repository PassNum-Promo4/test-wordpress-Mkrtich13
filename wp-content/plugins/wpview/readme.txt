=== Display Custom Fields - wpView ===
Contributors: gVectors Team
Donate link: http://gvectors.com
Tags: custom fields, field, display custom field, view, front-end, custom field view
Requires at least: 4.2
Tested up to: 4.7
Stable tag: 1.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Inserts custom fields as shortcode. Allows to display custom fields with different views on front-end. Uses popular custom field creator plugins.

== Description ==

This plugin allows to display custom fields on posts/pages and other content types. Puts custom fields as a shortcode in post/page content. The easiest way to display custom fields with different beautiful views. wpView supports WordPress native custom fields and almost all popular custom field plugins, such as:

* [Custom Field Suite](https://wordpress.org/plugins/custom-field-suite/)
* [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/)
* [Toolset Types](https://wordpress.org/plugins/types/)
* [WordPress Creation Kit](https://wordpress.org/plugins/wck-custom-fields-and-custom-post-types-creator/)
* Custom Content Type Manager

**Custom Field Shordcode Examples**

    [wpview type="all"] - All custom fields in a single table
    [wpview type="text" view="Country"]
    [wpview type="textarea" title="Country" view="left_border"]
    [wpview type="wysiwyg" title="Company Info" view="bordered_box"]
    [wpview type="file" title="Documentation File" view="only_link"]

    [wpview type="...." title="..." view="..."]


wpView has nice views for almost all custom field types:

* Number,
* Email,
* WYSIWYG,
* Date,
* Color,
* Select,
* Checkbox,
* True/False,
* Hyperlink,
* User,
* Relation (posts),
* Page Link,
* Post Object,
* Images,
* Audio,
* Category,
* Post Tag,
* Google Map
* etc...

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->Plugin Name screen to configure the plugin
4. Use wpView button on post editor to insert custom fields.


== Frequently Asked Questions ==

= Getting Started =

1. **Using wpView with Custom Field plugins:** If you already have some custom field managing plugin like "Advanced Custom Fields", "Custom Field Suite", "Toolset Types", "WordPress Creation Kit" or "Custom Content Type Manager", you should activate according plugin icons in Dashboard > Display Fields admin page. Just click on according plugin icon-button. The active plugin icon becomes colored.

2. **Managing Custom Field views:** After enabling a Custom Field plugin, choose the view mode using two large images-buttons (Table and Custom View). The default Table view displays all custom fields in a table. If you want to show different custom fields with separate view you should use the "Custom Separate View" option and set appropriate view type for each custom field type.

3. **Managing Custom Field displaying location:** Next to the custom field displaying views drop-down you can find custom field location options. Just select one of location options.


== Screenshots ==

1. Display custom fields - table view  | Screenshot #1
2. wpView custom field type insert button on post editor  | Screenshot #2
3. Custom field types pop-up  | Screenshot #3
4. vpView Settings - view modes  | Screenshot #4
5. vpView Settings - separate custom views  | Screenshot #5
6. Display custom fields - colors and styles  | Screenshot #6




== Changelog ==

= 1.3.0 =

* Added: Allows to set before/after content for All in One Table view mode
* Added: Allows to select individual view for each Custom Field shordcode


= 1.2.2 =

* Added: Option to hide/show custom fields in post excerpt (post list, archive, category page)


= 1.2.1 =

* New Shortcode: "All in one Table" shordcode [wpview type="all"] - displays all custom fields together in a table. 


= 1.2.0 =

* Added: Compatible with WordPress 4.7
* Added: Now you can insert custom fields as a shortcode in post content using Custom Field Selector button on post editor tollbar.
* Added: Single and Grouped mode of custom field shortcodes


= 1.1.2 =

* Fixed Bug: Conflicts with NextGEN gallery


= 1.1.1 =

* Fixed Bug: Skip empty custom fields on frontend
* Fixed Bug: Displaying issues with Post Object, Page Link and Relation custom fields


= 1.1.0 =

* Added: wpView button on post/page editor to insert certain custom field type in content.


= 1.0.0 =

* Initial version. We just started to help very easy display custom fields on website. If you found some issue or bug please open a support topic in plugin page or in our [support forum](http://gvectors.com/forum/).


