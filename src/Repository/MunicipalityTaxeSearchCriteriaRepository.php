<?php

namespace App\Repository;

use App\Entity\MunicipalityTaxeSearchCriteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MunicipalityTaxesSearchCriteria>
 *
 * @method MunicipalityTaxesSearchCriteria|null find($id, $lockMode = null, $lockVersion = null)
 * @method MunicipalityTaxesSearchCriteria|null findOneBy(array $criteria, array $orderBy = null)
 * @method MunicipalityTaxesSearchCriteria[]    findAll()
 * @method MunicipalityTaxesSearchCriteria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MunicipalityTaxeSearchCriteriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MunicipalityTaxeSearchCriteria::class);
    }

    public function save(MunicipalityTaxeSearchCriteria $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MunicipalityTaxeSearchCriteria $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return MunicipalityTaxesSearchCriteria[] Returns an array of MunicipalityTaxesSearchCriteria objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MunicipalityTaxesSearchCriteria
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
