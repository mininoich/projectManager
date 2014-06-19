<?php

namespace PM\WorkspaceBundle\Controller;

use PM\UserBundle\Entity\User;
use PM\WorkspaceBundle\Entity\Task;
use PM\WorkspaceBundle\Entity\TaskStatus;
use PM\WorkspaceBundle\Entity\Workspace;
use PM\WorkspaceBundle\Form\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
        
class TaskController extends Controller
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
    */
    public function todoAction(Workspace $workspace, User $user)
    {
        $em = $this->getDoctrine()->getManager();
        
        $repository = $this->getDoctrine()->getRepository('PMWorkspaceBundle:Task');
        $query = $repository->createQueryBuilder('t')
                ->where('t.workspace = :workspace')
                ->innerJoin('t.users', 'u', 'WITH', 'u = :user')
                ->setParameters(array('workspace' => $workspace, 'user' => $user))
                ->getQuery();
        $tasks = $query->getResult();
        
        return $this->render('PMWorkspaceBundle:Task:todo.html.twig', array('workspace' => $workspace, 'tasks' => $tasks));
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
        $task = new Task();
        return $this->form($workspace, $task, 'add');
    }
    
    /**
    * @ParamConverter("workspace",     options={"mapping": {"workspace_id": "id"}})
    * @ParamConverter("task",     options={"mapping": {"task_id": "id"}})
    */
    public function editAction(Workspace $workspace, Task $task){
        return $this->form($workspace, $task, 'edit');
    }
        
     private function form(Workspace $workspace, Task $task, $action){
        $form = $this->createForm(new TaskType($workspace), $task);
        
        
        $request = $this->getRequest();
        if($request->getMethod() === "POST"){
            $form->bind($request);
            if($form->isValid()){
                // Ajout du statut
                //$taskStatus = new TaskStatus();
                //$taskStatus->setStatus($form->get('status')->getData());
                //$task->addTaskStatus($taskStatus);
                
                $task->setWorkspace($workspace);
                $em = $this->getDoctrine()->getManager();
                //$em->persist($taskStatus);
                $em->persist($task);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('success', 'Tâche enregistrée avec succès');
                return $this->redirect($this->generateUrl('pm_task_index', array('workspace_id' => $workspace->getId())));
            }
        }
        
        return $this->render('PMWorkspaceBundle:Task:form.html.twig', array('form' => $form->createView(), 'action' => $action, 'workspace' => $workspace));
    }
}
