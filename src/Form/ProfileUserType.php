<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'label_attr' => [
                    'class' => 'col-sm-4 col-form-label',
                ],
                'attr' => [
                    'class' => 'form-control-plaintext',
                    'placeholder' => 'Pseudo',
                    'readonly' => 'readonly',
                ],
                'required' => true,
                'disabled' => true,
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'label_attr' => [
                    'class' => 'col-sm-4 col-form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Prénom',
                ],
                'required' => true,
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'col-sm-4 col-form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom',
                ],
                'required' => true,
            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'label_attr' => [
                    'class' => 'col-sm-4 col-form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Téléphone',
                ],
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'label_attr' => [
                    'class' => 'col-sm-4 col-form-label',
                ],
                'attr' => [
                    'class' => 'form-control-plaintext',
                    'placeholder' => 'Email',
                    'readonly' => 'readonly',
                ],
                'disabled' => true,
            ])
//            ->add('password', RepeatedType::class, [
//                'type' => PasswordType::class,
//                'invalid_message' => 'Les mots de passe doivent être identiques',
//                'required' => false,
//                'mapped' => true,
//                'first_options' => [
//                    'label' => 'Nouveau mot de passe',
//                    'label_attr' => [
//                        'class' => 'col-sm-4 col-form-label',
//                    ],
//                    'attr' => [
//                        'class' => 'form-control',
//                        'placeholder' => 'Mot de passe',
//                    ],
//                ],
//                'second_options' => [
//                    'label' => 'Confirmation du mot de passe',
//                    'label_attr' => [
//                        'class' => 'col-sm-4 col-form-label',
//                    ],
//                    'attr' => [
//                        'class' => 'form-control',
//                        'placeholder' => 'Confirmation du mot de passe',
//                    ],
//                ],
//            ])
            ->add('site', EntityType::class, [
                'label' => 'Site de rattachement',
                'label_attr' => [
                    'class' => 'col-sm-4 col-form-label',
                ],
                'attr' => [
                    'class' => 'form-select',
                ],
                'class' => Site::class,
                'empty_data' => '',
                'choice_label' => 'name',
                'required' => false,
            ])
            ->add('profilePicture', FileType::class, [
                'label' => 'Ma photo de profil',
                'label_attr' => [
                    'class' => 'col-sm-4 col-form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
                'mapped' => false,
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
