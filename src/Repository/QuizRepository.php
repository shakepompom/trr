<?php

namespace App\Repository;

use App\Entity\Quiz;
use App\Entity\User;
use App\Service\RatingCalculator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Quiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quiz[]    findAll()
 * @method Quiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    public function getResultsByRules(User $user): array
    {
        $rulesAverages = $this->createQueryBuilder('q')
            ->select(['IDENTITY(q.rule) AS rule_id', 'AVG(r.avg) AS avg'])
            ->innerJoin('q.result', 'r')
            ->where('q.user = :user')
            ->andWhere('r.avg >= :learnCondition')
            ->setParameter('user', $user)
            ->setParameter('learnCondition', RatingCalculator::LEARNT_RULE_RATE)
            ->groupBy('rule_id')
            ->getQuery()
            ->getArrayResult();

        return array_map(function ($item) {
            $item['avg'] = floatval($item['avg']);

            return $item;
        }, $rulesAverages);
    }
}
