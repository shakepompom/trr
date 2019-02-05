<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CourseItemRepository")
 * @ORM\HasLifecycleCallbacks
 */
class CourseItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @Groups("quiz_show")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @var Rule
     * @ORM\ManyToOne(targetEntity="App\Entity\Rule"), cascade={"persist"}
     * @ORM\JoinColumn(nullable=false)
     */
    private $rule;

    /**
     * @var Quiz
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups("quiz_show")
     */
    private $exam;

    /**
     * @var Quiz
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups("quiz_show")
     */
    private $practice;

    /**
     * @var FunFact
     * @ORM\ManyToOne(targetEntity="App\Entity\FunFact")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("quiz_show")
     */
    private $fact;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("quiz_show")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("quiz_show")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getRule(): ?Rule
    {
        return $this->rule;
    }

    public function setRule(?Rule $rule): self
    {
        $this->rule = $rule;

        return $this;
    }

    public function getExam(): ?Quiz
    {
        return $this->exam;
    }

    public function setExam(?Quiz $exam): self
    {
        $this->exam = $exam;

        return $this;
    }

    public function getPractice(): ?Quiz
    {
        return $this->practice;
    }

    public function setPractice(?Quiz $practice): self
    {
        $this->practice = $practice;

        return $this;
    }

    public function isFinished(): bool
    {
        return $this->getExam()->getResult() !== null && $this->getPractice()->getResult() !== null;
    }

    public function getFact(): ?FunFact
    {
        return $this->fact;
    }

    public function setFact(?FunFact $fact): self
    {
        $this->fact = $fact;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setUpdatedAt(new \DateTime('now'));

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }
}
