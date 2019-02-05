<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FunFactRepository")
 */
class FunFact
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups("quiz_show")
     */
    private $text;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("quiz_show")
     */
    private $source;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("quiz_show")
     */
    private $sourceImage;

    public function getId(): ?int
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

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getSourceImage(): ?string
    {
        return $this->sourceImage;
    }

    public function setSourceImage(?string $sourceImage): self
    {
        $this->sourceImage = $sourceImage;

        return $this;
    }
}
