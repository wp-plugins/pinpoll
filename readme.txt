=== Pinpoll Widget ===
Contributors: Tobias Oberascher
Tags: opinion, analytics, profiling, dashboard, cockpit, feedback, free
Requires at least: 3.0
Tested up to: 4.1.1
Stable tag: 2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create polls. Embed polls. Know your audience and customers. It's free!

== Description ==
This widget displays polls from pinpoll.com. Create your own polls or choose from more than 3.000 existing polls from over 20 categories, one of which will perfectly match the contents of you blog. Show specific or random polls and set a minimum sample size. You may also limit the number of polls and apply your custom look and feel. You can even show the results on a map.

It’s free to use! 

Want to get paid for publishing? Please get in touch: office@pinpoll.com

== Installation ==
1. Download and unzip pinpoll.zip 
2. Upload the folder containing "pinpoll-widget.php" to the "/wp-content/plugins/" directory
3. Activate the plug-in through the "Plugins" menu in WordPress
4. To add a poll to a sidebar, browse to "Appearance > Widgets" and drag "Pinpoll" to desired sidebar. Configure the parameters such as the ID for specific polls and boards or the Minimum Answers for random polls and save your changes. You may as well select your preferred category, limit the number of polls, apply your own look & feel and decide whether to show a poll's description or the results on a map. Language will be detected automatically.
5. To add polls to any post we suggest to use the Code Generator ("Settings > Pinpoll") to simply copy the generated code and paste it to your post. Experienced users may simply use and adjust this snippet [pinpoll]service_url,width,height[/pinpoll]

Some examples:
[pinpoll]https://pinpoll.com/plugin/getPoll/?id=3480[/pinpoll] 
[pinpoll]https://pinpoll.com/plugin/getPoll/?category_id=23&popular_min=100&limit=20&width=400&height=250,400,250[/pinpoll]. 
[pinpoll]https://pinpoll.com/plugin/getPoll/?board_id=202&width=400&height=400&description=0,400,400[/pinpoll]. 
[pinpoll]https://pinpoll.com/plugin/getPoll/?id=3480&colour=ff3366&map=0,500,500[/pinpoll] 

Please ensure that the URL complies with this format to avoid problems with our webservice.

== Frequently Asked Questions ==

= How much does this service cost? =
It’s absolutely free of charge to create your own polls or embed polls to your blog - and always will be!

= How is the widget detecting the language? =
Pinpoll uses intelligent algorithms to detect your browser's preferred language - you don't have to do anything here.

= How can I request more features? =
Just drop us a line: support@pinpoll.com - we appreciate any new ideas to make Pinpoll become the most engaging tool to create and embed polls.

== Screenshots ==

1. Pinpoll WordPress Widget - it's free!
2. Receive updates in your personal dashboard.
3. Infer deep insights of your audience from the current result.
4. Screenshot of an unanswered poll on NYT.
5. Screenshot of an answered poll on LinkedIn.
6. Activated widget in the plug-in section of WordPress.
7. Widget dragged to sidebar of the active theme, using a specific poll to be displayed.
8. Widget dragged to sidebar of the active theme, using random polls from a specific category to be displayed.
9. Settings of the Code Generator and code to be copied from a textfield.
10. Code snippet included in a post.
11. Screenshot of polls displayed in a post and the sidebar.

== Changelog ==

= 1.0 =
* Initial public release.

= 1.0.1 =
* Tested an upgraded for 3.8.2 compatibility

= 1.0.2 =
* Tested an upgraded for 3.9+ compatibility

= 1.0.3 =
* Changed default values to facilitate first time integration

= 1.1 =
* Added full SSL support to make calls more secure 

= 2.0 =
* New layout and customisation options 

= 2.1 =
* Removed max answers field, added eternal slider, faster (ad-hoc) loading

= 2.2 =
* New base domain is pinpoll.com

= 2.3 =
* Added new configuration options

== Upgrade Notice ==
* This is the very first version, we keep improving the widget on a regular basis - stay tuned!
* Please report any bugs to support@pinpoll.com