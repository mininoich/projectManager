<?php

namespace PM\WorkspaceBundle\Form;

use PM\UserBundle\Entity\User;
use PM\UserBundle\Entity\UserRepository;
use PM\WorkspaceBundle\Entity\Status;
use PM\WorkspaceBundle\Entity\StatusRepository;
use PM\WorkspaceBundle\Entity\Workspace;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaskType extends AbstractType
{
    private $workspace;
    private $status;
    private $user;
    private $action;
    
    function __construct(Workspace $workspace, Status $status, User $user, $action){
        if(!is_null($workspace)){
            $this->workspace = $workspace;
        }
        if(!is_null($status)){
            $this->status = $status;
        }
        if(!is_null($user)){
            $this->user = $user;
        }
        if(!is_null($action)){
            $this->action = $action;
        }
    }
      
     /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $workspace = $this->workspace;
        $status = $this->status;
        $user = $this->user;
        
        $builder
            ->add('name')
            ->add('note', 'textarea', array(
                'required' => false
            ))
            ->add('estimatedTime', 'number', array(
                'required' => false
            ))
            ->add('deadline')
            ->add('category')
            ->add('users')
            ->add('users', 'entity', array(
                'class' => 'PMUserBundle:User',
                'property' => 'username',
                'required' => false,
                'multiple' => true,
                'expanded' => true, 
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
        if($this->action == 'edit'){
            $builder->add('status', 'entity', array(
                'class' => 'PMWorkspaceBundle:Status',
                'property' => 'name',
                'required' => true,
                'mapped' => true,
                'empty_value' => $status->getName(), 
                'query_builder' => function(StatusRepository $sr) use ($status, $user, $workspace) {
                        
                        return $sr->createQueryBuilder('s')
                                ->innerJoin('s.workflowsAsNew', 'w', 'WITH', 'w.oldStatus = :status')
                                ->innerJoin('w.role', 'r')
                                ->innerJoin('r.userRoleWorkspace', 'urw')
                                ->where('urw.user = :current_user')
                                ->andWhere('w.workspace = :workspace')
                                ->setParameters(array('status' => $status, 'current_user' => $user, 'workspace' => $workspace));
                      }
                    )
                );
        }
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
