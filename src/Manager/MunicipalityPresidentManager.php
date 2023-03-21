<?php
namespace App\Manager;

use App\Entity\Municipality;
use App\Entity\MunicipalityAgent;
use Doctrine\Persistence\ManagerRegistry;
use SSH\MyJwtBundle\Manager\ExceptionManager;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class MunicipalityPresidentManager extends AbstractManager{

    public Security $security;
   
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
        return $this;
    }
    public function getOneDetail($code){
        $president = $this->apiEntityManager->getRepository(MunicipalityAgent::class)->findOneBy([
            "role"=>"ROLE_MUNICIPALITY_PRESIDENT",
            "code"=>$code
        ]);
        if (!$president) {
           throw new \Exception("no_municipality_president_found", 1);
           
        }
        return [
            "data"=>[
            "president"=>[
                "code"=>$president->getCode(),
                "first_name"=>$president->getFirstName(),
                "last_name"=>$president->getLastName(),
                "email"=>$president->getEmail(),
                "cin"=>$president->getCin(),
                "created_at"=>null,
                "updated_at"=>null,
                "is_activated"=>$president->isIsActivated(),
                "date_begin"=>$president->getDateBegin()->format('Y-m-d'),
                "date_end"=>$president->getDateEnd()->format('Y-m-d'),
                "municipality"=>[
                    "code"=>$president->getMunicipality()->getCode(),
                    "frensh_name"=>$president->getMunicipality()->getFrenshName(),
                    "arabic_name"=>$president->getMunicipality()->getArabicName(),
                ]
            ]
            ]
        ];
    }

       /**
    * filter based on (municipality,is_activated,first_name,last_name,cin) 
    * 
    */
    public function getMany(){
        $thereIsMunicipality = false;
        
        if ($this->request->query->get('municipality_code') ) {
           $municipality= $this->apiEntityManager->getRepository(Municipality::class)
            ->findOneBy(["code"=>$this->request->query->get('municipality_code') ]);
            if (!$municipality) {
                throw new \Exception("invalid_municiality_code", 1);
            }
            $thereIsMunicipality =true;
        }
        $filter = [
            "municipality_id"=> ($thereIsMunicipality) ? $municipality->getId() : null,
            "is_activated"=>$this->request->query->get('is_activated') ,
            "first_name"=>$this->request->query->get('first_name'),
            "last_name"=>$this->request->query->get('last_name'),
            "cin"=>$this->request->query->get('cin'),
            "role"=>$this->request->query->get('role'),
         ];    
         $presidents =  $this->apiEntityManager->getRepository(MunicipalityAgent::class)
         ->findManyAgents($filter);
         return [
            "data"=>[
                "presidents"=>$presidents
            ]
            
           
         ];

   }

    public function create(){

    }

    public function update(){

    }

    public function activation($agentCode){
       
         $agent = $this->apiEntityManager->getRepository(MunicipalityAgent::class)
                        ->findOneBy(['code'=>$agentCode]);
        if(!$agent)  
            throw new \Exception("municipality_president_not_found", 1);
         if ($agent->isIsActivated()) {
             $agent->setIsActivated(false);
            
             }
         else{
            $agent->setIsActivated(true);
        
         }
         $this->apiEntityManager->persist($agent);
         $this->apiEntityManager->flush();
        $status =($agent->isIsActivated())?"unblocked":"blocked";
         return [
            "data"=>[
                "messages"=>"municipality president  is ".$status
            ]
         ];
    }
}