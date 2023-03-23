<?php 
namespace App\Controller\Team;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaxeSerachCriteriaController extends AbstractController{
    
    public function __construct()
    {
        $this->$manager =$manager;
    }
    /**
     * @Route("/team/taxe/search-criteria/{code}",name="_search_criteria_taxe",methods={"GET"})
     */ 
    public function getOneDetail($code){
        return $this->manager->init('getOneDetail')
        ->getOneDetail($code);
    }

}