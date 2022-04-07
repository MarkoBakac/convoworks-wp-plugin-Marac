<?php

/*
Plugin Name: Convoworks Wp Trivia Adapter Pack
Plugin URI: https://github.com/zef-dev/convoworks-wp-plugin-package-template
Description: Example Plugin which provides additional features such as additional element, intents, entities, functions, template for Convoworks WP Plugin.
Version: 1.0
Author: Marko
Author URI: https://github.com/zef-dev
License: A "Slug" license name e.g. GPL2
*/

require_once __DIR__.'/vendor/autoload.php';

/**
 * @param Convo\Core\Factory\PackageProviderFactory $packageProviderFactory
 * @param Psr\Container\ContainerInterface $container
 */
function my_package_register($packageProviderFactory, $container) {
	$packageProviderFactory->registerPackage(
		new Convo\Core\Factory\FunctionPackageDescriptor(
			'\ConvoTriviaPack\Pckg\TriviaAdapterPack\TriviaAdapterPackageDefinition',
			function() use ( $container) {
				return new \ConvoTriviaPack\Pckg\TriviaAdapterPack\TriviaAdapterPackageDefinition( $container->get('logger'));
			}));
}
add_action( 'register_convoworks_package', 'my_package_register', 10, 2);
