<?php

declare (strict_types=1);
namespace MyNamespace\Pckg\MyExample;


use Convo\Core\Workflow\IConvoRequest;
use Convo\Core\Workflow\IConvoResponse;
class QuizCatAdapterElement extends \Convo\Core\Workflow\AbstractWorkflowContainerComponent implements \Convo\Core\Workflow\IConversationElement
{
    private $_quizId;
    private $_scopeType;
    private $_scopeName;
    const LETTERS = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

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
        $quiz_query = new \WP_Query([
            'post_type' => 'fca_qc_quiz',
            'ID' => $quizId,
            'post_status' => get_post_stati()
        ]);

        $quiz = $quiz_query->posts[0];

        $formatted_quiz = [
            'title' => $quiz->post_title,
            'questions' => []
        ];

        $meta = get_post_meta($quizId, "quiz_cat_questions");
        $questions = maybe_unserialize($meta);


        foreach ($questions[0] as $question) {
            $question['answers'][0]['is_correct'] = true;
            shuffle($question['answers']);

            $formatted_question = [
                'text' => $question['question'],
                'answers' => [],
                'correct_answer' => [],
            ];

            $i=0;
            foreach ($question['answers'] as $index=>$answer) {
                $formatted_answer = [
                    'text' => $answer['answer'],
                    'letter' => self::LETTERS[$index++ % \count(self::LETTERS)],
                    'is_correct'=> $answer['is_correct'] ?? false
                ];
                $i++;

                if ($formatted_answer['is_correct']) {
                    $formatted_question['correct_answer'] = $formatted_answer;
                }

                $formatted_question['answers'][] = $formatted_answer;
            }



            $formatted_quiz['questions'][] = $formatted_question;

        }
        return $formatted_quiz;
    }
}


