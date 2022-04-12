<?php

declare (strict_types=1);
namespace ConvoTriviaPack\Pckg\TriviaAdapterPack;

class QuizMakerAdapterElement extends AbstractQuestionsAdapterElement
{
    private $_wpdb;

    public function __construct($properties, $wpdb)
    {
        parent::__construct($properties);
        $this->_wpdb = $wpdb;

    }

    protected function _getQuestions($quizId)
    {
        $ays_questions = [];

        $qmp = new \Quiz_Maker_Public('Quiz Maker', AYS_QUIZ_VERSION);
        $quiz = $qmp->get_quiz_by_id($quizId);

        $this->_logger->info(\print_r($quiz, \true));

        $question_arr = $qmp->get_quiz_questions_count($quizId);

        $this->_logger->debug(\print_r($question_arr, \true));

        foreach ($question_arr as $q) {

            $questions = $this->_wpdb->get_results($this->_wpdb->prepare("SELECT * FROM {$this->_wpdb->prefix}aysquiz_questions WHERE id=%d", $q), 'ARRAY_A');

            $this->_logger->info(\print_r($questions, \true));


            foreach ($questions as $question) {
                $ays_answers = [];
                $correct = [];

                $answers = $this->_wpdb->get_results($this->_wpdb->prepare("SELECT * FROM {$this->_wpdb->prefix}aysquiz_answers WHERE question_id=%d", $q), 'ARRAY_A');

                $this->_logger->info(\print_r($answers, \true));

                if (!$answers || !\is_array($answers) || empty($answers) || \count($answers) === 0) {
                    $this->_logger->info('Question has no answers. Skipping.');
                    continue;
                }
                $this->_logger->debug(\print_r($answers, \true));

                foreach ($answers as $i => $answer) {

                    $ays_answers[] = ['answer' => $answer['answer'], 'letter' => self::LETTERS[$i % \count(self::LETTERS)], 'is_correct' => $answer['correct']];
                    if ($ays_answers[$i]['is_correct']) {
                        $correct = $ays_answers[$i]['letter'];
                        $correct_answer = $ays_answers[$i]['answer'];
                    }

                }


                $ays_questions[] = ['question' => $question['question'], 'answers' => $ays_answers,'correct'=>$correct, 'correct_answer' => $correct_answer];

            }
        }
        return $ays_questions;
    }
}

