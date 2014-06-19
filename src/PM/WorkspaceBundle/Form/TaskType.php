<?php

namespace PM\WorkspaceBundle\Form;

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
    
    function __construct(Workspace $workspace, Status $status){
        if(!is_null($workspace)){
            $this->workspace = $workspace;
        }
        if(!is_null($status)){
            $this->status = $status;
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
        
        $builder
            ->add('name')
            ->add('note')
            ->add('estimatedTime')
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
                )
            ->add('status', 'entity', array(
                'class' => 'PMWorkspaceBundle:Status',
                'property' => 'name',
                'required' => true,
                'mapped' => true,
                'empty_value' => $status->getName(), 
                'query_builder' => function(StatusRepository $sr) use ($status) {
                        
                        return $sr->createQueryBuilder('s')
                                ->innerJoin('s.workflowsAsNew', 'w', 'WITH', 'w.oldStatus = :status')
                                ->setParameter('status', $status);
                      }
                    )
                )
        ;
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
