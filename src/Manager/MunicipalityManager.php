<?php

namespace App\Manager;

use App\Entity\Team;
use DateTimeImmutable;
use App\Entity\Governorate;
use App\Entity\Municipality;
use App\Entity\MunicipalityAgent;
use Doctrine\Persistence\ManagerRegistry;
use SSH\MyJwtBundle\Manager\ExceptionManager;
use Symfony\Component\Security\Core\Security;
use App\ApiModel\Municipality\MunicipalityCreate;
use App\ApiModel\Municipality\MunicipalityUpdate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class MunicipalityManager extends AbstractManager
{
    public Security $security;
    public MunicipalityCreate $municipalityCRModel;
    public MunicipalityUpdate $municipalityUPModel;

    public function __construct(
        ManagerRegistry $entityManager,
        ExceptionManager $exceptionManager,
        RequestStack $requestStack,
        Security $security

    ) {
        $this->security = $security;
        parent::__construct($entityManager, $exceptionManager, $requestStack);
    }

    public function init(string $method, $settings = [])
    {
        parent::setSettings($settings);
        if ($method == "create") {
            # code...
            $this->municipalityCRModel = $this->request->get("Municipality");
        } elseif ($method == "update") {
            $this->municipalityUPModel = $this->request->get("Municipality");
        } elseif ($method == "getMany") {
            # code...
        }

        return $this;
    }

    /**
     * 1- Create a Municipality : done 
     * 2- Create a president with a default password : done 
     * 3- send an creadention to the president :sprint2 
     */
    public function create()
    {
        // verify governorate 
        $governorate = $this->apiEntityManager->getRepository(Governorate::class)
            ->findOneBy(["code" => $this->municipalityCRModel->governorate]);
        if (!$governorate) {
            throw new \Exception("governorate_not_found_exception", 1);
        }
        $municipality = new Municipality();
        $municipality->setGovernorate($governorate)
            ->setCreator(
                $this->apiEntityManager->getRepository(Team::class)->findOneBy(["code" => $this->security->getUser()->getCode()])

            )
            ->setFrenshName($this->municipalityCRModel->frensh_name)
            ->setArabicName($this->municipalityCRModel->arabic_name)
            ->setPhoneNumber(strval($this->municipalityCRModel->phone_number))
            ->setWebSite($this->municipalityCRModel->web_site)
            ->setNationalId($this->municipalityCRModel->national_id)
            ->setPopulationCount($this->municipalityCRModel->population_count)
            ->setYearPopulationCount($this->municipalityCRModel->population_year_count)
            ->setCreatedAt(new DateTimeImmutable())
            ->setIsActivated(true)
            ->setZipCode($this->municipalityCRModel->zip_code)
            ->setStreet($this->municipalityCRModel->street)
            ->setBuildingNumber($this->municipalityCRModel->building_number)
            ->setEmail($this->municipalityCRModel->email);
        $president = new MunicipalityAgent();
        $president->setFirstName($this->municipalityCRModel->president_first_name)
            ->setDateBegin(new DateTimeImmutable($this->municipalityCRModel->president_date_begin))
            ->setCreatedAt(new DateTimeImmutable("now"))
            ->setDateEnd(new DateTimeImmutable($this->municipalityCRModel->president_date_end))
            ->setLastName($this->municipalityCRModel->president_last_name)
            ->setEmail($this->municipalityCRModel->president_email)
            ->setCin($this->municipalityCRModel->president_cin)
            ->setIsActivated(true)
            ->setRole("ROLE_MUNICIPALITY_PRESIDENT");
        // generate random password     
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+';
        // Shuffle the characters to make it more random
        $characters = str_shuffle($characters);
        // Get the first $length characters of the shuffled string
        $password = substr($characters, 0, 12);

        // end pwd generation 
        $president->setPassword($password);

        // persist data 
        $this->apiEntityManager->getConnection()->beginTransaction();
        try {
            $this->apiEntityManager->persist($municipality);
            $this->apiEntityManager->flush();
            $this->apiEntityManager->refresh($municipality);

            $president->setMunicipality($municipality);
            $this->apiEntityManager->persist($president);

            $this->apiEntityManager->flush();
            $this->apiEntityManager->getConnection()->commit();
        } catch (\Throwable $th) {
            $this->apiEntityManager->getConnection()->rollBack();
            throw new \Exception("error_persisting_data :" . $th->getMessage(), 1);
        }
        return [


            "data" => [
                "messages" => "President&&Municipality successfuly created ",
                "municipality_code" => $municipality->getCode(),
                "president_code" => $president->getCode()
            ]
        ];
    }

    public function getOneDetail($code)
    {
        $municipality = $this->apiEntityManager->getRepository(Municipality::class)
            ->findOneBy(["code" => $code]);
        if (!$municipality) {
            throw new \Exception("invalid_municipality_code", 1);
        }
        $municipalityPresident = $this->apiEntityManager->getRepository(MunicipalityAgent::class)
            ->findOneBy(["municipality" => $municipality]);
        return [

            "data" => [
                "governorate" => [
                    "governorate_code" => $municipality->getGovernorate()->getCode(),
                    "governorate_frensh_name" => $municipality->getGovernorate()->getFrenshName(),
                    "governorate_frensh_name" => $municipality->getGovernorate()->getArabicName(),
                    "nationl_id" => $municipality->getGovernorate()->getNationalId()
                ],
                "address" => [
                    "zip_code" => $municipality->getZipCode(),
                    "street" => $municipality->getStreet(),
                    "building_number" => $municipality->getBuildingNumber()
                ],
                "president" => [
                    "first_name" => $municipalityPresident->getFirstName(),
                    "last_name" => $municipalityPresident->getLastName(),
                    "email" => $municipalityPresident->getEmail()
                ],
                "municipality_code" => $municipality->getCode(),
                "isActivated" => $municipality->isIsActivated(),
                "national_municipality_id" => $municipality->getNationalId(),
                "frensh_name" => $municipality->getFrenshName(),
                "arabic_name" => $municipality->getArabicName(),
                "web_site" => $municipality->getWebSite(),
                "phone_number" => $municipality->getPhoneNumber(),

            ]

        ];
    }

    public function getMany()
    {
        $governorateCode = $this->request->query->get('governorate_code');
        $governorate = $this->apiEntityManager->getRepository(Governorate::class)
            ->findOneBy(['code' => $governorateCode]);

        $filter = [
            "governorate_id" => ($governorate) ? $governorate->getId() : null,
            "frensh_name" => $this->request->query->get('frensh_name'),
            "arabic_name" => $this->request->query->get('arabic_name'),
            "is_activated" => $this->request->query->get('is_activated')
        ];

        $municipalities = $this->apiEntityManager->getRepository(Municipality::class)
            ->findMany($filter, []);
        return [
            "data" => [
                "municipalities" => $municipalities
            ]
        ];
    }
    public function activation($municipalityCode)
    {


        $municipality = $this->apiEntityManager->getRepository(Municipality::class)
            ->findOneBy(['code' => $municipalityCode]);
        if (!$municipality)
            throw new \Exception("municipality_not_found", 1);
        if ($municipality->isIsActivated()) {
            $municipality->setIsActivated(false);
        } else {
            $municipality->setIsActivated(true);
        }
        $this->apiEntityManager->persist($municipality);
        $this->apiEntityManager->flush();
        $status = ($municipality->isIsActivated()) ? "unblocked" : "blocked";
        return [
            "data" => [
                "messages" => "municipality is " . $status,
            ]

        ];
    }

    public function update($code)
    {
        $governorate = $this->apiEntityManager->getRepository(Governorate::class)
            ->findOneBy(["code" => $this->municipalityUPModel->governorate]);
        if (!$governorate) {
            throw new \Exception("governorate_not_found_exception", 1);
        }
        $municipality = $this->apiEntityManager->getRepository(Municipality::class)
            ->findOneBy(["code" => $code]);
        if (!$municipality) {
            throw new \Exception("invalid_municipality_code", 1);
        }
        $municipality->setGovernorate($governorate)
            ->setUpdator(
                $this->apiEntityManager->getRepository(Team::class)->findOneBy(["code" => $this->security->getUser()->getCode()])

            )
            ->setFrenshName($this->municipalityUPModel->frensh_name)
            ->setArabicName($this->municipalityUPModel->arabic_name)
            ->setPhoneNumber(strval($this->municipalityUPModel->phone_number))
            ->setWebSite($this->municipalityUPModel->web_site)
            ->setNationalId($this->municipalityUPModel->national_id)
            ->setPopulationCount($this->municipalityUPModel->population_count)
            ->setYearPopulationCount($this->municipalityUPModel->population_year_count)
            ->setCreatedAt(new DateTimeImmutable())
            ->setIsActivated(true)
            ->setZipCode($this->municipalityUPModel->zip_code)
            ->setStreet($this->municipalityUPModel->street)
            ->setBuildingNumber($this->municipalityUPModel->building_number)
            ->setEmail($this->municipalityUPModel->email);
        $this->apiEntityManager->persist($municipality);
        $this->apiEntityManager->flush();
        return [
            "data" => [
                "messages" => "Municipality_updated"
            ]

        ];
    }

    public function delete($codeMunicipality)
    {
        $municipality = $this->apiEntityManager->getRepository(Municipality::class)
            ->findOneBy(['code' => $codeMunicipality]);

        if (!$municipality) {
            throw new \Exception("invalid_municipality_code", 1);
        }
        $this->apiEntityManager->remove($municipality);
        $this->apiEntityManager->flush();
        return [
            "data" => [
                'messages' => "municipality_deleted_successfuly",
            ]
        ];
    }
}
