=== Bookgrail ===
Contributors: petersp
Tags: bookgrail, books, ebooks, bookstore, store, ecommerce
Requires at least: 3.0.1
Tested up to: 4.5.2
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily add the Bookgrail Buy button and start selling ebooks from your site with Bookgrail.

== Description ==

With [Bookgrail](http://www.bookgrail.com/) you're just minutes away from selling ebooks all over the world. BookGrail is completely free. We handle the back-office stuff leaving you free to concentrate on the books. When you make a sale, we'll bill it, deliver it, pay the author and publisher, and then split the profits with you 50/50.

When the Bookgrail plugin is enabled, you can integrate your Wordpress site with Bookgrail's ecommerce platform and earn credit for each sale.

== Installation ==

Option 1: Install using the Wordpress Admin Panel:

1. From your WordPress site's Admin Panel go to _Plugins -> Add New_.
2. Enter 'bookgrail' in the _Search Plugins_ text field and hit Enter.
3. Your search results will include a plugin named Bookgrail.
4. Click the _Install Now_ button to install the Bookgrail plugin.
5. Upon successful installation, the Bookgrail plugin will appear in _Plugins -> Installed Plugins_.
6. Finally, click _Activate Plugin_ to activate your new plugin.

Option 2: Install manually:

1. Download the plugin zip file by clicking on the _Download_ button on [this page](https://wordpress.org/plugins/bookgrail).
2. Unzip the plugin zip file into your Wordpress plugins directory (usually `wp-content/plugins`)
3. In the Wordpress Admin Panel, go to the _Plugins_ page.  In the list, you should see your new **Bookgrail** plugin.
4. Click **Activate** to activate your new plugin.

Upon successful activation, **Bookgrail** will appear on the _Settings_ menu in the Wordpress Admin Panel. Click on _Settings -> Bookgrail_ to open the Bookgrail plugin configuration page and complete setup.



== Changelog ==

= 1.0 =
* Initial Release.


== Available Shortcodes ==

Display any Bookgrail price and buy button using this plugin's Shortcodes

**bg_price**

Returns a book's price identified by its ISBN

E.g. [bg_price isbn="9781781852514"]

**bg_buy_button**

Displays a button which when clicked adds a book to the Bookgrail shopping cart and displays the cart as a popup on screen.

E.g. [bg_buy_button isbn="9781781852514" classes="btn blue"]

The classes shortcode parameter value is added to the class value of the button control.
