<?php
namespace App\Controller\Team;

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
     
    
}