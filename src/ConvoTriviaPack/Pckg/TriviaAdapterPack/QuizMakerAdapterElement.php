<?php

declare (strict_types=1);
namespace ConvoTriviaPack\Pckg\TriviaAdapterPack;


use Convo\Core\Workflow\IConvoRequest;
use Convo\Core\Workflow\IConvoResponse;
class QuizMakerAdapterElement extends \Convo\Core\Workflow\AbstractWorkflowContainerComponent implements \Convo\Core\Workflow\IConversationElement
{
    private $_quizId;
    private $_scopeType;
    private $_scopeName;
    private $_wpdb;
    const LETTERS = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
    public function __construct($properties, $wpdb)
    {
        parent::__construct($properties);
        $this->_quizId = $properties['quiz_id'];
        $this->_scopeType = $properties['scope_type'];
        $this->_scopeName = $properties['scope_name'];
        $this->_wpdb = $wpdb;
        set_include_path(
            WP_PLUGIN_DIR.
            PATH_SEPARATOR.
            get_include_path()
        );

        $qm_plugin_dir = WP_PLUGIN_DIR.'/quiz_maker';
        $file_path = \realpath(\str_replace('/', DIRECTORY_SEPARATOR, "$qm_plugin_dir/public/class-quiz-maker-public.php"));

        $this->_logger->info(\print_r($file_path, \true));

    }
    public function read(IConvoRequest $request, IConvoResponse $response)
    {
        $quiz_id = $this->evaluateString($this->_quizId);
        $questions = $this->_loadQuestions($quiz_id);
        $this->_logger->info('Got questions [' . \print_r($questions, \true) . ']');
        $scope_type = $this->evaluateString($this->_scopeType);
        $scope_name = $this->evaluateString($this->_scopeName);
        $params = $this->getService()->getServiceParams($scope_type);
        $params->setServiceParam($scope_name, $questions);
    }
    private function _loadQuestions($quizId)
    {
        $ays_questions = [];

        $qmp = new \Quiz_Maker_Public('Quiz Maker', '6.3.1.7');
        $quiz = $qmp->get_quiz_by_id($quizId);

        $this->_logger->info(\print_r($quiz, \true));

        $question_arr = $qmp->get_quiz_questions_count($quizId);

        $this->_logger->debug(\print_r($question_arr, \true));

        foreach ($question_arr as $q) {

            $questions = $this->_wpdb->get_results($this->_wpdb->prepare("SELECT * FROM {$this->_wpdb->prefix}aysquiz_questions WHERE id=%d", $q), 'ARRAY_A');

            $this->_logger->debug(\print_r($questions, \true));


            foreach ($questions as $question) {
                $ays_answers = [];
                $correct = [];

                $answers = $this->_wpdb->get_results($this->_wpdb->prepare("SELECT * FROM {$this->_wpdb->prefix}aysquiz_answers WHERE question_id=%d", $q), 'ARRAY_A');

                if (!$answers || !\is_array($answers) || empty($answers) || \count($answers) === 0) {
                    $this->_logger->info('Question has no answers. Skipping.');
                    continue;
                }
                $this->_logger->debug(\print_r($answers, \true));

                foreach ($answers as $i => $answer) {

                    $ays_answers[] = ['text' => $answer['answer'], 'letter' => self::LETTERS[$i % \count(self::LETTERS)], 'is_correct' => $answer['correct']];
                    if ($ays_answers[$i]['is_correct']) {
                        $correct = $ays_answers[$i];
                    }

                }


                $ays_questions[] = ['text' => $question['question'], 'answers' => $ays_answers, 'correct_answer' => $correct];

            }
        }
        return $ays_questions;
    }
}

