<?php 
namespace App\Controller\Team;

use App\Manager\MunicipalityManager;
use SSH\MyJwtBundle\Annotations\Mapping;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MunicipalityController extends AbstractController{
    private MunicipalityManager $manager; 
    public function __construct(MunicipalityManager $manager)
    {
        $this->manager=$manager;
    }

    /**
     * @Route("team/municipality",name="_create_municipality",methods={"POST"})
     *  @Mapping(object="App\ApiModel\Municipality\MunicipalityCreate", as="Municipality")
     */
    public function create(){
        return $this->manager->init()
                ->create();
    }

    /**
     * @Route("team/municipality/{code}",methods={"GET"})
     */
    public function getOneDetail($code){
        return $this->manager->getOneDetail($code);
    }
}