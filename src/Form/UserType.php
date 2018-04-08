<?php

namespace Form;

use User\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', EmailType::class, array(
                'required'    => true,
                'constraints' => array(
                    new Assert\NotBlank(), 
                    new Assert\Length(array(
                    'min' => 5,'max' => 100,
                    ))
                )
            ))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'required'    => true,
                'constraints' => array(
                    new Assert\NotBlank(), 
                    new Assert\Length(array(
                    'min' => 5,'max' => 100,
                    ))
                ),
                'first_options'  => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Retaper le mot de passe'),
            ))
        ;
        //TODO mieux gerer les contraintes
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }

    public function getName()
    {
        return 'Inscription';
    }
}