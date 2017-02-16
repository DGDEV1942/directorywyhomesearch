=== AppThemes Updater ===
Contributors: appthemes, scribu
Tags: appthemes, multisite, network, plugins, themes, updates
Requires at least: 3.4
Tested up to: 4.6
Stable tag: 1.4.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enables automatic updates for AppThemes products or items from our Marketplace.

== Description ==

The AppThemes Updater allows you to receive notifications of new product versions and automatically update them directly from within WordPress.

= Reporting Bugs & Contributing =

If you find a bug or are a developer and would like to contribute, please do so via GitHub: [https://github.com/AppThemes/Updater](https://github.com/AppThemes/Updater). We accept pull requests and appreciate bug reports.


== Installation ==

= Automatic Installation =

This is by far the easiest way to as WordPress handles the file transfers itself and you don't even need to leave your web browser.

1. Log in to your WordPress admin area.
2. Navigate to the "Plugins" sidebar menu section and click "Add New".
3. In the search field type, "AppThemes Updater" and click "Search Plugins".
4. Once you've found the plugin, click the "Install Now" button.
5. After it installs, click on the "Activate Plugin" link.


= Manual installation =

This requires you to manually download the plugin to your local machine and then upload it directly to your web server via SFTP. This method is not recommended.

1. Download the plugin file to your computer and unzip it (using WinZip for PC or for Mac, the Archive Utility).
2. Use an SFTP program (or your hosting control panel) to upload the unzipped plugin folder to your WordPress installation's `wp-content/plugins/` directory.
3. Activate the plugin from the "Plugins" sidebar menu section within the WordPress admin.

= Getting started =

Now that the plugin is active, follow the instructions on the top of the page. It'll guide you on how to obtain and enter your AppThemes API key. Once that's completed, you'll be notified whenever an update is available via the standard WordPress notification system (plugins or themes page).


== Frequently Asked Questions ==

= Where can I find my AppThemes API key? =

Log in to your [AppThemes account](https://my.appthemes.com/api-key/) and download from there.

= Can I have more than one API key? =

Not at this time.

= Do I need an AppThemes account? =

Yes. Accounts are free but you must purchase a product (or renew your subscription) before the AppThemes Updater plugin will work.


== Screenshots ==

1. Enter your API key screen


== Changelog ==

= 1.4.0 =
*Released - Aug 18 2016*

* added plugin action links to the settings, docs, and support pages
* moved the options page where is belongs under the settings menu
* moved the .pot language file into it's own /languages dir
* removed multisite options page to follow best practices. set options on each site
* don't require that plugin be multisite network activated only

= 1.3 =
* fixed compatibility with WordPress 3.7
* fixed not finding updates when only one theme is installed

= 1.2.1 =
* fixed not finding updates

= 1.2 =
* works with items from marketplace.appthemes.com
* added "Check for updates now" button

= 1.1.2 =
* check for updates each time wp-admin/update-core.php is accessed

= 1.1.1 =
* fixed error when accessing API on new infrastructure
* fixed confusing notice when inserting API key

= 1.1 =
* check for updates on activation
* better support for multisite

= 1.0 =
* initial release


== Upgrade Notice ==

= 1.3 =
This version fixes a compatibility issue with WordPress 3.7. Please upgrade immediately.

= 1.2 =
This version now works with items from our Marketplace. Please upgrade immediately.
