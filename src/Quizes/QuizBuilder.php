<?php

namespace App\Quizes;

use App\Entity\Quiz;
use App\Entity\Rule;
use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;

class QuizBuilder
{
    const QUIZ_SIZES = [
        Quiz::TYPE_EXAM => 5,
        Quiz::TYPE_PRACTICE => 5,
    ];

    /** @var TaskRepository */
    private $repository;

    public function __construct(TaskRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(User $user, Rule $rule, string $quizType): Quiz
    {
        $quiz = new Quiz();
        $quiz->setType($quizType);
        $quiz->setRule($rule);
        $quiz->setUser($user);

        /** @var Task $task */
        foreach ($this->collectTasks($rule, self::QUIZ_SIZES[$quizType]) as $task) {
            $quiz->addTask($task);
        }

        return $quiz;
    }

    /**
     * @param Rule $rule
     * @param int $limit
     * @return Task[]
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function collectTasks(Rule $rule, int $limit): array
    {
        $count = $this->repository->createQueryBuilder('t')
            ->select(['COUNT(t) as cnt'])
            ->where('t.rule = :rule')
            ->setParameter('rule', $rule)
            ->getQuery()
            ->getSingleScalarResult();

        if (!$count) {
            return [];
        }

        /*
         * TODO: implement good & optimized random picking up.
         */
        $offsets = range(0, $count - 1);
        shuffle($offsets);

        $tasks = [];
        for ($i = 0; $i < $limit; $i++) {
            $task = $this->repository->findBy(['rule' => $rule], ['id' => 'ASC'], 1, array_shift($offsets));
            $tasks[] = $task[0] ?? $task;
        }

        return $tasks;
    }
}