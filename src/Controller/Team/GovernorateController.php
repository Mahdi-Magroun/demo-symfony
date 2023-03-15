<?php

namespace App\Controller\Team;

use App\Manager\GovernorateManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GovernorateController extends AbstractController
{
    private GovernorateManager $manager;

    public function __construct(GovernorateManager $manager)
    {
        $this->manager = $manager ;
    }
 /**
  * @Route("/team/governorate",name="_team_governorate",methods={"GET"})
  */
    public function getManys(): Response
    {
        return  $this->manager->init()
                ->getMany();
    }
}
