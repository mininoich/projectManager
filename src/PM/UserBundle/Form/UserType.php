<?php

namespace PM\UserBundle\Form;

use PM\WorkspaceBundle\Form\RoleWorkspaceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('password')
            ->add('enabled')
            ->add('admin', 'checkbox', array('required' => false, 'mapped' => false))
            ->add('userRoleWorkspace', 'collection', array(
                                                            'type' => new RoleWorkspaceType(),
                                                            'allow_add' => true,
                                                            'prototype' => true,
                                                            'allow_delete' => true,
                                                            'by_reference' => false
                                                            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PM\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pm_userbundle_user';
    }
}
