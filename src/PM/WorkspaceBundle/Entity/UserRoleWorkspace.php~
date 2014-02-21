<?php

namespace PM\WorkspaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserRoleWorkspace
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PM\WorkspaceBundle\Entity\UserRoleWorkspaceRepository")
 */
class UserRoleWorkspace
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
     * @ORM\ManyToOne(targetEntity="PM\WorkspaceBundle\Entity\Role", inversedBy="userRoleWorkspace")
     */
    private $role;
    
    /** 
     *
     * @ORM\ManyToOne(targetEntity="PM\UserBundle\Entity\User", inversedBy="userRoleWorkspace")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;
    
    /** 
     *
     * @ORM\ManyToOne(targetEntity="PM\WorkspaceBundle\Entity\Workspace", inversedBy="userRoleWorkspace")
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
     * Set role
     *
     * @param \PM\WorkspaceBundle\Entity\Role $role
     * @return UserRoleWorkspace
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

    /**
     * Set user
     *
     * @param \PM\UserBundle\Entity\User $user
     * @return UserRoleWorkspace
     */
    public function setUser(\PM\UserBundle\Entity\User $user = null)
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
     * Set workspace
     *
     * @param \PM\WorkspaceBundle\Entity\Workspace $workspace
     * @return UserRoleWorkspace
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
