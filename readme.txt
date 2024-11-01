=== Shared Users Reloaded ===
Contributors: Sethmatics Inc. Featuring Seth Carstens
Donate link: http://smwphosting.com/extend/
Tags: admin, authentication, login, users, alternative, multi-site
Requires at least: 2.9.2
Tested up to: 3.0.1
Stable tag: 1.0.1

Share Wordpress users and assign roles between multiple blogs without using wordpress MU or multi-site.

== Description ==

When WordPress MU (now WordPress 3.0 MS) is not an option for you for whatever reason, sometimes you just want to "Share Users" between your databases. The reason this plugin was created was because we couldn't install wordpress into the "root directory" of our website, which is a requirement of a multi-site wordpress install regardless of the version.

Example: domain1.com/articles/ and domain2.com/articles/.

We wanted to only maintain 1 set of users similar to multi-site wordpress. This plugin requires NO MODIFICATION of your wp-config file. We took the outdated "Shared Users" plugin and completely overhauled it. With "Reloaded" you now have the following features:

* Option to import, or to NOT import users when moving the blogs users table to another blogs users table (where is Shared Users before it forced all administrators to be copied).
* Ability to manage and maintain all the users security to each blog from the "master blog". So you are no longer limited to only having administrators between the blogs. This is VERY SIMILAR to how you can control user roles in WordPress MU except you have completely seperate wordpress blog installs! This makes it possible and EASY to remove the blog from the Multi-Site type of environment whenever you want.

It took many hours to develop this plugin so if you find it useful, any donations would be greatly appreciated.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload "shared-users-reloaded" folder to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Make sure you activate the plugin on ALL your blogs, and decide if you want to import that blogs administrators into the "master blog's" users table.
4. From the plugin's admin panel, update users with proper role on each blog.


== Frequently Asked Questions ==

FAQ questions, feel free to ask more in the forum.

= Whats the "master blog"? =

The master blog is how we refer to the blog where you will be sharing the users table from. So you will point ALL your blogs users table to the "master blog" using this plugin.

= Do I need to install the plugin on all my domains? =

No, only the domains that you want to share users.

= Can I accidently remove my own admin access? =

Yes, right now you can so be carefull.

= Can I make it so you only have to login once for all domains? =

No cookies do not allow us to combine authentication between the domains.

== Screenshots ==

1. A screenshot of the Admin panel options showing the ability to choose the users table you want to use and the ability to control each users role on each blog (screenshot-1.png).
2. More Screenshots coming soon...

== Changelog ==

= 1.0.1 =
Trying to fix the bad characters input by textpad by using notepad+

= 1.0 =
* Added feature to turn off import of users.
* Added feature to control users security on every blog (assuming you have the plugin on every blog and all the blogs pointed to the same users table with this plugin)

= 0.9 =
* Reworked code from "Shared Users" plugin to be more efficient and work with wordpress 3.0.