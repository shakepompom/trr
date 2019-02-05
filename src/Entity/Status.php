<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

class Status
{
    /**
     * @var int
     */
    private $rulesLearnt;

    const RULES_TOTAL = 206;

    const LEVEL_FIRST = 1;
    const LEVELS = [50, 140, self::RULES_TOTAL];

    public function __construct(int $learnt)
    {
        $this->setRulesLearnt($learnt);
    }

    /**
     * @Groups("profile")
     */
    public function getLevel(): int
    {
        for ($i = 0; $i < count($this->getLevels()); $i++) {
            if ($this->getRulesLearnt() <= $this->getLevels()[$i]) {
                return $i + 1;
            }
        }
        
        return self::LEVEL_FIRST;
    }

    /**
     * @Groups("profile")
     */
    public function getRulesLearnt(): ?int
    {
        return $this->rulesLearnt;
    }

    public function setRulesLearnt(int $rules_learnt): self
    {
        $this->rulesLearnt = $rules_learnt;

        return $this;
    }

    /**
     * @Groups("profile")
     */
    public function getRulesTotal(): int
    {
        return self::RULES_TOTAL;
    }

    /**
     * @Groups("profile")
     */
    public function getRulesToNextLevel(): int
    {
        return $this->getLevels()[$this->getLevel() - 1] - $this->getRulesLearnt();
    }

    /**
     * @Groups("profile")
     */
    public function getLevels(): array
    {
        return self::LEVELS;
    }
}
