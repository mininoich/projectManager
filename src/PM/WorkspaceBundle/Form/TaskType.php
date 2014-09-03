<?php

namespace PM\WorkspaceBundle\Form;

use LogicException;
use PM\UserBundle\Entity\UserRepository;
use PM\WorkspaceBundle\Entity\StatusRepository;
use PM\WorkspaceBundle\Entity\Workspace;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

class TaskType extends AbstractType
{
    private $securityContext;
    private $workspace;
    
    function __construct(SecurityContext $securityContext, Workspace $workspace){
         $this->securityContext = $securityContext;
         $this->workspace = $workspace;
    }
      
     /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('note', 'textarea', array(
                'required' => false
            ))
            ->add('estimatedTime', 'integer', array(
                'required' => false
            ))
            ->add('deadline', 'date', array(
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd/MM/yyyy',
                'attr' => array('class' => 'madate form-control')
            ))
            ->add('category');
        
             // grab the user, do a quick sanity check that one exists
            $user = $this->securityContext->getToken()->getUser();
            if (!$user) {
                throw new LogicException(
                    'The TaskType cannot be used without an authenticated user!'
                );
            }
        
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($user){
                
                $task = $event->getData();
                $form = $event->getForm();
                $workspace = $this->workspace;
                
                if($task && null !== $task->getId()){
                    $status = $task->getStatus();
                }
                
                $form->add('users', 'entity', array(
                'class' => 'PMUserBundle:User',
                'property' => 'username',
                'required' => false,
                'multiple' => true,
                'expanded' => false, 
                'empty_value' => 'Choisissez...',
                'by_reference' => true, 
                'query_builder' => function(UserRepository $ur) use ($workspace) {
                        
                        return $ur->createQueryBuilder('u')
                            ->join('u.userRoleWorkspace', 'urw')
                            ->join('urw.workspace', 'w')
                            ->where('w = :workspace')
                            ->setParameter('workspace', $workspace);
                      }
                    )
                );
                      
                      
                // Si la tache nest pas nouvelle
                if($task && null !== $task->getId()){
                    $form->add('status', 'entity', array(
                    'class' => 'PMWorkspaceBundle:Status',
                    'property' => 'name',
                    'required' => true,
                    'mapped' => true,
                    'query_builder' => function(StatusRepository $sr) use ($status, $user, $workspace) {
                            
                            return $sr->createQueryBuilder('s')
                                    ->leftJoin('s.workflowsAsNew', 'w', 'WITH', 'w.oldStatus = :status')
                                    ->leftJoin('w.role', 'r')
                                    ->leftJoin('r.userRoleWorkspace', 'urw')
                                    ->where('urw.user = :current_user')
                                    ->andWhere('w.workspace = :workspace')
                                    ->orWhere('s = :status')
                                    ->setParameters(array('status' => $status, 'current_user' => $user, 'workspace' => $workspace));
                          }
                        )
                    );
                }
            });
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PM\WorkspaceBundle\Entity\Task'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pm_workspacebundle_task';
    }
}
