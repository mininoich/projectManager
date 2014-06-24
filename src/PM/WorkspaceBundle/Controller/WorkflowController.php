<?php

namespace PM\WorkspaceBundle\Controller;

use PM\WorkspaceBundle\Entity\Workflow;
use PM\WorkspaceBundle\Entity\WorkflowsRoles;
use PM\WorkspaceBundle\Entity\Workspace;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WorkflowController extends Controller
{
    public function editAction(Workspace $workspace){
        
        
        // Récupération des statuts
        $status = $this->getDoctrine()->getManager()->getRepository('PMWorkspaceBundle:Status')->findBy(array('deleted' => false));
        
        // Récupération des roles
        $roles = $this->getDoctrine()->getManager()->getRepository('PMWorkspaceBundle:Role')->findAll();
        
        // Récupération des workflows déjà persistés
        $originalWorkflows = $workspace->getWorkflows();
        
        // Création du formulaire 
        $form = $this->createFormBuilder();
        foreach($status as $statusFrom){
            foreach($status as $statusTo){
                if($statusFrom->getId() != $statusTo->getId()){
                    foreach($roles as $role){
                        $form->add('status_'.$statusFrom->getId().'_'.$statusTo->getId().'_'.$role->getId(), 'checkbox', array('required' => false ,'mapped' => false, 'label' => $role->getName() ));
                    }
                }
            }
        }
        $form = $form->getForm();
        
       // On coche les workflows déjà persistés
        $originalWorkflowsIds = array();
        foreach($originalWorkflows as $ow){
                $originalWorkflowsIds[] = $ow->getOldStatus()->getId().'_'.$ow->getNewStatus()->getId().'_'.$ow->getRole()->getId();
                $form->get('status_'.$ow->getOldStatus()->getId().'_'.$ow->getNewStatus()->getId().'_'.$ow->getRole()->getId())->setData(true);
        }
       
        
        $request = $this->getRequest();
        if($request->getMethod() === "POST"){
                $form->handleRequest($request);
                $em = $this->getDoctrine()->getManager();
                foreach($status as $statusFrom){
                    foreach($status as $statusTo){
                        if($statusFrom->getId() != $statusTo->getId()){
                            foreach($roles as $role){
                                if($form->get('status_'.$statusFrom->getId().'_'.$statusTo->getId().'_'.$role->getId())->getData() == true){
                                        if(!in_array($statusFrom->getId().'_'.$statusTo->getId().'_'.$role->getId(), $originalWorkflowsIds)){
                                            $workflow = new Workflow();
                                            $workflow->setOldStatus($statusFrom);
                                            $workflow->setNewStatus($statusTo);
                                            $workflow->setRole($role);
                                            $workflow->setWorkspace($workspace);
                                            $em->persist($workflow);
                                        }
                                } else {
                                    foreach($originalWorkflows as $ow){
                                        if($ow->getOldStatus()->getId() == $statusFrom->getId() && $ow->getNewStatus()->getId() == $statusTo->getId() && $ow->getRole()->getId() == $role->getId()){
                                            $em->remove($ow);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('success', 'Workflow enregistré avec succès');
                return $this->redirect($this->generateUrl('pm_workflow_edit', array('id' => $workspace->getId())));
            }
        return $this->render('PMWorkspaceBundle:Workflow:form.html.twig', array('form' => $form->createView(), 'status' => $status, 'roles' => $roles, 'workspace' => $workspace));
    }
}
