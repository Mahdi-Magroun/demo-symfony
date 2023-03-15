<?php
namespace App\Manager;

use App\Entity\Team;
use DateTimeImmutable;
use App\Entity\Gouvernorate;
use App\Entity\Municipality;
use App\Entity\MunicipalityAgent;
use Doctrine\Persistence\ManagerRegistry;
use SSH\MyJwtBundle\Manager\ExceptionManager;
use Symfony\Component\Security\Core\Security;
use App\ApiModel\Municipality\MunicipalityCreate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class MunicipalityManager extends AbstractManager{
    public Security $security;
    public MunicipalityCreate $municipalityCRModel;

    public function __construct(
        ManagerRegistry $entityManager,
        ExceptionManager $exceptionManager,
        RequestStack $requestStack,
        Security $security
      
    )
    {
        $this->security=$security;
        parent::__construct($entityManager, $exceptionManager, $requestStack);
    }

    public function init($settings=[]){
        $this->municipalityCRModel = $this->request->get("Municipality");
        parent::setSettings($settings);
        return $this; 
    }

    /**
     * 1- Create a Municipality : done 
     * 2- Create a president with a default password : done 
     * 3- send an creadention to the president :sprint2 
     */
    public function create(){
        // verify governorate 
        $governorate = $this->apiEntityManager->getRepository(Gouvernorate::class)
        ->findOneBy(["code"=>$this->municipalityCRModel->gouvernorate]);
        if(!$governorate){
            throw new \Exception("gouvernorate_not_found_exception", 1);
        }
        $municipality = new Municipality();
        $municipality->setGovernorate($governorate)
        ->setCreator(
            $this->apiEntityManager->getRepository(Team::class)->findOneBy(["code"=>$this->security->getUser()->getCode()])

        )
        ->setFrenshName($this->municipalityCRModel->frensh_name)
        ->setArabicName($this->municipalityCRModel->arabic_name)
        ->setPhoneNumber(strval( $this->municipalityCRModel->phone_number))
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
        ->setLastName($this->municipalityCRModel->president_last_name)
        ->setEmail($this->municipalityCRModel->president_email)
        ->setCin($this->municipalityCRModel->president_cin)
        ->setIsActivated(true)
        ->setRole("ROLE_MUNICIPALITY_PRESIDENT"); 
        // generate random password 
            
        $pwdLength=10;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ&$*%:\/-+';
        $password = '';
        for ($i = 0; $i < $pwdLength; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $password .= $characters[$index];
        }

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
            throw new \Exception("error_persisting_data :".$th->getMessage(), 1);
        }
        return new JsonResponse([
            "status"=>"Success",
            "message"=>"President&&Municipality successfuly created ",
            "result"=>[
                "municipality_code"=>$municipality->getCode(),
                "president_code"=>$president->getCode()
            ]
        ],201);
    
            
        
    }

    public function getOneDetail($code){
        $municipality = $this->apiEntityManager->getRepository(Municipality::class)
            ->findOneBy(["code"=>$code]);
        if (!$municipality) {
            throw new \Exception("invalid_municipality_code", 1);  
        }
        $municipalityPresident = $this->apiEntityManager->getRepository(MunicipalityAgent::class)
        ->findOneBy(["municipality"=>$municipality]);
        return new JsonResponse([
            "result"=>[
                "governorate"=>[
                    "gouvernorate_code"=>$municipality->getGovernorate()->getCode(),
                    "gouvernorate_frensh_name"=>$municipality->getGovernorate()->getFrenshName(),
                    "gouvernorate_frensh_name"=>$municipality->getGovernorate()->getArabicName(),
                    "nationl_id"=>$municipality->getGovernorate()->getNationalId()
                ],
                "address"=>[
                    "zip_code"=>$municipality->getZipCode(),
                    "street"=>$municipality->getStreet(),
                    "building_number"=>$municipality->getBuildingNumber()
                ],
                "president"=>[
                    "first_name"=>$municipalityPresident->getFirstName(),
                    "last_name"=>$municipalityPresident->getLastName(),
                    "email"=>$municipalityPresident->getEmail()
                ],
               
                "municipality_code"=>$municipality->getCode(),
                "isActivated"=>$municipality->isIsActivated(),
                "national_municipality_id"=>$municipality->getNationalId(),
                "frensh_name"=>$municipality->getFrenshName(),
                "arabic_name"=>$municipality->getArabicName(),
                "web_site"=>$municipality->getWebSite(),
                "phone_number"=>$municipality->getPhoneNumber(),
                
            ]
           
        ]);

    }

}