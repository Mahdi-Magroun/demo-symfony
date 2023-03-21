<?php
namespace App\Manager;

use App\ApiModel\Municipality\President\PresidentCreate;
use App\ApiModel\Municipality\President\PresidentUpdate;
use App\Entity\Municipality;
use App\Entity\MunicipalityAgent;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use SSH\MyJwtBundle\Manager\ExceptionManager;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class MunicipalityPresidentManager extends AbstractManager{

    private Security $security;
    private PresidentCreate $presidentCRModel;
    private PresidentUpdate $presidentUPModel;
    
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
            $this->presidentCRModel =$this->request->get("MunicipalityPresident");
        }
        if ($method=="update") {
            # code...
            $this->presidentUPModel =$this->request->get("MunicipalityPresident");
            
        }
        return $this;
    }
    public function getOneDetail($code){
        $president = $this->apiEntityManager->getRepository(MunicipalityAgent::class)->findOneBy([
            "role"=>"ROLE_MUNICIPALITY_PRESIDENT",
            "code"=>$code
        ]);
        if (!$president) {
           throw new \Exception("no_municipality_president_found", 1);
           
        }
        return [
            "data"=>[
            "president"=>[
                "code"=>$president->getCode(),
                "first_name"=>$president->getFirstName(),
                "last_name"=>$president->getLastName(),
                "email"=>$president->getEmail(),
                "cin"=>$president->getCin(),
                "created_at"=>null,
                "updated_at"=>null,
                "is_activated"=>$president->isIsActivated(),
                "date_begin"=>$president->getDateBegin()->format('Y-m-d'),
                "date_end"=>$president->getDateEnd()->format('Y-m-d'),
                "municipality"=>[
                    "code"=>$president->getMunicipality()->getCode(),
                    "frensh_name"=>$president->getMunicipality()->getFrenshName(),
                    "arabic_name"=>$president->getMunicipality()->getArabicName(),
                ]
            ]
            ]
        ];
    }

       /**
    * filter based on (municipality,is_activated,first_name,last_name,cin) 
    * 
    */
    public function getMany(){
        $thereIsMunicipality = false;
        
        if ($this->request->query->get('municipality_code') ) {
           $municipality= $this->apiEntityManager->getRepository(Municipality::class)
            ->findOneBy(["code"=>$this->request->query->get('municipality_code') ]);
            if (!$municipality) {
                throw new \Exception("invalid_municiality_code", 1);
            }
            $thereIsMunicipality =true;
        }
        $filter = [
            "municipality_id"=> ($thereIsMunicipality) ? $municipality->getId() : null,
            "is_activated"=>$this->request->query->get('is_activated') ,
            "first_name"=>$this->request->query->get('first_name'),
            "last_name"=>$this->request->query->get('last_name'),
            "cin"=>$this->request->query->get('cin'),
            "role"=>$this->request->query->get('role'),
         ];    
         $presidents =  $this->apiEntityManager->getRepository(MunicipalityAgent::class)
         ->findManyAgents($filter);
         return [
            "data"=>[
                "presidents"=>$presidents
            ]
            
           
         ];

   }
/** Creation steps : 
 *  1-verify if there is active president && is_activated = true : throw
 *  2-create a new user and generate a random password 
 *  3-send an email that contain the password to the president : sprint2 
 * 
 */
    public function create(){
        // verify if the municipality is valid 
        $municipality = $this->apiEntityManager->getRepository(Municipality::class)
        ->findOneBy(['code'=>$this->presidentCRModel->municipality_code]);
        if(!$municipality)
            throw new \Exception("municipality_not_found", 1);
        //verify if there is an active user 
        $oldPresident = $this->apiEntityManager->getRepository(MunicipalityAgent::class)
        ->findOneBy(["municipality"=>$municipality,"isActivated"=>true]);
        if ($oldPresident && $this->presidentCRModel->is_activated) {
            throw new \Exception("only_one_active_user_should_be_provided", 1); 
        }   
        // generate random password 
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+';

        // Shuffle the characters to make it more random
        $characters = str_shuffle($characters);

     // Get the first $length characters of the shuffled string
     $password = substr($characters, 0, 12);

        $newPresident = new MunicipalityAgent();
        $newPresident->setMunicipality($municipality)
        ->setIsActivated($this->presidentCRModel->is_activated)
        ->setFirstName($this->presidentCRModel->first_name)
        ->setLastName($this->presidentCRModel->last_name)
        ->setEmail($this->presidentCRModel->email)
        ->setDateBegin(new DateTimeImmutable($this->presidentCRModel->date_begin))
        ->setDateEnd(new DateTimeImmutable($this->presidentCRModel->date_end))
        ->setCin($this->presidentCRModel->cin)
        ->setIsActivated($this->presidentCRModel->is_activated)
        ->setRole("ROLE_MUNICIPALITY_PRESIDENT")
        ->setPassword($password)
        ->setCreatedAt(new DateTimeImmutable());
        $this->apiEntityManager->persist($newPresident);
        $this->apiEntityManager->flush();
        return [
            "data"=>[
                "messages"=>"president created successfuly ",
                "president_code"=>$newPresident->getCode()
            ]
            ];

    }

    public function update($code){
        $president = $this->apiEntityManager->getRepository(MunicipalityAgent::class)
        ->findOneBy(['role'=>"ROLE_MUNICIPALITY_PRESIDENT","code"=>$code]);
        if(!$president)
            throw new \Exception("presedent_not_found", 1);
        if ($president->isIsActivated()==false && $this->presidentUPModel->is_activated == true) {
            # verify if there is an active president
            $activePresident = $this->apiEntityManager->getRepository(MunicipalityAgent::class)
            ->findOneBy(['role'=>"ROLE_MUNICIPALITY_PRESIDENT","isActivated"=>true,"municipality"=>$president->getMunicipality()]);
            if ($activePresident) {
                throw new \Exception("only_one_active_user_should_be_provided", 1);
                
            }
        }
        $president->setIsActivated($this->presidentUPModel->is_activated)
        ->setFirstName($this->presidentUPModel->first_name)
        ->setLastName($this->presidentUPModel->last_name)
        ->setEmail($this->presidentUPModel->email)
        ->setDateBegin(new DateTimeImmutable($this->presidentUPModel->date_begin))
        ->setDateEnd(new DateTimeImmutable($this->presidentUPModel->date_end))
        ->setCin($this->presidentUPModel->cin)
        ->setIsActivated($this->presidentUPModel->is_activated)
        ->setUpdatedAt(new DateTimeImmutable());
        $this->apiEntityManager->persist($president);
        $this->apiEntityManager->flush();
        return [
            "data"=>[
                "messages"=>"president updated successfuly",
                "president_code"=>$president->getCode()
            ]
        ];
    }

    public function activation($agentCode){
       
         $agent = $this->apiEntityManager->getRepository(MunicipalityAgent::class)
                        ->findOneBy(['code'=>$agentCode]);
        if(!$agent)  
            throw new \Exception("municipality_president_not_found", 1);
         if ($agent->isIsActivated()) {
             $agent->setIsActivated(false);
            
             }
         else{
            $agent->setIsActivated(true);
        
         }
         $this->apiEntityManager->persist($agent);
         $this->apiEntityManager->flush();
        $status =($agent->isIsActivated())?"unblocked":"blocked";
         return [
            "data"=>[
                "messages"=>"municipality president  is ".$status
            ]
         ];
    }
}