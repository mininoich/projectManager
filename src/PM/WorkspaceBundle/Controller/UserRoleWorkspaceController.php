<?php

namespace PM\WorkspaceBundle\Controller;

use PM\UserBundle\Entity\User;
use PM\WorkspaceBundle\Entity\UserRoleWorkspace;
use PM\WorkspaceBundle\Entity\Workspace;
use PM\WorkspaceBundle\Form\UserRoleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
        
class UserRoleWorkspaceController extends Controller
{
    /**
    * @ParamConverter("workspace",     options={"mapping": {"workspace_id": "id"}})
    */
    public function indexAction(Workspace $workspace)
    {
        $em = $this->getDoctrine()->getManager();
        $usersRolesWorkspace = $workspace->getUserRoleWorkspace();
        
        $userRoleWorkspace = new UserRoleWorkspace();
        $form = $this->createForm(new UserRoleType($workspace), $userRoleWorkspace);
        
        $cloned = clone $form;
        
        $request = $this->getRequest();
        if($request->getMethod() === "POST"){
            $form->bind($request);
            if($form->isValid()){
                $userRoleWorkspace->setWorkspace($workspace);
                $em->persist($userRoleWorkspace);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('success', $userRoleWorkspace->getUser()->getUsername().' est maintenant affecté au workspace "'.$workspace->getName().'" en tant que '.$userRoleWorkspace->getRole()->getName().'.');
                
                $form = $cloned;
            }
        }
        
        return $this->render('PMWorkspaceBundle:UserRoleWorkspace:index.html.twig', array('workspace' => $workspace, 'usersRolesWorkspace' => $usersRolesWorkspace, 'form' => $form->createView()));
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
    
    
}
