<?php

use Silex\Application;
use Form\UserType;
use User\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController
{

    public function registerAction(Request $request, Application $app)
    {
        $user = new User('','',array("ROLE_USER"),true,true,true,true,false,false,false);
        $userForm = $app['form.factory']->create(UserType::class, $user);

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {

            // Encode the password (you could also do this via Doctrine listener)
            //$password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            //$user->setPassword($password);
            //$user->setPassword($user->getPlainPassword());

            $app['userProvider']->save($user);

            //$entityManager = $this->getDoctrine()->getManager();
           // $entityManager->persist($user);
            //$entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $app->redirect('kit');
            //TODO creer une page de confirmation
        }

        $userFormView = $userForm->createView();

        return $app['twig']->render('register.html.twig', array(
        	'pageName' => 'Inscription',
        	'form' => $userFormView
        ));
    }
}