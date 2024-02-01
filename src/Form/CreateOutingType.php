<?php

namespace App\Form;

use App\Entity\Outing;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateOutingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $minDate = new DateTime();

        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom de la sortie :',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Sortie au bar...',
                ],
                'required' => true,
            ])
            ->add('startDate', DateTimeType::class, [
                'label' => 'Date et heure de la sortie :',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'min' => $minDate->format('Y-m-d\TH:i'),
                    'value' => $minDate->format('Y-m-d\TH:i'),
                ],
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('closingDate', DateType::class, [
                'label' => 'Date limite d\'inscription :',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'min' => $minDate->format('Y-m-d'),
                    'value' => $minDate->format('Y-m-d'),
                ],
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('registrationsMax', NumberType::class, [
                'label' => 'Nombre de places :',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '10',
                    'min' => '0',
                ],
                'required' => true,
                'html5' => true,
            ])
            ->add('duration', NumberType::class, [
                'label' => 'Durée (en minutes) :',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '120',
                    'min' => '0',
                ],
                'required' => true,
                'html5' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description et infos :',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Description de la sortie...',
                    'rows' => '4',
                ],
                'required' => true,
            ])
            ->add('place', PlaceType::class, [
                'label' => 'Lieu de la sortie',
            ])
            ->add('poster', FileType::class, [
                'label' => 'Photo pour la sortie :',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Outing::class,
        ]);
    }
}
