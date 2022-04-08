<?php

declare (strict_types=1);
namespace ConvoTriviaPack\Pckg\TriviaAdapterPack;

use Convo\Core\Workflow\IConvoRequest;
use Convo\Core\Workflow\IConvoResponse;
class LifterLMSAdapterElement extends \Convo\Core\Workflow\AbstractWorkflowContainerComponent implements \Convo\Core\Workflow\IConversationElement
{
    private $_quizId;
    private $_scopeType;
    private $_scopeName;

    public function __construct($properties)
    {
        parent::__construct($properties);
        $this->_quizId = $properties['quiz_id'];
        $this->_scopeType = $properties['scope_type'];
        $this->_scopeName = $properties['scope_name'];
    }

    public function read(IConvoRequest $request, IConvoResponse $response)
    {
        $quiz_id = $this->evaluateString($this->_quizId);
        $questions = $this->_getQuestions($quiz_id);
        $this->_logger->info('Got questions [' . \print_r($questions, \true) . ']');
        $scope_type = $this->evaluateString($this->_scopeType);
        $scope_name = $this->evaluateString($this->_scopeName);
        $params = $this->getService()->getServiceParams($scope_type);
        $params->setServiceParam($scope_name, $questions);
    }

    private function _getQuestions($quizId)
    {
        $thequiz = new \LLMS_Quiz($quizId);
        $lqm = new \LLMS_Question_Manager($thequiz);
        $questions=$lqm->get_questions();

        $this->_logger->debug(\print_r($thequiz, \true));

        $this->_logger->debug(\print_r($questions, \true));

        $formatted_quiz = [];

        foreach ($questions as $question) {
            $formatted_question = [
                'question' => $question->title,
                'answers' => [],
            ];

            foreach ($question->get_choices() as $choice) {
                $formatted_answer = [
                    'answer' => $choice->get('choice'),
                    'letter' => $choice->get('marker'),
                    'is_correct' => $choice->get('correct')
                ];
                $formatted_question['answers'][] = $formatted_answer;

                if ($formatted_answer['is_correct']) {
                    $correct = $formatted_answer['letter'];
                    $correct_answer = $formatted_answer['answer'];
                }

            }

            $this->_logger->info('Question choices [' . print_r($question->get_choices(), true) . ']');

            $formatted_quiz[] = ['question' => $formatted_question['question'], 'answers' => $formatted_question['answers'],'correct'=>$correct, 'correct_answer' => $correct_answer];
        }

        $this->_logger->debug(\print_r($formatted_quiz, \true));

        return $formatted_quiz;
    }

}

