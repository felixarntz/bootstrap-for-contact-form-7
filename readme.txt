=== Bootstrap for Contact Form 7 ===

Plugin Name:       Bootstrap for Contact Form 7
Plugin URI:        http://wordpress.org/plugins/bootstrap-for-contact-form-7/
Author URI:        http://leaves-and-love.net
Author:            Felix Arntz
Donate link:       http://leaves-and-love.net/wordpress-plugins/
Contributors:      flixos90
Requires at least: 3.6 
Tested up to:      4.1.1
Stable tag:        1.1.0
Version:           1.1.0
License:           GPL v2 
License URI:       http://www.gnu.org/licenses/gpl-2.0.html
Tags:              contact form 7, wpcf7, bootstrap, bootstrap 3, bootstrap framework, addon, css framework, contact form 7 addon, contact form, cf7bs, css

This plugin modifies the output of the popular Contact Form 7 plugin to be styled in compliance with themes using the Bootstrap CSS framework.

== Description ==

Bootstrap for Contact Form 7 modifies all the output of the popular [Contact Form 7 plugin](https://wordpress.org/plugins/contact-form-7/) to be fully compatible with the current version 3 of the popular CSS framework [Bootstrap](http://getbootstrap.com/). What this means to you as a Bootstrap user: No additional CSS rules necessary - from now on, Contact Form 7 integrates seamlessly with the overall Bootstrap design. It is even possible to use different form layouts via an easy-to-use filter.

**This plugin is actually an addon to another plugin, so it requires Contact Form 7 to work. Furthermore you have to be using it in conjunction with a Bootstrap-based WordPress theme, otherwise the forms might look weird.**

= Usage =

Bootstrap for Contact Form 7 does not provide additional options itself, so you can continue using Contact Form 7 (almost) the same way you did before.

The plugin will not break your form's appearance, however it is recommended to adjust the contact form shortcodes to achieve perfect results: The most important thing you need to know is that form field labels are now integrated in the field shortcodes, so you don't need to wrap them into paragraphs when editing the form shortcode. If you want to use a label for a specific field, you should instead make the shortcode enclosing (by default, all Contact Form 7 shortcodes are self-closing) and put the label in between. Make sure that, if your field should be required, you add the asterisk to the closing tag as well.

Generally, you should not be using HTML tags any longer to wrap the field shortcodes. The new shortcodes are automatically printed out with wrapping div elements, so an additional wrapper is neither necessary nor recommended. As of version 1.1 of this plugin, the submit button is also automatically positioned according to the form layout. You can specify its alignment using a new 'align' attribute, for example `align:right`. Of course you can still use HTML code to separate parts of your form, for example using the fieldset tag.

An additional feature of this plugin is the possibility to predefine field values for your forms using GET parameters which allows you to bring an improved user experience to your visitors by creating custom links. Simply use the field name as the parameter key and the desired value as value. This works with checkboxes, date fields, number fields, select fields, text fields and text areas. To create such a URL, you need to use the plugin function `cf7bs_add_get_parameter()` where you provide parameters similarly to the WordPress Core function [add_query_arg](https://codex.wordpress.org/Function_Reference/add_query_arg).

For additional information, please read the [FAQ](http://wordpress.org/plugins/bootstrap-for-contact-form-7/faq/).

= Basic Idea behind the Plugin =

Lots of WordPress Themes are based on Bootstrap - and while it is the general approach to use CSS rules to style your HTML content, it is also possible the other way around - with many benefits.

When using a well-known framework which provides general styles for all the important components of a website, it can be time-consuming to apply the same styles to third-party plugins which (obviously) have not been written with a framework in mind. This is perfectly fine, but if you're using Bootstrap for your WordPress theme, you will certainly love the fact that you do not need to write CSS rules for the Contact Form 7 plugin any longer. It will all look like Bootstrap from the beginning so that it fits into your website design. If you're not using Bootstrap, this plugin is useless for you - but maybe you're just having an idea how you can adjust another popular WordPress plugin to integrate with another well-written CSS framework.

== Installation ==

1. Upload the entire `bootstrap-for-contact-form-7` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Adjust your Contact Form 7 shortcodes to achieve perfect results with the plugin.

== Frequently Asked Questions ==

= How can I use a different form layout for my form? =

To modify the layout (or one of the other six form properties), you need to use the filter `'cf7bs_form_' . $form_id . '_properties'` where `$form_id` must be the ID of the form you'd like to modify. You find this number in the form shortcode. An array is passed to the function you specify, containing entries with the following keys:

* `layout` - possible values: default, inline, horizontal; default value: default
* `label_width` - possible values: any integer between 1 and 11; default value: 3
* `breakpoint` - possible values: xs, sm, md, lg; default value: sm
* `size` - possible values: default, small, large; default value: default
* `required_html` - possible values: any HTML output to append to labels for required fields; default value: `<span class="required">*</span>`
* `group_layout` - possible values: default, inline, buttons; default value: default
* `group_type` - possible values: default, primary, success, info, warning, danger; default value: default
* `submit_type` - possible values: default, primary, success, info, warning, danger; default value: primary

So if you need to change the layout to a horizontal one, the function can look like this: `function yourfunction( $properties ) { $properties['layout'] = 'horizontal'; return $properties; }`

You could also modify the default form properties (affecting every form on your site) by using the filter `cf7bs_default_form_properties`.

= Why do my labels always appear in a separate line? =

If your labels still appear in a separate line although you have already changed the layout, you might still be using the shortcode the same way you did before. Please read the description to see what to look out for.

= Why are the labels of radio buttons and checkboxes always displayed after them? =

While the option to display radio and checkbox labels before the actual input field is still visually available, it does not work with Bootstrap for Contact Form 7. Radio buttons and checkboxes in Bootstrap follow certain conventions, and switching their order is not part of those. However, you can instead modify the appearance of radio buttons and checkboxes in another cool way, using a filter as described above.

= Why don't I see any change after having activated the plugin? =

Bootstrap for Contact Form 7 is an (inofficial) addon to Contact Form 7. You must have the plugin installed to see any changes. Furthermore you should only use this plugin if your theme is based on the CSS framework Bootstrap.

= How can I contribute to the plugin? =

If you're a developer and you have some ideas to improve the plugin or to solve a bug, feel free to raise an issue or submit a pull request in the [Github repository for the plugin](https://github.com/felixarntz/bootstrap-for-contact-form-7).

== Screenshots ==

1. A general form by the Contact Form 7 plugin as rendered with Bootstrap for Contact Form 7
2. The default Contact Form 7 form code, formatted correctly for Bootstrap for Contact Form 7
3. A warning alert as displayed by Bootstrap for Contact Form 7

== Changelog ==

= 1.1.0 =
* Added: new attribute 'align' can be added to the submit button
* Enhanced: submit button now positioned properly according to form layout
* Fixed: select and radio/checkbox options now use the main plugin's `get_data_option` method

= 1.0.0 =
* First stable version

== Upgrade Notice ==

The current version of Bootstrap for Contact Form 7 requires WordPress 3.6 or higher.
