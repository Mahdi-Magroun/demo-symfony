<?php
namespace App\Controller\Team;
use SSH\MyJwtBundle\Annotations\Mapping;
use App\Manager\MunicipalityPresidentManager;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MunicipalityPresidentController extends AbstractController{
    
    private MunicipalityPresidentManager $manager; 
    public function __construct(MunicipalityPresidentManager $manager)
    {
        $this->manager = $manager; 
    }
    /**
     * @Route("team/municipality/detail/presidents",name="_municipality_many_president",methods={"GET"})
     */
    public function getMany(){
        return $this->manager->init('getMany')
        ->getMany();
    }

     /**
     * @Route("team/municipality/president/{code}/activation",name="_municipality_president_activation",methods={"PUT"})
     */
    public function activation($code){
        return $this->manager->init("activation")
            ->activation($code);
    }


    /**
     * @Route("team/municipality/president/{code}",name="_municipality_president_oneDetail",methods={"GET"})
     */
    public function getOneDetail($code){
        return $this->manager->init('getOneDetail')
            ->getOneDetail($code);
    }

    /**
     * @Route("team/municipality/president",name="_municipality_president_creation",methods={"POST"})
     * @Mapping(object="App\ApiModel\Municipality\President\PresidentCreate", as="MunicipalityPresident")
     */
    public function create(){
        return $this->manager->init('create')
            ->create();
    }
     /**
     * @Route("team/municipality/president/{code}",name="_municipality_president_updating",methods={"PUT"})
     * @Mapping(object="App\ApiModel\Municipality\President\PresidentUpdate", as="MunicipalityPresident")
     */
    public function update(string $code){
        return $this->manager->init('update')
            ->update($code);
    }

     
    
}