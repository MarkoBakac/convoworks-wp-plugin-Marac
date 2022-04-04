<?php declare(strict_types=1);

namespace MyNamespace\Pckg\MyExample;

use Convo\Core\Factory\AbstractPackageDefinition;
use Convo\Core\Intent\EntityModel;
use Convo\Core\Intent\SystemEntity;
use Convo\Core\Expression\ExpressionFunction;
use Psr\SimpleCache\CacheInterface;

class MyExamplePackageDefinition extends AbstractPackageDefinition
{
    const NAMESPACE = 'act-example';
    private $_wpdb;

    public function __construct(\Psr\Log\LoggerInterface $logger)

    {
        global $wpdb;

        $this->_wpdb = $wpdb;
        parent::__construct($logger, self::NAMESPACE, __DIR__);

        $this->addTemplate($this->_loadFile(__DIR__ . '/my-example.template.json'));
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
        return $this->_loadIntents(__DIR__ . '/system-intents.json');
    }

    public function getFunctions()
    {
        $functions = [];

        $functions[] = new ExpressionFunction(
            'my_example_function',
            function ($text) {
                return sprintf('my_example_function(%1$t)', $text);
            },
            function ($args, $text) {
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
        return array(

            new \Convo\Core\Factory\ComponentDefinition($this->getNamespace(), '\\MyNamespace\\Pckg\\MyExample\\LifterLMSAdapterElement', 'LifterLMS Adapter Element',
                'Adapt a LifterLMS multiple choice question quiz into a suitable format for Convoworks Trivia', ['quiz_id' => ['editor_type' => 'text', 'editor_properties' => [],
                    'defaultValue' => null, 'name' => 'LifterLMS Quiz ID', 'description' => 'LifterLMS quiz ID to fetch questions for (check the shortcode for the quiz ID)', 'valueType' => 'string'],
                    'scope_type' => ['editor_type' => 'select', 'editor_properties' => ['options' => ['request' => 'Request', 'session' => 'Session', 'installation' => 'Installation', 'user' => 'User']],
                        'defaultValue' => 'session', 'name' => 'Storage type', 'description' => 'Where to store the adapted quiz', 'valueType' => 'string'],
                    'scope_name' => ['editor_type' => 'text', 'editor_properties' => array('multiple' => \false), 'defaultValue' => 'questions', 'name' => 'Name',
                        'description' => 'Name under which to store the quiz', 'valueType' => 'string'], '_preview_angular' => ['type' => 'html',
                        'template' => '<div class="code">' . 'Get questions from LifterLMS quiz [<b>{{ component.properties.quiz_id }}</b>]' . '</div>'],
                    '_workflow' => 'read', '_help' => ['type' => 'file'], '_factory' => new class($this->_wpdb) implements \Convo\Core\Factory\IComponentFactory {
                        private $_wpdb;

                        public function __construct($wpdb)
                        {
                            $this->_wpdb = $wpdb;
                        }

                        public function createComponent($properties, $service)
                        {
                            return new \MyNamespace\Pckg\MyExample\LifterLMSAdapterElement($properties, $this->_wpdb);
                        }
                    }]),

            new \Convo\Core\Factory\ComponentDefinition($this->getNamespace(), '\\MyNamespace\\Pckg\\MyExample\\QuizCatAdapterElement', 'QuizCat Adapter Element',
                'Adapt a QuizCat multiple choice question quiz into a suitable format for Convoworks Trivia', ['quiz_id' => ['editor_type' => 'text', 'editor_properties' => [],
                    'defaultValue' => null, 'name' => 'QuizCat Quiz ID', 'description' => 'QuizCat quiz ID to fetch questions for (check the shortcode for the quiz ID)', 'valueType' => 'string'],
                    'scope_type' => ['editor_type' => 'select', 'editor_properties' => ['options' => ['request' => 'Request', 'session' => 'Session', 'installation' => 'Installation', 'user' => 'User']],
                        'defaultValue' => 'session', 'name' => 'Storage type', 'description' => 'Where to store the adapted quiz', 'valueType' => 'string'],
                    'scope_name' => ['editor_type' => 'text', 'editor_properties' => array('multiple' => \false), 'defaultValue' => 'questions', 'name' => 'Name',
                        'description' => 'Name under which to store the quiz', 'valueType' => 'string'], '_preview_angular' => ['type' => 'html',
                        'template' => '<div class="code">' . 'Get questions from QuizCat quiz [<b>{{ component.properties.quiz_id }}</b>]' . '</div>'],
                    '_workflow' => 'read', '_help' => ['type' => 'file'], '_factory' => new class($this->_wpdb) implements \Convo\Core\Factory\IComponentFactory {
                        private $_wpdb;

                        public function __construct($wpdb)
                        {
                            $this->_wpdb = $wpdb;
                        }

                        public function createComponent($properties, $service)
                        {
                            return new \MyNamespace\Pckg\MyExample\QuizCatAdapterElement($properties, $this->_wpdb);
                        }
                    }]),


            new \Convo\Core\Factory\ComponentDefinition($this->getNamespace(), '\\MyNamespace\\Pckg\\MyExample\\QuizMakerElement', 'Quiz Maker Element',
                'Adapt a Quiz Maker multiple choice question quiz into a suitable format for Convoworks Trivia', ['quiz_id' => ['editor_type' => 'text', 'editor_properties' => [],
                    'defaultValue' => null, 'name' => 'Quiz Maker Quiz ID', 'description' => 'Quiz Maker quiz ID to fetch questions for (check the shortcode for the quiz ID)', 'valueType' => 'string'],
                    'scope_type' => ['editor_type' => 'select', 'editor_properties' => ['options' => ['request' => 'Request', 'session' => 'Session', 'installation' => 'Installation', 'user' => 'User']],
                        'defaultValue' => 'session', 'name' => 'Storage type', 'description' => 'Where to store the adapted quiz', 'valueType' => 'string'],
                    'scope_name' => ['editor_type' => 'text', 'editor_properties' => array('multiple' => \false), 'defaultValue' => 'questions', 'name' => 'Name',
                        'description' => 'Name under which to store the quiz', 'valueType' => 'string'], '_preview_angular' => ['type' => 'html',
                        'template' => '<div class="code">' . 'Get questions from Quiz Maker quiz [<b>{{ component.properties.quiz_id }}</b>]' . '</div>'],
                    '_help' => array('type' => 'file'),'_workflow' => 'read', '_factory' => new class($this->_wpdb) implements \Convo\Core\Factory\IComponentFactory {
                        private $_wpdb;

                        public function __construct($wpdb)
                        {
                            $this->_wpdb = $wpdb;
                        }

                        public function createComponent($properties, $service)
                        {
                            return new \MyNamespace\Pckg\MyExample\QuizMakerElement($properties, $this->_wpdb);
                        }
                    }]),



        );


    }
}
