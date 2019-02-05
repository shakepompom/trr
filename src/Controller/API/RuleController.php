<?php

namespace App\Controller\API;

use App\Entity\Rule;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/rules")
 */
class RuleController extends Controller
{
    /**
     * @Route("/", name="api_rules_index", methods={"GET", "HEAD"})
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function index(SerializerInterface $serializer)
    {
        $data = $this->getDoctrine()->getRepository(Rule::class)->findAll();

        return new Response($serializer->serialize($data, 'json', ['groups' => ['rules_list']]));
    }
}
