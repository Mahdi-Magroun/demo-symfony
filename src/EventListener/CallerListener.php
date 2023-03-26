<?php

namespace App\EventListener;

use App\Entity\Team;
use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Comapny;
use App\Entity\BackUser;
use App\Manager\CompanyManager;
use App\Entity\MunicipalityAgent;
use Doctrine\Bundle\DoctrineBundle\Registry;
use SSH\MyJwtBundle\Manager\ExceptionManager;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Caller Listener.
 */
class CallerListener
{

    /**
     * @var ExceptionManager
     */
    private $exceptionManager;

    /**
     * @var apiEntityManager
     */
    private $apiEntityManager;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage
     */
    private $tokenStorage;

    /**
     *
     * @var type
     */
    private $unchekedRoutes = [
        'jwt_token_authenticate',
        'jwt_loginpassword_authenticate',
        'home',
        'nelmio_api_doc.swagger_ui',
    ];

    /**
     * Constructor.
     *
     * @param ModelFactory $modelFactory
     */
    public function __construct(Registry $entityManager, ExceptionManager $exceptionManager, TokenStorageInterface $tokenStorage)
    {
        $this->apiEntityManager = $entityManager;
        $this->exceptionManager = $exceptionManager;
        $this->tokenStorage = $tokenStorage;
       
    }

    /**
     * On kernet request call object manager.
     */
    public function onKernelRequest(ControllerEvent $event)
    {


        $request = $event->getRequest();
        $route = $request->get('_route');

        if (
                $this->tokenStorage->getToken() &&
                $this->tokenStorage->getToken()->getUser() &&
                !in_array($route, $this->unchekedRoutes)
        ) {
            $wsUser = $this->tokenStorage->getToken()->getUser();
           

            if (is_object($wsUser)) {
                if (in_array('ROLE_TEAM', $wsUser->getRoles())) {
                   
                    $user = $this->apiEntityManager
                    ->getRepository(Team::class)
                    ->findOneBy(['email'=>$wsUser->getUsername(),'password'=>$wsUser->getPassword()]);;
//            
                    $request->attributes->set('teamCaller', $user);
                }

                if (in_array('ROLE_MUNICIPALITY', $wsUser->getRoles())) {

                    if (is_a($wsUser, \SSH\MsJwtBundle\Entity\ApiUser::class) && $wsUser->getUsername()) {

                        $companyuser = $this->apiEntityManager
                                ->getRepository(MunicipalityAgent::class)
                                ->findOneBy(['email'=>$wsUser->getUsername(),'password'=>$wsUser->getPassword()]);

                       

                        $request->attributes->set('municipalityAgentCaller', $companyuser);
                         
                    }
                }
            }
        }
    }

}
