<?php

namespace App\Repository;

use App\Entity\TaxeSearchCriteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TaxeSearchCriteria>
 *
 * @method TaxeSearchCriteria|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaxeSearchCriteria|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaxeSearchCriteria[]    findAll()
 * @method TaxeSearchCriteria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaxeSearchCriteriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaxeSearchCriteria::class);
    }

    public function save(TaxeSearchCriteria $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TaxeSearchCriteria $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TaxeSearchCriteria[] Returns an array of TaxeSearchCriteria objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TaxeSearchCriteria
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
