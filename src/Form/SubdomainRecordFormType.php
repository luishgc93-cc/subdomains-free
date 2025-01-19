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
                'required' => true,
            ])
            ->add('priority', IntegerType::class, [
                'label' => 'Priority (only for MX records)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Leave blank if not applicable',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SubdomainRecord::class,
        ]);
    }
}
