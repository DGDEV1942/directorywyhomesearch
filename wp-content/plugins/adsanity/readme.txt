=== AdSanity ===
Contributors: brandondove, jeffreyzinn
Tags: Advertising, Banners
Requires at least: 3.1.4
Tested up to: 4.4.1
Stable tag: 1.0.9.1
License: GPLv2 or later

Simplified banner advertising uses WordPress core functionality to group, display and track advertising campaigns.

== Description ==

Simplified banner advertising uses WordPress core functionality to group, display and track advertising campaigns.

== Installation ==

1. Upload the `adsanity` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enjoy the sanity and simplicity that this plugin offers for your ad campaigns

== Frequently Asked Questions ==

= How do I put an ad in my content? =

ADsanity has a shortcode just for this purpose. Simply add [adsanity id=1] to you content where you want the ad with
an id of 1 to show in your content. You can optionally add the align parameter to the shortcode to align the ad within
your content. This requires css to be available in your theme. Possible values are: alignleft, aligncenter, alignright
and alignnone.

== Changelog ==

= 1.0 =
* Launch!

= 1.0.1 =
* Removed non-standard ad sizes from the defaults
* Tracking filters can now be disabled by setting the constant ADSANITY_TRACK_THIS to true in wp-config.php
* Fixed default CSS for 125s and 140s

= 1.0.2 =
* Update far future date for ADSANITY_EOL to Dec 31, 2035 to keep within unix time limit for all hosts.

= 1.0.3 =
* WordPress 3.5 compatibility. The upgrade of the jQuery javascript library in 3.5 introduced an error in the jQuery UI Datepicker widget we have bundled. In 1.0.3, we're eliminating the redundancy of this external library. WordPress now ships with the datapicker functionality, so we're going to be using that moving forward.
* Fixed "Add featured image" and "Remove featured image" text being replaced by "Set banner ad image" and "Remove banner ad image" on non-ad post types
* Moved theme_support call to after_setup_theme hook

= 1.0.4 =
* Added adsanity_group shortcode
* Added TinyMCE buttons for both [adsanity] (single ad) and [adsanity_group] (group of ads)
* Introduced script debug mode
* Compressed all images to be as small as possible
* Fixed undefined index notices, silence is golden
* View/Click tracking now works in accordance with the time zone set it WordPress

= 1.0.4.1 =
* Fixed a bug where shortcode form file paths were incorrect on some installs

= 1.0.4.2 =
* Fixed a bug where a filter would apply an order to non-ad posts. props @bradyvercher

= 1.0.4.3 =
* Return the original request

= 1.0.4.4 =
* Fixed issue on multi-site or with in WordPress in a subdirectory where shortcode pop-ups would not pull in ads or ad groups (props Mike Dowling)

= 1.0.4.5 =
* Fixed issue on the custom stats page where the search filter wouldnwasn't working
* Show ads that are scheduled to be published in the future in sidebar widgets.

= 1.0.4.6 =
* Fixed javascript enqueuing errors (props Heather & Amy for the catch)

= 1.0.5 =
* Adds PressTrends anonymous usage tracking
* Fixes CSS issue when the plugin is not authorized
* Visual updates for WordPress 3.8 "Parker"
* Fixes Javascript issue when not authorized
* Added Notes field to create/edit screen to track data about ad units
* If theme doesn't support thumbnails, add thumbnail support globally
* Show size label instead of size value on ad list View
* Reworked menu and screen titles due to change in WordPress admin style
* Updated admin styles for WordPress 3.8

= 1.0.5.1 =
* Fixes an issue with the single ad widget where the clicking on the text label for the ad's checkbox would always select the first radio button. (props Taylor Johnson & Greg Yoder)

= 1.0.5.2 =
* Fixed timezone string functionality for sites using UTC with offsets
* Removed Presstrends tracking

= 1.0.6 =
* Interesting tidbit: 32-bit systems can only handle integers up to 2147483647. Timestamps are integers. Sometime in 2038, timestamps will stop being valid integers.
* Fixed: Custom stats engine bug. Users can now return results in any time range.
* Upgraded jQuery Flot library to 0.8.3
* Adds ability to export custom stats to csv

= 1.0.7 =
* Fixes a Javascript error on the Ad Edit page with the jQuery Flot library

= 1.0.7.1 =
* Fixes date formatting on charts

= 1.0.8 =
* Fixes a bug when a new plugin is added so that AdSanity doesn't throw an error.
* Changes plugin activation workflow so that AdSanity is functional without being authenticated, but won't receive automatic updates until it's properly authenticated.
* Fixes issue when rewrite rules aren't flushed after activation causing ads to not redirect to their destination properly.
* Adds helpful language to the ad edit screen for advanced HTML/text ad creation via network ads.
* Fixes issue where Tracking URL field was hidden from network ad code when it might have been useful for creating HTML/text ads.
* General code formatting updates for consistency.

= 1.0.8.1 =
* Removes PHP4 style constructors from widgets because WordPress 4.3 deprecated them
* Fixes an undefined notice in the tracking filters admin page

= 1.0.9 =
* Fixed CSS font issues
* Moved AdRoatate importer into free add-on to streamline codebase and improve UI
* Removed legacy files
* Added loads of inline documentation
* Improved internationalization (if you're interested in helping translate AdSanity, let us know!)
* Switched WP_Cache API to Transients API for greater performance compatibility
* Continued code improvements using PHP 5.3 compatible functionality
* Fixed Authorization Engine
* Updated styling for admin screens
* Fixed errors for static function calls on non static functions.

= 1.0.9.1 =
* Introduces ad theme templates for individual ads (ad-123.php)
