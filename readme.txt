=== RSS Feed Podcast by Categories ===
Requires at least: 2.5
Requires PHP: 5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Contributors: carlsansfa
Tags: podcast, rss, category, feed
Tested up to: 5.4.1
Stable tag: 1.1

== Description ==
This plugin enable you to display a specific podcast RSS feed in categories displayed using accordions using shortcode.
I'm using Blubrry PowerPress for my podcast page. Having no way to display my podcast by category, I decided the create this plugin.
Note that you are responsible for whatever content you import on your website using this plugin.
This plugin use the open source solution AmplitudeJs to read the audio files :
https://521dimensions.com/open-source/amplitudejs
It was really easy to use and reliable so thank you very much.

== Installation ==
Add the following shortcode to your page : [rssreader rssurl=&quot;http://yourrssfeedadress.com/yourfeed.rss&quot;] using your rss feed address in the rssurl parameter.
If using Blubrry PowerPress :
When creating an article, specify a category in the menu at your right. If you do not, all your podcast will go in the same folder named "Uncategorized".
If not using Blubrry PowerPress :
Insure that each item has a category mark-up and an url specified in an enclosure mark-up. Example :
[RSS code Example](http://moduloinfo.ca/wordpress/rss-feed-podcast-by-categories-xml-feed-example/)

== Changelog ==

= 1.1 =
You can now open the podcast index you want using the podcast argument ex. : myaddress.com/mypodcastpage?podcast=4
You can now open the podcast category you want using the category argument ex. : myaddress.com/mypodcastpage?category=2
You can use both argument at the same time ex.: myaddress.com/mypodcastpage?category=2&podcast4

== Screenshots ==

1. How the plugin looks like with one category open and hovering over a podcast. Note that the content language depends entirely on your RSS feed.
