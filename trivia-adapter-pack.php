<?php

/*
Plugin Name: Trivia Adapter Pack
Plugin URI: https://github.com/MarkoBakac/trivia-adapter-pack
Description: This plugin adds a package for Convoworks and allows us to make a voice trivia quiz game for a QuizCat, Quiz Maker or LifterLMS quiz.
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
