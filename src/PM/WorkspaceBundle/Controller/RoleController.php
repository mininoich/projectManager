<?php

namespace PM\WorkspaceBundle\Controller;

use PM\WorkspaceBundle\Form\RoleType;
use PM\WorkspaceBundle\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RoleController extends Controller
{
    public function indexAction()
    {
        $roles = $this->getDoctrine()->getRepository('PMWorkspaceBundle:Role')->findAll();
        return $this->render('PMWorkspaceBundle:Role:index.html.twig', array('roles' => $roles));
    }
    
    public function addAction(){
        $role = new Role();
        return $this->form($role, 'add');
    }
    
    public function editAction(Role $role){
        return $this->form($role, 'edit');
    }
    
    private function form($role, $action){
        $form = $this->createForm(new RoleType(), $role);
        
        
        $request = $this->getRequest();
        if($request->getMethod() === "POST"){
            $form->bind($request);
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($role);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('success', 'Rôle enregistré avec succès');
                return $this->redirect($this->generateUrl('pm_role_index'));
            }
        }
        
        return $this->render('PMWorkspaceBundle:Role:form.html.twig', array('form' => $form->createView(), 'action' => $action));
    }
    
    public function deleteAction(Role $role){
        // On vérifie si le rôle n'est pas déjà utilisé pour au moins un projet
        $affectations = $role->getUserRoleWorkspace();
        $num = count($affectations);
        
        if($num > 0){
            $this->get('session')->getFlashBag()->add('danger', 'Impossible de supprimer ce rôle car il est utilisé');
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($role);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Rôle supprimé avec succès');
        }
        
        return $this->redirect($this->generateUrl('pm_role_index'));
    }
}
