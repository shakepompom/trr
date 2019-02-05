<?php

namespace App\Service;

use App\Entity\QuizResult;
use App\Entity\User;

class RatingCalculator
{
    const SCORE_FOR_LEARNT_RULE = 1;

    public function registerResult(User $user, QuizResult $quizResult): void
    {
        if ($quizResult->isSuccessful()) {
            $user->setRating($user->getRating() + self::SCORE_FOR_LEARNT_RULE);
        }
    }
}
