<?php 
namespace App\Manager;

use App\Entity\Taxe;
use App\Entity\Team;
use DateTimeImmutable;
use App\ApiModel\Taxe\TaxeCreate;
use App\ApiModel\Taxe\TaxeUpdate;
use Doctrine\Persistence\ManagerRegistry;
use SSH\MyJwtBundle\Manager\ExceptionManager;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class TaxeManager extends AbstractManager{

    private Security $security;
    private TaxeCreate $taxeCRModel ;
    private TaxeUpdate $taxeUPModel; 
    public function __construct(
        ManagerRegistry $entityManager,
        ExceptionManager $exceptionManager,
        RequestStack $requestStack,
        Security $security
    )
    {
        $this->security=$security;
        parent::__construct($entityManager, $exceptionManager, $requestStack);
    }

    public function init(string $method,$settings=[]){
        parent::setSettings($settings); 
        // get current user 

        if ($method=="create") {
            # code...
           $this->taxeCRModel = $this->request->get('Taxe');
           if ($this->taxeCRModel->is_activated == null ) {
                throw new \Exception("missing parameter", 1);
                
           }
        }

        elseif ($method=="update") {
           
           $this->taxeUPModel = $this->request->get('Taxe');
           if ($this->taxeUPModel->is_activated == null ) {
                throw new \Exception("missing parameter", 1);
                
           }
        }
        return $this;
    }
    public function create(){
        $taxe = new Taxe();
        $creator = $this->apiEntityManager->getRepository(Team::class)->findOneBy(['code'=>$this->security->getUser()->getCode()]);
        $taxe->setCreator($creator)
        ->setName($this->taxeCRModel->taxe_name)
        ->setAbbreviation($this->taxeCRModel->taxe_abbreviation)
        ->setDateBegin(new DateTimeImmutable($this->taxeCRModel->date_begin))
        ->setCreatedAt(new DateTimeImmutable('now'))
        ->setIsActivated($this->taxeCRModel->is_activated);
        $this->apiEntityManager->persist($taxe);
        $this->apiEntityManager->flush();
        return [
            "data"=>[
                "messages"=>"taxe successfuly created",
                "taxe_code"=>$taxe->getCode()
            ]
            ];

    }

    public function update($code){
       $taxe = $this->apiEntityManager->getRepository(Taxe::class)
       ->findOneBy(['code'=>$code]);
       if(!$taxe)
            throw new \Exception("invalid_taxe_code", 1);

        $updator = $this->apiEntityManager->getRepository(Team::class)->findOneBy(['code'=>$this->security->getUser()->getCode()]);
        $taxe->setAbbreviation($this->taxeUPModel->taxe_abbreviation)
        ->setName($this->taxeUPModel->taxe_name)
        ->setDateBegin(new DateTimeImmutable( $this->taxeUPModel->date_begin))
        ->setIsActivated($this->taxeUPModel->is_activated)
        ->setUpdator($updator)
        ->setUpdatedAt(new DateTimeImmutable());
        if ($this->taxeUPModel->date_end !=null) {
            # code...
            $taxe->setDateEnd(new DateTimeImmutable());
        }
        $this->apiEntityManager->persist($taxe);
        $this->apiEntityManager->flush();
        return [
           "data"=>[
            'messages'=>'taxe updated successfuly ',
            'taxe_code'=>$taxe->getCode()
           ]
            
           ];


            
    }
}