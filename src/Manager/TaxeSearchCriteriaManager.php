<?php

namespace App\Manager;

use App\Manager\AbstractManager;

use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use SSH\MyJwtBundle\Manager\ExceptionManager;
use App\Repository\TaxeSearchCriteriaRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class TaxeSearchCriteriaManager extends AbstractManager
{
    private Security $security;
    private TaxeSearchCriteriaRepository $repository;
    public function __construct(
        ManagerRegistry $entityManager,
        ExceptionManager $exceptionManager,
        RequestStack $requestStack,

        TaxeSearchCriteriaRepository $taxeSearchCriteriaRepository,
        Security $security
    ) {
        parent::__construct($entityManager, $exceptionManager, $requestStack);

        $this->repository = $taxeSearchCriteriaRepository;
        $this->security = $security; 
    }
    public function init($settings = [])
    {
        parent::setSettings($settings);
        return $this;
    }
    public function getMany()
    {
    }
    public function getOneDetail($code)
    {
        $taxeSearchCriteria = $this->repository->findOneBy(['code' => $code]);
        $data = [
            "code" => $taxeSearchCriteria->getId(),
            "value" => $taxeSearchCriteria->getValue(),
            "type" => $taxeSearchCriteria->getType(),
            "is_activated" => $taxeSearchCriteria->isIsActivated(),
            "created_at" => $taxeSearchCriteria->getCreatedAt()->format('Y-m-d'),
            "updated_at" => $taxeSearchCriteria->getUpdatedAt()->format('Y-m-d'),
            "date_begin" => $taxeSearchCriteria->getDateBegin()->format('Y-m-d'),
            "date_end" => ($taxeSearchCriteria->getDateEnd()) ? $taxeSearchCriteria->getDateEnd()->format('Y-m-d') : null,
            "creator" => [
                "code"=> $taxeSearchCriteria->getCreator()->getCode(),
                "first_name"=> $taxeSearchCriteria->getCreator()->getFirstName(),
                "last_name"=> $taxeSearchCriteria->getCreator()->getLastName(),
            ], 
            "updator"=>( $taxeSearchCriteria->getUpdator())?[
                "code"=> $taxeSearchCriteria->getUpdator()->getCode(),
                "first_name"=> $taxeSearchCriteria->getUpdator()->getFirstName(),
                "last_name"=> $taxeSearchCriteria->getUpdator()->getLastName(),
            ]:null
        ];
        $taxeSearchCriteria = null;
        return [
            "data" => [
                "taxe_search_criteria" => $data
            ]
        ];
    }


    public function create(){
        
    }
}
