<?php

namespace PM\WorkspaceBundle\Controller;

use PM\WorkspaceBundle\Entity\Role;
use PM\WorkspaceBundle\Entity\Workflow;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WorkflowController extends Controller
{
    public function editAction(Role $role){
        
        
        // Récupération des statuts
        $status = $this->getDoctrine()->getManager()->getRepository('PMWorkspaceBundle:Status')->findAll();
        
        // Récupération des workflows déjà persistés
        $originalWorkflows = $role->getWorkflows();
        
        // Création du formulaire 
        $form = $this->createFormBuilder();
        foreach($status as $statusFrom){
            foreach($status as $statusTo){
                if($statusFrom->getId() != $statusTo->getId()){
                    $form->add('status_'.$statusFrom->getId().'_'.$statusTo->getId(), 'checkbox', array('required' => false ,'mapped' => false));
                    
                    
                }
            }
        }
        $form = $form->getForm();
        
       // On coche les workflows déjà persistés
        $originalWorkflowsIds = array();
        foreach($originalWorkflows as $ow){
            $originalWorkflowsIds[] = $ow->getOldStatus()->getId().'_'.$ow->getNewStatus()->getId();
            $form->get('status_'.$ow->getOldStatus()->getId().'_'.$ow->getNewStatus()->getId())->setData(true);
        }
       
        
        $request = $this->getRequest();
        if($request->getMethod() === "POST"){
                $form->handleRequest($request);
                $em = $this->getDoctrine()->getManager();
                foreach($status as $statusFrom){
                    foreach($status as $statusTo){
                        if($statusFrom->getId() != $statusTo->getId()){
                            if($form->get('status_'.$statusFrom->getId().'_'.$statusTo->getId())->getData() == true){
                                    if(!in_array($statusFrom->getId().'_'.$statusTo->getId(), $originalWorkflowsIds)){
                                        $workflow = new Workflow();
                                        $workflow->setOldStatus($statusFrom);
                                        $workflow->setNewStatus($statusTo);
                                        $workflow->setRole($role);
                                        $em->persist($workflow);
                                    }
                            } else {
                                foreach($originalWorkflows as $ow){
                                    if($ow->getOldStatus()->getId() == $statusFrom->getId() && $ow->getNewStatus()->getId() == $statusTo->getId()){
                                        $em->remove($ow);
                                    }
                                }
                            }
                        }
                    }
                }
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('success', 'Workflow enregistré avec succès');
                return $this->redirect($this->generateUrl('pm_role_index'));
            }
        return $this->render('PMWorkspaceBundle:Workflow:form.html.twig', array('form' => $form->createView(), 'status' => $status));
    }
}
