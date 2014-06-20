<?php

namespace PM\WorkspaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaskStatus
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PM\WorkspaceBundle\Entity\TaskStatusRepository")
 */
class TaskStatus
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="taskStatus") 
     * 
     */
    private $task;
    
    /**
     * @ORM\ManyToOne(targetEntity="Status", inversedBy="taskStatus")
     */
    private $status;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="PM\UserBundle\Entity\User", inversedBy="taskStatus") 
     * 
     */
    private $user;

    public function __construct() {
        $this->date = new \Datetime();
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
     * Set date
     *
     * @param \DateTime $date
     * @return TaskStatus
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set task
     *
     * @param \PM\WorkspaceBundle\Entity\Task $task
     * @return TaskStatus
     */
    public function setTask(\PM\WorkspaceBundle\Entity\Task $task = null)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return \PM\WorkspaceBundle\Entity\Task 
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * Set status
     *
     * @param \PM\WorkspaceBundle\Entity\Status $status
     * @return TaskStatus
     */
    public function setStatus(\PM\WorkspaceBundle\Entity\Status $status = null)
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
     * Set user
     *
     * @param \PM\WorkspaceBundle\Entity\User $user
     * @return TaskStatus
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
}
