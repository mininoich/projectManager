<?php

namespace PM\WorkspaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Time
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PM\WorkspaceBundle\Entity\TimeRepository")
 */
class Time
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
     * @var float
     *
     * @ORM\Column(name="hours", type="float")
     */
    private $hours;

    /**
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="times")
     */
    private $task;

    /**
     *
     * @ORM\ManyToOne(targetEntity="PM\UserBundle\Entity\User", inversedBy="times")
     */
    private $user;
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
     * Set hours
     *
     * @param float $hours
     * @return Time
     */
    public function setHours($hours)
    {
        $this->hours = $hours;

        return $this;
    }

    /**
     * Get hours
     *
     * @return float 
     */
    public function getHours()
    {
        return $this->hours;
    }
}
