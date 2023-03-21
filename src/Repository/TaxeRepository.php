<?php

namespace App\Repository;

use App\Entity\Taxe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Taxe>
 *
 * @method Taxe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Taxe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Taxe[]    findAll()
 * @method Taxe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaxeRepository extends ServiceEntityRepository
{
    private $table = "taxe";
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Taxe::class);
    }

    public function save(Taxe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Taxe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findMany(array $filter, array $order){

        $sql = "SELECT abbreviation,code,name from $this->table m  ";
       $where=[];
       $parameter=[];
       
      if (isset($filter['abbreviation'])) {
        $where[]=" m.abbreviation ILIKe :abbreviation";
        $parameter[":abbreviation"] = $filter['governabbreviationorate_id'].'%';
        
      }
      if (isset($filter['name'])) {
        $where[]=" m.name ILIKE :name";
        $parameter[":name"] = $filter['name'].'%';
        # code...
      }
      if (isset($filter['is_activated'])) {
        $where[]=" m.is_activated =:is_activated";
        $parameter[":is_activated"] = $filter['is_activated'];
        # code...
      }
      if (isset($filter['arabic_name'])) {
        $where[]=" m.arabic_name ILIKE :arabic_name";
        $parameter[":arabic_name"] = $filter['arabic_name'].'%';
        # code...
      }
      if (!empty($where)) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
        }
     
     //  dd($sql);
     $conn = $this->getEntityManager()->getConnection();
     $stm = $conn->prepare($sql);
     $result=  $stm->execute($parameter);
    return $result->fetchAllAssociative();
        
    }
//    /**
//     * @return Taxe[] Returns an array of Taxe objects
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

//    public function findOneBySomeField($value): ?Taxe
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
