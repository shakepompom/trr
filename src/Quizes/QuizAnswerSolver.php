<?php

namespace App\Quizes;

use App\Entity\Quiz;
use App\Entity\QuizResult;
use App\Entity\QuizUserAnswer;
use App\Entity\Task;

class QuizAnswerSolver
{
    public function calculateResult(Quiz $quiz, array $answers): QuizResult
    {
        $quizResult = new QuizResult();
        $quizResult->setTotal($quiz->getTasks()->count());
        $correct = 0;

        /** @var QuizUserAnswer $answer */
        foreach ($answers as $answer) {
            /** @var Task $task */
            foreach ($quiz->getTasks() as $task) {
                if ($task->getId() === $answer->getTask()->getId() && $task->getAnswer() === $answer->getAnswer()) {
                    $correct++;
                }
            }
            $quizResult->addUserAnswer($answer);
        }

        $quizResult->setCorrect($correct);
        $quizResult->setQuiz($quiz);

        return $quizResult;
    }
}