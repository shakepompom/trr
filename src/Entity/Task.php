<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 */
class Task
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @Groups({"quiz_show", "quiz_result_show"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups("quiz_show")
     */
    private $text;

    /**
     * @ORM\Column(type="text")
     * @Groups("quiz_show")
     */
    private $options;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Rule", inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $rule;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("quiz_show")
     */
    private $answer;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("quiz_show")
     */
    private $explanation;

    public function getId()
    {
        return $this->id;
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

    public function getOptions(): ?array
    {
        return explode('|', trim($this->options, '|'));
    }

    public function setOptions(array $options): self
    {
        $this->options = '|'.implode('|', $options).'|';

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

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getExplanation(): ?string
    {
        return $this->explanation;
    }

    public function setExplanation(?string $explanation): self
    {
        $this->explanation = $explanation;

        return $this;
    }
}
