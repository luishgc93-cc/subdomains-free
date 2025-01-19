<?php

namespace App\Form;

use App\Entity\Subdomain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubdomainFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Subdomain Name',
                'attr' => ['maxlength' => 40],
            ])
            ->add('record', ChoiceType::class, [
                'label' => 'Record Type',
                'choices' => [
                    'A' => 'A',
                    'AAAA' => 'AAAA',
                    'CNAME' => 'CNAME',
                    'MX' => 'MX',
                    'TXT' => 'TXT',
                ],
            ])
            ->add('value', TextType::class, [
                'label' => 'Value',
                'attr' => ['maxlength' => 500],
            ])
            ->add('ttl', IntegerType::class, [
                'label' => 'TTL (Time To Live)',
            ])
            ->add('priority', IntegerType::class, [
                'label' => 'Priority',
            ])
            ->add('createdAt', DateTimeType::class, [
                'label' => 'Creation Date',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'disabled' => true, // Este campo suele establecerse automáticamente
            ])
            ->add('updatedAt', DateTimeType::class, [
                'label' => 'Last Updated',
                'widget' => 'single_text',
                'required' => false,
                'input' => 'datetime_immutable',
                'disabled' => true, // Este campo también puede ser manejado automáticamente
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Is Active?',
                'required' => false,
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'Notes',
                'required' => false,
                'attr' => ['maxlength' => 255],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subdomain::class,
        ]);
    }
}
