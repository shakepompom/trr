<?php

namespace App\Repository;

use App\Entity\Quiz;
use App\Entity\QuizResult;
use App\Entity\Rule;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QuizResult|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizResult|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizResult[]    findAll()
 * @method QuizResult[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizResultRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QuizResult::class);
    }

    public function findLastResults(User $user, Rule $rule): array
    {
        return $this->createQueryBuilder('qr')
            ->innerJoin('qr.quiz', 'q')
            ->where('q.user = :user')
            ->andWhere('q.rule = :rule')
            ->setParameter('user', $user)
            ->setParameter('rule', $rule)
            ->orderBy('qr.id', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }
}
