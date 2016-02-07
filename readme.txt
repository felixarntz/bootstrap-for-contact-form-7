=== Bootstrap for Contact Form 7 ===

Plugin Name:       Bootstrap for Contact Form 7
Plugin URI:        https://wordpress.org/plugins/bootstrap-for-contact-form-7/
Author URI:        http://leaves-and-love.net
Author:            Felix Arntz
Donate link:       http://leaves-and-love.net/wordpress-plugins/
Contributors:      flixos90
Requires at least: 3.6 
Tested up to:      4.4.2
Stable tag:        1.3.1
Version:           1.3.1
License:           GPL v3
License URI:       http://www.gnu.org/licenses/gpl-3.0.html
Tags:              wordpress, plugin, contact form 7, wpcf7, bootstrap, bootstrap 3, bootstrap framework, addon, contact form 7 addon, contact form, cf7bs, css

This plugin modifies the output of the popular Contact Form 7 plugin to be styled in compliance with themes using the Bootstrap CSS framework.

== Description ==

Bootstrap for Contact Form 7 modifies all the output of the popular [Contact Form 7 plugin](https://wordpress.org/plugins/contact-form-7/) to be fully compatible with the current version 3 of the popular CSS framework [Bootstrap](http://getbootstrap.com/). What this means to you as a Bootstrap user: No additional CSS rules necessary - from now on, Contact Form 7 integrates seamlessly with the overall Bootstrap design. It is even possible to use different form layouts via Contact Form 7's "Additional Settings" tab.

> <strong>This plugin is an addon to Contact Form 7.</strong><br>
> The plugin requires Contact Form 7 to be activated, otherwise it won't change anything. Furthermore you should be using it in conjunction with a Bootstrap-based WordPress theme, otherwise the forms might look weird (and there would be no point in using this addon anyway).

= Usage =

Bootstrap for Contact Form 7 does not provide additional options itself, so you can continue using Contact Form 7 (almost) the same way you did before.

The plugin will not break your form's appearance, however it is recommended to adjust the contact form shortcodes to achieve perfect results: Generally, you should not be using HTML tags any longer to wrap the field shortcodes. They already include the complete Bootstrap-ready markup, including displaying labels. Read the [Setup Guide](https://wordpress.org/plugins/bootstrap-for-contact-form-7/installation/) for a quick introduction.

= Advanced Features =

The plugin brings some additional useful features to enhance your forms even more:

* the form layout can be changed to a horizontal or inline one
* the form's input size can be globally changed
* checkbox and radio groups can be displayed either one per line, inline or as Bootstrap buttons
* text inputs and textareas support Bootstrap's input group feature to add content before or after them
* text inputs and textareas can show a character count (the [count] shortcode from Contact Form 7) inline
* the captcha input field can show the captcha image inline
* by using GET parameters in a URL to a contact form, field values can be predefined

The above features are explained in detail on the [Other Notes](https://wordpress.org/plugins/bootstrap-for-contact-form-7/other_notes/) page.

= Basic Idea behind the Plugin =

Lots of WordPress Themes are based on Bootstrap - and while it is the general approach to use CSS rules to style your HTML content, it is also possible the other way around - with many benefits.

When using a well-known framework which provides general styles for all the important components of a website, it can be time-consuming to apply the same styles to third-party plugins which (obviously) have not been written with a framework in mind. This is perfectly fine, but if you're using Bootstrap for your WordPress theme, you will certainly love the fact that you do not need to write CSS rules for the Contact Form 7 plugin any longer. It will all look like Bootstrap from the beginning so that it fits into your website design. If you're not using Bootstrap, this plugin is useless for you - but maybe you're just having an idea how you can adjust another popular WordPress plugin to integrate with another well-written CSS framework.

== Installation ==

= Download and Activation =

1. Either download the plugin from within your WordPress site, or download it manually and then upload the entire `bootstrap-for-contact-form-7` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

= Setup Guide =

While the shortcodes generally work the same like in the original plugin, there are a few things to consider. If you like to get started quickly, just copy/paste the content below and adjust it to get the form you like (the code below recreates the default form from Contact Form 7).

	[text* your-name]Your Name[/text*]
	[email* your-email]Your Email[/email*]
	[text your-subject]Subject[/text]
	[textarea your-message]Your Message[/textarea]
	[submit "Send"]

The following are the most important things that are different in Bootstrap for Contact Form 7:

1. Field labels are now integrated in the field shortcodes, so you don't need to wrap them in paragraphs when editing the form. Instead, make the field shortcode enclosing and put the label text between the opening and closing tag. Example: `[text* your-name]Your Name[/text*]`
2. You don't need to use HTML tags any longer to give your form a layout. The field shortcodes handle this manually, so you should remove all HTML tags from the form editor. Of course you can still use HTML code to separate parts of your form, for example using the `<fieldset>` tag.

It is recommended to adjust the shortcodes to be conform with the explainations above to ensure perfect results with the plugin and Boostrap themes.

For details on everything else that you can do to enhance your forms even further, feel free to check out the [Other Notes](https://wordpress.org/plugins/bootstrap-for-contact-form-7/other_notes/) section.

== Frequently Asked Questions ==

= How can I use a different form layout for my form? =

The form layout (as well as other properties) can be adjusted in the "Additional Settings" tab. Please read the [Other Notes](https://wordpress.org/plugins/bootstrap-for-contact-form-7/other_notes/) section to learn how to do that.

= Why do my labels always appear in a separate line? =

If your labels still appear in a separate line although you have already changed the `group_layout`, you might still be using the shortcode the same way you did before. Please read the [Setup Guide](https://wordpress.org/plugins/bootstrap-for-contact-form-7/installation/) to see what to look out for.

= Why are the labels of radio buttons and checkboxes always displayed after them? =

While the option to display radio and checkbox labels before the actual input field is still visually available, it does not work with Bootstrap for Contact Form 7. Radio buttons and checkboxes in Bootstrap follow certain conventions, and switching their order is not part of those. However, you can instead modify the appearance of radio buttons and checkboxes in another cool way (read the [Other Notes](https://wordpress.org/plugins/bootstrap-for-contact-form-7/other_notes/) section to learn how).

= Why don't I see any change after having activated the plugin? =

Bootstrap for Contact Form 7 is an (inofficial) addon to Contact Form 7. You must have the plugin installed to see any changes. Furthermore you should only use this plugin if your theme is based on the CSS framework Bootstrap.

= Where should I submit my support request? =

I preferably take support requests as [issues on Github](https://github.com/felixarntz/bootstrap-for-contact-form-7/issues), so I would appreciate if you created an issue for your request there. However, if you don't have an account there and do not want to sign up, you can of course use the [wordpress.org support forums](https://wordpress.org/support/plugin/bootstrap-for-contact-form-7) as well.

= How can I contribute to the plugin? =

If you're a developer and you have some ideas to improve the plugin or to solve a bug, feel free to raise an issue or submit a pull request in the [Github repository for the plugin](https://github.com/felixarntz/bootstrap-for-contact-form-7).

== Screenshots ==

1. A general form by the Contact Form 7 plugin as rendered with Bootstrap for Contact Form 7
2. The default Contact Form 7 form code, formatted correctly for Bootstrap for Contact Form 7
3. A warning alert as displayed by Bootstrap for Contact Form 7

== Changelog ==

= 1.3.1 =
* Enhanced: alerts can now be made dismissible by defining the constant `CF7BS_ALERT_DISMISSIBLE`
* Fixed: properly add `for` attributes to labels for checkbox and radio groups for accessibility
* Fixed: wrapped checkbox and radio groups with `<fieldset>` for accessibility; needs to be manually enabled by defining the constant `CF7BS_FIELDSET_WRAP` (because of possible backwards compatibility issues with styling)
* Fixed: ID is no longer created for button groups which have an empty ID specified

= 1.3.0 =
* Added: the new Google reCAPTCHA shortcode is now supported
* Enhanced: added an additional filter to adjust form field arguments
* Enhanced: a notice in the admin is now shown if the plugin cannot be initialized
* Enhanced: language files no longer bundled in the plugin; now completely relies on language packs
* Fixed: form element errors are now displayed correctly on horizontal forms and hidden on inline forms
* Fixed: plugin constant definitions now happen on 'plugins_loaded' hook
* Fixed: typo 'dismissable' in alert setting / class is now 'dismissible'
* Fixed: readme link to 'Additional Settings' now translateable

= 1.2.4 =
* Tweaked: added textdomain for translate.wordpress.org
* Fixed: defaults are now working correctly on all field types
* Fixed: the label for attribute is no longer printed if no ID has been provided

= 1.2.3 =
* Enhanced: Arguments for every field are now filtered to allow detailed adjustments

= 1.2.2 =
* Fixed: CSS is now specific to the contact form to prevent conflicts

= 1.2.1 =
* Added: the captchar shortcode now supports an 'include_captchac' option to display the captcha image inline with the input field
* Added: the textarea shortcode and all other text inputs now support an 'include_count' option to display their character count inline with them
* Enhanced: the default grid column count of Bootstrap can now be overridden using the form property 'grid_columns'
* Enhanced: the textarea shortcode now supports 'input_before' and 'input_after' (content is displayed above / below the textarea)
* Enhanced: the submit button size can now be adjusted separately from the rest of the form's size
* Tweaked: captcha images now have their image size adjusted to the 'size' form property by default
* Tweaked: `---` typed in the 'input_before' or 'input_after' option will render as a space in the frontend
* Fixed: minlength and maxlength attributes are now honored by all text inputs and textareas

= 1.2.0 =
* Added: new CF7 count shortcode is now supported
* Enhanced: form properties can now be modified without any code (i.e. without a filter); the properties can be defined in the new "Additional Settings" tab of Contact Form 7
* Enhanced: textual inputs now support Bootstrap's input group feature
* Enhanced: checkbox and radio types can now show an actual label; it is only used as the checkbox label if no option is provided
* Tweaked: plugin now adheres to WordPress Coding Standards
* Fixed: improved display method for captcha images
* Fixed: textarea row attribute now honored
* Fixed: free_text attribute on checkbox and radio types now honored
* Fixed: form attribute 'group_type' now honored
* Fixed: additional CF7 styles are now outputted in the head
* Fixed: check if CF7 functions are available before calling them

= 1.1.1 =
* Fixed: exclusive option for checkbox now working
* Fixed: default option for radio/checkbox now working
* Fixed: PHP notice for radio/checkbox with only one option
* Fixed: Captcha not valid message now only shows up once as it is supposed to

= 1.1.0 =
* Added: new attribute 'align' can be added to the submit button
* Enhanced: submit button now positioned properly according to form layout
* Fixed: select and radio/checkbox options now use the main plugin's `get_data_option` method

= 1.0.0 =
* First stable version

== Advanced Features ==

= Additional Settings =

> Here you find additional settings which are part of the Bootstrap for Contact Form 7 plugin. If you want to learn more about the additional settings of the original Contact Form 7 plugin, please visit [this page](http://contactform7.com/additional-settings/).

You can adjust several form properties (properties that affect an entire form, not just a single field of it) to give your forms the appearance you want. Here is a list of the properties, what they do and their possible values:

* `layout` - adjusts the form's layout (note that in most cases the inline form will need additional styling to look good); valid values: 'default', 'inline', 'horizontal'; default value: 'default'
* `size` - adjusts the size of all input fields; valid values: 'default', 'small', 'large'; default value: 'default'
* `group_layout` - adjusts the layout of checkbox and radio groups; valid values: 'default', 'inline', 'buttons'; default value: 'default'
* `group_type` - adjusts the color of checkbox and radio groups with buttons layout; valid values: 'default', 'primary', 'success', 'info', 'warning', 'danger'; default value: 'default'
* `submit_size` - adjusts the size of the submit button; valid values: 'default', 'small', 'large' or an empty string to force it to have the size defined in the `size` form property; default value is an empty string
* `submit_type` - adjusts the color of the submit button; valid values: 'default', 'primary', 'success', 'info', 'warning', 'danger'; default value: 'primary'
* `required_html` - adjusts the HTML output to append to required fields' labels; valid values: any HTML output; default value: `<span class="required">*</span>`
* `grid_columns` - allows you to override the total grid column count of Bootstrap (you might only need to adjust this if you're using a custom version of Bootstrap); valid values: any integer greater than 1; default value: 12
* `label_width` - adjusts the form's label width (applies only to horizontal layout); valid values: any integer between 1 and the value of `grid_columns` minus 1; default value: 3
* `breakpoint` - adjusts the responsive breakpoint (applies only to horizontal layout); valid values: 'xs', 'sm', 'md', 'lg'; default value: 'sm'

There are three methods to adjust the above properties: The easiest one is to use the "Additional Settings" tab when editing a form in Contact Form 7 and insert any property and its desired value there, one per line. For example:

	layout:horizontal
	size:large
	group_layout:inline

Alternatively you can use the filter `cf7bs_form_{{FORM_ID}}_properties` where `{{FORM_ID}}` must be replaced by the ID of the form you would like to modify (you find that number in the overall form's shortcode). An array of all the properties and their values is passed to that function so that you can easily adjust them. Example (in this case we would adjust the contact form with the ID 3):

	function my_custom_form_properties( $properties ) {
		$properties['layout'] = 'horizontal';
		$properties['size'] = 'large';
		$properties['group_layout'] = 'inline';
		return $properties;
	}
	add_filter( 'cf7bs_form_3_properties', 'my_custom_form_properties' );

The third way does something slightly different from the other two since it does not change a specific form's properties, but the default properties for all forms. To do that, you should use the filter `cf7bs_default_form_properties` which works exactly like the other filter mentioned above.

Note that the custom form filter takes precedence over the properties defined in the admin, while the default filter is just used as fallback.

= Input Groups =

All textual input fields support the input group feature that Bootstrap provides. To use it, add an option `input_before` and/or `input_after` to any text / email / url / tel input. Example:

	[text twitter-username input_before:@]Your Twitter Handle[/text]

Note that the `input_before` and `input_after` options can also be added to textareas. In this case, the content will be displayed directly above or below the textarea respectively. To display content that contains one or more spaces, just enter it as the option value, replacing all spaces by three dashes. Example:

	[textarea* your-text input_before:Please---enter---something]Your Text[/textarea*]

= Submit Button Alignment =

The submit button can be aligned left, center or right to fit your form's desired appearance. Simply provide an `align` option with either 'left', 'center' or 'right' as value. Example:

	[submit align:right "Send"]

= Inline Character Count =

Contact Form 7 provides a `[count]` shortcode that renders a number indicating how many characters have been entered or how many characters are remaining in a specific input field. Using it on its own looks kind of ugly though. But guess what, you can adjust that too by adding an option `include_count` to any text / email / url / tel / textarea input. You can optionally specify a value for that option as well which can consist of the positioning ('before' or 'after') and the count direction ('up' or 'down') of the counter. Just as a reminder, when choosing 'down', make sure you give the input element a maximum length, otherwise there is no point in having that counter. Example:

	[text your-text maxlength:80 include_count:after:down]Your Text[/text]

By the way, have you read the information about input groups above? You can combine those with the character count (because just seeing a number without any additional information might confuse your site's visitors). The following example will show a message like '433 characters left' after the field:

	[textarea your-text maxlength:500 include_count:after:down input_after:characters---left]Your Text[/textarea]

= Inline Captcha Image =

If you've been using Contact Form 7 together with the [Really Simple CAPTCHA](https://wordpress.org/plugins/really-simple-captcha/) plugin, you are probably aware of the `[captchar]` (captcha input field) and `[captchac]` (captcha image) shortcodes it provides. You can still use them independently, but it probably looks nicer to have the captcha image show up inline, right beside its input field. To accomplish this, remove the `[captchac]` shortcode completely and instead add a new option `include_captchac` to the `[captchar]` shortcode. You can optionally give this option a value (either 'before' or 'after') to mark the location where the image should show up. Example:

	[captchar your-captcha include_captchac:before]Captcha[/captchar]

= Custom Form URLs =

You can add GET parameters to populate your forms with custom initial values by simply using the field name as parameter's key and the desired value as the parameter's value. This works with checkboxes, date fields, number fields, select fields, all text fields and textareas. The easiest way to create such a URL is to use the plugin's function `cf7bs_add_get_parameter()` where you provide parameters similarly to the WordPress Core function [add_query_arg](https://codex.wordpress.org/Function_Reference/add_query_arg). Example:

	$my_custom_url = cf7bs_add_get_parameter( array(
		'your-name'		=> 'John Doe',
		'your-email'	=> 'johndoe@example.com',
		'your-subject'	=> 'Support Request',
	), 'http://www.example.com/my-custom-form/' );

= Filter Field Arguments =

As of version 1.2.3, field arguments for every single field can be filtered, which allows you to basically modify anything you like. The filters you need to hook your function into have the following structure `cf7bs_form_{{FORM_ID}}_field_{{FIELD_BASETYPE}}_{{FIELD_NAME}}_properties`. Yep, it's a long filter name, but it is necessary so that you can filter in the most detailed way possible. When using one of the filters, replace `{{FORM_ID}}` with the ID of the form, `{{FIELD_BASETYPE}}` with the type of the field you need to adjust (be sure to not include the asterisks here!) and `{{FIELD_NAME}}` with the name of the field. The function should accept one argument, an array of parameters. For an overview about the available parameters, please check the plugin's source code.

== Unsupported functionality ==

While the plugin tries to support as many features as possible from the original Contact Form 7 (in combination with adding new ones!), not everything is and can be supported.

There are two things in particular which are explicitly not supported:

* the `size` option on all input fields (Bootstrap form elements scale automatically, so we do not need a custom size for those)
* the `label_first` option on checkbox and radio fields (Bootstrap natively only supports checkboxes and radio buttons where the label is displayed after them)

If you discover any other Contact Form 7 feature than the above two which is not supported by Bootstrap for Contact Form 7, you can [raise an issue](https://github.com/felixarntz/bootstrap-for-contact-form-7/issues) or [submit a support request](https://wordpress.org/support/plugin/bootstrap-for-contact-form-7).
