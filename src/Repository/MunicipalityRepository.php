<?php

namespace App\Repository;

use App\Entity\Municipality;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Municipality>
 *
 * @method Municipality|null find($id, $lockMode = null, $lockVersion = null)
 * @method Municipality|null findOneBy(array $criteria, array $orderBy = null)
 * @method Municipality[]    findAll()
 * @method Municipality[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MunicipalityRepository extends ServiceEntityRepository
{
    private string $table = "municipality";
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Municipality::class);
    }

    public function save(Municipality $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Municipality $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Municipality[] Returns an array of Municipality objects
    */
   public function findMany(array $filter,$order=[])
   {
    
        $sql = "SELECT frensh_name,arabic_name,code from $this->table m  ";
       $where=[];
       $parameter=[];
       
      if (isset($filter['governorate_id'])) {
        $where[]=" m.governorate_id = :governorate_id";
        $parameter[":governorate_id"] = $filter['governorate_id'];
        # code...
      }
      if (isset($filter['frensh_name'])) {
        $where[]=" m.frensh_name ILIKE :frensh_name";
        $parameter[":frensh_name"] = $filter['frensh_name'].'%';
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

//    public function findOneBySomeField($value): ?Municipality
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
