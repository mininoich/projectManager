<?php

namespace PM\WorkspaceBundle\Controller;

use PM\WorkspaceBundle\Form\CategoryType;
use PM\WorkspaceBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    public function indexAction()
    {
        $categories = $this->getDoctrine()->getRepository('PMWorkspaceBundle:Category')->findAll();
        return $this->render('PMWorkspaceBundle:Category:index.html.twig', array('categories' => $categories));
    }
    
    public function addAction(){
        $category = new Category();
        return $this->form($category, 'add');
    }
    
    public function editAction(Category $category){
        return $this->form($category, 'edit');
    }
        
     private function form($category, $action){
        $form = $this->createForm(new CategoryType(), $category);
        
        
        $request = $this->getRequest();
        if($request->getMethod() === "POST"){
            $form->bind($request);
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($category);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('success', 'Catégorie enregistrée avec succès');
                return $this->redirect($this->generateUrl('pm_category_index'));
            }
        }
        
        return $this->render('PMWorkspaceBundle:Category:form.html.twig', array('form' => $form->createView(), 'action' => $action));
    }
    
    public function deleteAction(Category $category){
        // On vérifie si le rôle n'est pas déjà utilisé pour au moins un projet
        $affectations = $category->getTasks();
        $num = count($affectations);
        
        if($num > 0){
            $this->get('session')->getFlashBag()->add('danger', 'Impossible de supprimer cette catégorie car elle est utilisée');
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Catégorie supprimée avec succès');
        }
        
        return $this->redirect($this->generateUrl('pm_category_index'));
    }
}
