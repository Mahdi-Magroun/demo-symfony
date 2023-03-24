<?php

namespace App\Manager;

use App\Entity\Taxe;
use App\Entity\Team;
use App\Manager\AbstractManager;
use App\Entity\TaxeSearchCriteria;
use Doctrine\Persistence\ManagerRegistry;
use SSH\MyJwtBundle\Manager\ExceptionManager;
use Symfony\Component\Security\Core\Security;
use App\Repository\TaxeSearchCriteriaRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use app\ApiModel\Taxe\TaxeSearchCriteria\TaxeSearchCriteriaCreate;
use App\ApiModel\Taxe\TaxeSearchCriteria\TaxeSearchCriteriaUpdate;

class TaxeSearchCriteriaManager extends AbstractManager
{
    private Security $security;
    private TaxeSearchCriteriaRepository $repository;

    private TaxeSearchCriteriaCreate $taxeSearchCriteriaCRModel;
    private TaxeSearchCriteriaUpdate $taxeSearchCriteriaUPModel;
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



    public function init(string $method, $settings = [])
    {

        parent::setSettings($settings);
        if ($method == "create") {
            $this->taxeSearchCriteriaCRModel = $this->request->get("TaxeSearchCriteria");
            if ($this->taxeSearchCriteriaCRModel->is_activated == null && $this->taxeSearchCriteriaCRModel->date_begin == null) {
                throw new \Exception("missing parameter", 1);
                # code...
            }
        }
        else if($method == "update"){
            $this->taxeSearchCriteriaUPModel = $this->request->get("TaxeSearchCriteria");
            if ($this->taxeSearchCriteriaUPModel->is_activated == null && $this->taxeSearchCriteriaUPModel->date_begin == null) {
                throw new \Exception("missing parameter", 1);
                # code...
            }
        }
        return $this;
    }
    public function getMany()
    {
        $taxeSearchCriteria = $this->repository->findAll();
        $data = [];
        foreach ($taxeSearchCriteria as $key => $value) {
            $data[$key] = [
                "code" => $value->getCode(),
                "name"=>$value->getName(),
            ];
        }
        $taxeSearchCriteria = null;
        return [
            "data" => [
                "taxe_search_criteria" => $data
            ]
        ];
    }
    public function getOneDetail($code)
    {
        $taxeSearchCriteria = $this->repository->findOneBy(['code' => $code]);
        $data = [
            "code" => $taxeSearchCriteria->getCode(),
            "value" => $taxeSearchCriteria->getValue(),
            "type" => $taxeSearchCriteria->getType(),
            "is_activated" => $taxeSearchCriteria->getIsActivated(),
            "created_at" => $taxeSearchCriteria->getCreatedAt()->format('Y-m-d'),
            "updated_at" => ($taxeSearchCriteria->getUpdatedAt())?$taxeSearchCriteria->getUpdatedAt()->format('Y-m-d'):null,
            "date_begin" => $taxeSearchCriteria->getDateBegin()->format('Y-m-d'),
            "date_end" => ($taxeSearchCriteria->getDateEnd()) ? $taxeSearchCriteria->getDateEnd()->format('Y-m-d') : null,
            "creator" => [
                "code" => $taxeSearchCriteria->getCreator()->getCode(),
                "first_name" => $taxeSearchCriteria->getCreator()->getFirstName(),
                "last_name" => $taxeSearchCriteria->getCreator()->getLastName(),
            ],
            "updator" => ($taxeSearchCriteria->getUpdator()) ? [
                "code" => $taxeSearchCriteria->getUpdator()->getCode(),
                "first_name" => $taxeSearchCriteria->getUpdator()->getFirstName(),
                "last_name" => $taxeSearchCriteria->getUpdator()->getLastName(),
            ] : null
        ];
        $taxeSearchCriteria = null;
        return [
            "data" => [
                "taxe_search_criteria" => $data
            ]
        ];
    }


    public function create()
    {
        $taxe = $this->apiEntityManager->getRepository(Taxe::class)->findOneBy(['code' => $this->taxeSearchCriteriaCRModel->taxe_code]);
        if (!$taxe) {
            throw new \Exception("taxe_not_found", 1);
        }
        $taxeSearchCriteria = new TaxeSearchCriteria();

        if ($this->taxeSearchCriteriaCRModel->is_date) {
            $taxeSearchCriteria->setType("date");
            $value = $this->numberOfMonth($this->taxeSearchCriteriaCRModel->value, 'now');
        } else {
            $taxeSearchCriteria->setType("amount");
            $value = $this->taxeSearchCriteriaCRModel->value;
        }
        $taxeSearchCriteria->setName($this->taxeSearchCriteriaCRModel->name);
        $taxeSearchCriteria->setTaxe($taxe);
        $creator = $this->entityManager->getRepository(Team::class)->findOneBy(['code' => $this->security->getUser()->getCode()]);
        $taxeSearchCriteria->setValue($value);
        $taxeSearchCriteria->setIsActivated($this->taxeSearchCriteriaCRModel->is_activated);
        $taxeSearchCriteria->setDateBegin(new \DateTimeImmutable($this->taxeSearchCriteriaCRModel->date_begin));
        $taxeSearchCriteria->setDateEnd(($this->taxeSearchCriteriaCRModel->date_end) ? new \DateTimeImmutable($this->taxeSearchCriteriaCRModel->date_end) : null);
        $taxeSearchCriteria->setCreator($creator);
        $taxeSearchCriteria->setCreatedAt(new \DateTimeImmutable());
        $this->apiEntityManager->persist($taxeSearchCriteria);
        $this->apiEntityManager->flush();
       
        return [
            "data" => [
                "message" => "taxe search criteria created successfully",
                "code"=> $taxeSearchCriteria->getCode()
            ]
        ];
    }

    
    public function update($code){
        $taxeSearchCriteria = $this->repository->findOneBy(['code' => $code]);
        if (!$taxeSearchCriteria) {
            throw new \Exception("taxe_search_criteria_not_found", 1);
        }
        
        if ($taxeSearchCriteria->getType()=="date") {
            $value = $this->numberOfMonth($this->taxeSearchCriteriaUPModel->value, 'now');
        } else {

            $value = $this->taxeSearchCriteriaUPModel->value;
        }
        $taxeSearchCriteria->setName($this->taxeSearchCriteriaUPModel->name);
    
        $updator = $this->entityManager->getRepository(Team::class)->findOneBy(['code' => $this->security->getUser()->getCode()]);
        $taxeSearchCriteria->setValue($value);
        $taxeSearchCriteria->setIsActivated($this->taxeSearchCriteriaUPModel->is_activated);
        $taxeSearchCriteria->setDateBegin(new \DateTimeImmutable($this->taxeSearchCriteriaUPModel->date_begin));
        $taxeSearchCriteria->setDateEnd(($this->taxeSearchCriteriaUPModel->date_end) ? new \DateTimeImmutable($this->taxeSearchCriteriaUPModel->date_end) : null);
        $taxeSearchCriteria->setUpdator($updator);
        $taxeSearchCriteria->setUpdatedAt(new \DateTimeImmutable());
        $this->apiEntityManager->persist($taxeSearchCriteria);
        $this->apiEntityManager->flush();
       
        return [
            "data" => [
                "message" => "taxe search criteria updated successfully",
                "taxe_search_criteria_code"=> $taxeSearchCriteria->getCode()
            ]
        ];
    }

    public function delete($code)
    {
        $taxeSearchCriteria = $this->repository->findOneBy(['code' => $code]);
        if (!$taxeSearchCriteria) {
            throw new \Exception("taxe_search_criteria_not_found", 1);
        }
        $this->apiEntityManager->remove($taxeSearchCriteria);
        $this->apiEntityManager->flush();
        return [
            "data" => [
                "message" => "taxe search criteria deleted successfully",
            ]
        ];
    }


    public function numberOfMonth($date_begin, $date_end)
    {
        $date_begin = new \DateTime($date_begin);
        $date_end = new \DateTime($date_end);
        $interval = $date_begin->diff($date_end);
        $months = $interval->format('%m');
        $years = $interval->format('%y');
        $months += $years * 12;
        return $months;
    }
}
