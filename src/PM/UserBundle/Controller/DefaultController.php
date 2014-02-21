<?php

namespace PM\UserBundle\Controller;

use PM\UserBundle\Entity\User;
use PM\UserBundle\Form\UserEditType;
use PM\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('PMUserBundle:User')->findAll();
        return $this->render('PMUserBundle:Default:index.html.twig', array('users' => $users));
    }
    
    public function addAction(){
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $form = $this->createForm(new UserType(), $user);
        
        
        $request = $this->getRequest();
        if($request->getMethod() === "POST"){
            $form->bind($request);
            if($form->isValid()){
                $user->setPlainPassword($form->getData('password'));
                $userManager->updateUser($user);
                $this->get('session')->getFlashBag()->add('success', 'Utilisateur enregistré avec succès');
                return $this->redirect($this->generateUrl('pm_user_index'));
            }
        }
        
        return $this->render('PMUserBundle:Default:form.html.twig', array('form' => $form->createView(), 'action' => 'add'));
    }
    
    public function editAction(User $user){
        $userManager = $this->get('fos_user.user_manager');
        
        $form = $this->createForm(new UserEditType($this->getUser()), $user);
        
        if($user->isGranted('ROLE_ADMIN')){
            $form->get('admin')->setData(true);
        }
        
        $originalRWs = array();
        // Crée un tableau contenant les affectations courantes du user 
        foreach($user->getUserRoleWorkspace() as $urw){$originalRWs[] = $urw; }
        
        $request = $this->getRequest();
        if($request->getMethod() === "POST"){
            $form->bind($request);
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                if($form->get('admin')->getData() == true){
                    $user->addRole('ROLE_ADMIN');
                } else {
                    // Il faut au moins un admin dans l'application
                    $admins = $em->getRepository('PMUserBundle:User')->findByRole('ROLE_ADMIN');
                    if(count($admins) <= 1){
                        $this->get('session')->getFlashBag()->add('danger', 'Pour enlever le droit administrateur de cet utilisateur veuillez ajouter ce droit à un autre utilisateur puis réessayer. Au moins un administrateur est nécessaire dans le système.');
                    } else {
                        $user->removeRole('ROLE_ADMIN');
                    }
                }
                
                // Filtre $originalRWs pour ne contenir que les affectations à supprimer
                foreach($user->getUserRoleWorkspace() as $urw){
                    foreach($originalRWs as $key => $toDel){
                        if($toDel->getId() === $urw->getId()){
                            unset($originalRWs[$key]);
                        }
                    }
                }
                
                // Supprime les affectations à supprimer
                foreach($originalRWs as $urw){
                    $em->remove($urw);
                }
                $em->persist($user);
                $em->flush();
                $userManager->updateUser($user);
                $this->get('session')->getFlashBag()->add('success', 'Utilisateur modifié avec succès');
                return $this->redirect($this->generateUrl('pm_user_index'));
            }
        }
        
        return $this->render('PMUserBundle:Default:form.html.twig', array('form' => $form->createView(), 'action' => 'edit'));
    }
    
    public function deleteAction(User $user){
        $em = $this->getDoctrine()->getManager();
        if(count($user->getTasks()) == 0){
            $em->remove($user);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Utilisateur supprimé avec succès');
        } else {
            $this->get('session')->getFlashBag()->add('danger', 'Impossible de supprimer un utilisateur qui est affecté à au moins une tâche');
        }
        return $this->redirect($this->generateUrl('pm_user_index'));
    }
}
