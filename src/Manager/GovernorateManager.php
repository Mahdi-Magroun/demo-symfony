<?php 
namespace App\Manager;

use App\Entity\Gouvernorate;
use Doctrine\Persistence\ManagerRegistry;
use SSH\MyJwtBundle\Manager\ExceptionManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class GovernorateManager extends AbstractManager{

    public function __construct(
        ManagerRegistry $entityManager,
        ExceptionManager $exceptionManager,
        RequestStack $requestStack,
      
    )
    {
        
        parent::__construct($entityManager, $exceptionManager, $requestStack);
    }

    public function init($settings=[]){
        parent::setSettings($settings);

        return $this; 
    }


    public function getMany(){
        $governorates = $this->apiEntityManager->getRepository(Gouvernorate::class)->findAll();
        $data=[];
        for ($i=0; $i <count($governorates) ; $i++) { 
            $data[]= [
                "code"=>$governorates[$i]->getCode(),
                "frensh_name"=>$governorates[$i]->getFrenshName(),
                "arabic_name"=>$governorates[$i]->getArabicName()
            ];
        }
        $governorates = null ; 
        return ["status"=>"Success","message"=>"","data"=>$data];
    }


}