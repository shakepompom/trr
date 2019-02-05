<?php

namespace App\Service;

use App\Entity\QuizResult;
use App\Entity\Rule;
use App\Entity\User;
use App\Repository\CourseItemRepository;
use App\Repository\QuizResultRepository;

class RepetitionMechanism
{
    /** @var CourseItemRepository */
    private $repository;

    public function __construct(QuizResultRepository $repository)
    {
        $this->repository = $repository;
    }

    public function addIntervalRepetition(User $user, Rule $rule, QuizResult $quizResult): void
    {
        $level = $quizResult->isSuccessful() ? 1 : 0;

        $lastExamResults = $this->repository->findLastResults($user, $rule);
        /** @var QuizResult $lastResult */
        for ($i = 0; $i < count($lastExamResults); $i++) {
            $level += $lastExamResults[$i]->isSuccessful() ? 1 : -1;
        }
        $level = $level > 0 ? $level : 0;

        $user->addRuleInThePath($rule->getId(), pow(2, $level) * 3);
    }
}
