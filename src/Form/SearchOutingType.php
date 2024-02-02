<?php

namespace App\Form;

use App\Entity\Site;
use App\Model\SearchOuting;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchOutingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', SearchType::class, [
                'label' => 'Le nom de la sortie contient',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'empty_data' => '',
                'attr' => [
                    'placeholder' => 'Sortie au bar...',
                    'class' => 'form-control',
                ],
                'required' => false,
            ])
            ->add('site', EntityType::class, [
                'label' => 'Le site',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-select',
                ],
                'empty_data' => '',
                'class' => Site::class,
                'choice_label' => 'name',
                'required' => false,
            ])
            ->add('startDate', DateType::class, [
                'label' => 'Entre le',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                ],
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('endDate', DateType::class, [
                'label' => 'Et le',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'empty_data' => '',
                'attr' => [
                    'class' => 'form-control',
                ],
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('isOrganizer', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'label_attr' => [
                    'class' => 'form-check-label',
                ],
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'required' => false,
            ])
            ->add('isRegistered', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'label_attr' => [
                    'class' => 'form-check-label',
                ],
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'required' => false,
            ])
            ->add('isNotRegistered', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'label_attr' => [
                    'class' => 'form-check-label',
                ],
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'required' => false,
            ])
            ->add('isFinished', CheckboxType::class, [
                'label' => 'Sorties passées',
                'label_attr' => [
                    'class' => 'form-check-label',
                ],
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchOuting::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
}
