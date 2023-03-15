<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TeamFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i=0; $i <20 ; $i++) { 
            # code...
            $teamMember = new Team();
            $teamMember->setEmail("user$i@gmail.com");
            if ($i>=10) {
                # code...
                $teamMember->setIsActivated(false);
            }
            else{
                $teamMember->setIsActivated(true);
            }
            
            $teamMember->setLastName("mylatename$i");
            $teamMember->setFirstName("user$i");
            $teamMember->setPassword("password".$i); 
            $teamMember->setRole(json_encode(['ROLE_TEAM']));
            $manager->persist($teamMember);
        }
       
                $manager->flush();
    }
}
