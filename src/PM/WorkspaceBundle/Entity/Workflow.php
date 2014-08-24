<?php

namespace PM\WorkspaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Workflow
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PM\WorkspaceBundle\Entity\WorkflowRepository")
 */
class Workflow
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
     * @ORM\ManyToOne(targetEntity="Status", inversedBy="workflowsAsOld")
     */
    private $oldStatus;
    
    /**
     * @ORM\ManyToOne(targetEntity="Status", inversedBy="workflowsAsNew")
     */
    private $newStatus;
    
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="workflows")
     */
    private $role;
    
    /**
     * @ORM\ManyToOne(targetEntity="Workspace", inversedBy="workflows")
     */
    private $workspace;
    
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
     * Set oldStatus
     *
     * @param \PM\WorkspaceBundle\Entity\Status $oldStatus
     * @return Workflow
     */
    public function setOldStatus(\PM\WorkspaceBundle\Entity\Status $oldStatus = null)
    {
        $this->oldStatus = $oldStatus;

        return $this;
    }

    /**
     * Get oldStatus
     *
     * @return \PM\WorkspaceBundle\Entity\Status 
     */
    public function getOldStatus()
    {
        return $this->oldStatus;
    }

    /**
     * Set newStatus
     *
     * @param \PM\WorkspaceBundle\Entity\Status $newStatus
     * @return Workflow
     */
    public function setNewStatus(\PM\WorkspaceBundle\Entity\Status $newStatus = null)
    {
        $this->newStatus = $newStatus;

        return $this;
    }

    /**
     * Get newStatus
     *
     * @return \PM\WorkspaceBundle\Entity\Status 
     */
    public function getNewStatus()
    {
        return $this->newStatus;
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->workflowsRoles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set role
     *
     * @param \PM\WorkspaceBundle\Entity\Role $role
     * @return Workflow
     */
    public function setRole(\PM\WorkspaceBundle\Entity\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Set workspace
     *
     * @param \PM\WorkspaceBundle\Entity\Workspace $workspace
     * @return Workflow
     */
    public function setWorkspace(\PM\WorkspaceBundle\Entity\Workspace $workspace = null)
    {
        $this->workspace = $workspace;

        return $this;
    }

    /**
     * Get workspace
     *
     * @return \PM\WorkspaceBundle\Entity\Workspace 
     */
    public function getWorkspace()
    {
        return $this->workspace;
    }
}
