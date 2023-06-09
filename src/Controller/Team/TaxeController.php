<?php 
namespace App\Controller\Team;

use App\Manager\TaxeManager;

use SSH\MyJwtBundle\Annotations\Mapping;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaxeController extends AbstractController{
    private TaxeManager $manager; 
    public function __construct(TaxeManager $manager)
    {
        $this->manager=$manager;
    }
    /**
     * @Route("/team/taxe",name="_create_taxe",methods={"POST"})
     * @Mapping(object="App\ApiModel\Taxe\TaxeCreate", as="Taxe")
     */
    public function create(){
        return $this->manager->init('create')
        ->create();
    }

    /**
     * @Route("/team/taxe/{code}",name="_update_taxe",methods={"PUT"})
     * @Mapping(object="App\ApiModel\Taxe\TaxeUpdate", as="Taxe")
     */
    public function update($code){
        return $this->manager->init('update')
        ->update($code);
    }
     /**
     * @Route("/team/taxe/{code}",name="_get_one_detail_taxe",methods={"GET"})
     * 
     */
    public function getOneDetail($code){
        return $this->manager->init('getOneDetail')
        ->getOneDetail($code);
    }

      /**
     * @Route("/team/taxes",name="_get_many_taxes",methods={"GET"})
     * 
     */
    public function getMany(){
        return $this->manager->init('getOneDetail')
        ->getMany();
    }
    
}