<?php

namespace App\Manager;

use App\Entity\Taxe;
use App\Entity\Team;
use DateTime;
use App\ApiModel\Taxe\TaxeCreate;
use App\ApiModel\Taxe\TaxeUpdate;
use Doctrine\Persistence\ManagerRegistry;
use SSH\MyJwtBundle\Manager\ExceptionManager;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TaxeManager extends AbstractManager
{

    private Security $security;
    private TaxeCreate $taxeCRModel;
    private TaxeUpdate $taxeUPModel;
    private NormalizerInterface $normalizer;
    public function __construct(
        ManagerRegistry $entityManager,
        ExceptionManager $exceptionManager,
        RequestStack $requestStack,
        Security $security,
        NormalizerInterface $normalizer
    ) {
        $this->normalizer = $normalizer;
        $this->security = $security;
        parent::__construct($entityManager, $exceptionManager, $requestStack);
    }

    public function init(string $method, $settings = [])
    {
        parent::setSettings($settings);
        // get current user 

        if ($method == "create") {
            # code...
            $this->taxeCRModel = $this->request->get('Taxe');
            if ($this->taxeCRModel->is_activated == null) {
                throw new \Exception("missing parameter", 1);
            }
        } elseif ($method == "update") {

            $this->taxeUPModel = $this->request->get('Taxe');
            if ($this->taxeUPModel->is_activated == null) {
                throw new \Exception("missing parameter", 1);
            }
        }
        return $this;
    }
    public function create()
    {
        $taxeCRModel = (array)$this->taxeCRModel;
        $taxeCRModel['creator'] = $this->request->get('teamCaller');

        $this->formatDatetime($taxeCRModel);
        //  dd($taxeCRModel);
        $taxe = new Taxe($taxeCRModel);
        //dd($taxe);
        $this->apiEntityManager->persist($taxe);
        $this->apiEntityManager->flush();
        return [
            "data" => [
                "messages" => "taxe successfuly created",
                "taxe_code" => $taxe->getCode()
            ]
        ];
    }

    public function update($code)
    {
        $taxeUPModel = (array)$this->taxeUPModel;
        $taxe = $this->getTaxeByCode($code);
        if (!$taxe)
            throw new \Exception("invalid_taxe_code", 1);

        $taxeUPModel['updator'] = $this->request->get('teamCaller');
        $this->formatDatetime($taxeUPModel);
        $this->updateObject($taxe, $taxeUPModel);
        $this->apiEntityManager->persist($taxe);
        $this->apiEntityManager->flush();
        return [
            "data" => [
                'messages' => 'taxe updated successfuly ',
                'taxe_code' => $taxe->getCode()
            ]

        ];
    }

    public function getMany()
    {
        $filter = [
            "abbreviation" => $this->request->query->get('abbreviation'),
            "name" => $this->request->query->get('name'),
            "is_activated" => $this->request->query->get('is_activated'),
        ];

        $taxes  = $this->apiEntityManager->getRepository(Taxe::class)
            ->findMany($filter, []);
        return [
            'data' => [
                "taxes" => $taxes
            ]
        ];
    }
    public function getOneDetail($code)
    {
        $taxe = $this->apiEntityManager->getRepository(Taxe::class)
            ->findOneBy(['code' => $code]);
        if (!$taxe)
            throw new \Exception("invalid_taxe_code", 1);
        $taxe = $this->normalizer->normalize($taxe, null, ['groups' => ['show_taxe', "show_no_credentials"]]);
        return [
            "data" => [
                "taxe" => $taxe
            ]
        ];
    }

    # get taxe by code
    public function getTaxeByCode($code)
    {
        $taxe = $this->apiEntityManager->getRepository(Taxe::class)
            ->findOneBy(['code' => $code]);
        if (!$taxe)
            throw new \Exception("invalid_taxe_code", 1);
        return $taxe;
    }
}
