<?php
// src/Form/SubdomainFormType.php
namespace App\Form;

use App\Entity\Subdomain;
use App\Entity\Domain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; 
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubdomainFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Nombre de subdominio',
            'row_attr' => ['class' => 'mb-5'],
            'attr' => [
                'class' => 'text-lg bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                'placeholder' => 'Nombre del subdominio',
            ],
        ])
        ->add('notes', TextareaType::class, [
            'label' => 'Notas del subdominio',
            'row_attr' => ['class' => 'mb-5'],
            'attr' => [
                'class' => 'text-lg bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
                'placeholder' => 'Escribe notas aquí...',
            ],
        ])
        ->add('domain', EntityType::class, [
            'class' => Domain::class,
            'choice_label' => 'name',
            'label' => 'Seleccionar dominio principal',
            'row_attr' => ['class' => 'mb-5'],
            'attr' => [
                'class' => 'text-lg bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500',
            ],
        ]);
    
    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subdomain::class,
        ]);
    }
}
