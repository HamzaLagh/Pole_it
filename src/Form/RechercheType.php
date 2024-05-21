<?php

namespace App\Form;

use App\Entity\Recherche;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Ville;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Contracts\Translation\TranslatorInterface;



class RechercheType extends AbstractType
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => false,
                'required'   => false,
                "attr" => ["placeholder" => $this->translator->trans('recherche_question'), "class" => "search-slt"]
            ])
            ->add('categorie', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Rencontres Escortes' => 'Rencontres Escortes',
                    'Massages' => 'Massages',
                    'Vente des produits' => 'Vente des produits'
                ],
                "attr" => ["placeholder" => $this->translator->trans('all_categorie'), "class" => "search-slt"]
            ])
            ->add('ville', EntityType::class, [
                'label' => false,
                'class' => Ville::class,
                "mapped" => false,
                'choice_label' => 'nom',
                "attr" => ["placeholder" => $this->translator->trans('all_cameroun'), "class" => "search-slt"]

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recherche::class,
        ]);
    }
}
