<?php

namespace PM\WorkspaceBundle\Controller;

use PM\WorkspaceBundle\Entity\Workspace;
use PM\WorkspaceBundle\Form\WorkspaceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WorkspaceController extends Controller
{
    public function indexAction()
    {
        return $this->render('PMWorkspaceBundle:Workspace:index.html.twig');
    }
    
    public function sidebarAction(){
        $em = $this->getDoctrine()->getManager();
        
        $workspaces = array();
        
        if($this->getUser()->isGranted('ROLE_ADMIN')){
            $workspaces = $em->getRepository('PMWorkspaceBundle:Workspace')->findAll();
        } else {
            $urws = $em->getRepository('PMWorkspaceBundle:UserRoleWorkspace')
                ->findBy(array('user' => $this->getUser()));
        
            foreach($urws as $urw){
                $workspaces[] = $urw->getWorkspace();
            }
        }
        
        return $this->render('PMWorkspaceBundle:Workspace:sidebar.html.twig', array('workspaces' => $workspaces));
    }
    
    public function addAction(){
        $workspace = new Workspace();
        $form = $this->createForm(new WorkspaceType(), $workspace);
        
        
        $request = $this->getRequest();
        if($request->getMethod() === "POST"){
            $form->bind($request);
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($workspace);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('success', 'Workspace enregistré avec succès');
                return $this->redirect($this->generateUrl('pm_workspace_home'));
            }
        }
        
        return $this->render('PMWorkspaceBundle:Workspace:form.html.twig', array('form' => $form->createView()));
    }
    
    public function showAction(Workspace $workspace){
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery("SELECT s, ts FROM PMWorkspaceBundle:Status s "
                . "LEFT JOIN s.taskStatus ts WITH ts.lastStatus = 1 "
                . "LEFT JOIN ts.task t "
                . "LEFT JOIN t.workspace w WITH  w = :workspace ")
                ->setParameters(array('workspace' => $workspace));
        
        $status = $query->getResult();
        
        return $this->render('PMWorkspaceBundle:Workspace:show.html.twig', array('workspace' => $workspace, 'status' => $status));
    }
}
