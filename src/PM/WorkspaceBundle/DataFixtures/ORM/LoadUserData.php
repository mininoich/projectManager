<?php

namespace PM\WorkspaceBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PM\WorkspaceBundle\Entity\Category;
use PM\WorkspaceBundle\Entity\Role;
use PM\WorkspaceBundle\Entity\Status;
use PM\WorkspaceBundle\Entity\Workspace;

class LoadUserData implements FixtureInterface, OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        
        // Création des statuts
        $statusNouveau = new Status();
        $statusNouveau->setName('Nouveau')
                ->setDefaultValue(true);
        
        $statusEnCours = new Status();
        $statusEnCours->setName('En cours');
        
        $statusAValider = new Status();
        $statusAValider->setName('A valider');
        
        $statusAPreRecetter = new Status();
        $statusAPreRecetter->setName('A pré-recetter');
        
        $statusARecetter = new Status();
        $statusARecetter->setName('A recetter');
        
        $statusTermine = new Status();
        $statusTermine->setName('Terminé');
        
        $manager->persist($statusNouveau);
        $manager->persist($statusEnCours);
        $manager->persist($statusAValider);
        $manager->persist($statusAPreRecetter);
        $manager->persist($statusARecetter);
        $manager->persist($statusTermine);
        
        
        // Création des rôles
        $roleDeveloppeur = new Role();
        $roleDeveloppeur->setName('Developpeur');
        
        $roleRecette = new Role();
        $roleRecette->setName('Recette');
        
        $roleSuperviseur = new Role();
        $roleSuperviseur->setName('Superviseur');
        
        $roleManager = new Role();
        $roleManager->setName('Manager');
        
        $manager->persist($roleDeveloppeur);
        $manager->persist($roleRecette);
        $manager->persist($roleSuperviseur);
        $manager->persist($roleManager);
        
        // Création des catégories
        $categorieDeveloppement = new Category();
        $categorieDeveloppement->setName('Développement');
        
        $manager->persist($categorieDeveloppement);
        
        // Création d'un workspace exemple
        $workspace = new Workspace();
        $workspace->setName('Mon 1er projet');
        $manager->persist($workspace);
        
        $manager->flush();
    }
    
    public function getOrder(){
        return 2;
    }
}

