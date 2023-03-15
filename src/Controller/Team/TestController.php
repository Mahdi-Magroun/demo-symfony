<?php

namespace App\Controller\Team;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class TestController extends AbstractController
{
    /**
     * @IsGranted("ROLE_TEAM")
     */
    #[Route('/test', name: 'app_tests')]
    public function index(): Response
    {
        return new JsonResponse(['status'=>"Success"]);
    }
}
