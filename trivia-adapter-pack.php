<?php

/*
Plugin Name: Trivia Adapter Pack
Plugin URI: https://github.com/MarkoBakac/trivia-adapter-pack
Description: Example Plugin which provides additional features such as additional element, intents, entities, functions, template for Convoworks WP Plugin.
Version: 1.0.0
Author: Marko Bakac
Author URI: https://github.com/MarkoBakac
License: A "Slug" license name e.g. GPL3
*/

require_once __DIR__.'/vendor/autoload.php';

/**
 * @param Convo\Core\Factory\PackageProviderFactory $packageProviderFactory
 * @param Psr\Container\ContainerInterface $container
 */
function trivia_pack_register($packageProviderFactory, $container){
	$packageProviderFactory->registerPackage(
		new Convo\Core\Factory\FunctionPackageDescriptor(
			'\ConvoTriviaPack\Pckg\TriviaAdapterPack\TriviaAdapterPackageDefinition',
            function () use ($container) {
                global $wpdb;
                return new \ConvoTriviaPack\Pckg\TriviaAdapterPack\TriviaAdapterPackageDefinition($container->get('logger'), $wpdb);
            }));
}
add_action( 'register_convoworks_package', 'trivia_pack_register', 10, 2);
