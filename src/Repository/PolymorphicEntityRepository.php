<?php

namespace App\Repository;

use App\Entity\PolymorphicEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PolymorphicEntity>
 *
 * @method PolymorphicEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method PolymorphicEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method PolymorphicEntity[]    findAll()
 * @method PolymorphicEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PolymorphicEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PolymorphicEntity::class);
    }

    public function save(PolymorphicEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PolymorphicEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PolymorphicEntity[] Returns an array of PolymorphicEntity objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PolymorphicEntity
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
