<?php

namespace PM\WorkspaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="PM\WorkspaceBundle\Entity\TaskRepository")
 */
class Task
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
     * @var string
     *
     * @ORM\Column(name="note", type="text")
     */
    private $note;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var float
     *
     * @ORM\Column(name="estimatedTime", type="float")
     */
    private $estimatedTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deadline", type="date")
     */
    private $deadline;


    /**
     *
     * @ORM\OneToMany(targetEntity="TaskStatus", mappedBy="task")
     */
    private $taskStatus;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Workspace", inversedBy="tasks")
     */
    private $workspace;
    
    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="tasks")
     */
    private $category;
    
    /**
     * @ORM\OneToMany(targetEntity="Time", mappedBy="task", cascade={"remove"})
     * 
     */
    private $times;
    
    /**
     * @ORM\ManyToMany(targetEntity="PM\UserBundle\Entity\User", inversedBy="tasks")
     */
    private $users;
    
    /**
     * @ORM\prePersist()
     */
    public function prePersist(){
        $this->dateCreation = new \Datetime();
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
     * Set name
     *
     * @param string $name
     * @return Task
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
     * Set note
     *
     * @param string $note
     * @return Task
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     * @return Task
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime 
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set estimatedTime
     *
     * @param float $estimatedTime
     * @return Task
     */
    public function setEstimatedTime($estimatedTime)
    {
        $this->estimatedTime = $estimatedTime;

        return $this;
    }

    /**
     * Get estimatedTime
     *
     * @return float 
     */
    public function getEstimatedTime()
    {
        return $this->estimatedTime;
    }

    /**
     * Set deadline
     *
     * @param \DateTime $deadline
     * @return Task
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Get deadline
     *
     * @return \DateTime 
     */
    public function getDeadline()
    {
        return $this->deadline;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->taskStatus = new \Doctrine\Common\Collections\ArrayCollection();
        $this->times = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add taskStatus
     *
     * @param \PM\WorkspaceBundle\Entity\TaskStatus $taskStatus
     * @return Task
     */
    public function addTaskStatus(\PM\WorkspaceBundle\Entity\TaskStatus $taskStatus)
    {
        $this->taskStatus[] = $taskStatus;
        $taskStatus->setTask($this);
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
     * Set workspace
     *
     * @param \PM\WorkspaceBundle\Entity\Workspace $workspace
     * @return Task
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

    /**
     * Set category
     *
     * @param \PM\WorkspaceBundle\Entity\Category $category
     * @return Task
     */
    public function setCategory(\PM\WorkspaceBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \PM\WorkspaceBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add times
     *
     * @param \PM\WorkspaceBundle\Entity\Time $times
     * @return Task
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
     * Add users
     *
     * @param \PM\UserBundle\Entity\User $users
     * @return Task
     */
    public function addUser(\PM\UserBundle\Entity\User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \PM\UserBundle\Entity\User $users
     */
    public function removeUser(\PM\UserBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}
