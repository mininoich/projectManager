<?php

namespace PM\WorkspaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Directory
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PM\WorkspaceBundle\Entity\DirectoryRepository")
 */
class Directory
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
     * @var type 
     * @ORM\OneToMany(targetEntity="Task", mappedBy="directory")
     */
    private $tasks;
    
    /**
     *
     * @var type 
     * @ORM\OneToMany(targetEntity="Directory", mappedBy="parent")
     */
    private $children;
    
    /**
     * @ORM\ManyToOne(targetEntity="Directory", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;
    
    /**
     * @ORM\ManyToOne(targetEntity="Workspace", inversedBy="directories")
     */
    private $workspace;
    
    public function __toString(){
        return $this->name;
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

    public function __construct(){
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set name
     *
     * @param string $name
     * @return Directory
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
     * Add tasks
     *
     * @param \PM\WorkspaceBundle\Entity\Task $tasks
     * @return Directory
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

    /**
     * Add children
     *
     * @param \PM\WorkspaceBundle\Entity\Directory $children
     * @return Directory
     */
    public function addChild(\PM\WorkspaceBundle\Entity\Directory $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \PM\WorkspaceBundle\Entity\Directory $children
     */
    public function removeChild(\PM\WorkspaceBundle\Entity\Directory $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \PM\WorkspaceBundle\Entity\Directory $parent
     * @return Directory
     */
    public function setParent(\PM\WorkspaceBundle\Entity\Directory $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \PM\WorkspaceBundle\Entity\Directory 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set workspace
     *
     * @param \PM\WorkspaceBundle\Entity\Workspace $workspace
     * @return Directory
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
