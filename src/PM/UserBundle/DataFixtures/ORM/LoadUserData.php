<?php

namespace PM\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PM\UserBundle\Entity\User;

class LoadUserData implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        
        //$encoder = $this->container->get('security.encoder_factory')->getEncoder($userAdmin);
        
        $userAdmin->setPlainPassword('admin');
        $userAdmin->setEmail('admin@yopmail.com');
        $userAdmin->setEnabled(true);
        $userAdmin->setAdmin(true);
        $userAdmin->addRole('ROLE_ADMIN');
        $manager->persist($userAdmin);
        $manager->flush();
    }
    
    public function getOrder(){
        return 1;
    }
}

