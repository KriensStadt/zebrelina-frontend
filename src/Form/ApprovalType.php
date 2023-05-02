<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Approval;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApprovalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('approved', CheckboxType::class, [
                'required' => false,
                'label' => 'approval.approved',
                'help' => 'approval.approved_help',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Approval::class,
            'translation_domain' => 'forms',
        ]);
    }
}
