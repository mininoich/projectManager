<?php

namespace PM\WorkspaceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RoleWorkspaceType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('role', 'entity', array(
                'class' => 'PMWorkspaceBundle:Role',
                'property' => 'name',
                'required' => true,
                'by_reference' => true
                )
                    )
            ->add('workspace', 'entity', array(
                'class' => 'PMWorkspaceBundle:Workspace',
                'property' => 'name',
                'required' => true,
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
