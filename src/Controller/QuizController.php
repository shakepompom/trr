<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends Controller
{
    /**
     * @Route("/quiz", name="quiz_index")
     */
    public function index()
    {
        return $this->render('quiz/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
