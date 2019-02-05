<?php

namespace App\Twig;

use App\Entity\Status;
use App\Entity\User;
use App\Repository\QuizRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    /** @var QuizRepository */
    private $repo;

    public function __construct(QuizRepository $quizRepository)
    {
        $this->repo = $quizRepository;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('userStatus', array($this, 'getStatus')),
        );
    }

    public function getStatus(?User $user): Status
    {
        $learntRules = $user ? $user->getRating() : 0;

        return new Status($learntRules);
    }
}