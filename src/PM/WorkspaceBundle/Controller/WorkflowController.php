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
        $status = $this->getDoctrine()->getManager()->getRepository('PMWorkspaceBundle:Status')->findAll();
        
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
            foreach($ow->getWorkflowsRoles() as $wr){
                $originalWorkflowsIds[] = $ow->getOldStatus()->getId().'_'.$ow->getNewStatus()->getId().'_'.$wr->getRole()->getId();
                $form->get('status_'.$ow->getOldStatus()->getId().'_'.$ow->getNewStatus()->getId().'_'.$wr->getRole()->getId())->setData(true);
            }
        }
       
        
        $request = $this->getRequest();
        if($request->getMethod() === "POST"){
                $form->handleRequest($request);
                $em = $this->getDoctrine()->getManager();
                foreach($status as $statusFrom){
                    foreach($status as $statusTo){
                        if($statusFrom->getId() != $statusTo->getId()){
                            foreach($roles as $role){
                                $workflowsRoles = array();
                                if($form->get('status_'.$statusFrom->getId().'_'.$statusTo->getId().'_'.$role->getId())->getData() == true){
                                        if(!in_array($statusFrom->getId().'_'.$statusTo->getId().'_'.$role->getId(), $originalWorkflowsIds)){
                                            // Créer le workflow s'il n'existe pas encore en BDD
                                            //$workflow = repo
                                            // si le nb res = 0 alors on créé 
                                            $workflow = new Workflow();
                                            $workflow->setOldStatus($statusFrom);
                                            $workflow->setNewStatus($statusTo);
                                            
                                            // Puis on ajoute le role
                                            $wr = new WorkflowsRoles();
                                            $wr->setRole($role);
                                            $workflow->addWorkflowsRoles();
                                            $workflow->setWorkspace($workspace);
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
                }
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('success', 'Workflow enregistré avec succès');
                return $this->redirect($this->generateUrl('pm_role_index'));
            }
        return $this->render('PMWorkspaceBundle:Workflow:form.html.twig', array('form' => $form->createView(), 'status' => $status, 'roles' => $roles, 'workspace' => $workspace));
    }
}
