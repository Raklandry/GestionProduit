<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label'=> false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Votre nom...',
                ],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Votre nom est trop courte',
                    ])
                ]
            ])
            ->add('prenom', TextType::class, [
                'label'=> false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Votre prenom...',
                ],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Votre prenom est trop courte',
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'label'=> false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Votre adresse email...',
                ]
            ])
            ->add('adresse', TextType::class, [
                'label'=> false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Votre adresse...',
                ]
            ])
            ->add('telephone', NumberType::class, [
                'label'=> false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Votre numéro téléphone...',
                ]
            ])
            ->add('password', RepeatedType::class,[
                'type'=> PasswordType::class,
                'invalid_message'=> 'les mots de passe doivent être identique',
                'label'=> false,
                'first_options'=> [
                	'label'=> false,
                    'attr' => [
                        'placeholder' => 'Votre mot de passe...',
                    ]
                ],
                'second_options'=> [
                	'label'=> false,
                    'attr' => [
                        'placeholder' => 'Confirmez votre mot de passe...',
                    ]
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
