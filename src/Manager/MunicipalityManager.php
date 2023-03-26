<?php

namespace App\Manager;

use DateTime;
use App\Entity\Team;
use App\Entity\Governorate;
use App\Entity\Municipality;
use App\Manager\AbstractManager;
use App\Entity\MunicipalityAgent;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Serializer;
use SSH\MyJwtBundle\Manager\ExceptionManager;
use Symfony\Component\Security\Core\Security;
use App\ApiModel\Municipality\MunicipalityCreate;
use App\ApiModel\Municipality\MunicipalityUpdate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class MunicipalityManager extends AbstractManager
{
    public Security $security;
    public MunicipalityCreate $municipalityCRModel;
    public MunicipalityUpdate $municipalityUPModel;

    private SerializerInterface $serializer;
    private NormalizerInterface $normalizer;

    public function __construct(
        ManagerRegistry $entityManager,
        ExceptionManager $exceptionManager,
        RequestStack $requestStack,
        Security $security,
        NormalizerInterface $normalizer

    ) {
        $this->security = $security;
        parent::__construct($entityManager, $exceptionManager, $requestStack);
        $this->normalizer = $normalizer;
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
        $municipalityCr = (array) $this->municipalityCRModel;
        $this->findObjects($municipalityCr, ["governorate"]);


        $municipalityCr["creator"] = $this->apiEntityManager->getRepository(Team::class)->findOneBy(["code" => $this->security->getUser()->getCode()]);

        $municipality = new Municipality($municipalityCr);
        // create president
        $president = new MunicipalityAgent();
        $president->setFirstName($this->municipalityCRModel->president_first_name)
            ->setDateBegin(new DateTime($this->municipalityCRModel->president_date_begin))
            ->setCreatedAt(new DateTime("now"))
            ->setDateEnd(new DateTime($this->municipalityCRModel->president_date_end))
            ->setLastName($this->municipalityCRModel->president_last_name)
            ->setEmail($this->municipalityCRModel->president_email)
            ->setCin($this->municipalityCRModel->president_cin)
            ->setIsActivated(true)
            ->setRole("ROLE_MUNICIPALITY_PRESIDENT")
            ->setPassword($this->generateRandomPassword());
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

       $creator = $municipality->getCreator();
       $data=[];
       $data['municipality']= $this->normalizer->normalize($municipality, null,['groups' => ['show_municipality','show_no_credentials']]);
       $data['president']= $this->normalizer->normalize ($municipalityPresident, null,['groups' => 'show_no_credentials']);
       
        return [
            "data" => [
             $data
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
        if ($municipality->getIsActivated()) {
            $municipality->setIsActivated(false);
        } else {
            $municipality->setIsActivated(true);
        }
        $this->apiEntityManager->persist($municipality);
        $this->apiEntityManager->flush();
        $status = ($municipality->getIsActivated()) ? "unblocked" : "blocked";
        return [
            "data" => [
                "messages" => "municipality is " . $status,
            ]

        ];
    }

    public function update($code)
    {
        $municipalityFomUser = (array) $this->municipalityUPModel;
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

            );
       
        $municipalityFomUser = array_merge($municipalityFomUser, ["governorate" => $governorate, "updator" => $this->apiEntityManager->getRepository(Team::class)->findOneBy(["code" => $this->security->getUser()->getCode()])]);
        $this->updateObject($municipality, $municipalityFomUser);

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

    public function generateRandomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}
