=== WooPoly ===
Contributors: decarvalhoaa, hyyan
Donate link: https://www.paypal.com/cgi-bin/webscr?hosted_button_id=YKMDTB62QYJUC&cmd=_s-xclick
Tags: cms, commerce, e-commerce, e-shop, ecommerce, multilingual, products, shop, woocommerce, polylang, bilingual, international, language, localization, multilanguage, multilingual, translate, translation
Requires at least: 3.8
Tested up to: 4.5.2
Stable tag: 1.0.1
License: MIT
License URI: https://github.com/decarvalhoaa/woopoly/blob/master/LICENSE

Integrates Woocommerce With Polylang

== Description ==

This plugin makes it possible to run multilingual e-commerce sites using
WooCommerce and Polylang.

This is a fork of the [Hyyan WooCommerce Polylang Integration](https://wordpress.org/plugins/woo-poly-integration/)
develop by Hyyan that is no longer mantained. Credit goes entirely to
Hyyan for this great plugin.

= Features  =

- [√] Auto Download Woocommerce Translation Files
- [√] Page Translation
- [√] Endpoints Translation
- [√] Product Translation
  - [√] Categories
  - [√] Tags
  - [√] Attributes
  - [√] Shipping Classes
  - [√] Meta Synchronization
  - [√] Variation Product
  - [√] Product Gallery
- [√] Order Translation
- [√] Stock Synchronization
- [√] Cart Synchronization
- [√] Coupon Synchronization
- [√] Emails
- [√] Reports
  - [√] Filter by language
  - [√] Combine reports for all languages

= What you need to know about this plugin =

1. The plugin needs `PHP5.3 and above`
2. This plugin is developed in sync with [Polylang](https://wordpress.org/plugins/polylang)
   and [WooCommerce](https://wordpress.org/plugins/woocommerce/) latest version.
3. The plugin support variable products, but using them will `disallow you to
   change the default language`, because of the way the plugin implements this
   support. So you have to make sure to choose the default language before you
   start adding new variable products.
4. Polylang URL modifications method `The language is set from content` is not
   supported yet

= Setup your environment =

1. You need to translate woocommerce pages by yourself
2. The plugin will handle the rest for you

= Translations =

* Arabic by [Hyyan Abo Fakher](https://github.com/hyyan)

= Contributing =

Everyone is welcome to help contribute and improve this plugin. There are several
ways you can contribute:

* Reporting issues (please read [issue guidelines](https://github.com/necolas/issue-guidelines))
  and report the issues [here](https://github.com/decarvalhoaa/woopoly/issues)
* Suggesting new features [here](https://github.com/decarvalhoaa/woopoly/issues)
* Fixing issue (fork from the [master](https://github.com/decarvalhoaa/woopoly)
  and make a pull request with your fixes)

== Installation ==

1. Download the plugin as zip archive and then upload it to your wordpress plugins
   folder and extract it there.
2. Activate the plugin from your admin panel

== Frequently Asked Questions ==

= Does this work with other e-commerce plugins ? =

No. This plugin work only with Polylang and WooCommerce.

= Does this plugin works with WPML plugin? =

No. This plugin was dedsign to work only with Polylang.

= Do I need to change something in my theme? =

Nope, nothing.

= Products Category or tags pages are blank =

Make sure to setup your permalinks in the admin backend.

== Screenshots ==

1. Add and translate products from the same interface you love
2. Products meta is synced , no need to do anything by your own
3. Orders use the customer chosen language
4. Orders language can be changed
5. Get reports in specific language and combine reports for all langauges
6. Control plugin features from its admin page

== Changelog ==

= 1.0.1 =

* Fix: Some emails not sent in the correct language

= 1.0.0 =

* Fix: Attribute selection language and attribute sync bug (#2, #16)
* Fix: Fatal error on cart page if not all translations are available (#18)
* Fix: Plugin activates/don't auto-deactive if dependencies are not met (#19)
* Tweak: Add action links to settings and support
* Tweak: Doesn't translate product variation attributes that don't have translations in current language (#20)

= 1.0.0b (Beta) =

First release for wordpress.org

== Fixed from Hyyan WooCommerce Polylang Integration plugin (v0.25) ==

* Dev: Added support for WooCommerce 2.6
* Dev: Improvements to WordPress Coding Standards and code documentation
* Dev: Change of plugin name, root dir and textdomian for submission to wordpress.org
* Fix: Product type sync for products created before plugin activation (issue #8)
* Fix: Default attributes for variable products not synced (issue #11)
* Fix: Non-taxonomy attributes not synced (issue #12)
* Fix: Taxonomy attributes not translated in cart and checkout pages (issue #9)
* Fix: Variation attributes not translated in cart permalinks
* Tweak: Wrong product id (not translated) in remove from cart links
* Fix: Order emails translations
* Fix_ Payment gateways translation
* Fix: Shipment methods translation
* Fix: Broken 'my account' breadcrumbs links
* Fix: Order details backend page html
* Fix: PHP warning due to polylang deprecated methods
* Fix: Product duplication in shop page when default language is changed
* Fix: Total sales is synced even if product is not managing stock
* Fix: Duplicator class PHP notice when product is being edited in quick mode
* Fix: Random behaviour for product type sync
* Fix: Tax class are not synced

== Upgrade Notice ==

= 1.0.0 =

Please deactivate Hyyan WooCommerce Polylang Intgeration plugin, if installed, before
activating WooPoly.
