<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                "label" => "Nom et prénom",
                "attr" => ["placeholder" => "Insérez Votre nom"],
                'constraints' => [
                    new NotBlank([
                        'message' => "Le nom est obligatoire",
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => "Le nom doit avoir au moins 5 caractères",
                        'max' => 70,
                        'maxMessage' => "Le nom doit avoir au plus 70 caractères",

                        // max length allowed by Symfony for security reasons

                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                "label" => "Adresse email",
                "attr" => ["placeholder" => "Insérez Votre adresse email"],
                'constraints' => [
                    new NotBlank([
                        'message' => "L'adresse email est obligatoire",
                    ]),

                ],
            ])
            ->add('telephone', IntegerType::class, [
                "label" => "Votre numero de téléphone(whatsapp de préference)",
                "attr" => ["placeholder" => "Insérez Votre numéro ici"],
                'constraints' => [
                    new NotBlank([
                        'message' => "Le numéro est obligatoire",
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => "Le numéro doit avoir au moins 5 caractères",
                        'max' => 15,
                        'maxMessage' => "Le numéro doit avoir au plus 15 caractères",

                        // max length allowed by Symfony for security reasons

                    ]),
                ],
            ])
            ->add('content', TextareaType::class, [
                "label" => "Contenu",
                "attr" => ["placeholder" => "Insérez le contenu de votre message"],
                'constraints' => [
                    new NotBlank([
                        'message' => "Le contenu est obligatoire",
                    ]),
                    new Length([
                        'min' => 15,
                        'minMessage' => "Le contenu doit avoir au moins 15 caractères"


                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
