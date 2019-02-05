<?php

namespace App\Controller\API;

use App\Entity\Quiz;
use App\Entity\Status;
use App\Entity\User;
use App\Form\ApiRegistrationType;
use App\Service\CourseItemBuilder;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Serializer\SerializerInterface;

class AuthController extends Controller
{
    /**
     * @Route("/api/register", name="api_register", methods={"POST"})
     * @param Request $request
     * @param UserManagerInterface $userManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return JsonResponse
     */
    public function register(Request $request, UserManagerInterface $userManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $submittedData = json_decode($request->getContent(), true);
        $user = $userManager->createUser();
        $user->setPlainPassword($submittedData['plainPassword']);

        $form = $this->createForm(ApiRegistrationType::class, $user);
        $form->submit($submittedData);

        if ($request->isMethod('POST')) {
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var User $user */
                $user = $form->getData();
                $user->setEnabled(true);
                $user->addRole('ROLE_USER');
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
                $userManager->updatePassword($user);
                $userManager->updateUser($user);

                $jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');

                return new JsonResponse(['token' => $jwtManager->create($user)]);
            }
        }

        return new JsonResponse([
            'error' => [
                'code' => 10001,
                'message' => $this->getFormErrors($form->getErrors(true)),
            ]
        ], 401);
    }

    /**
     * @Route("/api/profile", name="api_profile", methods={"GET"})
     * @param Request $request
     * @param CourseItemBuilder $courseItemBuilder
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function profile(Request $request, CourseItemBuilder $courseItemBuilder, SerializerInterface $serializer)
    {
        /** @var User $user */
        $user = $this->getUser();

        return new Response($serializer->serialize(
            [
                'user' => [
                    'username' => $user->getUsernameCanonical(),
                    'email' => $user->getEmailCanonical(),
                ],
                'status' => new Status($user->getRating()),
                'courseItem' => $courseItemBuilder->get($this->getUser()),
            ], 'json', ['groups' => ['profile', 'quiz_show']]));
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
