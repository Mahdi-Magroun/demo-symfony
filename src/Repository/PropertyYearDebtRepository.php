<?php

namespace App\Repository;

use App\Entity\PropertyYearDebt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PropertyYearDebt>
 *
 * @method PropertyYearDebt|null find($id, $lockMode = null, $lockVersion = null)
 * @method PropertyYearDebt|null findOneBy(array $criteria, array $orderBy = null)
 * @method PropertyYearDebt[]    findAll()
 * @method PropertyYearDebt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyYearDebtRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PropertyYearDebt::class);
    }

    public function save(PropertyYearDebt $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PropertyYearDebt $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PropertyYearDebt[] Returns an array of PropertyYearDebt objects
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

//    public function findOneBySomeField($value): ?PropertyYearDebt
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
