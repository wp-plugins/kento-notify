=== Kento Notify ===
Contributors: kentothemes
Donate link: 
Tags:  wordpress notification, comments notifier, notifier, wp notification
Requires at least: 3.5
Tested up to: 4.2.2
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Notification bubble for wordpress comments on post


== Description ==

Get notified by bubble like facebook if some one posted comment on post.

Live Preview: http://kentothemes.com/demo/kento-notify/


<strong>Plugin Features</strong>

* Notification Bubble.
* Read/Unread marker.
* loggedin user can Save Read/Unread marker to database.
* Gravatar image of commenter.
* Comment content on hover notification.


<strong>How Its Work ?</strong>

we use wp.comments table to fetch approved comments and display in notification list and get current time on visitors(clients) computer to calculate difference comment time and current time.

and if user logged-in then set a background color to single notification to mark as unread and when user click on single notification then it will change color to white and mark as read will calculate notification bubble total current notification.

if user not logged-in then won't save data to database and will not calculate current bubble total notification.





== Installation ==

1. Install as regular WordPress plugin.
2. Go your Pluings setting via WordPress Dashboard and activate it.
3. Paste code anywhere in your theme files `<?php kento_notify(); ?>` (usually paste on header.php will display all page)
3. This Plugin Doesn't have any setting page.
4. By visiting your site see the result.


== Screenshots ==
1. Comments on hover.
2. Notification bubble.
3. Zero notification.
4. Unread Marker.
5. Scroll Notification.
6. Read Marker.


== Changelog ==

= 1.0 =
* Initial release

