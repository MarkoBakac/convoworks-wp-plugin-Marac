<?php

declare (strict_types=1);
namespace ConvoTriviaPack\Pckg\TriviaAdapterPack;

class QuizCatAdapterElement extends AbstractQuestionsAdapterElement
{
    protected function _getQuestions($quizId)
    {
        $formatted_quiz = [
        ];

        $meta = get_post_meta($quizId, "quiz_cat_questions");
        $questions = maybe_unserialize($meta);

        foreach ($questions[0] as $question) {
            $question['answers'][0]['is_correct'] = true;
            shuffle($question['answers']);

            $formatted_question = [
                'question' => $question['question'],
                'answers' => [],
            ];


            foreach ($question['answers'] as $index=>$answer) {
                $formatted_answer = [
                    'answer' => $answer['answer'],
                    'letter' => self::LETTERS[$index++ % \count(self::LETTERS)],
                    'is_correct'=> $answer['is_correct'] ?? false
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


