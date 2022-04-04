<?php

declare (strict_types=1);
namespace MyNamespace\Pckg\MyExample;

use Convo\Core\Workflow\IConvoRequest;
use Convo\Core\Workflow\IConvoResponse;
class QuizMakerElement extends \Convo\Core\Workflow\AbstractWorkflowContainerComponent implements \Convo\Core\Workflow\IConversationElement
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
        $quiz_id = \intval($quizId);
        $quiz = $this->_wpdb->get_results($this->_wpdb->prepare("SELECT * FROM {$this->_wpdb->prefix}aysquiz_quizes WHERE id=%d", $quiz_id), 'ARRAY_A');

        $this->_logger->debug(\print_r($quiz, \true));

        $question_id = $quiz[0]['question_ids'];

        $this->_logger->debug(\print_r($question_id, \true));

        $question_str = ( explode( ',', $question_id) );

        $this->_logger->debug(\print_r($question_str, \true));

        foreach ($question_str as $q) {

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

