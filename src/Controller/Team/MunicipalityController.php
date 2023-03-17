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
     * @Mapping(object="App\ApiModel\Municipality\MunicipalityCreate", as="Municipality")
     */
    public function create(){
        return $this->manager->init("create")
                ->create();
    }

    /**
     * @Route("team/municipality/{code}",name="_get_one_municipality_detailed",methods={"GET"})
     */
    public function getOneDetail($code){
        return $this->manager->getOneDetail($code);
    }

    /**
     * @Route("team/municipality",name="_get_many_municipality",methods={"GET"})
     */
    public function getMany(){
        return $this->manager->init("getMany")
                ->getMany();
    }

    /**
     * @Route("team/municipality/{code}/activation",name="_activation",methods={"PUT"})
     */
    public function activation($code){
        return $this->manager->init("activation")
                ->activation($code);
    }

    /**
     * @Route("team/municipality/{code}",name="_update_municipality",methods={"PUT"})
     * @Mapping(object="App\ApiModel\Municipality\MunicipalityUpdate", as="Municipality")
     */
     public function update($code){
        return $this->manager->init('update')
                ->update($code);
     }

     /**
      * @Route("team/municipality/{code}",name="_delete_municipality",methods={"DELETE"})
      */
      public function delete($code){
        return $this->manager->init("delete")
            ->delete($code);
      }



}