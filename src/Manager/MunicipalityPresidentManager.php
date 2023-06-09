<?php

namespace App\Manager;

use DateTime;
use App\Entity\Municipality;
use App\Entity\MunicipalityAgent;
use Doctrine\Persistence\ManagerRegistry;
use SSH\MyJwtBundle\Manager\ExceptionManager;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use App\ApiModel\Municipality\President\PresidentCreate;
use App\ApiModel\Municipality\President\PresidentUpdate;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MunicipalityPresidentManager extends AbstractManager
{

    private Security $security;
    private PresidentCreate $presidentCRModel;
    private PresidentUpdate $presidentUPModel;

    private NormalizerInterface $normalizer;

    public function __construct(
        ManagerRegistry $entityManager,
        ExceptionManager $exceptionManager,
        RequestStack $requestStack,
        Security $security,
        NormalizerInterface $normalizer

    ) {
        $this->security = $security;
        $this->normalizer = $normalizer;
        parent::__construct($entityManager, $exceptionManager, $requestStack);
    }
    public function init(string $method, $settings = [])
    {
        parent::setSettings($settings);
        if ($method == "create") {
            $this->presidentCRModel = $this->request->get("MunicipalityPresident");
        }
        if ($method == "update") {
            # code...
            $this->presidentUPModel = $this->request->get("MunicipalityPresident");
        }
        return $this;
    }
    public function getOneDetail($code)
    {
        $president = $this->getMunicipalityPresident($code);
        if (!$president) {
            throw new \Exception("no_municipality_president_found", 1);
        }

        $data =  $this->normalizer->normalize($president, null, [
            "groups" => ["president_details"]
        ]);
        $municipality = $data['municipality'];
        unset($data['municipality']);
        return [
            "data" => [
                "municipality_president" => $data,
                "municipality" => $municipality
            ]
        ];
    }

    /**
     * filter based on (municipality,is_activated,first_name,last_name,cin) 
     * 
     */
    public function getMany()
    {

        $municipality = null;
        // check if municipality code is valid :: filter by municipality code
        if ($this->request->query->get('municipality_code')) {
            $municipality = $this->apiEntityManager->getRepository(Municipality::class)
                ->findOneBy(["code" => $this->request->query->get('municipality_code')]);
            if (!$municipality) {
                throw new \Exception("invalid_municiality_code", 1);
            }
        }
        $filter = [
            "municipality_id" => ($municipality) ? $municipality->getId() : null,
            "is_activated" => $this->request->query->get('is_activated'),
            "first_name" => $this->request->query->get('first_name'),
            "last_name" => $this->request->query->get('last_name'),
            "cin" => $this->request->query->get('cin'),
            "role" => $this->request->query->get('role'),
        ];
        $presidents =  $this->apiEntityManager->getRepository(MunicipalityAgent::class)
            ->findManyAgents($filter);
        return [
            "data" => [
                "presidents" => $presidents
            ]


        ];
    }
    /** Creation steps : 
     *  1-verify if there is active president && is_activated = true : throw
     *  2-create a new user and generate a random password 
     *  3-send an email that contain the password to the president : sprint2 
     * 
     */
    public function create()
    {
        $presidentCRModel = (array)$this->presidentCRModel;

        // verify if the municipality is valid : throw if not
        $municipality = $this->apiEntityManager->getRepository(Municipality::class)
            ->findOneBy(['code' => $presidentCRModel['municipality']]);
        if (!$municipality)
            throw new \Exception("municipality_not_found", 1);
        //verify if there is an active user  : throw if there is an active user 
        $oldPresident = $this->apiEntityManager->getRepository(MunicipalityAgent::class)
            ->findOneBy(["municipality" => $municipality, "isActivated" => true]);
        if ($oldPresident && $presidentCRModel['is_activated'] == true) {
            throw new \Exception("only_one_active_user_should_be_provided", 1);
        }
        // create a new president from prsidnetCRModel

        $presidentCRModel['municipality'] = $municipality;
        $this->formatDatetime($presidentCRModel);
        $newPresident = new MunicipalityAgent($presidentCRModel);
        $newPresident->setRole("ROLE_MUNICIPALITY_PRESIDENT")
            ->setPassword($this->generateRandomPassword())
            ->setCreatedAt(new DateTime());


        $this->apiEntityManager->persist($newPresident);
        $this->apiEntityManager->flush();
        return [
            "data" => [
                "messages" => "president created successfuly ",
                "president_code" => $newPresident->getCode()
            ]
        ];
    }

    public function update($code)
    {
        $presidentUPModel = (array)$this->presidentUPModel;
        $president = $this->getMunicipalityPresident($code);
        if (!$president)
            throw new \Exception("presedent_not_found", 1);
        # verify if there is an active president: throw if there is an active president
        if ($president->getIsActivated() == false && $presidentUPModel['is_activated'] == true) {

            $activePresident = $this->apiEntityManager->getRepository(MunicipalityAgent::class)
                ->findOneBy(['role' => "ROLE_MUNICIPALITY_PRESIDENT", "isActivated" => true, "municipality" => $president->getMunicipality()]);
            if ($activePresident) {
                throw new \Exception("only_one_active_user_should_be_provided", 1);
            }
        }
        // update president from presidentUPModel
        $this->formatDatetime($presidentUPModel);
        $this->updateObject($president, $presidentUPModel);
        $this->apiEntityManager->persist($president);
        $this->apiEntityManager->flush();
        return [
            "data" => [
                "messages" => "president updated successfuly",
                "president_code" => $president->getCode()
            ]
        ];
    }

    # block or unblock a president
    public function activation($agentCode)
    {

        $agent = $this->getMunicipalityPresident($agentCode);
        if (!$agent)
            throw new \Exception("municipality_president_not_found", 1);
        if ($agent->getIsActivated()) {
            $agent->setIsActivated(false);
        } else {
            $agent->setIsActivated(true);
        }
        $this->apiEntityManager->persist($agent);
        $this->apiEntityManager->flush();
        $status = ($agent->getIsActivated()) ? "unblocked" : "blocked";
        return [
            "data" => [
                "messages" => "municipality president  is " . $status
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

    public function getMunicipalityPresident($code)
    {
        $president = $this->apiEntityManager->getRepository(MunicipalityAgent::class)
            ->findOneBy(['role' => "ROLE_MUNICIPALITY_PRESIDENT", "code" => $code]);
        if (!$president)
            throw new \Exception("president_not_found", 1);
        return $president;
    }
}
