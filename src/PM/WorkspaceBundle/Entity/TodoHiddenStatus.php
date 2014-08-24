<?php

namespace PM\WorkspaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TodoHiddenStatus
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PM\WorkspaceBundle\Entity\TodoHiddenStatusRepository")
 */
class TodoHiddenStatus
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
     *
     * @ORM\ManyToOne(targetEntity="PM\UserBundle\Entity\User", inversedBy="todoHiddenStatus")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;
    
    /** 
     * 
     * @ORM\ManyToOne(targetEntity="PM\WorkspaceBundle\Entity\Status", inversedBy="todoHiddenStatus")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id", nullable=false)
     */
    private $status;
    
    /** 
     *
     * @ORM\ManyToOne(targetEntity="PM\WorkspaceBundle\Entity\Workspace", inversedBy="todoHiddenStatus")
     * @ORM\JoinColumn(name="workspace_id", referencedColumnName="id", nullable=false)
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
     * Set user
     *
     * @param \PM\UserBundle\Entity\User $user
     * @return TodoHiddenStatus
     */
    public function setUser(\PM\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \PM\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set status
     *
     * @param \PM\WorkspaceBundle\Entity\Status $status
     * @return TodoHiddenStatus
     */
    public function setStatus(\PM\WorkspaceBundle\Entity\Status $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \PM\WorkspaceBundle\Entity\Status 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set workspace
     *
     * @param \PM\WorkspaceBundle\Entity\Workspace $workspace
     * @return TodoHiddenStatus
     */
    public function setWorkspace(\PM\WorkspaceBundle\Entity\Workspace $workspace)
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
