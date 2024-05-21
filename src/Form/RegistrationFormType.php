<?php

namespace App\Form;

use App\Entity\User;
use App\Validator\Birthday;
use App\Validator\BirthdayValidator;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Unique;

class RegistrationFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => "Pseudo ",
                'attr' => ['class' => 'inputcontrol', 'placeholder' => "Votre pseudo ici"],
                'constraints' => [
                    new NotBlank([
                        'message' => "Le pseudo est obligatoire",
                    ]),
                    // new UniqueEntity([

                    //     'message' => "Le pseudo est dejà utilisé",
                    // ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => "Le pseudo doit avoir au moins 3 caractères",
                        // max length allowed by Symfony for security reasons
                        'max' => 30,
                        'maxMessage' => "Le pseudo doit avoir au plus 30 caractères",

                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => ['placeholder' => "Votre adresse email ici"],
                'constraints' => [
                    new NotBlank([
                        'message' => "L'adresse email est obligatoire",
                    ]),
                    // new UniqueEntity([
                    //     'message' => "Cette adresse email est dejà utilisée",
                    // ])
                ],
            ])

            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => "Conditions d'utilisation",
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => "Mot de passe sécurisé",
                'attr' => ['class' => 'password'],
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => "Le mot de passe est obligatoire",
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => "Le mot de passe doit avoir au moins 6 caractères",
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'constraints' => [
                new UniqueEntity([
                    'entityClass' => User::class,
                    'fields' => 'email',
                    'message' => "Cette adresse email est dejà utilisée",
                ]),

                new UniqueEntity([
                    'entityClass' => User::class,
                    'fields' => 'username',
                    'message' => "Le pseudo est dejà utilisé",
                ]),
            ]
        ]);
    }
}
