<?php

namespace App\Form;

use App\Entity\Fournisseur;
use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label'=> 'Titre',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Titre du produit...',
                ]
            ])
            ->add('description', TextType::class, [
                'label'=> 'Description',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Description du produit...',
                ]
            ])
            ->add('quantite_en_stock', NumberType::class, [
                'label'=> 'Quantité',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Quantité en stock...',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => "Veuillez entrer un nombre valide au lieu de lettres.",
                    ])
                ]
            ])
            ->add('marque', TextType::class, [
                'label'=> 'Marque',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Marque du produit...',
                ]
            ])
            ->add('prix_ttc', MoneyType::class, [
                'label'=> 'Prix ttc',
                'required' => true,
                'currency' => "MGA",
                'attr' => [
                    'placeholder' => 'Prix du produit...',
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\d{1,3}(,\d{3})*(\.\d+)?$/',
                        'message' => "Veuillez entrer un prix valide",
                    ])
                ]
            ])
            ->add('types', TextType::class, [
                'label'=> 'Types',
                'required' => true,
                'attr' => [
                    'placeholder' => 'BMW, Peugeot, Mercedes,...',
                ]
            ])
            ->add('genre', TextType::class, [
                'label'=> 'Genre',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Genre du produit...',
                ]
            ])
            ->add('fournisseur', EntityType::class,[
                'class'=>Fournisseur::class,
                'choice_label' => 'nom',
                'label'=>'Fournisseur',
                'required'=> true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
