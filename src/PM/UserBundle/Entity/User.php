<?php
namespace PM\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="UserRepository")
 */
class User extends BaseUser {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="PM\WorkspaceBundle\Entity\UserRoleWorkspace", mappedBy="user", cascade={"remove", "persist"})
     */
    private $userRoleWorkspace;

    /**
     * @ORM\OneToMany(targetEntity="PM\WorkspaceBundle\Entity\Time", mappedBy="user")
     */
    private $times;

    /**
     * @ORM\ManyToMany(targetEntity="PM\WorkspaceBundle\Entity\Task", mappedBy="users")
     */
    private $tasks;
    
    /**
     *
     * @ORM\OneToMany(targetEntity="PM\WorkspaceBundle\Entity\TaskStatus", mappedBy="user")
     */
    private $taskStatus;
    
    /**
     * @ORM\OneToMany(targetEntity="PM\WorkspaceBundle\Entity\TodoHiddenStatus", mappedBy="user", cascade={"remove", "persist"})
     */
    private $todoHiddenStatus;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Get expiresAt
     *
     * @return \DateTime 
     */
    public function getExpiresAt() {
        return $this->expiresAt;
    }

    /**
     * Get credentials_expire_at
     *
     * @return \DateTime 
     */
    public function getCredentialsExpireAt() {
        return $this->credentialsExpireAt;
    }


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
     * Add userRoleWorkspace
     *
     * @param \PM\WorkspaceBundle\Entity\UserRoleWorkspace $userRoleWorkspace
     * @return User
     */
    public function addUserRoleWorkspace(\PM\WorkspaceBundle\Entity\UserRoleWorkspace $userRoleWorkspace)
    {
        $this->userRoleWorkspace[] = $userRoleWorkspace;
        $userRoleWorkspace->setUser($this);
        return $this;
    }

    /**
     * Remove userRoleWorkspace
     *
     * @param \PM\WorkspaceBundle\Entity\UserRoleWorkspace $userRoleWorkspace
     */
    public function removeUserRoleWorkspace(\PM\WorkspaceBundle\Entity\UserRoleWorkspace $userRoleWorkspace)
    {
        $this->userRoleWorkspace->removeElement($userRoleWorkspace);
    }

    /**
     * Get userRoleWorkspace
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserRoleWorkspace()
    {
        return $this->userRoleWorkspace;
    }

    /**
     * Add times
     *
     * @param \PM\WorkspaceBundle\Entity\Time $times
     * @return User
     */
    public function addTime(\PM\WorkspaceBundle\Entity\Time $times)
    {
        $this->times[] = $times;

        return $this;
    }

    /**
     * Remove times
     *
     * @param \PM\WorkspaceBundle\Entity\Time $times
     */
    public function removeTime(\PM\WorkspaceBundle\Entity\Time $times)
    {
        $this->times->removeElement($times);
    }

    /**
     * Get times
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTimes()
    {
        return $this->times;
    }

    /**
     * Add tasks
     *
     * @param \PM\WorkspaceBundle\Entity\Task $tasks
     * @return User
     */
    public function addTask(\PM\WorkspaceBundle\Entity\Task $tasks)
    {
        $this->tasks[] = $tasks;

        return $this;
    }

    /**
     * Remove tasks
     *
     * @param \PM\WorkspaceBundle\Entity\Task $tasks
     */
    public function removeTask(\PM\WorkspaceBundle\Entity\Task $tasks)
    {
        $this->tasks->removeElement($tasks);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTasks()
    {
        return $this->tasks;
    }
    
    public function isGranted($role)
    {
        return in_array($role, $this->getRoles());
    }
    
    public function isAdmin(){
        return $this->isGranted('ROLE_ADMIN');
    }
    
    public function setAdmin(){
        
    }

    /**
     * Add taskStatus
     *
     * @param \PM\WorkspaceBundle\Entity\TaskStatus $taskStatus
     * @return User
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
     * Add todoHiddenStatus
     *
     * @param \PM\WorkspaceBundle\Entity\TodoHiddenStatus $todoHiddenStatus
     * @return User
     */
    public function addTodoHiddenStatus(\PM\WorkspaceBundle\Entity\TodoHiddenStatus $todoHiddenStatus)
    {
        $this->todoHiddenStatus[] = $todoHiddenStatus;

        return $this;
    }

    /**
     * Remove todoHiddenStatus
     *
     * @param \PM\WorkspaceBundle\Entity\TodoHiddenStatus $todoHiddenStatus
     */
    public function removeTodoHiddenStatus(\PM\WorkspaceBundle\Entity\TodoHiddenStatus $todoHiddenStatus)
    {
        $this->todoHiddenStatus->removeElement($todoHiddenStatus);
    }

    /**
     * Get todoHiddenStatus
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTodoHiddenStatus()
    {
        return $this->todoHiddenStatus;
    }
}
