=== Pinpoll Widget ===
Contributors: Tobias Oberascher
Tags: opinion, analytics, profiling, dashboard, cockpit, feedback, free, poll, survey
Requires at least: 3.0
Tested up to: 4.2.2
Stable tag: 2.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create polls. Embed polls. Know your audience and customers.

== Description ==
This widget embeds polls created on pinpoll.com. Create your own polls or choose from more than 3.000 existing polls from over 20 categories, one of which will perfectly match the topic of you blog. Show specific or random polls and set a minimum sample size. You may also limit the number of polls and apply your custom look and feel. You can even show the results distributed on a map.

Pinpoll is fully responsive and free to use!

You want to get paid for publishing? Please get in touch: office@pinpoll.com

== Installation ==
1. Download and unzip pinpoll.zip
2. Upload the folder containing "pinpoll-widget.php" to the "/wp-content/plugins/" directory.
3. In WordPress, activate the plug-in through the "Plugins" menu in WordPress.
4. Visit https://pinpoll.com/cockpit/ to create and manage your polls and results for free.
5. In WordPress, to add a poll to a sidebar, browse to "Appearance > Widgets" and drag "Pinpoll" to desired sidebar. Configure the parameters such as the ID for specific polls and boards or the minimum answers for random polls and save your changes. You may as well select your preferred category, limit the number of polls, apply your own look & feel and decide whether to show a poll's description or the results on a map. Language will be detected automatically.
6. In WordPress, to add polls to any post we suggest to use the Code Generator ("Settings > Pinpoll") to simply copy the generated code and paste it to your post. Experienced users may simply use and adjust this snippet [pinpoll]service_url,height[/pinpoll]

Some examples:
[pinpoll]https://pinpoll.com/plugin?id=3480[/pinpoll]
[pinpoll]https://pinpoll.com/plugin?category_id=23&popular_min=100&limit=20&height=300,300[/pinpoll].
[pinpoll]https://pinpoll.com/plugin?board_id=202&height=300&description=0,300[/pinpoll].
[pinpoll]https://pinpoll.com/plugin?id=3480&colour=ff3366&map=0,500[/pinpoll]

Please ensure that the URL complies with this format to avoid problems with our webservice.

== Frequently Asked Questions ==

= How much does this service cost? =
It’s absolutely free of charge to create your own polls or embed polls to your blog - and always will be! If you enjoy using Pinpoll though, we'd very much appreciate your review.

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

= 2.4 =
* New tags, descriptions and screenshots

= 2.5 =
* Removed width and updated service URLs

= 2.6 =
* Fixed CSS Bug

== Upgrade Notice ==
* This widget is still in beta and we keep improving it on a regular basis - stay tuned!
* Please report any bugs to support@pinpoll.com
