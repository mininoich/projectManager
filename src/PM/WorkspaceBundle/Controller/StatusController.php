<?php

namespace PM\WorkspaceBundle\Controller;

use PM\WorkspaceBundle\Form\StatusType;
use PM\WorkspaceBundle\Entity\Status;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StatusController extends Controller
{
    public function indexAction()
    {
        $status = $this->getDoctrine()->getRepository('PMWorkspaceBundle:Status')->findAll();
        return $this->render('PMWorkspaceBundle:Status:index.html.twig', array('status' => $status));
    }
    
    public function addAction(){
        $status = new Status();
        return $this->form($status, 'add');
    }
    
    public function editAction(Status $status){
        return $this->form($status, 'edit');
    }
        
     private function form($status, $action){
        $form = $this->createForm(new StatusType(), $status);
        
        
        $request = $this->getRequest();
        if($request->getMethod() === "POST"){
            $form->bind($request);
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($status);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('success', 'Statut enregistré avec succès');
                return $this->redirect($this->generateUrl('pm_status_index'));
            }
        }
        
        return $this->render('PMWorkspaceBundle:Status:form.html.twig', array('form' => $form->createView(), 'action' => $action));
    }
    
    public function deleteAction(Status $status){
        // On vérifie si le rôle n'est pas déjà utilisé pour au moins un projet
        $affectations = $status->getTaskStatus();
        $num = count($affectations);
        
        if($num > 0){
            $this->get('session')->getFlashBag()->add('danger', 'Impossible de supprimer ce statut car il est utilisé');
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($status);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Statut supprimé avec succès');
        }
        
        return $this->redirect($this->generateUrl('pm_status_index'));
    }
}
