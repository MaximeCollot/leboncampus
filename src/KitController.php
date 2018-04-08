<?php

use Silex\Application;
use User\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class KitController
{

    public function registerAction(Request $request, Application $app)
    {
        $data = array(
        	'kitVie' => false,
        	'kitHygiene' => false,
        	'kitRentree' => false
        );

        $isKitVieDisabled = $app['user']->isKitVieRecu() == 1 ? true : false;
        $isKitHygieneDisabled = $app['user']->isKitHygieneRecu() == 1 ? true : false;
        $isKitRentreeDisabled = $app['user']->isKitRentreeRecu() == 1 ? true : false;

        $kitForm = $app['form.factory']->create(FormType::class, $data)
        ->add('kitVie', CheckboxType::class, array(
		    'label'    => 'Kit de vie',
		    'required' => false,
		    'disabled' => $isKitVieDisabled,
		))
        ->add('kitHygiene', CheckboxType::class, array(
		    'label'    => 'Kit d\'hygiène',
		    'required' => false,
		    'disabled' => $isKitHygieneDisabled,
		))
        ->add('kitRentree', CheckboxType::class, array(
		    'label'    => 'Kit de rentrée',
		    'required' => false,
		    'disabled' => $isKitRentreeDisabled,
		));

        $kitForm->handleRequest($request);
        if ($kitForm->isSubmitted()) {
        	$data = $kitForm->getData();
            // Encode the password (you could also do this via Doctrine listener)
            //$password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            //$user->setPassword($password);
            //$user->setPassword($user->getPlainPassword());

        	if ($data['kitVie'] || $data['kitHygiene'] || $data['kitRentree']){
        		if ($data['kitVie']){
        			//envoyer une demande
        			$app['user']->setKitVieRecu(1);
        		}
        		if ($data['kitHygiene']){
        			//envoyer une demande
        			$app['user']->setKitHygieneRecu(1);
        		}
        		if ($data['kitRentree']){
        			//envoyer une demande
        			$app['user']->setKitRentreeRecu(1);
        		}
        		$app['userProvider']->update($app['user']);
        	}
            

            //$entityManager = $this->getDoctrine()->getManager();
           // $entityManager->persist($user);
            //$entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $app->redirect('kit');
            //TODO creer une page de confirmation
        }

        $kitFormView = $kitForm->createView();

        return $app['twig']->render('kit.html.twig', array(
        	'pageName' => 'Inscription',
        	'form' => $kitFormView
        ));
    }
}