<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AuthenticateListener
{

    private $backuserManager;
    private $clientManager;

    public function __construct(
            TranslatorInterface $translator,
            \App\Manager\BackUserManager $backuserManager,
            \App\Manager\ClientManager $clientManager
    )
    {
        $this->translator = $translator;
        $this->backuserManager = $backuserManager;
        $this->clientManager = $clientManager;
    }

    /**
     * Handle the output from the controllers.
     *
     * @param GetResponseForControllerResultEvent $event
     */
    public function onKernelView(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();
        $request = $event->getRequest();
        $route = $request->get('_route');
        $informations = [];

        if ($route == 'jwt_loginpassword_authenticate') {

            $code = $result['user']['code'];
            $roles = $result['user']['roles'];
            unset($result['user']);

            if (in_array('ROLE_BACK', $roles)) {

//                $informations = $this->backuserManager
//                        ->init(['code' => $code])
//                        ->getInformations();
            }

            if (in_array('ROLE_FRONT', $roles)) {

//                $informations = $this->clientManager
//                        ->init(['userCode' => $code])
//                        ->getInformations();
            }
        }

        $event->setControllerResult($informations + $result);
    }

}
