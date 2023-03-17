<?php

namespace App\Repository;

use App\Entity\MunicipalityAgent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MunicipalityAgent>
 *
 * @method MunicipalityAgent|null find($id, $lockMode = null, $lockVersion = null)
 * @method MunicipalityAgent|null findOneBy(array $criteria, array $orderBy = null)
 * @method MunicipalityAgent[]    findAll()
 * @method MunicipalityAgent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MunicipalityAgentRepository extends ServiceEntityRepository
{
    private string $table = "municipality_agent";
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MunicipalityAgent::class);
    }

    public function save(MunicipalityAgent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MunicipalityAgent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findManyAgents(array $filter){

        $sql = "SELECT first_name,last_name,code from $this->table m  ";
       $where=[];
       $parameter=[];
       
      if (isset($filter['first_name'])) {
        $where[]=" m.first_name ilike :first_name";
        $parameter[":first_name"] = $filter['first_name'].'%';
        # code...
      }
      if (isset($filter['last_name'])) {
        $where[]=" m.last_name ilike :last_name";
        $parameter[":last_name"] = $filter['last_name'].'%';
        # code...
      }
      if (isset($filter['is_activated'])) {
        $where[]=" m.is_activated =:is_activated";
        $parameter[":is_activated"] = $filter['is_activated'];
        # code...
      }
      if (isset($filter['role'])) {
        $where[]=" m.role =:role";
        $parameter[":role"] = $filter['role'];
        # code...
      }
      if (isset($filter['cin'])) {
        $where[]=" m.cin =:cin";
        $parameter[":cin"] = $filter['cin'];
        # code...
      }
      if (isset($filter['municipality_id'])) {
        $where[]=" m.municipality_id =:municipality_id";
        $parameter[":municipality_id"] = $filter['municipality_id'];
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
//     * @return MunicipalityAgent[] Returns an array of MunicipalityAgent objects
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

//    public function findOneBySomeField($value): ?MunicipalityAgent
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
