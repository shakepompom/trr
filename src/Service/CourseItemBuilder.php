<?php

namespace App\Service;

use App\Entity\CourseItem;
use App\Entity\Quiz;
use App\Entity\User;
use App\Quizes\QuizBuilder;
use App\Repository\CourseItemRepository;
use App\Repository\FunFactRepository;
use App\Repository\RuleRepository;

class CourseItemBuilder
{
    /** @var CourseItemRepository */
    private $repository;

    /** @var RuleRepository */
    private $ruleRepository;

    /** @var FunFactRepository */
    private $funFactRepository;

    /** @var QuizBuilder */
    private $quizBuilder;

    public function __construct(
        CourseItemRepository $repository,
        RuleRepository $ruleRepository,
        FunFactRepository $factRepository,
        QuizBuilder $quizBuilder
    ) {
        $this->repository = $repository;
        $this->ruleRepository = $ruleRepository;
        $this->funFactRepository = $factRepository;
        $this->quizBuilder = $quizBuilder;
    }

    public function get(User $user): CourseItem
    {
        if ($courseItem = $this->findLastNotFinished($user)) {
            return $courseItem;
        }

        $courseItem = $this->create($user);

        return $courseItem;
    }

    public function create(User $user): CourseItem
    {
        $rule = $this->ruleRepository->find($user->pickUpNextRuleFromThePath());
        $this->repository->createQueryBuilder('r')->getEntityManager()->persist($user);

        $courseItem = new CourseItem();
        $courseItem->setUser($user);
        $courseItem->setExam($this->quizBuilder->create($user, $rule, Quiz::TYPE_EXAM));
        $courseItem->setPractice($this->quizBuilder->create($user, $rule, Quiz::TYPE_PRACTICE));
        $courseItem->setRule($rule);
        $courseItem->setFact($this->funFactRepository->findOneRandom());
        $this->repository->save($courseItem);

        return $courseItem;
    }

    public function createNewPractice(User $user): CourseItem
    {
        $courseItem = $this->get($user);

        $courseItem->setPractice($this->quizBuilder->create($user, $courseItem->getRule(), Quiz::TYPE_PRACTICE));
        $this->repository->save($courseItem);

        return $courseItem;
    }

    private function findLastNotFinished($user): ? CourseItem
    {
        $courseItem = $this->repository->findOneBy([
            'user' => $user,
        ], [
            'updatedAt' => 'DESC',
        ]);

        if (!$courseItem) {
            return null;
        }

        return $courseItem;
    }
}
