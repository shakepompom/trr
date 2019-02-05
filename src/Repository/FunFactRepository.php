<?php

namespace App\Repository;

use App\Entity\FunFact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FunFact|null find($id, $lockMode = null, $lockVersion = null)
 * @method FunFact|null findOneBy(array $criteria, array $orderBy = null)
 * @method FunFact[]    findAll()
 * @method FunFact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FunFactRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FunFact::class);
    }

    public function findOneRandom(): FunFact
    {
        $facts = $this->findAll();

        return $facts[mt_rand(0, count($facts) - 1)];
    }
}
