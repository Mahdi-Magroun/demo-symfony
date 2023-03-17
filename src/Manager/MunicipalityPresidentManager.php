<?php
namespace App\Manager;

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

    }
    public function getOneDetail($code){
        
    }

}