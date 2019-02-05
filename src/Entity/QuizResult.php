<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuizResultRepository")
 * @ORM\HasLifecycleCallbacks
 */
class QuizResult
{
    const MAX_POINTS = 5;

    const LEARNT_RULE_RATE = 0.8;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Quiz", inversedBy="result", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $quiz;

    /**
     * @ORM\Column(type="integer")
     * @Groups("quiz_result_show")
     */
    private $correct = 0;

    /**
     * @ORM\Column(type="integer")
     * @Groups("quiz_result_show")
     */
    private $total = 0;

    /**
     * @ORM\Column(type="float")
     * @Groups("quiz_result_show")
     */
    private $avg;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("quiz_result_show")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("quiz_result_show")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\QuizUserAnswer", mappedBy="quizResult", orphanRemoval=true, cascade={"persist", "remove"})
     * @Groups("quiz_result_show")
     */
    private $userAnswers;

    public function __construct()
    {
        $this->userAnswers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }

    public function getCorrect(): ?int
    {
        return $this->correct;
    }

    public function setCorrect(int $correct): self
    {
        $this->correct = $correct;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getAvg(): ?float
    {
        return $this->avg;
    }

    public function setAvg(float $avg): self
    {
        $this->avg = $avg;

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

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedAverageValue()
    {
        $this->setAvg(0);
        if ($this->getTotal() > 0) {
            $this->setAvg($this->getCorrect() / $this->getTotal());
        }
    }

    /**
     * @return Collection|QuizUserAnswer[]
     */
    public function getUserAnswers(): Collection
    {
        return $this->userAnswers;
    }

    public function addUserAnswer(QuizUserAnswer $userAnswer): self
    {
        if (!$this->userAnswers->contains($userAnswer)) {
            $this->userAnswers[] = $userAnswer;
            $userAnswer->setQuizResult($this);
        }

        return $this;
    }

    public function removeUserAnswer(QuizUserAnswer $userAnswer): self
    {
        if ($this->userAnswers->contains($userAnswer)) {
            $this->userAnswers->removeElement($userAnswer);
            // set the owning side to null (unless already changed)
            if ($userAnswer->getQuizResult() === $this) {
                $userAnswer->setQuizResult(null);
            }
        }

        return $this;
    }

    public function getUserAnswerForTask(Task $task): ?QuizUserAnswer
    {
        $answers = $this->getUserAnswers()->filter(function (QuizUserAnswer $answer) use ($task) {
            return $answer->getTask()->getId() === $task->getId();
        });

        return $answers->current() ?? null;
    }

    public function isSuccessful(): bool
    {
        return $this->getTotal() && $this->getCorrect() / $this->getTotal() > self::LEARNT_RULE_RATE;
    }
}
