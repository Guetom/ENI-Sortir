<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Place;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', EntityType::class, [
                'label' => 'Ville :',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'class' => City::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => true,
            ])
            ->add('name', TextType::class, [
                'label' => 'Lieu :',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Lieu',
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse :',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Adresse',
                ],
            ])
            ->add('latitude', TextType::class, [
                'label' => 'Latitude :',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Latitude',
                ],
            ])
            ->add('longitude', TextType::class, [
                'label' => 'Longitude :',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Longitude',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}
