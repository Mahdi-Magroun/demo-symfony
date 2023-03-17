<?php
namespace App\Manager;

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
            "code"=>$president->getCode(),
            "first_name"=>$president->getFirstName(),
            "last_name"=>$president->getLastName(),
            "email"=>$president->getEmail(),
            "cin"=>$president->getCin(),
            "created_at"=>null,
            "updated_at"=>null,
            "is_activated"=>$president->isIsActivated(),
            "municipality"=>[
                "code"=>$president->getMunicipality()->getCode(),
                "frensh_name"=>$president->getMunicipality()->getFrenshName(),
                "arabic_name"=>$president->getMunicipality()->getArabicName(),
            ]
        ];
    }

    public function getMany(){

    }

    public function create(){

    }

    public function update(){

    }

    public function activation(){
        
    }
}