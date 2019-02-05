<?php

namespace App\Controller;

use App\Entity\FunFact;
use App\Entity\Quiz;
use App\Entity\QuizResult;
use App\Entity\Rule;
use App\Entity\Status;
use App\Entity\Task;
use App\Form\QuizAnswerCollectionType;
use App\Service\CourseItemBuilder;
use App\Quizes\QuizAnswerSolver;
use App\Service\RatingCalculator;
use App\Service\RepetitionMechanism;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $learntRules = 0;
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $learntRules = $this->getUser()->getRating();
        }

        return $this->render('home/index.html.twig', [
            'status' => new Status($learntRules),
        ]);
    }

    /**
     * @Route("/about", name="about")
     */
    public function about()
    {
        return $this->render('home/about.html.twig');
    }

    /**
     * @Route("/exam", name="exam", methods={"GET"})
     * @param CourseItemBuilder $courseItemBuilder
     * @return Response
     */
    public function exam(CourseItemBuilder $courseItemBuilder)
    {
        $courseItem = $courseItemBuilder->get($this->getUser());

        if ($courseItem->getExam()->getResult()) {
            return $this->redirectToRoute('exam_result');
        }

        return $this->render('home/exam.html.twig', [
            'quiz' => $courseItem->getExam(),
        ]);
    }

    /**
     * @Route("/exam/next", name="exam_next", methods={"GET"})
     * @param CourseItemBuilder $courseItemBuilder
     * @return Response
     */
    public function nextExam(CourseItemBuilder $courseItemBuilder)
    {
        $courseItem = $courseItemBuilder->get($this->getUser());

        if ($courseItem->getExam()->getResult()) {
            $courseItemBuilder->create($this->getUser());

            return $this->funFact();
        }

        return $this->redirectToRoute('exam');
    }

    /**
     * @Route("/exam/result", name="exam_result_save", methods={"POST"})
     * @Route("/practice/result", name="practice_result_save", methods={"POST"})
     * @param CourseItemBuilder $courseItemBuilder
     * @param Request $request
     * @param QuizAnswerSolver $quizAnswerSolver
     * @param RatingCalculator $ratingCalculator
     * @param RepetitionMechanism $repetitionMechanism
     * @param SerializerInterface $serializer
     * @return Response|JsonResponse
     */
    public function saveResult(
        CourseItemBuilder $courseItemBuilder,
        Request $request,
        QuizAnswerSolver $quizAnswerSolver,
        RatingCalculator $ratingCalculator,
        RepetitionMechanism $repetitionMechanism,
        SerializerInterface $serializer)
    {
        $courseItem = $courseItemBuilder->get($this->getUser());

        $quiz = $courseItem->getExam();
        if ($request->get('_route') === 'practice_result_save') {
            $quiz = $courseItem->getPractice();
        }

        if ($quiz->getResult() !== null) {
            return new Response($serializer->serialize($quiz->getResult(), 'json', ['groups' => ['quiz_result_show']]));
        }

        $submittedData = json_decode($request->request->get('answers'), true);

        $form = $this->createForm(QuizAnswerCollectionType::class);
        $form->submit(['answers' => $submittedData]);

        if ($form->isSubmitted() && $form->isValid()) {
            $answers = $form->getData()['answers'];

            $em = $this->getDoctrine()->getManager();
            $quizResult = $quizAnswerSolver->calculateResult($quiz, $answers);
            $em->persist($quizResult);
            $ratingCalculator->registerResult($this->getUser(), $quizResult);
            $repetitionMechanism->addIntervalRepetition($this->getUser(), $quiz->getRule(), $quizResult);
            $em->persist($this->getUser());
            $em->flush();

            return new Response($serializer->serialize($quizResult, 'json', ['groups' => ['quiz_result_show']]));
        }

        return new JsonResponse([
            'error' => [
                'code' => 10004,
                'message' => $this->getFormErrors($form->getErrors(true)),
            ]
        ], 401);
    }

    /**
     * @Route("/exam/result", name="exam_result", methods={"GET"})
     * @param CourseItemBuilder $courseItemBuilder
     * @return Response
     */
    public function examResult(CourseItemBuilder $courseItemBuilder)
    {
        $courseItem = $courseItemBuilder->get($this->getUser());

        if (!$courseItem->getExam()->getResult()) {
            return $this->redirectToRoute('exam');
        }

        $lastExamResultsForSameRule = $this->getDoctrine()
            ->getRepository(QuizResult::class)
            ->findLastResults($this->getUser(), $courseItem->getExam()->getRule());

        return $this->render('home/exam_result.html.twig', [
            'quiz' => $courseItem->getExam(),
            'lastResults' => $lastExamResultsForSameRule,
        ]);
    }

    /**
     * @Route("/practice", name="practice", methods={"GET"})
     * @param CourseItemBuilder $courseItemBuilder
     * @return Response
     */
    public function practice(CourseItemBuilder $courseItemBuilder)
    {
        $courseItem = $courseItemBuilder->get($this->getUser());

        if ($courseItem->getPractice()->getResult()) {
            return $this->redirectToRoute('practice_result');
        }

        return $this->render('home/practice.html.twig', [
            'quiz' => $courseItem->getPractice(),
        ]);
    }

    /**
     * @Route("/practice/next", name="practice_next", methods={"GET"})
     * @param CourseItemBuilder $courseItemBuilder
     * @return Response
     */
    public function nextPractice(CourseItemBuilder $courseItemBuilder)
    {
        $courseItem = $courseItemBuilder->get($this->getUser());

        if ($courseItem->getPractice()->getResult()) {
            $courseItemBuilder->createNewPractice($this->getUser());
        }

        return $this->redirectToRoute('practice');
    }

    /**
     * @Route("/practice/result", name="practice_result", methods={"GET"})
     * @param CourseItemBuilder $courseItemBuilder
     * @return Response
     */
    public function practiceResult(CourseItemBuilder $courseItemBuilder)
    {
        $courseItem = $courseItemBuilder->get($this->getUser());

        if (!$courseItem->getPractice()->getResult()) {
            return $this->redirectToRoute('practice');
        }

        return $this->render('home/practice_result.html.twig', [
            'quiz' => $courseItem->getPractice(),
        ]);
    }

    /**
     * @Route("/fun-fact", name="fun_fact", methods={"GET"})
     */
    public function funFact()
    {
        $randomId = mt_rand(1, 21);
        $randomFact = $this->getDoctrine()->getRepository(FunFact::class)->find($randomId);

        return $this->render('home/fun_fact.html.twig', [
            'fact' => $randomFact,
        ]);
    }

    /**
     * @Route("/rules", name="rules")
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function rules(SerializerInterface $serializer)
    {
        $rules = $this->getDoctrine()->getRepository(Rule::class)->findBy([], ['number' => 'ASC']);

        return $this->render('rules/index.html.twig', [
            'rules' => $rules,
        ]);
    }

    /*
     * TODO: move to a trait.
     */
    private function getFormErrors(FormErrorIterator $iterator): string
    {
        $result = [];
        foreach ($iterator as $key => $error) {
            $result[] = $error->getMessage();
        }

        return implode("\n", $result);
    }
}
