<?php

namespace App\Controller\API;

use App\Entity\CourseItem;
use App\Entity\User;
use App\Service\CourseItemBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CourseController extends Controller
{
    /**
     * @Route("/api/course", name="api_course", methods={"GET", "HEAD"})
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function index(SerializerInterface $serializer)
    {
        return new JsonResponse([1, 2, 3, 4, 1, 5, 2, 6, 3, 4]);
    }

    /**
     * @Route("/api/course-item", name="api_course_item", methods={"POST"})
     * @param SerializerInterface $serializer
     * @param CourseItemBuilder $courseItemBuilder
     * @return Response
     */
    public function courseItem(SerializerInterface $serializer, CourseItemBuilder $courseItemBuilder)
    {
        $courseItem = $courseItemBuilder->get($this->getUser());

        return new Response($serializer->serialize($courseItem, 'json', ['groups' => ['quiz_show']]));
    }

    /**
     * @Route("/api/course-item/next", name="api_course_item_next", methods={"POST"})
     * @param SerializerInterface $serializer
     * @param CourseItemBuilder $courseItemBuilder
     * @return Response
     */
    public function courseItemNext(SerializerInterface $serializer, CourseItemBuilder $courseItemBuilder)
    {
        $courseItem = $courseItemBuilder->get($this->getUser());

        if ($courseItem->getExam()->getResult()) {
            $courseItem = $courseItemBuilder->create($this->getUser());
        }

        return new Response($serializer->serialize($courseItem, 'json', ['groups' => ['quiz_show']]));
    }
}
