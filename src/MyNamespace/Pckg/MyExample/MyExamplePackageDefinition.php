<?php declare(strict_types=1);

namespace MyNamespace\Pckg\MyExample;

use Convo\Core\Factory\AbstractPackageDefinition;
use Convo\Core\Intent\EntityModel;
use Convo\Core\Intent\SystemEntity;
use Convo\Core\Expression\ExpressionFunction;

class MyExamplePackageDefinition extends AbstractPackageDefinition
{
	const NAMESPACE	=	'act-example';

	public function __construct(
		\Psr\Log\LoggerInterface $logger
	) {
		parent::__construct( $logger, self::NAMESPACE, __DIR__);

		$this->addTemplate( $this->_loadFile( __DIR__ .'/my-example.template.json'));
	}

	protected function _initEntities()
	{
		$entities = [];
		$entities['Direction'] = new SystemEntity('Direction');
		$entity_name_model = new EntityModel('Direction', false);
		$entity_name_model->load([
			"name" => "Direction",
			"values" => [
				[
					"value" => "left",
				],
				[
					"value" => "right",
				],
				[
					"value" => "forward",
				],
				[
					"value" => "backward",
				]
			]
		]);
		$entities['Direction']->setPlatformModel('amazon', $entity_name_model);
		$entities['Direction']->setPlatformModel('dialogflow', $entity_name_model);

		return $entities;
	}

	protected function _initIntents()
	{
		return $this->_loadIntents(__DIR__.'/system-intents.json');
	}

	public function getFunctions()
	{
		$functions = [];

		$functions[] = new ExpressionFunction(
			'my_example_function',
			function ($text) {
				return sprintf('my_example_function(%1$t)', $text);
			},
			function($args, $text) {
				if (is_string($text)) {
					return $text . ' adding example text';
				}
				return 'This is some example text, since you failed to provide an string.';
			}
		);

		return $functions;
	}

	protected function _initDefintions()
	{
		return array (
			new \Convo\Core\Factory\ComponentDefinition(
				$this->getNamespace(),
				'\MyNamespace\Pckg\MyExample\MyExampleElement',
				'My Example Element',
				'Changing this',
				[
					'direction' => [
						'editor_type' => 'text',
						'editor_properties' => [],
						'defaultValue' => null,
						'name' => 'Direction',
						'description' => 'Direction where to go.',
						'valueType' => 'string'
					],
					'status_var' => array(
						'editor_type' => 'text',
						'editor_properties' => array(),
						'defaultValue' => 'status',
						'name' => 'Status variable',
						'description' => 'Variable name for accessing element status variable information, such as the current index and available directions.',
						'valueType' => 'string'
					),
					'direction_options' => [
						'editor_type' => 'params',
						'editor_properties' => [
							'multiple' => true
						],
						'defaultValue' => array(),
						'name' => 'Direction Options',
						'description' => 'Set of directions where the text game progresses.',
						'valueType' => 'array'
					],
					'go' => [
						'editor_type' => 'service_components',
						'editor_properties' => [
							'allow_interfaces' => ['\Convo\Core\Workflow\IConversationElement'],
							'multiple' => true
						],
						'defaultValue' => [],
						'defaultOpen' => false,
						'name' => 'Going',
						'description' => 'Flow to be executed if can go in certain direction.',
						'valueType' => 'class'
					],
					'can_not_go' => [
						'editor_type' => 'service_components',
						'editor_properties' => [
							'allow_interfaces' => ['\Convo\Core\Workflow\IConversationElement'],
							'multiple' => true
						],
						'defaultValue' => [],
						'defaultOpen' => false,
						'name' => 'Can not go',
						'description' => 'Flow to be executed if can not go in certain direction.',
						'valueType' => 'class'
					],
					'_preview_angular' => [
						'type' => 'html',
						'template' => '<div class="code">Example Element <br>' .
							' <span ng-if="!component.properties[\'_use_var_properties\']" ng-repeat="(key, val) in component.properties.direction_options track by key">' .
							' <b>{{ key}}</b> = <b>{{ val }};</b><br>' .
							' </span>' .
							'<span ng-if="component.properties[\'_use_var_properties\']">{{ component.properties.properties }}</span>' .
							'</div>'
					],
					'_interface' => '\Convo\Core\Workflow\IServiceContext',
					'_workflow' => 'read',
					'_help' =>  [
						'type' => 'file',
						'filename' => 'my-example-element.html'
					],
				]
			),

            new \Convo\Core\Factory\ComponentDefinition(
                $this->getNamespace(),
                '\\MyNamespace\\Pckg\\MyExample\\SmthgWrongElement',
                'Something went wrong Element',
                'Changing this',
                [
                    'direction' => [
                        'editor_type' => 'text',
                        'editor_properties' => [],
                        'defaultValue' => null,
                        'name' => 'Direction',
                        'description' => 'Direction where to go.',
                        'valueType' => 'string'
                    ],
                    'status_var' => array(
                        'editor_type' => 'text',
                        'editor_properties' => array(),
                        'defaultValue' => 'status',
                        'name' => 'Status variable',
                        'description' => 'Variable name for accessing element status variable information, such as the current index and available directions.',
                        'valueType' => 'string'
                    ),
                    'direction_options' => [
                        'editor_type' => 'params',
                        'editor_properties' => [
                            'multiple' => true
                        ],
                        'defaultValue' => array(),
                        'name' => 'Direction Options',
                        'description' => 'Set of directions where the text game progresses.',
                        'valueType' => 'array'
                    ],
                    'go' => [
                        'editor_type' => 'service_components',
                        'editor_properties' => [
                            'allow_interfaces' => ['\Convo\Core\Workflow\IConversationElement'],
                            'multiple' => true
                        ],
                        'defaultValue' => [],
                        'defaultOpen' => false,
                        'name' => 'Going',
                        'description' => 'Flow to be executed if can go in certain direction.',
                        'valueType' => 'class'
                    ],
                    'can_not_go' => [
                        'editor_type' => 'service_components',
                        'editor_properties' => [
                            'allow_interfaces' => ['\Convo\Core\Workflow\IConversationElement'],
                            'multiple' => true
                        ],
                        'defaultValue' => [],
                        'defaultOpen' => false,
                        'name' => 'Can not go',
                        'description' => 'Flow to be executed if can not go in certain direction.',
                        'valueType' => 'class'
                    ],
                    '_preview_angular' => [
                        'type' => 'html',
                        'template' => '<div class="code">Example Element <br>' .
                            ' <span ng-if="!component.properties[\'_use_var_properties\']" ng-repeat="(key, val) in component.properties.direction_options track by key">' .
                            ' <b>{{ key}}</b> = <b>{{ val }};</b><br>' .
                            ' </span>' .
                            '<span ng-if="component.properties[\'_use_var_properties\']">{{ component.properties.properties }}</span>' .
                            '</div>'
                    ],
                    '_interface' => '\Convo\Core\Workflow\IServiceContext',
                    '_workflow' => 'read',
                    '_help' =>  [
                        'type' => 'file',
                        'filename' => 'my-example-element.html'
                    ],
                ]
            ),


            new \Convo\Core\Factory\ComponentDefinition($this->getNamespace(), '\\Convo\\Pckg\\Core\\Elements\\TextResponseElement', 'Something', 'Present the user with a "Something" response.',
                array('type' => array('editor_type' => 'select', 'editor_properties' => array('options' => array('default' => 'Default', 'reprompt' => 'Reprompt', 'both' => 'Both')),
                    'defaultValue' => 'default', 'name' => 'Type', 'description' => 'Type of response. "Default" is a standard message. "Reprompt" is what is said after some period of no user input.',
                    'valueType' => 'string'), 'text' => array('editor_type' => 'ssml', 'editor_properties' => array(), 'defaultValue' => 'Something went wrong', 'name' => 'Text', 'description' => 'The message you wish to present.',
                    'valueType' => 'string'), 'append' => array('editor_type' => 'boolean', 'editor_properties' => array(), 'defaultValue' => \false, 'name' => 'Append', 'description' => 'If true, text will be appended to the preceding sentence (if any) instead of creating a new one.',
                    'valueType' => 'boolean'), 'alexa_domain' => array('editor_type' => 'select', 'editor_properties' => array('options' => array('normal' => 'Normal', 'conversational' => 'Conversational', 'long-form' => 'Long Form', 'music' => 'Music', 'news' => 'News')),
                    'defaultValue' => 'normal', 'name' => 'Alexa Domain', 'description' => 'Change the speech style for Amazon Alexa.', 'valueType' => 'string'), 'alexa_emotion' => array('editor_type' => 'select',
                    'editor_properties' => array('options' => array('neutral' => 'Neutral', 'excited' => 'Excited', 'disappointed' => 'Disappointed')), 'defaultValue' => 'neutral', 'name' => 'Alexa Emotion',
                    'description' => 'Emotion of spoken text by Alexa.', 'valueType' => 'string'), 'alexa_emotion_intensity' => array('editor_type' => 'select', 'editor_properties' => array('options' => array('low' => 'Low', 'medium' => 'Medium', 'high' => 'High')),
                    'defaultValue' => 'medium', 'name' => 'Alexa Emotion Intensity', 'description' => 'Emotion intensity of spoken text by Alexa.', 'valueType' => 'string'),
                    '_preview_angular' => array('type' => 'html', 'template' => '<div class="we-say">' . '<div ng-if="component.properties.type != \'both\'"> {{ component.properties.type == \'default\' ? \'Say:\' : \'Repeat:\' }} <span class="we-say-text">{{component.properties.text}}</span>
                    </div>' . '<div ng-if="component.properties.type == \'both\'"> {{ \'Say and Repeat:\' }} <span class="we-say-text">{{component.properties.text}}</span> </div>' . '</div>'), '_help' => array('type' => 'file', 'filename' => 'text-response-element.html'), '_workflow' => 'read'))

        );

	}
}
