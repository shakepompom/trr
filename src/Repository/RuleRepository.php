<?php

namespace App\Repository;

use App\Entity\Rule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Rule|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rule|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rule[]    findAll()
 * @method Rule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RuleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Rule::class);
    }

    /*
     * TODO: Change to real getting Rule from the course.
     */
    public function findRandomRuleForNewCourseItem(): ?Rule
    {
        $ruleIds = [82, 14, 46, 81, 83, 12, 36, 1, 11, 80, 4, 198, 50, 6, 2, 23, 31, 52, 5, 24, 7, 37,];

        $randomRuleId = $ruleIds[mt_rand(0, count($ruleIds) - 1)];

        return $this->find($randomRuleId);
    }
}
