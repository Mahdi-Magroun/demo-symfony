<?php

namespace App\DataFixtures;

use App\Entity\Municipality;
use App\Entity\MunicipalityAgent;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MunicipalityAgentsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
    //     $municipality= $manager->getRepository(Municipality::class)->findOneById(1);

    //     for ($i=0; $i <20 ; $i++) { 
    //         # code...
    //         $teamMember = new MunicipalityAgent();
    //         $teamMember->setMunicipality($municipality);
    //         $teamMember->setEmail("user$i@gmail.com");

    //         if ($i>=10) {
    //             # code...
    //             $teamMember->setIsActivated(false);
    //             $teamMember->setRoles(json_encode(['ROLE_SIMPLEAGENT']));
    //         }
    //         else{
    //             $teamMember->setIsActivated(true);
    //             $teamMember->setRoles(json_encode(['ROLE_PRISIDENT']));
    //         }
            
    //         $teamMember->setLastName("mylatename$i");
    //         $teamMember->setFirstName("user$i");
    //         $teamMember->setPassword("password".$i); 
    //         $teamMember->setCin(10101010+$i);
    //         $manager->persist($teamMember);
    //     }
       
    //             $manager->flush();
     }
}
