=== Trivia Adapter Pack ===
Tags: convoworks, trivia, quiz
Requires at least: 5.0
Tested up to: 5.9.3
Requires PHP: 7.2
Stable tag: 1.0.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Provides 3 Quiz Adapter elements (QuizCat,LifterLMS and Quiz Maker) which extend the existing Convoworks WP Plugin.

== Description ==
# Trivia Adapter Pack
> This plugin adds a package for Convoworks and allows us to make a voice trivia quiz game for a QuizCat, Quiz Maker or LifterLMS quiz.

These 3 adapter elements that are included in the package allow you to make a trivia quiz from QuizCat, Quiz Maker or LifterLMS in a very simple way using convoworks. The plugin adds elements which know how to read the quizzes and then use them in your Trivia game.<br />
Check out more about Convoworks trivia on their official site.

== Quiz implementation ==
1. In the Convoworks WP Services create a new service (e.g. Quiz Maker) with the Mini Film Trivia or Trivia Multiplayer template.
2. To enable the package, we simply just press configure packages when we make a new service and select the name of the plugin package, in this example: `trivia-adapter-pack`.
3. When the package is turned on in the configure packages menu then we can see, additionaly to the `convoworks-core` package, there is the `trivia-adapter-pack` package.
4. Under the `Fragments` tab on the right of the screen click on `Load questions` and delete every element inside of it and place an Adapter Element of your choosing.
5. Enter the ID of the plugins quiz you want into the Adapter Element and save everything.

== Installation ==
To begin with it is necessary to have Convoworks WP plugin installed on your WordPress site.<br />
There are 2 ways to setup this plugin:
1. Upload the zip file of the plugin through your Wordpress site
2. Manually extract the folder inside the zip file into your `wp_content/plugins` folder

== Changelog ==
= 1.0.0 =
* The first stable version of the plugin