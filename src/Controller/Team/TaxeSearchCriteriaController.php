<?php

namespace App\Controller\Team;

use SSH\MyJwtBundle\Annotations\Mapping;

use App\Manager\TaxeSearchCriteriaManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaxeSearchCriteriaController extends AbstractController
{
    private TaxeSearchCriteriaManager $manager;
    
    public function __construct(TaxeSearchCriteriaManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/team/taxe-search-criteria/{code}",name="_search_criteria_taxe",methods={"GET"})
     */
    public function getOneDetail($code)
    {
        return $this->manager->init('getOneDetail')
            ->getOneDetail($code);
    }

    /**
     * @Route("/team/taxe-search-criteria",name="_create_search_criteria_taxe",methods={"POST"})
     * @Mapping(object="App\ApiModel\Taxe\TaxeSearchCriteria\TaxeSearchCriteriaCreate", as="TaxeSearchCriteria")
     */
    public function create()
    {

        return $this->manager->init('create')
            ->create();
    }

    /**
     * @Route("/team/taxe-search-criterias",name="_get_many_search_criteria_taxe",methods={"GET"})
     */
    public function getMany()
    {
        return $this->manager->init('getMany')
            ->getMany();
    }

    /**
     * @Route("/team/taxe-search-criteria/{code}",name="_update_search_criteria_taxe",methods={"PUT"})
     * @Mapping(object="App\ApiModel\Taxe\TaxeSearchCriteria\TaxeSearchCriteriaUpdate", as="TaxeSearchCriteria")
     */
    public function update($code)
    {
        return $this->manager->init('update')
            ->update($code);
    }

    /**
     * @Route("/team/taxe-search-criteria/{code}",name="_delete_search_criteria_taxe",methods={"DELETE"})
     */
    public function delete($code)
    {
        return $this->manager->init('delete')
            ->delete($code);
    }
}
