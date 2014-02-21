<?php

namespace PM\WorkspaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Status
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PM\WorkspaceBundle\Entity\StatusRepository")
 */
class Status
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     *
     * @ORM\OneToMany(targetEntity="TaskStatus", mappedBy="task")
     */
    private $taskStatus;
    
    /**
     *
     * @ORM\OneToMany(targetEntity="Workflow", mappedBy="oldStatus")
     */
    private $workflowsAsOld;
    
    /**
     *
     * @ORM\OneToMany(targetEntity="Workflow", mappedBy="newStatus")
     */
    private $workflowsAsNew;
    
    
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
     * Set name
     *
     * @param string $name
     * @return Status
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->taskStatus = new \Doctrine\Common\Collections\ArrayCollection();
        $this->workflowsAsOld = new \Doctrine\Common\Collections\ArrayCollection();
        $this->workflowsAsNew = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add taskStatus
     *
     * @param \PM\WorkspaceBundle\Entity\TaskStatus $taskStatus
     * @return Status
     */
    public function addTaskStatus(\PM\WorkspaceBundle\Entity\TaskStatus $taskStatus)
    {
        $this->taskStatus[] = $taskStatus;

        return $this;
    }

    /**
     * Remove taskStatus
     *
     * @param \PM\WorkspaceBundle\Entity\TaskStatus $taskStatus
     */
    public function removeTaskStatus(\PM\WorkspaceBundle\Entity\TaskStatus $taskStatus)
    {
        $this->taskStatus->removeElement($taskStatus);
    }

    /**
     * Get taskStatus
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTaskStatus()
    {
        return $this->taskStatus;
    }

    /**
     * Add workflowsAsOld
     *
     * @param \PM\WorkspaceBundle\Entity\Workflow $workflowsAsOld
     * @return Status
     */
    public function addWorkflowsAsOld(\PM\WorkspaceBundle\Entity\Workflow $workflowsAsOld)
    {
        $this->workflowsAsOld[] = $workflowsAsOld;

        return $this;
    }

    /**
     * Remove workflowsAsOld
     *
     * @param \PM\WorkspaceBundle\Entity\Workflow $workflowsAsOld
     */
    public function removeWorkflowsAsOld(\PM\WorkspaceBundle\Entity\Workflow $workflowsAsOld)
    {
        $this->workflowsAsOld->removeElement($workflowsAsOld);
    }

    /**
     * Get workflowsAsOld
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWorkflowsAsOld()
    {
        return $this->workflowsAsOld;
    }

    /**
     * Add workflowsAsNew
     *
     * @param \PM\WorkspaceBundle\Entity\Workflow $workflowsAsNew
     * @return Status
     */
    public function addWorkflowsAsNew(\PM\WorkspaceBundle\Entity\Workflow $workflowsAsNew)
    {
        $this->workflowsAsNew[] = $workflowsAsNew;

        return $this;
    }

    /**
     * Remove workflowsAsNew
     *
     * @param \PM\WorkspaceBundle\Entity\Workflow $workflowsAsNew
     */
    public function removeWorkflowsAsNew(\PM\WorkspaceBundle\Entity\Workflow $workflowsAsNew)
    {
        $this->workflowsAsNew->removeElement($workflowsAsNew);
    }

    /**
     * Get workflowsAsNew
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWorkflowsAsNew()
    {
        return $this->workflowsAsNew;
    }
}
