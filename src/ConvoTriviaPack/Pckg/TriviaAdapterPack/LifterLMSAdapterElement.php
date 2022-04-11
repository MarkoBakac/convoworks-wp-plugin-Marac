<?php

declare (strict_types=1);
namespace ConvoTriviaPack\Pckg\TriviaAdapterPack;

class LifterLMSAdapterElement extends AbstractRead  implements \Convo\Core\Workflow\IConversationElement
{
    protected function _getQuestions($quizId)
    {
        $thequiz = new \LLMS_Quiz($quizId);
        $lqm = new \LLMS_Question_Manager($thequiz);
        $questions=$lqm->get_questions();

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

            $formatted_quiz[] = ['question' => $formatted_question['question'], 'answers' => $formatted_question['answers'],'correct'=>$correct, 'correct_answer' => $correct_answer];
        }

        return $formatted_quiz;
    }

}

