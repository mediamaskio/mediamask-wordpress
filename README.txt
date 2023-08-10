=== Plugin Name ===
Contributors: mediamask
Donate link: http://mediamask.io/
Tags: opgen-graph, image, generation, api
Requires at least: 6.0
Tested up to: 6.3.1
Stable tag: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin makes it easy to setup automatically generated custom open graph images, no coding required!

== Description ==


This plugin allows you to automatically generate open graph images for posts, pages etc. via mediamask.io
Mediamask allows rendering of custom images via predefined templates via APIs and integrations.

This plugin makes it easy to setup automatically generated custom open graph images, no coding required!

== Add API Key ==

1. Register an account at [mediamask.io](https://mediamask.io/) and confirm your e-mail address.
2. Head to the [API Keys Area](https://mediamask.io/team/api-tokens) and create a new API Key for your Wordpress instance.
3. Copy the API Key, paste and save it into the "API Token" Section of the Mediamask Plugin Settings

== Configure Template ==

1. To configure a template in the plugin you need to add it on mediamask first.
You can either choose one of the existing templates or create a new one from scratch.
   Make sure the Template has "REST API" and "Signed URL API" enabled when you create it (those are the default options, so don't worry).
2. After creating the template, you can select it in the mediamask plugin.
Every Dynamic Layer (texts and images) can be configured to be filled with Wordpress Attributes like the_title(), the_excerpt(), the_author() etc.

== Changelog ==

= 1.0 =
* Initial release
