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
     * @ORM\OneToMany(targetEntity="WorkflowsRoles", mappedBy="workflow")
     */
    private $workflowsRoles;
    
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
     * Set role
     *
     * @param \PM\WorkspaceBundle\Entity\Role $role
     * @return Workflow
     */
//    public function setRole(\PM\WorkspaceBundle\Entity\Role $role = null)
//    {
//        $this->role = $role;
//
//        return $this;
//    }

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
     * Add workflowsRoles
     *
     * @param \PM\WorkspaceBundle\Entity\WorkflowsRoles $workflowsRoles
     * @return Workflow
     */
    public function addWorkflowsRole(\PM\WorkspaceBundle\Entity\WorkflowsRoles $workflowsRoles)
    {
        $this->workflowsRoles[] = $workflowsRoles;

        return $this;
    }

    /**
     * Remove workflowsRoles
     *
     * @param \PM\WorkspaceBundle\Entity\WorkflowsRoles $workflowsRoles
     */
    public function removeWorkflowsRole(\PM\WorkspaceBundle\Entity\WorkflowsRoles $workflowsRoles)
    {
        $this->workflowsRoles->removeElement($workflowsRoles);
    }

    /**
     * Get workflowsRoles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWorkflowsRoles()
    {
        return $this->workflowsRoles;
    }
}
