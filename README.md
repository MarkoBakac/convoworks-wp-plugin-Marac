# Convoworks WP Plugin Package
> This plugin template provides a package of 3 Quiz Adapter elements, intents, entities, functions and a template which extends the existing Convoworks WP Plugin.

## Table of Contents
* [General Info](#general-information)
* [Setup](#setup)
* [Usage](#usage)
* [Additional Information](#additional-information)


## General Information
- Provides a package of 3 Quiz Adapter elements, intents, entities, functions and a template
- Extends the existing Convoworks WP Plugin
- The purpose of this package is to use other plugin quizzes on the existing Convoworks WP Plugin


## Setup
There are 2 ways to setup this plugin:
1. You can manually download this package here and just paste it into your `wp_content/plugins` folder
2. You can download this package as a zip file and upload the plugin through your Wordpress site


## Usage
1. To use it on the Convoworks WP plugin, we simply just press configure packages when we make a new service and select the name of the plugin package, in this example: `act-example`

![Act screenshot](./img/conf.jpg)

![Act screenshot](./img/conf_package.jpg)

2. When the package is turned on in the configure packages menu then we can see, additionaly to the `convoworks-core` package, there is the `act-example` package

![Act screenshot](./img/usage.jpg)


## Additional Information
You can also add additional functionality to convoworks from your existing plugin or theme by registering your own package like this:
```php
function my_package_register($packageProviderFactory, $container) {
    $packageProviderFactory->registerPackage(
        new Convo\Core\Factory\FunctionPackageDescriptor(
            MyExamplePackageDefinition::class,
            function() use ( $container) {
                return new \MyNamespace\Pckg\MyExample\MyExamplePackageDefinition( $container->get('logger'));
            }));
}
add_action( 'register_convoworks_package', 'my_package_register', 10, 2);
```

