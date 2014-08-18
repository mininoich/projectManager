<?php

namespace PM\WorkspaceBundle\Controller;

use PM\UserBundle\Entity\User;
use PM\WorkspaceBundle\Entity\Task;
use PM\WorkspaceBundle\Entity\TaskStatus;
use PM\WorkspaceBundle\Entity\Workspace;
use PM\WorkspaceBundle\Form\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
        
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
    * @ParamConverter("id",     options={"mapping": {"workspace_id": "id"}})
    */
    public function todoAction(Workspace $workspace)
    {
        $hiddenStatus = $todoHiddenStatus = array();
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        
        $todoHiddenStatus = $this->getDoctrine()->getRepository('PMWorkspaceBundle:TodoHiddenStatus')->findBy(array('user' => $user, 'workspace' => $workspace));
        foreach($todoHiddenStatus as $ths){
            $hiddenStatus[$ths->getStatus()->getId()] = $ths->getStatus()->getId();
        }
        
        $repository = $this->getDoctrine()->getRepository('PMWorkspaceBundle:Status');
        $query = $repository->createQueryBuilder('s')
                ->where('s.deleted = 0')
                ->leftJoin('s.taskStatus', 'ts', 'WITH', 'ts.lastStatus = 1')
                ->leftJoin('ts.task', 't', 'WITH', 't.workspace = :workspace')
                ->leftJoin('t.users', 'u', 'WITH', 'u = :user')
                ->setParameters(array('workspace' => $workspace, 'user' => $user))
                ->getQuery();
        $status = $query->getResult();
        
        return $this->render('PMWorkspaceBundle:Task:todo.html.twig', array('workspace' => $workspace, 'status' => $status, 'hiddenStatus' => $hiddenStatus));
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
        $user = $this->get('security.context')->getToken()->getUser();
        $repo = $this->getDoctrine()->getRepository('PMWorkspaceBundle:Status');
        $initialStatus = $repo->createQueryBuilder('s')
                            ->where('s.defaultValue = 1')
                            ->getQuery()
                            ->getSingleResult();
        
        if($action == 'add'){
            $status = $initialStatus;
        } else {
            $status = $task->getCurrentStatus();
        }
        
        $form = $this->createForm(new TaskType($workspace, $status, $user, $action), $task);
        
        
        $request = $this->getRequest();
        if($request->getMethod() === "POST"){
            $form->bind($request);
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                
                if($action == 'add'){
                    // Ajout du statut
                    $taskStatus = new TaskStatus();
                    $taskStatus->setStatus($initialStatus);
                    $task->addTaskStatus($taskStatus);
                }
                
                
                if($action != 'add'){
                    // On met l'ancien status à LastStatus = false
                    $ts = $this->getDoctrine()->getRepository('PMWorkspaceBundle:TaskStatus')->findOneBy(array('task' => $task, 'lastStatus' => 1));
                    $ts->setLastStatus(false);
                    $em->persist($ts);
                }
                
                $task->setWorkspace($workspace);
                
                //$em->persist($taskStatus);
                $em->persist($task);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('success', 'Tâche enregistrée avec succès');
                return $this->redirect($this->generateUrl('pm_task_index', array('workspace_id' => $workspace->getId())));
            }
        }
        
        return $this->render('PMWorkspaceBundle:Task:form.html.twig', array('form' => $form->createView(), 'action' => $action, 'workspace' => $workspace));
    }
    
    public function edittaskstatusAction(){
        $request = $this->getRequest();
        $response = new JsonResponse();
        
        if($request->getMethod() === "POST"){
            $user = $this->get('security.context')->getToken()->getUser();
            $taskid = $request->get('taskid');
            $fromstatusid = $request->get('fromstatusid');
            $tostatusid = $request->get('tostatusid');
            
            $em = $this->getDoctrine()->getManager();
            
            $task = $this->getDoctrine()->getRepository('PMWorkspaceBundle:Task')->find($taskid);
            $fromstatus = $this->getDoctrine()->getRepository('PMWorkspaceBundle:Status')->find($fromstatusid);
            $tostatus = $this->getDoctrine()->getRepository('PMWorkspaceBundle:Status')->find($tostatusid);
            
            // On vérifie si on a le droit de passer de ce statut au nouveau 
            $repo = $this->getDoctrine()->getRepository('PMWorkspaceBundle:Status');
            $qb = $repo->createQueryBuilder('s')
            ->innerJoin('s.workflowsAsNew', 'w', 'WITH', 'w.oldStatus = :fromstatus AND w.newStatus = :tostatus')
            ->innerJoin('w.role', 'r')
            ->innerJoin('r.userRoleWorkspace', 'urw')
            ->where('urw.user = :current_user')
            ->andWhere('w.workspace = :workspace')
            ->setParameters(array('fromstatus' => $fromstatus, 'tostatus' => $tostatus, 'current_user' => $user, 'workspace' => $task->getWorkspace()));
            $count = count($qb->getQuery()->getResult());
            
            if($count == 1){
                // On met l'ancien status à LastStatus = false
                $ts = $this->getDoctrine()->getRepository('PMWorkspaceBundle:TaskStatus')->findOneBy(array('task' => $task, 'lastStatus' => 1));
                $ts->setLastStatus(false);
                $em->persist($ts);

                // Ajout du nouveau statut
                $taskStatus = new TaskStatus();
                $taskStatus->setStatus($tostatus);
                $task->addTaskStatus($taskStatus);

                $em->persist($task);
                $em->flush();
                $response->setData(array('type' => 'succes', 'message' => 'Statut mis à jour avec succès'));
            } else {
                $response->setData(array('type' => 'danger', 'message' => 'Vous n\'avez pas les droits nécessaires pour effectuer cette action'));
            }
        } else {
            $response->setData(array('type' => 'danger', 'message' => 'Action impossible'));
        }
        return $response;
    }
}
