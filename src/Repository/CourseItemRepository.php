<?php

namespace App\Repository;

use App\Entity\CourseItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CourseItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourseItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourseItem[]    findAll()
 * @method CourseItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CourseItem::class);
    }

    /**
     * @param CourseItem $courseItem
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(CourseItem $courseItem): void
    {
        $this->getEntityManager()->persist($courseItem);
        $this->getEntityManager()->flush();
    }
}
