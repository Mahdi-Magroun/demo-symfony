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

    
}