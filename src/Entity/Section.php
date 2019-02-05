<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SectionRepository")
 */
class Section
{
    const PART_SPELLING = 1;
    const PART_PUNCTUATION = 2;

    const SUBPART_SPELLING_VOWEL = 1;
    const SUBPART_SPELLING_CONSONANTS = 2;
    const SUBPART_SPELLING_HYPHEN_OR_TOGETHER = 3;
    const SUBPART_PUNCTUATION_COMMA = 1;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("rules_list")
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $number;

    /**
     * @ORM\Column(type="smallint")
     */
    private $part;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $subpart;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rule", mappedBy="section")
     * @ORM\OrderBy({"number" = "ASC"})
     */
    private $rules;

    public function __construct()
    {
        $this->rules = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getPart(): ?int
    {
        return $this->part;
    }

    public function setPart(int $part): self
    {
        $this->part = $part;

        return $this;
    }

    public function getSubpart(): ?int
    {
        return $this->subpart;
    }

    public function setSubpart(?int $subpart): self
    {
        $this->subpart = $subpart;

        return $this;
    }

    /**
     * @return Collection|Rule[]
     */
    public function getRules(): Collection
    {
        return $this->rules;
    }

    public function addRule(Rule $rule): self
    {
        if (!$this->rules->contains($rule)) {
            $this->rules[] = $rule;
            $rule->setSection($this);
        }

        return $this;
    }

    public function removeRule(Rule $rule): self
    {
        if ($this->rules->contains($rule)) {
            $this->rules->removeElement($rule);
            // set the owning side to null (unless already changed)
            if ($rule->getSection() === $this) {
                $rule->setSection(null);
            }
        }

        return $this;
    }
}
