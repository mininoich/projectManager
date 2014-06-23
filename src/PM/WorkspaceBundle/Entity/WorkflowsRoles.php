<?php

namespace PM\WorkspaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WorkflowsRoles
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PM\WorkspaceBundle\Entity\WorkflowsRolesRepository")
 */
class WorkflowsRoles
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Workflow", inversedBy="workflowsRoles")
     */
    private $workflow;
    
    /**
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="workflowsRoles")
     */
    private $role;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set workflow
     *
     * @param \PM\WorkspaceBundle\Entity\Workflow $workflow
     * @return WorkflowsRoles
     */
    public function setWorkflow(\PM\WorkspaceBundle\Entity\Workflow $workflow = null)
    {
        $this->workflow = $workflow;

        return $this;
    }

    /**
     * Get workflow
     *
     * @return \PM\WorkspaceBundle\Entity\Workflow 
     */
    public function getWorkflow()
    {
        return $this->workflow;
    }

    /**
     * Set role
     *
     * @param \PM\WorkspaceBundle\Entity\Role $role
     * @return WorkflowsRoles
     */
    public function setRole(\PM\WorkspaceBundle\Entity\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \PM\WorkspaceBundle\Entity\Role 
     */
    public function getRole()
    {
        return $this->role;
    }
}
