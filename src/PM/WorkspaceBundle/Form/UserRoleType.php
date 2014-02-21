<?php

namespace PM\WorkspaceBundle\Form;

use PM\UserBundle\Entity\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserRoleType extends AbstractType
{
    private $workspace;
    
    function __construct($workspace = null){
        if(!is_null($workspace)){
            $this->workspace = $workspace;
        }
    }
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $workspace = $this->workspace;
        $builder
            ->add('user', 'entity', array(
                'class' => 'PMUserBundle:User',
                'property' => 'username',
                'required' => true,
                'empty_value' => 'Choisissez...',
                'by_reference' => true
                /*, 'query_builder' => function(UserRepository $ur) use ($workspace) {
                        
                        return $ur->createQueryBuilder('u')
                            ->leftjoin('u.userRoleWorkspace', 'urw')
                            ->leftjoin('urw.workspace', 'w', 'WITH', 'w = :workspace')
                            ->where('w.id IS NULL')
                            ->setParameter('workspace', $workspace);
                      }*/
                    )
                )
            ->add('role', 'entity', array(
                'class' => 'PMWorkspaceBundle:Role',
                'property' => 'name',
                'required' => true,
                'empty_value' => 'Choisissez...',
                'by_reference' => true
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PM\WorkspaceBundle\Entity\UserRoleWorkspace'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pm_workspacebundle_roleworkspace';
    }
}
