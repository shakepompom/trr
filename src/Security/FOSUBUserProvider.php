<?php

namespace App\Security;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;

class FOSUBUserProvider extends BaseClass
{
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();
        $email = $response->getEmail();

        $user = $this->userManager->findUserBy([$this->getProperty($response) => $username]);
        if (null === $user) {
            $user = $this->userManager->findUserBy(['emailCanonical' => mb_strtolower($email)]);
        }
        if (null === $user) {
            $user = $this->userManager->findUserBy(['usernameCanonical' => mb_strtolower($email)]);
        }

        //when the user is registrating
        if (null === $user) {
//            $setter_token = $setter.'AccessToken';

            // create new user here
            $user = $this->userManager->createUser();
//            $user->$setter_token($response->getAccessToken());

            //I have set all requested data with the user's username
            //modify here with relevant data
            $user->setUsername($email);
            $user->setEmail($email);
            $user->setPassword(12345);
            $user->setEnabled(true);
            $user->addRole('ROLE_USER');
            $this->userManager->updateUser($user);

            return $user;
        }

        $service = $response->getResourceOwner()->getName();
        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $user->$setter_id($username);
        $this->userManager->updateUser($user);


        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);
        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';

        //update access token
//        $user->$setter($response->getAccessToken());

        return $user;
    }
}