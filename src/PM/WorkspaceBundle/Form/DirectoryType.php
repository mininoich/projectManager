<?php

namespace PM\WorkspaceBundle\Form;

use PM\WorkspaceBundle\Entity\DirectoryRepository;
use PM\WorkspaceBundle\Entity\Workspace;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DirectoryType extends AbstractType
{
    private $workspace;
    
    function __construct(Workspace $workspace){
        $this->workspace = $workspace;
    }
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $workspace = $this->workspace; 
        $builder
            ->add('name')
            ->add('parent', 'entity', array(
                'required' => false, 
                'class' => 'PM\WorkspaceBundle\Entity\Directory',
                'query_builder' => function(DirectoryRepository $dr) use ($workspace) {
                        
                        return $dr->createQueryBuilder('d')
                            ->join('d.workspace', 'w')
                            ->where('w = :workspace')
                            ->setParameter('workspace', $workspace);
                      }
                ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PM\WorkspaceBundle\Entity\Directory'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pm_workspacebundle_directory';
    }
}
