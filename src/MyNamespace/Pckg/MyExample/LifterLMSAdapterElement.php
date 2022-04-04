<?php

declare (strict_types=1);
namespace MyNamespace\Pckg\MyExample;


use Convo\Core\Workflow\IConvoRequest;
use Convo\Core\Workflow\IConvoResponse;
class LifterLMSAdapterElement extends \Convo\Core\Workflow\AbstractWorkflowContainerComponent implements \Convo\Core\Workflow\IConversationElement
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
            'post_type' => 'llms_quiz',
            'ID' => $quizId,
            'post_status' => get_post_stati()
        ]);

        $quiz = $quiz_query->posts[0];

        $formatted_quiz = [
            'title' => $quiz->post_title,
            'questions' => []
        ];

        $question_query = new \WP_Query([
            'post_type' => 'llms_question',
            'posts_per_page' => -1,
            'post_status' => get_post_stati(),
            'meta_query' => [
                ['key' => '_llms_parent_id', 'value' => $quiz->ID]
            ]
        ]);

        $this->_logger->debug(\print_r($question_query, \true));

        $questions = $question_query->posts;

        foreach ($questions as $question) {
            $letter_index = 0;
            $formatted_question = [
                'text' => $question->post_title,
                'answers' => [],
                'correct_answer'=>[]
            ];

            $meta = get_post_meta($question->ID);

            foreach ($meta as $meta_key => $meta_value) {
                if (strpos($meta_key, '_choice_') === false) continue;

                $answer = maybe_unserialize($meta_value[0]);

                $formatted_answer = [
                    'text' => $answer['choice'],
                    'is_correct' => $answer['correct'],
                    'letter' => self::LETTERS[$letter_index++ % \count(self::LETTERS)]
                ];

                $formatted_question['answers'][] = $formatted_answer;

                if ($formatted_answer['is_correct']) {
                    $formatted_question['correct_answer'] = $formatted_answer;
                }
            }

            $formatted_quiz['questions'][] = $formatted_question;
        }
        $this->_logger->debug(\print_r($formatted_quiz, \true));

        return $formatted_quiz;
    }
}

