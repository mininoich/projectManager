<?php

namespace PM\WorkspaceBundle\Controller;

use PM\UserBundle\Entity\User;
use PM\WorkspaceBundle\Entity\Directory;
use PM\WorkspaceBundle\Entity\Workspace;
use PM\WorkspaceBundle\Form\DirectoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
        
class DirectoryController extends Controller
{
    /**
    * @ParamConverter("workspace",     options={"mapping": {"workspace_id": "id"}})
    */
    public function indexAction(Workspace $workspace)
    {
        $em = $this->getDoctrine()->getManager();
        $tasks = $workspace->getTasks();
        
        return $this->render('PMWorkspaceBundle:Task:index.html.twig', array('workspace' => $workspace, 'tasks' => $tasks));
    }
    
    /**
    * @ParamConverter("workspace",     options={"mapping": {"workspace_id": "id"}})
    * @ParamConverter("user",     options={"mapping": {"member_id": "id"}})
    */
    public function deleteAction(Workspace $workspace, User $user){
        $em = $this->getDoctrine()->getManager();
        
        foreach($user->getUserRoleWorkspace() as $urw ){
            if($urw->getWorkspace()->getId() === $workspace->getId()){
                $em->remove($urw);
            }
        }
        $em->flush();
        $this->get('session')->getFlashBag()->add('success', $user->getUsername().' n\'est plus affecté au workspace "'.$workspace->getName().'"');
        return $this->redirect($this->generateUrl('pm_userroleworkspace_index', array('workspace_id'=>$workspace->getId())));
    }
    
    
    /**
    * @ParamConverter("workspace",     options={"mapping": {"workspace_id": "id"}})
    */
    public function addAction(Workspace $workspace){
        $directory = new Directory();
        return $this->form($workspace, $directory, 'add');
    }
    
    /**
    * @ParamConverter("workspace",     options={"mapping": {"workspace_id": "id"}})
    * @ParamConverter("task",     options={"mapping": {"task_id": "id"}})
    */
    public function editAction(Workspace $workspace, Directory $directory){
        return $this->form($workspace, $directory, 'edit');
    }
        
     private function form(Workspace $workspace, Directory $directory, $action){
        $form = $this->createForm(new DirectoryType($workspace), $directory);
        
        
        $request = $this->getRequest();
        if($request->getMethod() === "POST"){
            $form->bind($request);
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $directory->setWorkspace($workspace);
                $em->persist($directory);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('success', 'Dossier enregistré avec succès');
                return $this->redirect($this->generateUrl('pm_task_index', array('workspace_id' => $workspace->getId())));
            }
        }
        return $this->render('PMWorkspaceBundle:Directory:form.html.twig', array('form' => $form->createView(), 'action' => $action, 'workspace' => $workspace));
    }
}
