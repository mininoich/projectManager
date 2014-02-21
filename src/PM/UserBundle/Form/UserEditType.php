<?php

namespace PM\UserBundle\Form;

use PM\UserBundle\Entity\User;
use PM\WorkspaceBundle\Form\RoleWorkspaceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserEditType extends AbstractType
{
    private $editor;
    
    public function __construct(User $editor){
        $this->editor = $editor;
    }
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('enabled', 'checkbox', array('required' => false))
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
                $editor = $this->editor;
                
                $form = $event->getForm();
                if ($editor->isAdmin()) {
                    $form->add('admin', 'checkbox', array('required' => false, 'mapped' => false));
                }
            })
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
