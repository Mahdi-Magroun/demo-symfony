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
     * @Route("team/municipality/president/{code}",name="_municipality_president_oneDetail",methods={"GET"})
     */
    public function getOneDetail($code){
        return $this->manager->init('getOneDetail')
            ->getOneDetail($code);
    }
}