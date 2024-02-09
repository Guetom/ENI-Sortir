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
                'required' => true,
            ])
            ->add('startDate', DateTimeType::class, [
                'attr' => [
                    'min' => $minDate->format('Y-m-d\TH:i'),
                ],
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('closingDate', DateType::class, [
                'attr' => [
                    'min' => $minDate->format('Y-m-d'),
                ],
                'required' => true,
                'widget' => 'single_text',
            ])
            ->add('registrationsMax', NumberType::class, [
                'attr' => [
                    'min' => '0',
                ],
                'required' => true,
                'html5' => true,
            ])
            ->add('duration', NumberType::class, [
                'attr' => [
                    'min' => '0',
                ],
                'required' => true,
                'html5' => true,
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'rows' => '4',
                ],
                'required' => true,
            ])
            ->add('place', PlaceType::class, [
            ])
            ->add('poster', FileType::class, [
                'required' => false,
                'mapped' => false,
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
