<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RuleRepository")
 */
class Rule
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("quiz_show")
     */
    private $id;

    /**
     * @Groups("rules_list")
     * @ORM\Column(type="smallint")
     * @Groups("quiz_show")
     */
    private $number;

    /**
     * @Groups("rules_list")
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @Groups("rules_list")
     * @ORM\ManyToOne(targetEntity="App\Entity\Section", inversedBy="rules")
     * @ORM\JoinColumn(nullable=true)
     */
    private $section;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="rule", orphanRemoval=true)
     */
    private $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function __toString()
    {
        return '§'.$this->number;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setRule($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getRule() === $this) {
                $task->setRule(null);
            }
        }

        return $this;
    }
}
