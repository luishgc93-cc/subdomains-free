<?php

namespace App\Form;

use App\Entity\SubdomainRecord;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubdomainRecordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('type', ChoiceType::class, [
            'label' => 'Tipo',
            'row_attr' => ['class' => 'mb-5'],
            'attr' => [
                'class' => 'text-lg bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
            ],
            'choices' => [
                'A' => 'A',
                'AAAA' => 'AAAA',
                'CNAME' => 'CNAME',
                'MX' => 'MX',
                'TXT' => 'TXT',
            ],
        ])
        ->add('value', TextType::class, [
            'label' => 'Valor',
            'row_attr' => ['class' => 'mb-5'],
            'attr' => [
                'class' => 'text-lg bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                'maxlength' => 500,
                'placeholder' => 'Introducir un valor',
            ],
        ])
        ->add('ttl', IntegerType::class, [
            'label' => 'TTL (tiempo de vida)',
            'row_attr' => ['class' => 'mb-5'],
            'attr' => [
                'class' => 'text-lg bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                'placeholder' => 'Introducir en segundos',
            ],
        ])
        ->add('priority', IntegerType::class, [
            'label' => 'Prioridad (solo para registros MX )',
            'row_attr' => ['class' => 'mb-5'],
            'attr' => [
                'class' => 'text-lg bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                'placeholder' => 'Dejar en blanco si no se necesita',
            ],
            'required' => false,
        ]);
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SubdomainRecord::class,
        ]);
    }
}
