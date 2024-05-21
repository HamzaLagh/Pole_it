<?php

namespace App\Form;

use App\Datatransformer\PostImageTransformer;
use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PostsType extends AbstractType
{

    public function __construct(private PostImageTransformer $postImageTransformer, private TranslatorInterface $translator)
    {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                "label" => "Titre de l'annonce",
                "attr" => ["placeholder" => "Insérez le titre de votre publication"],
                'constraints' => [
                    new NotBlank([
                        'message' => "Le titre est obligatoire",
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => "Le titre doit avoir au moins 10 caractères",
                        'max' => 70,
                        'maxMessage' => "Le titre doit avoir au plus 70 caractères",

                        // max length allowed by Symfony for security reasons

                    ]),
                ],
            ])

            ->add('content', TextareaType::class, [
                "label" => "Contenu",
                "attr" => ["placeholder" => "Insérez le contenu de votre publication"],
                'constraints' => [
                    new NotBlank([
                        'message' => "Le contenu est obligatoire",
                    ]),
                    new Length([
                        'min' => 15,
                        'minMessage' => "Le contenu doit avoir au moins 15 caractères"


                    ]),
                ],
            ])
            ->add(
                'image',
                FileType::class,
                [
                    'multiple' => true,
                    'mapped' => false,
                    'required' => false,

                    'constraints' => [
                        new All([
                            new File([
                                'maxSize' => '4096k',
                                'mimeTypes' => [
                                    'image/jpg',
                                    'image/png',
                                    'image/jpeg',
                                    'image/JPEG',
                                    'image/PNG',
                                    'image/JPG'
                                ],
                                'mimeTypesMessage' => "Formats autorisés: jpeg,jpg,png",
                                'uploadFormSizeErrorMessage' => "La taille de votre image est très grande",
                            ])
                        ]),

                    ],

                    "label" => "(Pas obligatoire) Cliquez ici si vous souhaitez ajouter une ou plusieurs photos",
                    'data_class' => null
                ],


            );;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
