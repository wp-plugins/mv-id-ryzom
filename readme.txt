=== MV ID: Ryzom ===
Contributors: signpostmarv
Tags: mv-id, MV-ID, Metaverse, ID, hCard, vCard, hResume, Ryzom
Requires at least: 2.8
Tested up to: 2.9.2
Stable tag: 1.0

Use Metaverse-ID to display your identity from Ryzom!

== Description ==
MV-ID: Ryzom is a plugin for the framework provided by the [Metaverse ID plugin](http://wordpress.org/extend/plugins/metaverse-id/), and is the first example of how new Metaverses can be supported without being distributed with the core plugin!


== Installation ==

1. Install [Metaverse ID plugin](http://wordpress.org/extend/plugins/metaverse-id/)
1. Upload the `mv-id-ryzom` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Requirements ==

* [Metaverse ID](http://wordpress.org/extend/plugins/metaverse-id/)
* PHP5 (I'm using features not present in PHP4, WordPress runs fine on PHP5, so upgrade already!)
* SimpleXML
* PHP cURL extension (WordPress HTTP API seems to choke when fetching the remote data in some server configurations)

== Changes ==

1.0
--------------------
* Fixed a bug that occurs when the main MV-ID plugin is deactivated

0.1.1
--------------------
* Added .po file as template for multi-lingual support

0.1
--------------------
* Optimised the UI by using javascript to dynamically add more fields instead of using a fixed list of fields (which would take up more and more space with every metaverse that was added).