# Trivia Adapters for Convoworks
> This plugin adds a package for Convoworks and allows us to make a voice trivia quiz game for a QuizCat, Quiz Maker or LifterLMS quiz

## Table of Contents
* [General Info](#general-information)
* [Setup](#setup)
* [Usage](#usage)
* [Plugin links](#plugin-links)


## General Information
These 3 adapter elements that are included in the package allow you to make a trivia quiz from [QuizCat](https://wordpress.org/plugins/quiz-cat/), [Quiz Maker](https://wordpress.org/plugins/quiz-maker/) or [LifterLMS](https://wordpress.org/plugins/lifterlms/) in a very simple way using convoworks. The plugin adds elements which know how to read the quizzes and then use them in your Trivia game.<br />
Check out more about Convoworks trivia [here](https://convoworks.com/using-quiz-and-survey-master-for-wordpress-or-open-trivia-db-quizzes-for-your-alexa-skill/).

## Installation
To begin with it is necessary to have Convoworks WP plugin installed on your WordPress site.<br />
There are 2 ways to setup this plugin:
1. Upload the zip file of the plugin through your Wordpress site
2. Manually extract the folder inside the zip file into your `wp_content/plugins` folder

You will also need to have composer installed so the plugin package will work. Simply open the Command Prompt and navigate to the directory where the plugin is installed and run `composer install`. When you have done that, the plugin will have full functionality.

## Implementation
1. In the Convoworks WP Services create a new service (e.g. Quiz Maker) with the Mini Film Trivia or Trivia Multiplayer template.
2. To enable the package, we simply just press configure packages when we make a new service and select the name of the plugin package, in this example: `trivia-adapter-pack`

![Act screenshot](./img/conf.jpg)

![Act screenshot](./img/conf_package.jpg)

3When the package is turned on in the configure packages menu then we can see, additionaly to the `convoworks-core` package, there is the `trivia-adapter-pack` package

![Act screenshot](./img/usage.jpg)

4. Under the `Fragments` tab on the right of the screen click on `Load questions` and delete every element inside of it and place an Adapter Element of your choosing.
5. Enter the ID of the plugins quiz you want into the Adapter Element and save everything.

Everything should work now perfectly.

