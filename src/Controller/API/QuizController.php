<?php

namespace App\Controller\API;

use App\Quizes\QuizAnswerSolver;
use App\Quizes\QuizBuilder;
use App\Entity\Quiz;
use App\Entity\Rule;
use App\Form\QuizAnswerCollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class QuizController extends Controller
{
    /**
     * @Route("/api/rules/{id}/quiz/practice", name="api_quiz_create_practice", methods={"POST"})
     * @param Rule $rule
     * @param QuizBuilder $quizBuilder
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function createPractice(Rule $rule, QuizBuilder $quizBuilder, SerializerInterface $serializer)
    {
        $quiz = $quizBuilder->create($this->getUser(), $rule, Quiz::TYPE_PRACTICE);
        $this->getDoctrine()->getManager()->persist($quiz);
        $this->getDoctrine()->getManager()->flush();

        return new Response($serializer->serialize($quiz, 'json', ['groups' => ['quiz_show']]));
    }

    /**
     * @Route("/api/quizes/{id}/result", name="api_quizes_save_result", methods={"POST"})
     * @param Quiz $quiz
     * @param Request $request
     * @param QuizAnswerSolver $quizAnswerSolver
     * @param SerializerInterface $serializer
     * @return Response|JsonResponse
     */
    public function saveResult(Quiz $quiz, Request $request, QuizAnswerSolver $quizAnswerSolver, SerializerInterface $serializer)
    {
        if ($quiz->getResult() !== null) {
            return new Response($serializer->serialize($quiz->getResult(), 'json', ['groups' => ['quiz_result_show']]));
        }

        $submittedData = json_decode($request->getContent(), true);

        $form = $this->createForm(QuizAnswerCollectionType::class);
        $form->submit($submittedData);

        if ($request->isMethod('POST')) {
            if ($form->isSubmitted() && $form->isValid()) {
                $answers = $form->getData()['answers'];

                $quizResult = $quizAnswerSolver->calculateResult($quiz, $answers);
                $this->getDoctrine()->getManager()->persist($quizResult);
                $this->getDoctrine()->getManager()->flush();

                return new Response($serializer->serialize($quizResult, 'json', ['groups' => ['quiz_result_show']]));
            }
        }

        return new JsonResponse([
            'error' => [
                'code' => 10002,
                'message' => $this->getFormErrors($form->getErrors(true)),
            ]
        ], 401);
    }

    private function getFormErrors(FormErrorIterator $iterator): string
    {
        $result = [];
        foreach ($iterator as $key => $error) {
            $result[] = $error->getMessage();
        }

        return implode("\n", $result);
    }
}
