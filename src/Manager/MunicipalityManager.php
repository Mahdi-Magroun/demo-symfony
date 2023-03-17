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
use App\ApiModel\Municipality\MunicipalityUpdate;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class MunicipalityManager extends AbstractManager{
    public Security $security;
    public MunicipalityCreate $municipalityCRModel;
    public MunicipalityUpdate $municipalityUPModel;

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

    public function init(string $method,$settings=[]){
        parent::setSettings($settings);
        if ($method=="create") {
            # code...
            $this->municipalityCRModel = $this->request->get("Municipality");
        }
        elseif ($method=="update") {
           $this->municipalityUPModel=$this->request->get("Municipality");
        }
        elseif ($method=="getMany") {
            # code...
        }
        
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
        ->setIsCurentlyActivated(true)
        ->setLastName($this->municipalityCRModel->president_last_name)
        ->setEmail($this->municipalityCRModel->president_email)
        ->setCin($this->municipalityCRModel->president_cin)
        ->setIsActivated(true)
        ->setRole("ROLE_MUNICIPALITY_PRESIDENT"); 
        // generate random password 
            
        $pwdLength=10;
        $characters = '&$*%:\/-+0123456789abcdefg&$*%:\/-+hijklmnopqrstuvwx&$*%:\/-+yzABCDEFGHIJKLMNOPQRSTUVWXYZ&$*%:\/-+';
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
        return[
            "status"=>"Success",
            "message"=>"President&&Municipality successfuly created ",
            "data"=>[
                "municipality_code"=>$municipality->getCode(),
                "president_code"=>$president->getCode()
            ]
        ];
    
            
        
    }

    public function getOneDetail($code){
        $municipality = $this->apiEntityManager->getRepository(Municipality::class)
            ->findOneBy(["code"=>$code]);
        if (!$municipality) {
            throw new \Exception("invalid_municipality_code", 1);  
        }
        $municipalityPresident = $this->apiEntityManager->getRepository(MunicipalityAgent::class)
        ->findOneBy(["municipality"=>$municipality]);
        return [
            "status"=>"Success",
            "message"=>"",
            "data"=>[
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
           
        ];

    }

    public function getMany(){
         $gouvernorateCode = $this->request->query->get('governorate_code');
         $governorate = $this->apiEntityManager->getRepository(Gouvernorate::class)
         ->findOneBy(['code'=>$gouvernorateCode]);
        
         $filter = [
            "governorate_id"=> ($governorate) ? $governorate->getId() : null,
            "frensh_name"=>$this->request->query->get('frensh_name') ,
            "arabic_name"=>$this->request->query->get('arabic_name'),
            "is_activated"=>$this->request->query->get('is_activated')
         ];    
         
        $municipalities = $this->apiEntityManager->getRepository(Municipality::class)
         ->findMany($filter,[]);
        return [
            "status"=>"Success",
            "message"=>"",
            "data"=>$municipalities
        ];
         
    }
    public function activation($municipalityCode){
        $parameter = (array)json_decode( $this->request->getContent());
        if(!isset($parameter['is_activated']))
            throw new \Exception("missing_parameter", 1);
            
        $municipality = $this->apiEntityManager->getRepository(Municipality::class)
                        ->findOneBy(['code'=>$municipalityCode]);
        if(!$municipality)  
            throw new \Exception("municipality_not_found", 1);
        $municipality->setIsActivated($parameter['is_activated']);
        $agents = $this->apiEntityManager->getRepository(MunicipalityAgent::class)
        ->findBy(["municipality"=>$municipality,"isCurentlyActivated"=>true]);
        
        //unblock and unblock agent  all agent
        foreach ($agents as $agent) {
            if ($parameter['is_activated']) {
                $agent->setIsActivated(true);
                }
            else
            $agent->setIsActivated(false);
        }



        $this->apiEntityManager->persist($municipality);
        $this->apiEntityManager->flush();
       $status =($municipality->isIsActivated())?"unblocked":"blocked";
        return new JsonResponse([
            "status"=>"updated",
            "message"=>"municipality is ".$status   ,
           
        ]);
            
    }

    public function update($code){
        $governorate = $this->apiEntityManager->getRepository(Gouvernorate::class)
        ->findOneBy(["code"=>$this->municipalityUPModel->gouvernorate]);
        if(!$governorate){
            throw new \Exception("gouvernorate_not_found_exception", 1);
        }
        $municipality = $this->apiEntityManager->getRepository(Municipality::class)
            ->findOneBy(["code"=>$code]);
        if (!$municipality) {
            throw new \Exception("invalid_municipality_code", 1);
        }
        $municipality->
        setGovernorate($governorate)
        ->setUpdator(
            $this->apiEntityManager->getRepository(Team::class)->findOneBy(["code"=>$this->security->getUser()->getCode()])

        )
        ->setFrenshName($this->municipalityUPModel->frensh_name)
        ->setArabicName($this->municipalityUPModel->arabic_name)
        ->setPhoneNumber(strval( $this->municipalityUPModel->phone_number))
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
            "status"=>"Success",
            "message"=>"Municipality_updated"
        ];

    }

    public function delete($codeMunicipality){
        $municipality= $this->apiEntityManager->getRepository(Municipality::class)
                        ->findOneBy(['code'=>$codeMunicipality]);

        if (!$municipality) {
            throw new \Exception("invalid_municipality_code", 1);
        }
        $this->apiEntityManager->remove($municipality);
        $this->apiEntityManager->flush();
        return [
            'message'=>"municipality_deleted_successfuly",
            "status"=>"Success"
        ];  
    }

}