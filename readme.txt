=== Micro.blog Importer ===
Contributors: Michal Lichwa
Tags: rss, feeds, import, micro.blog, rss-feed, feed, rss
Requires at least: 3.5
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Micro.blog Importer


== Description ==
**The Micro.blog Importer plugin fetchs an RSS feed and publishes the full article content of each Feed Item as stand-alone post.**
I used https://github.com/wp-plugins/rss-post-importer/ as a base for this project. The rrs-post-importer is a great tool, but my goal
was to create something that would be targeted specifically for Micro.Blog platform. 

In order to not duplicate the work of authors of RRS Post Importer, I decided to remove most of the paid features they provide and 
I will restrict this plugin to work only with Micro.blog. 


**Features include:**

* Importing feeds automatically using cron.
* Importing the full text rss feeds content.
* Display the full content of the articles.
* Chose to only display the titles of posts.
* Set number of posts and category per feed.
* Set what author to assign imported content to.
* Simple template for formatting imported content.
* Append prefilled HTML code or text to each published Post. 
* Append the no-follow tag to all outbond links for **better SEO.**
* Idiot-proof Templating system allowing you to add backlinks and excerpts.
* Block search indexing to prevent your content from appearing in search results.
* **Advanced Statistics**: Piechart for feeds items distribution , Bar charts for posts and much more advanced charts



== Installation ==


1. Upload the files to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Set up what feeds to import and when!


== Frequently Asked Questions ==


== Screenshots ==




== Change Log ==

= Version 1.0.0 =
 * RSS reader ported from https://github.com/wp-plugins/rss-post-importer/


