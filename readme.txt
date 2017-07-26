=== DIYCWare Image Editor ===
Contributors: Mark_Bailly
Donate link: http://www.diycware.com/donate
Tags: image editor, image upload, image, web to print, diy, canvas
Requires at least: 3.0
Tested up to: 3.8.1
Stable tag: 2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds customer image upload and image editing functionality to your e-commerce site.

== Description ==
If you are a printing business or photography business, you can add this plugin to your store so that customers can upload and edit images. The customer uploads the image, makes adjustments, selects the type of media, saves the image as a product, and adds it to the cart. The tool uses the Woocommerce plugin for the store management. 

We have a demo WP site that you can view [HERE](http://diycware.wepds.com/wpwoo/shop/canvas/).

**Requirements:**

1. Imagemagick on your hosting service. Most hosting companies have Imagemagick on their servers for image creation/editing.
2. Woocommerce
3. Turn on permalinks (pretty URLs).

**Current Image Editing Features:**

1. Cropping
2. Brightness
3. Contrast
4. Sepia

More features will be added in the future, depending on demand.

**Documentation**
Please read the complete [Owners Manual](http://www.diycware.com/diyc-image-editor).

We will be coming out with a [Pro version](http://www.diycware.com/diyc-image-editor-pro) soon with many more features and functionality.

**Support** 
For the quickest response to a question, please email us using the email address on our web site. Or use the support forums on this page.

== Installation ==

Steps:

1. Download the zip file for the plugin.
2. Unzip it to your local machine.
3. Upload the *diyc-image-editor* directory to your *plugins*. Note: If you use the Windows zip extract tool you will end-up with a double directory 'diyc-image-editor\diyc-image-editor'. If this is the case then you need to upload the lower of the two directories.
4. Activate the plugin through the 'Plugins' menu in WordPress
5. Read the complete [Owners Manual.](http://www.diycware.com/diyc-image-editor)

== Frequently Asked Questions ==

= Do I need to use Woocommerce? =

Yes. The plugin works with the Woocommerce cart.

= Do I need to use the Woocommerce Product Addons plugin? =

No. As of version 2.0, this plugin is no longer needed. 

= The plugin does not work - what do I do? =
Please use the comment section here to get support. Please be descriptive with your problem, including the versions you are using, which broswer etc.

= Image does not appear after upload. =
Is the assets/temp directory set to writable? Set the permissions to 0x777.
Is your Imagemagick install set to usr/bin (ask your hosting provider)? If it is a different location then you will need to edit the assets/php/convert-location.php file. Some hosts install Imagemagick at usr/local/bin.

= Is the plugin offered in other languages? =
As of the initial release, English is the only language. In the future, we may add translations as requested. Leave a comment if you want the plugin translated.

== Screenshots ==

1. Image Editor Gui
2. Image added to cart

== Changelog ==
= 2.2 =

* Fixed error in css file that caused cropping errors.
* Added feature so that the cropping is disabled after it is used. An UndoAll will re-activate the cropping tool.
* Fixes error in diycGUI.js that caused caching of old saved image.
* Added nonce security to image save.
* Removed incorrect licensing text in php/getimage1.php.

= 2.1 =

* Fixed error in diycGUI.js. Some image editing parameters were incorrect after saving the image.

= 2.0 =

* Improved version. No longer requires product-addons plugin. Saved image creates new product for customer.

= 1.0 =

* Initial release.

== Upgrade Notice ==
= 2.2 = 
This update fixes an error with cropping. Please upgrade.
= 2.1 = 
Fixes bug that may product incorrect image parameters in saved image details. Please upgrade.
= 2.0 =
New version no longer requires etra plugin. Saved image creates a new product.
= 1.0 =
Initial Release.

== More Information ==
We want this to be a succesful plugin, so please let us know if you have problems, issues, thoughts or concerns.

If you need a custom version, let us know and we will see what we can do for you.


