<?php

namespace App\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    const DEFAULT_PATH = [82, 14, 46, 81, 83, 12, 36, 1, 11, 80, 4, 198, 50, 6, 2, 23, 31, 52, 5, 24, 7, 37,];

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, options={"default"=null})
     */
    private $vkontakteId = '';

    /**
     * @ORM\Column(type="integer", options={"default"=0})
     */
    private $rating = 0;

    /**
     * @ORM\Column(type="json_array", nullable=false, options={"default"="[82, 14, 46, 81, 83, 12, 36, 1, 11, 80, 4, 198, 50, 6, 2, 23, 31, 52, 5, 24, 7, 37]"})
     */
    private $path = self::DEFAULT_PATH;


    public function __construct()
    {
        parent::__construct();
        $this->path = $this->path ?: self::DEFAULT_PATH;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVkontakteId(): ?string
    {
        return $this->vkontakteId;
    }

    public function setVkontakteId(string $vkontakteId): self
    {
        $this->vkontakteId = $vkontakteId;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getPath(): array
    {
        return $this->path;
    }

    public function setPath(?array $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function pickUpNextRuleFromThePath(): int
    {
        return array_shift($this->path);
    }

    public function addRuleInThePath(int $ruleId, int $position): array
    {
        return array_splice( $this->path, $position, 0, [$ruleId]);
    }
}